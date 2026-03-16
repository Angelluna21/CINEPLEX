@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-4xl">
    <div class="bg-[#151E2E] shadow-2xl rounded-3xl overflow-hidden border border-gray-800">
        <div class="bg-gray-900/50 border-b border-gray-800 px-8 py-6">
            <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#00BCD4] to-[#42A5F5]">Registrar Nueva Sala</h2>
        </div>

        <form action="{{ route('salas.store') }}" method="POST" class="p-8 space-y-6 bg-[#151E2E]">
            @csrf

            <div>
                <label for="sucursal_id" class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wider ml-1">Sucursal</label>
                <select name="sucursal_id" id="sucursal_id" 
                    class="block w-full border border-gray-700 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 bg-[#0B0F19] text-white font-medium transition-all appearance-none @error('sucursal_id') border-red-500 @enderror" required>
                    <option value="" class="text-gray-500">-- Selecciona la ubicación --</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}" {{ old('sucursal_id') == $sucursal->id ? 'selected' : '' }}>
                            {{ $sucursal->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('sucursal_id')
                    <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="nombre" class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wider ml-1">Tipo de Sala</label>
                    <select name="nombre" id="nombre" onchange="updateSalaInfo()" 
                        class="block w-full border border-gray-700 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 bg-[#0B0F19] text-white font-medium transition-all appearance-none">
                        <option value="Tradicional">Tradicional</option>
                        <option value="3D">3D</option>
                        <option value="4D">4D</option>
                        <option value="IMAX">IMAX</option>
                    </select>
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wider ml-1">Número de Sala</label>
                    <input type="text" id="numero_display" value="1" 
                        class="block w-full border border-gray-800 rounded-xl shadow-sm py-3 px-4 bg-[#0B0F19]/50 text-gray-500 font-bold cursor-not-allowed focus:outline-none" readonly>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wider ml-1">Capacidad (Asientos)</label>
                    <input type="text" id="capacidad_display" value="100" 
                        class="block w-full border border-gray-800 rounded-xl shadow-sm py-3 px-4 bg-[#0B0F19]/50 text-gray-500 font-bold cursor-not-allowed focus:outline-none" readonly>
                </div>
            </div>

            <div>
                <label for="estatus" class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wider ml-1">Estatus Operativo</label>
                <select name="estatus" id="estatus" 
                    class="block w-full border border-gray-700 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 bg-[#0B0F19] text-white font-medium transition-all appearance-none @error('estatus') border-red-500 @enderror" required>
                    <option value="Disponible" {{ old('estatus') == 'Disponible' ? 'selected' : '' }}>🟢 Disponible</option>
                    <option value="Fuera de servicio" {{ old('estatus') == 'Fuera de servicio' ? 'selected' : '' }}>🔴 Fuera de servicio (Mantenimiento)</option>
                </select>
                @error('estatus')
                    <p class="text-red-500 text-xs mt-2 font-bold ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4 pt-8 border-t border-gray-800 mt-8">
                <a href="{{ route('salas.index') }}" 
                   class="bg-gray-800/40 border border-gray-700 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-xl shadow-sm text-sm transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-gradient-to-r from-[#00BCD4] to-[#42A5F5] hover:from-[#42A5F5] hover:to-[#00BCD4] text-white font-bold py-3 px-10 rounded-xl shadow-[0_0_15px_rgba(0,188,212,0.3)] text-sm transform hover:scale-105 transition duration-200 uppercase tracking-widest">
                    Guardar Sala
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Función para cambiar Número y Asientos al mismo tiempo
function updateSalaInfo() {
    const datos = {
        'Tradicional': { num: 1, cap: 100 },
        '3D':          { num: 2, cap: 120 },
        '4D':          { num: 3, cap: 60 },
        'IMAX':        { num: 4, cap: 200 }
    };
    const seleccion = document.getElementById('nombre').value;
    
    document.getElementById('numero_display').value = datos[seleccion].num;
    document.getElementById('capacidad_display').value = datos[seleccion].cap;
}

// Para que se cargue bien la primera vez que abres la pantalla
document.addEventListener('DOMContentLoaded', updateSalaInfo);
</script>
@endsection