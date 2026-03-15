@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-8 max-w-7xl mt-8">
    <div class="py-8">
        <div class="flex flex-col md:flex-row mb-6 justify-between items-center w-full gap-4">
            <h2 class="text-2xl leading-tight font-bold text-gray-800">
                Cartelera (Funciones Programadas)
            </h2>
            <a href="{{ route('funciones.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-200 whitespace-nowrap">
                + Nueva Función
            </a>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
                <p class="font-bold">¡Éxito!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
            <form action="{{ route('funciones.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                
                <div class="flex-1 w-full">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Buscar Película</label>
                    <input type="text" name="pelicula" value="{{ request('pelicula') }}" placeholder="Ej. Avengers..." 
                           class="w-full border border-gray-300 rounded-md py-2 px-3 text-black bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="w-full md:w-48">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Fecha de Función</label>
                    <input type="date" name="fecha" value="{{ request('fecha') }}" 
                           min="{{ now()->format('Y-m-d') }}" 
                           max="{{ now()->addMonth()->format('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-md py-2 px-3 text-black bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex space-x-2 w-full md:w-auto">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded-md shadow-sm transition w-full md:w-auto">
                        Filtrar
                    </button>
                    @if(request('pelicula') || request('fecha'))
                        <a href="{{ route('funciones.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md shadow-sm transition text-center w-full md:w-auto">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
            <div class="inline-block min-w-full shadow-lg rounded-lg overflow-hidden border border-gray-200">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">Fecha y Hora</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">Película</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">Ubicación</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">Precio</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-center text-xs font-bold text-white uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($funciones as $funcion)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 font-bold whitespace-no-wrap">{{ \Carbon\Carbon::parse($funcion->fecha)->format('d/m/Y') }}</p>
                                    <p class="text-gray-500 whitespace-no-wrap">{{ \Carbon\Carbon::parse($funcion->hora)->format('h:i A') }}</p>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 font-bold whitespace-no-wrap">{{ $funcion->pelicula->titulo }}</p>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 font-medium whitespace-no-wrap">{{ $funcion->sala->sucursal->nombre }}</p>
                                    <p class="text-gray-500 text-xs whitespace-no-wrap">Sala {{ $funcion->sala->numero }} ({{ $funcion->sala->nombre }})</p>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold border border-green-300 shadow-sm">
                                        ${{ number_format($funcion->precio, 2) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                                    <div class="flex justify-center space-x-4">
                                        <a href="{{ route('funciones.edit', $funcion->id) }}" class="text-blue-600 hover:text-blue-900 font-bold transition">Editar</a>
                                        <form action="{{ route('funciones.destroy', $funcion->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar esta función?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition">Cancelar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($funciones->isEmpty())
                    <div class="px-5 py-10 bg-white text-center">
                        <p class="text-gray-500 font-medium">No se encontraron funciones programadas con esos criterios.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection