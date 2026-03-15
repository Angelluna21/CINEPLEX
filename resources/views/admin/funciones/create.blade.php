@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-4xl">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-bold text-white">Programar Nueva Función</h2>
            <p class="text-sm text-gray-300 mt-1">Asigna una película a una sala disponible. Solo se permiten 2 funciones por día en cada sala.</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-6 rounded-md shadow-sm" role="alert">
                <p class="font-bold">¡Atención!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('funciones.store') }}" method="POST" class="p-6 space-y-6 bg-white">
            @csrf 

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-black mb-1">Película *</label>
                    <select name="pelicula_id" required class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium cursor-pointer">
                        <option value="">-- Selecciona una Película --</option>
                        @foreach($peliculas as $pelicula)
                            <option value="{{ $pelicula->id }}" {{ old('pelicula_id') == $pelicula->id ? 'selected' : '' }}>
                                {{ $pelicula->titulo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-1">Sucursal y Sala *</label>
                    <select name="sala_id" id="sala_id" required onchange="actualizarPrecio()" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium cursor-pointer">
                        <option value="">-- Selecciona una Sala --</option>
                        @foreach($salas as $sala)
                            <option value="{{ $sala->id }}" data-tipo="{{ $sala->nombre }}" {{ old('sala_id') == $sala->id ? 'selected' : '' }}>
                                {{ $sala->sucursal->nombre }} - Sala {{ $sala->numero }} ({{ $sala->nombre }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-black mb-1">Fecha de Función *</label>
                    <input type="date" name="fecha" required value="{{ old('fecha') }}"
                           min="{{ now()->format('Y-m-d') }}" 
                           max="{{ now()->addMonth()->format('Y-m-d') }}"
                           class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium cursor-pointer">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-1">Hora (Turno) *</label>
                    <div class="flex items-center space-x-6 bg-gray-50 border border-gray-300 rounded-md py-2 px-4 h-[42px]">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="hora" value="16:00" required class="form-radio text-blue-600 h-4 w-4 cursor-pointer" {{ old('hora') == '16:00' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700">4:00 PM</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="hora" value="18:00" required class="form-radio text-blue-600 h-4 w-4 cursor-pointer" {{ old('hora') == '18:00' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm font-medium text-gray-700">6:00 PM</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-1">Precio Sugerido</label>
                    <input type="text" id="precio_display" placeholder="Se asigna por sala..." 
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-green-50 text-green-800 font-bold cursor-not-allowed" readonly>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                <a href="{{ route('funciones.index') }}" 
                   class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-6 rounded-lg shadow-sm text-sm transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow-lg text-sm transform hover:scale-105 transition duration-200 flex items-center gap-2">
                    Agendar Función
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function actualizarPrecio() {
    // Tarifario maestro
    const precios = {
        'Tradicional': 80.00,
        '3D': 105.00,
        '4D': 120.00,
        'IMAX': 135.00
    };
    
    const selectSala = document.getElementById('sala_id');
    const inputDisplay = document.getElementById('precio_display');
    
    if (selectSala.selectedIndex > 0) {
        const opcion = selectSala.options[selectSala.selectedIndex];
        const tipo = opcion.getAttribute('data-tipo');
        
        if (tipo && precios[tipo]) {
            inputDisplay.value = '$ ' + precios[tipo].toFixed(2) + ' MXN';
        }
    } else {
        inputDisplay.value = '';
    }
}

// Ejecutar al cargar
document.addEventListener('DOMContentLoaded', actualizarPrecio);
</script>
@endsection