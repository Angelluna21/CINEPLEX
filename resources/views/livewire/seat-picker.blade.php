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
    public $funcionId;    
    public $sessionId;    
    public $asientos = []; 
    public $filasAgrupadas = []; // <-- Variable para que la vista cargue rapidísimo
    public $misSelecciones = []; 
    public $tipoSala = ''; 

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
        $asientosDB = Asiento::where('sala_id', $salaId)
            ->orderBy('fila', 'asc')
            ->orderBy('numero', 'asc')
            ->get();

        // 1. Si la sala está vacía, creamos los asientos por primera vez (INSERCIÓN MASIVA)
        if ($asientosDB->isEmpty()) {
            $asientosPorFila = ($capacidad > 160) ? 20 : (($capacidad > 100) ? 15 : 10);
            $totalFilas = ceil($capacidad / $asientosPorFila);
            $letras = range('A', 'Z');
            
            $asientosNuevos = [];
            $asientosGenerados = 0;

            for ($f = 0; $f < $totalFilas; $f++) {
                for ($i = 1; $i <= $asientosPorFila; $i++) {
                    if ($asientosGenerados >= $capacidad) break;

                    $asientosNuevos[] = [
                        'sala_id' => $salaId,
                        'fila' => $letras[$f],
                        'numero' => $i,
                        'tipo' => 'Tradicional',
                        'created_at' => now(), 
                        'updated_at' => now(),
                    ];
                    $asientosGenerados++;
                }
            }

            // Guardamos todos de un solo golpe en la BD (1 consulta en vez de 150)
            Asiento::insert($asientosNuevos); 

            $asientosDB = Asiento::where('sala_id', $salaId)
                ->orderBy('fila', 'asc')
                ->orderBy('numero', 'asc')
                ->get();
        }

        // 2. Traer el estado actual de las reservaciones
        $reservaciones = DB::table('funcion_asiento')
            ->where('funcion_id', $this->funcionId)
            ->get()
            ->keyBy('asiento_id');

        // 3. Reconstruir matriz plana (para la lógica) y agrupada (para la vista)
        $this->asientos = []; 
        $this->filasAgrupadas = []; 

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

            // Arreglo para manipular los clics rápido
            $this->asientos[$idStr] = [
                'db_id' => $asiento->id,
                'estatus' => $estatus,
                'bloqueado_por' => $bloqueadoPor,
            ];

            // Arreglo ya pre-ordenado para que Blade no trabaje extra
            $this->filasAgrupadas[$asiento->fila][] = [
                'id' => $idStr,
                'numero' => $asiento->numero
            ];
        }
    }

    public function seleccionarAsiento($id)
    {
        $asiento = $this->asientos[$id];
        $dbId = $asiento['db_id'];

        if ($asiento['estatus'] === 'vendido') return;
        
        if ($asiento['estatus'] === 'bloqueado' && $asiento['bloqueado_por'] !== $this->sessionId) {
            return;
        }

        if (in_array($id, $this->misSelecciones)) {
            // Deseleccionar asiento
            $this->misSelecciones = array_diff($this->misSelecciones, [$id]);
            $this->asientos[$id]['estatus'] = 'disponible';
            $this->asientos[$id]['bloqueado_por'] = null;

            DB::table('funcion_asiento')
                ->where('funcion_id', $this->funcionId)
                ->where('asiento_id', $dbId)
                ->where('session_id', $this->sessionId)
                ->delete();
        } else {
            // Seleccionar asiento
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
            
            @foreach ($filasAgrupadas as $fila => $asientosFila)
                <div wire:key="fila-{{ $fila }}" class="flex items-center gap-3 md:gap-6 lg:gap-8">
                    <span class="text-gray-400 font-bold w-6 text-center text-sm lg:text-base">{{ $fila }}</span>
                    
                    <div class="flex gap-2 sm:gap-3 md:gap-4 lg:gap-5">
                        @foreach ($asientosFila as $asiento)
                            @php
                                $estatus = $asientos[$asiento['id']]['estatus'];
                            @endphp

                            <button 
                                wire:key="btn-{{ $asiento['id'] }}"
                                wire:click="seleccionarAsiento('{{ $asiento['id'] }}')"
                                wire:loading.attr="disabled"
                                @class([
                                    'relative rounded-t-xl rounded-b-md flex items-center justify-center font-bold transition-colors duration-300 border-2 border-transparent',
                                    'w-9 h-9 text-[10px] sm:w-10 sm:h-10 sm:text-xs md:w-11 md:h-11 lg:w-12 lg:h-12 lg:text-sm', 
                                    
                                    'bg-green-500 hover:bg-green-400 text-green-900 shadow-[0_0_10px_rgba(34,197,94,0.3)]' => $estatus === 'disponible',
                                    'bg-blue-600 border-blue-400 text-white shadow-[0_0_20px_rgba(37,99,235,0.6)]' => $estatus === 'seleccionado',
                                    'bg-red-600 cursor-not-allowed opacity-50' => $estatus === 'vendido',
                                    'bg-gray-600 cursor-not-allowed border-gray-500' => $estatus === 'bloqueado',
                                ])
                                {{ in_array($estatus, ['vendido', 'bloqueado']) ? 'disabled' : '' }}
                            >
                                @if($estatus === 'bloqueado')
                                    <i class="bi bi-lock-fill text-gray-300"></i>
                                @elseif($estatus === 'seleccionado')
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
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-green-500 shadow-[0_0_5px_rgba(34,197,94,0.5)]"></div> Disponible</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-blue-600 border border-blue-400 shadow-[0_0_5px_rgba(37,99,235,0.5)]"></div> Tu Selección</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-gray-600 flex items-center justify-center text-[10px] text-white"><i class="bi bi-lock-fill"></i></div> En Proceso</div>
        <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-red-600"></div> Vendido</div>
    </div>

    @if(count($misSelecciones) > 0)
        <div class="mt-8 text-center">
            <button class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold py-3 md:py-4 px-8 md:px-12 rounded-full hover:scale-105 transition-all shadow-[0_0_20px_rgba(66,165,245,0.4)] text-lg uppercase tracking-wider">
                Proceder al Pago ({{ count($misSelecciones) }} boletos)
            </button>
        </div>
    @endif
</div>