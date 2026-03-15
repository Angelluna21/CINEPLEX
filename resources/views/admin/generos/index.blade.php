@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-4xl">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
        <h1 class="text-3xl font-bold text-gray-800">Catálogo de Géneros</h1>
        
        <div class="flex items-center space-x-4 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="busqueda" placeholder="Buscar género..." 
    class="pl-12 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm text-sm text-gray-900 bg-white">
            </div>

            <a href="{{ route('generos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow whitespace-nowrap text-sm">
                + Nuevo Género
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
                    <th class="py-3 px-4 uppercase font-semibold text-xs text-left">Nombre del Género</th>
                    <th class="py-3 px-4 uppercase font-semibold text-xs text-center w-48">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($generos as $genero)
                <tr class="border-b hover:bg-gray-50 transition duration-150">
                    <td class="py-3 px-4 text-sm">{{ $genero->id }}</td>
                    <td class="py-3 px-4 font-medium text-sm">{{ $genero->nombre }}</td>
                    <td class="py-3 px-4">
                        <div class="flex justify-center items-center space-x-2">
                            <a href="{{ route('generos.edit', $genero->id) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1.5 px-3 rounded text-xs w-20 text-center shadow-sm">
                                Editar
                            </a>
                            <form action="{{ route('generos.destroy', $genero->id) }}" method="POST" class="m-0" onsubmit="return confirm('¿Eliminar este género?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1.5 px-3 rounded text-xs w-20 text-center shadow-sm">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputBusqueda = document.getElementById('busqueda');
        
        inputBusqueda.addEventListener('keyup', function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll('tbody tr');

            filas.forEach(fila => {
                // Selecciona la celda del nombre (segunda columna)
                let nombreGenero = fila.cells[1].textContent.toLowerCase();
                
                if (nombreGenero.includes(filtro)) {
                    fila.style.display = "";
                } else {
                    fila.style.display = "none";
                }
            });
        });
    });
</script>
@endsection