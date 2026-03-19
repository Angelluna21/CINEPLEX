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
        $asientosDB = Asiento::where('sala_id', $salaId)
    ->orderBy('fila', 'asc')
    ->orderBy('numero', 'asc')
    ->get();
        if ($asientosDB->isEmpty()) {
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

        // 2. Traer el estado actual de las reservaciones
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
                    if ($res->session_id === $this->sessionId || ($res->user_id && $res->user_id === Auth::id())) {
                        $estatus = 'seleccionado';
                        $bloqueadoPor = $this->sessionId;
                        if (!in_array($idStr, $this->misSelecciones)) {
                            $this->misSelecciones[] = $idStr;
                        }
                    } else {
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

        if (in_array($id, $this->misSelecciones)) {
            $this->misSelecciones = array_diff($this->misSelecciones, [$id]);
            
            $this->asientos[$id]['estatus'] = 'disponible';
            $this->asientos[$id]['bloqueado_por'] = null;

            DB::table('funcion_asiento')
                ->where('funcion_id', $this->funcionId)
                ->where('asiento_id', $dbId)
                ->where('session_id', $this->sessionId)
                ->delete();

        } else {
            if(count($this->misSelecciones) >= 10) return; 

            $existe = DB::table('funcion_asiento')
                ->where('funcion_id', $this->funcionId)
                ->where('asiento_id', $dbId)
                ->exists();

            if ($existe) {
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

    #[On('echo:sala.{funcionId},SeatSelected')]
    public function procesarBloqueoDeOtroUsuario($event)
    {
        $asientoId = $event['asiento_id'];
        
        if (!in_array($asientoId, $this->misSelecciones)) {
            $this->asientos[$asientoId]['estatus'] = 'bloqueado';
            $this->asientos[$asientoId]['bloqueado_por'] = $event['session_id'];
        }
    }
};
?>

<div class="w-full max-w-[95%] xl:max-w-[90%] mx-auto bg-[#0B0F19] p-4 md:p-8 lg:p-12 rounded-3xl border border-gray-800 shadow-2xl text-white">
    
    <div class="mb-14 w-full max-w-4xl mx-auto">
        <div class="w-full h-3 bg-gradient-to-r from-transparent via-cyan-500 to-transparent shadow-[0_15px_40px_rgba(66,165,245,0.6)] rounded-full"></div>
        <p class="text-center text-gray-500 text-sm mt-6 tracking-[0.5em] uppercase font-bold">Pantalla {{ $tipoSala }}</p>
    </div>

    <div class="overflow-x-auto pb-12 custom-scrollbar">
        <div class="min-w-max flex flex-col gap-4 md:gap-6 items-center px-4">
            
            @foreach (collect($asientos)->groupBy('fila') as $fila => $asientosFila)
                <div class="flex items-center gap-3 md:gap-6 lg:gap-8">
                    <span class="text-gray-400 font-bold w-6 text-center text-sm lg:text-base">{{ $fila }}</span>
                    
                    <div class="flex gap-2 sm:gap-3 md:gap-4 lg:gap-5">
                        @foreach ($asientosFila as $asiento)
                            <button 
                                wire:click="seleccionarAsiento('{{ $asiento['id'] }}')"
                                @class([
                                    // Base sin brincos
                                    'relative rounded-t-xl rounded-b-md flex items-center justify-center font-bold transition-colors duration-300 border-2 border-transparent',
                                    
                                    // Tamaño proporcional
                                    'w-9 h-9 text-[10px] sm:w-10 sm:h-10 sm:text-xs md:w-11 md:h-11 lg:w-12 lg:h-12 lg:text-sm', 
                                    
                                    // Estados de color
                                    'bg-green-500 hover:bg-green-400 text-green-900 shadow-[0_0_10px_rgba(34,197,94,0.3)]' => $asiento['estatus'] === 'disponible',
                                    'bg-blue-600 border-blue-400 text-white shadow-[0_0_20px_rgba(37,99,235,0.6)]' => $asiento['estatus'] === 'seleccionado',
                                    'bg-red-600 cursor-not-allowed opacity-50' => $asiento['estatus'] === 'vendido',
                                    'bg-gray-600 cursor-not-allowed border-gray-500' => $asiento['estatus'] === 'bloqueado',
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
                    
                    <span class="text-gray-400 font-bold w-6 text-center text-sm lg:text-base hidden md:block">{{ $fila }}</span>
                </div>
            @endforeach
            
        </div>
    </div>

    <div class="bg-[#151E2E] p-4 md:p-6 rounded-2xl border border-gray-800 flex flex-wrap justify-center gap-4 md:gap-6 text-xs md:text-sm text-gray-400 mt-4">
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-green-500"></div> Disponible</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-blue-600 border border-blue-400"></div> Tu Selección</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-gray-600 flex items-center justify-center text-[10px] text-white"><i class="bi bi-lock-fill"></i></div> En Proceso</div>
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
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #0B0F19; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 10px; border: 2px solid #0B0F19; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #374151; }
    .custom-scrollbar { scrollbar-width: thin; scrollbar-color: #1f2937 #0B0F19; }
</style>