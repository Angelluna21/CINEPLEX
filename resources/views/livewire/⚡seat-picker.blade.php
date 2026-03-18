<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Funcion;
use App\Models\Asiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    // PROPIEDADES
    public $funcionId;    // Recibe el ID de la función al cargar
    public $sessionId;    // Para saber quién bloquea qué asiento
    public $asientos = []; // Matriz de asientos (id, fila, numero, estatus, bloqueado_por)
    public $misSelecciones = []; // Lista de IDs de asientos que yo elegí
    public $tipoSala = ''; // Nombre de la sala (Tradicional, IMAX, 4D...)

    // SE EJECUTA AL INICIAR EL COMPONENTE
    // SE EJECUTA AL INICIAR EL COMPONENTE
    public function mount($funcionId)
    {
        $this->funcionId = $funcionId;
        $this->sessionId = session()->getId();
        
        $funcion = Funcion::with('sala')->find($this->funcionId);
        if (!$funcion || !$funcion->sala) {
            $this->tipoSala = 'Normal';
            return;
        }

        $this->tipoSala = $funcion->sala->nombre;
        $capacidad = $funcion->sala->capacidad ?: 60;
        
        $this->generarMapaDeAsientos($funcion->sala->id, $capacidad);
    }

    // LÓGICA CONECTADA A LA BASE DE DATOS
    public function generarMapaDeAsientos($salaId, $capacidad)
    {
        // 1. Verificar si existen los asientos en la BD para esta sala, si no, los creamos
        $asientosDB = Asiento::where('sala_id', $salaId)->get();

        if ($asientosDB->isEmpty()) {
            // Default: roughly square shape (e.g. 10x10 for 100, 8x8 for 60)
            // Or typically cinemas have 10-20 seats per row.
            // Let's use 10 for capacities <= 100, 15 for <= 160, 20 for > 160.
            $asientosPorFila = 10;
            if ($capacidad > 100 && $capacidad <= 160) {
                $asientosPorFila = 15;
            } elseif ($capacidad > 160) {
                $asientosPorFila = 20;
            }

            $totalFilas = ceil($capacidad / $asientosPorFila);
            $letras = range('A', 'Z');
            
            $nuevosAsientos = [];
            $asientosGenerados = 0;

            for ($f = 0; $f < $totalFilas; $f++) {
                $fila = $letras[$f];
                
                // Only generate up to total capacity to avoid over-generating
                for ($i = 1; $i <= $asientosPorFila; $i++) {
                    if ($asientosGenerados >= $capacidad) break;

                    $tipo = 'Tradicional';
                    $nuevosAsientos[] = [
                        'sala_id' => $salaId,
                        'fila' => $fila,
                        'numero' => $i,
                        'tipo' => $tipo,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $asientosGenerados++;
                }
            }
            if (!empty($nuevosAsientos)) {
                Asiento::insert($nuevosAsientos);
                $asientosDB = Asiento::where('sala_id', $salaId)->get();
            }
        }

        // 2. Traer el estado actual de las reservaciones desde funcion_asiento
        $reservaciones = DB::table('funcion_asiento')
            ->where('funcion_id', $this->funcionId)
            ->get()
            ->keyBy('asiento_id');

        // 3. Reconstruir matiz $this->asientos
        foreach ($asientosDB as $asiento) {
            $idStr = $asiento->fila . $asiento->numero;
            $estatus = 'disponible';
            $bloqueadoPor = null;

            if ($reservaciones->has($asiento->id)) {
                $res = $reservaciones->get($asiento->id);
                
                if ($res->status === 'ocupado') {
                    $estatus = 'vendido';
                } elseif ($res->status === 'reservado') {
                    // Si fuiste tú quien lo reservó
                    if ($res->session_id === $this->sessionId || ($res->user_id && $res->user_id === Auth::id())) {
                        $estatus = 'seleccionado';
                        $bloqueadoPor = $this->sessionId;
                        if (!in_array($idStr, $this->misSelecciones)) {
                            $this->misSelecciones[] = $idStr;
                        }
                    } else {
                        // Bloqueado temporalmente por otra persona
                        $estatus = 'bloqueado';
                        $bloqueadoPor = $res->session_id;
                    }
                }
            }

            $this->asientos[$idStr] = [
                'db_id' => $asiento->id,
                'id' => $idStr,
                'fila' => $asiento->fila,
                'numero' => $asiento->numero,
                'estatus' => $estatus,
                'bloqueado_por' => $bloqueadoPor,
            ];
        }
    }

    // LÓGICA AL DAR CLIC A UN ASIENTO
    public function seleccionarAsiento($id)
    {
        $asiento = $this->asientos[$id];
        $dbId = $asiento['db_id'];

        if ($asiento['estatus'] === 'vendido') return;
        
        if ($asiento['estatus'] === 'bloqueado' && $asiento['bloqueado_por'] !== $this->sessionId) {
            return;
        }

        // SI YA ES MI SELECCIÓN: Lo deselecciono (Liberar en BD)
        if (in_array($id, $this->misSelecciones)) {
            $this->misSelecciones = array_diff($this->misSelecciones, [$id]);
            
            $this->asientos[$id]['estatus'] = 'disponible';
            $this->asientos[$id]['bloqueado_por'] = null;

            // Eliminar reservación
            DB::table('funcion_asiento')
                ->where('funcion_id', $this->funcionId)
                ->where('asiento_id', $dbId)
                ->where('session_id', $this->sessionId)
                ->delete();

        } 
        // SI ESTÁ LIBRE: Lo selecciono (Bloquear en BD)
        else {
            if(count($this->misSelecciones) >= 10) return; 

            // Verificar rápido que alguien no lo haya ganado en BD justo antes de mí
            $existe = DB::table('funcion_asiento')
                ->where('funcion_id', $this->funcionId)
                ->where('asiento_id', $dbId)
                ->exists();

            if ($existe) {
                // Alguien más lo agarró
                $this->asientos[$id]['estatus'] = 'bloqueado';
                return;
            }

            $this->misSelecciones[] = $id;
            $this->asientos[$id]['estatus'] = 'seleccionado';
            $this->asientos[$id]['bloqueado_por'] = $this->sessionId;

            DB::table('funcion_asiento')->insert([
                'funcion_id' => $this->funcionId,
                'asiento_id' => $dbId,
                'status' => 'reservado',
                'user_id' => Auth::check() ? Auth::id() : null,
                'session_id' => $this->sessionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // ==========================================
    // MAGIA DE WEBSOCKETS (REVERB / ECHO)
    // ==========================================
    // Livewire v3 escucha mágicamente a Laravel Echo con este atributo.
    // Escucha el canal público "sala.{id_funcion}" y el evento "SeatSelected"
    #[On('echo:sala.{funcionId},SeatSelected')]
    public function procesarBloqueoDeOtroUsuario($event)
    {
        $asientoId = $event['asiento_id'];
        
        // Si el usuario no soy yo y eligió un asiento libre para mí...
        if (!in_array($asientoId, $this->misSelecciones)) {
            // Se le pone el candado gris (bloqueado)
            $this->asientos[$asientoId]['estatus'] = 'bloqueado';
            $this->asientos[$asientoId]['bloqueado_por'] = $event['session_id'];
        }
    }
};
?>

<div class="max-w-6xl mx-auto bg-[#0B0F19] p-4 md:p-8 rounded-3xl border border-gray-800 shadow-2xl text-white">
    
    <div class="mb-12 max-w-2xl mx-auto">
        <div class="w-full h-2 bg-gradient-to-r from-transparent via-cyan-500 to-transparent shadow-[0_10px_30px_rgba(66,165,245,0.5)] rounded-full"></div>
        <p class="text-center text-gray-500 text-xs mt-4 tracking-[0.3em] uppercase">Pantalla {{ $tipoSala }}</p>
    </div>

    <div class="overflow-x-auto pb-8 custom-scrollbar">
        <div class="min-w-max flex flex-col gap-4 items-center px-4">
            @foreach (collect($asientos)->groupBy('fila') as $fila => $asientosFila)
                <div class="flex items-center gap-2 md:gap-4">
                    <span class="text-gray-400 font-bold w-6 text-center text-sm">{{ $fila }}</span>
                    
                    <div class="flex gap-2">
                        @foreach ($asientosFila as $asiento)
                            <button 
                                wire:click="seleccionarAsiento('{{ $asiento['id'] }}')"
                                @class([
                                    'relative rounded-t-xl rounded-b-md flex items-center justify-center font-bold text-xs md:text-sm transition-all duration-300 transform hover:-translate-y-1',
                                    'w-8 h-8 md:w-10 md:h-10' => ($asiento['estatus'] !== 'seleccionado'), // Tamaño normal
                                    'w-10 h-10 md:w-12 md:h-12 z-10' => ($asiento['estatus'] === 'seleccionado'), // Un poco más grande al seleccionar
                                    
                                    // Verde Neón (Disponible)
                                    'bg-green-500 hover:bg-green-400 text-green-900 shadow-[0_0_10px_rgba(34,197,94,0.3)]' => $asiento['estatus'] === 'disponible',
                                    
                                    // Tu Selección (Azul Neón Brillo)
                                    'bg-blue-600 border-2 border-blue-400 text-white shadow-[0_0_20px_rgba(37,99,235,0.6)] scale-110' => $asiento['estatus'] === 'seleccionado',
                                    
                                    // Vendido (Rojo Oscuro Apagado)
                                    'bg-red-600 cursor-not-allowed opacity-50' => $asiento['estatus'] === 'vendido',
                                    
                                    // Bloqueado por otro (Gris Candado)
                                    'bg-gray-600 cursor-not-allowed border border-gray-500' => $asiento['estatus'] === 'bloqueado',
                                ])
                                {{ in_array($asiento['estatus'], ['vendido', 'bloqueado']) ? 'disabled' : '' }}
                            >
                                @if($asiento['estatus'] === 'bloqueado')
                                    <i class="bi bi-lock-fill text-gray-300"></i>
                                @elseif($asiento['estatus'] === 'seleccionado')
                                    <i class="bi bi-check-lg text-white"></i>
                                @else
                                    {{ $asiento['numero'] }}
                                @endif
                            </button>
                        @endforeach
                    </div>
                    
                    <span class="text-gray-400 font-bold w-6 text-center text-sm hidden md:block">{{ $fila }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-[#151E2E] p-4 md:p-6 rounded-2xl border border-gray-800 flex flex-wrap justify-center gap-4 md:gap-6 text-xs md:text-sm text-gray-400 mt-4">
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-green-500"></div> Disponible</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-blue-600 border border-blue-400"></div> Tu Selección</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-gray-600 flex items-center justify-center text-[10px] text-white"><i class="bi bi-lock-fill"></i></div> En Proceso (WebSockets)</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-red-600"></div> Vendido</div>
    </div>

    @if(count($misSelecciones) > 0)
        <div class="mt-8 text-center">
            <button class="bg-gradient-to-r from-cinecyan to-blue-600 text-white font-bold py-3 md:py-4 px-8 md:px-12 rounded-full hover:scale-105 transition-all shadow-[0_0_20px_rgba(66,165,245,0.4)] text-lg uppercase tracking-wider">
                Proceder al Pago ({{ count($misSelecciones) }} boletos)
            </button>
        </div>
    @endif

</div>

<style>
    /* Estilo para navegadores WebKit (Chrome, Safari, Edge) */
    .custom-scrollbar::-webkit-scrollbar { height: 8px; /* Grosor de barra horizontal */ }
    .custom-scrollbar::-webkit-scrollbar-track { background: #0B0F19; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #1f2937; /* Color de la barra gris oscuro */ border-radius: 10px; border: 2px solid #0B0F19; /* Espaciado */ }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #374151; /* Color al pasar mouse */ }

    /* Estilo para Firefox */
    .custom-scrollbar { scrollbar-width: thin; scrollbar-color: #1f2937 #0B0F19; }
</style>