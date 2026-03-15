@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-4xl">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-800 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Registrar Nueva Sala</h2>
        </div>

        <form action="{{ route('salas.store') }}" method="POST" class="p-6 space-y-6 bg-white">
            @csrf

            <div>
                <label for="sucursal_id" class="block text-sm font-bold text-gray-700 mb-1 text-black">Sucursal</label>
                <select name="sucursal_id" id="sucursal_id" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium @error('sucursal_id') border-red-500 @enderror" required>
                    <option value="">-- Selecciona la ubicación --</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}" {{ old('sucursal_id') == $sucursal->id ? 'selected' : '' }}>
                            {{ $sucursal->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('sucursal_id')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="nombre" class="block text-sm font-bold text-black mb-1">Tipo de Sala</label>
                    <select name="nombre" id="nombre" onchange="updateSalaInfo()" 
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium">
                        <option value="Tradicional">Tradicional</option>
                        <option value="3D">3D</option>
                        <option value="4D">4D</option>
                        <option value="IMAX">IMAX</option>
                    </select>
                    @error('nombre')
                        <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-1">Número de Sala</label>
                    <input type="text" id="numero_display" value="1" 
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 text-gray-600 font-bold cursor-not-allowed" readonly>
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-1">Capacidad (Asientos)</label>
                    <input type="text" id="capacidad_display" value="100" 
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 text-gray-600 font-bold cursor-not-allowed" readonly>
                </div>
            </div>

            <div>
                <label for="estatus" class="block text-sm font-bold text-gray-700 mb-1 text-black">Estatus Operativo</label>
                <select name="estatus" id="estatus" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium @error('estatus') border-red-500 @enderror" required>
                    <option value="Disponible" {{ old('estatus') == 'Disponible' ? 'selected' : '' }}>🟢 Disponible</option>
                    <option value="Fuera de servicio" {{ old('estatus') == 'Fuera de servicio' ? 'selected' : '' }}>🔴 Fuera de servicio (Mantenimiento)</option>
                </select>
                @error('estatus')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                <a href="{{ route('salas.index') }}" 
                   class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-6 rounded-lg shadow-sm text-sm transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow-lg text-sm transform hover:scale-105 transition duration-200">
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