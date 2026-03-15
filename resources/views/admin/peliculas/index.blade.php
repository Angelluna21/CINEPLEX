@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-6xl">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
        <h1 class="text-3xl font-bold text-gray-800">Catálogo de Películas</h1>
        
        <div class="flex items-center space-x-4 w-full md:w-auto">
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="busqueda" placeholder="Buscar por título o clasificación..." 
                    class="pl-12 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm text-sm text-gray-900 bg-white">
            </div>

            <a href="{{ route('peliculas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow whitespace-nowrap text-sm">
                + Nueva Película
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 uppercase font-semibold text-xs text-left w-16">ID</th>
                    <th class="py-3 px-4 uppercase font-semibold text-xs text-left">Título</th>
                    <th class="py-3 px-4 uppercase font-semibold text-xs text-left">Clasificación</th>
                    <th class="py-3 px-4 uppercase font-semibold text-xs text-left">Duración</th>
                    <th class="py-3 px-4 uppercase font-semibold text-xs text-left">Estatus</th>
                    <th class="py-3 px-4 uppercase font-semibold text-xs text-center w-48">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse($peliculas as $pelicula)
                <tr class="border-b hover:bg-gray-50 transition duration-150">
                    <td class="py-3 px-4 text-sm">{{ $pelicula->id }}</td>
                    <td class="py-3 px-4 font-medium text-sm">{{ $pelicula->titulo }}</td>
                    <td class="py-3 px-4 text-sm">
                        <span class="px-2 py-1 bg-gray-200 rounded text-xs font-bold text-gray-700">
                            {{ $pelicula->clasificacion }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-sm">{{ $pelicula->duracion }} min</td>
                    <td class="py-3 px-4 text-sm">
                        @php
                            $color = match($pelicula->estatus) {
                                'Estreno' => 'bg-green-100 text-green-800',
                                'Cartelera' => 'bg-blue-100 text-blue-800',
                                'Próximamente' => 'bg-yellow-100 text-yellow-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $color }}">
                            {{ $pelicula->estatus }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex justify-center items-center space-x-2">
                            <a href="{{ route('peliculas.edit', $pelicula->id) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1.5 px-3 rounded text-xs w-20 text-center shadow-sm">
                                Editar
                            </a>
                            <form action="{{ route('peliculas.destroy', $pelicula->id) }}" method="POST" class="m-0" onsubmit="return confirm('¿Eliminar?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1.5 px-3 rounded text-xs w-20 text-center shadow-sm">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center text-gray-500 italic">
                        No hay películas registradas. Intenta usar el botón "Nueva Película".
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputBusqueda = document.getElementById('busqueda');
        
        if (inputBusqueda) {
            inputBusqueda.addEventListener('keyup', function() {
                let filtro = this.value.toLowerCase().trim();
                let filas = document.querySelectorAll('tbody tr');

                filas.forEach(fila => {
                    // Obtenemos TODO el texto de la fila (título, género, duración, etc.)
                    let todoElTexto = fila.innerText.toLowerCase();
                    
                    // Esto es para que tú lo veas en la consola de tu Mac
                    console.log("Buscando en: " + todoElTexto); 
                    
                    if (todoElTexto.includes(filtro)) {
                        fila.style.display = "";
                    } else {
                        fila.style.display = "none";
                    }
                });
            });
        }
    });
</script>
@endsection