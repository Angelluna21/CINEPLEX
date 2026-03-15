@extends('layouts.app')

@section('title', 'Funciones Programadas - Admin')

@section('sidebar')
<nav class="flex flex-col gap-2 font-roboto h-full">
    <a href="/admin" class="flex items-center gap-3 text-gray-400 px-4 py-3 rounded-md hover:bg-gray-800 transition">
        <i class="bi bi-house-door"></i> Inicio
    </a>
    
    <h3 class="text-gray-500 text-xs font-montserrat mt-6 mb-2 uppercase tracking-wider font-bold">Catálogos</h3>
    <a href="/admin/peliculas" class="flex items-center gap-3 text-gray-400 px-4 py-2 rounded transition hover:text-white hover:bg-gray-800/50">
        <i class="bi bi-film"></i> Películas
    </a>

    <h3 class="text-gray-500 text-xs font-montserrat mt-6 mb-2 uppercase tracking-wider font-bold">Operaciones</h3>
    <a href="/admin/funciones" class="flex items-center gap-3 text-white bg-gray-800/50 border border-gray-700 px-4 py-2 rounded transition">
        <i class="bi bi-calendar-date text-cinemagenta"></i> Programar Funciones
    </a>
</nav>
@endsection

@section('content')
<section class="bg-cinecard p-6 md:p-8 rounded-lg shadow-lg border border-gray-800 max-w-5xl mx-auto">

    <header class="mb-6 border-b border-gray-700 pb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-montserrat font-bold text-white">Funciones Programadas</h2>
            <p class="font-roboto text-sm text-gray-400 mt-1">Administra la cartelera de todas las sucursales.</p>
        </div>
        <a href="/admin/funciones/create" class="bg-[#4CAF50] hover:bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 font-roboto font-bold transition text-sm">
            <i class="bi bi-plus-lg"></i> Nueva Función
        </a>
    </header>

    @if(session('success'))
        <aside role="alert" class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6 flex items-center gap-3 font-roboto animate-fade-in-down">
            <i class="bi bi-check-circle-fill text-xl"></i>
            <p class="font-bold">{{ session('success') }}</p>
        </aside>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm font-roboto">
            <thead>
                <tr class="bg-gray-800/50 border-b border-gray-700 text-gray-400">
                    <th class="p-3 font-bold">Película</th>
                    <th class="p-3 font-bold">Sucursal y Sala</th>
                    <th class="p-3 font-bold">Fecha</th>
                    <th class="p-3 font-bold">Hora</th>
                    <th class="p-3 font-bold">Precio</th>
                    <th class="p-3 font-bold text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($funciones as $funcion)
                    <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition text-gray-200">
                        <td class="p-3">{{ $funcion->pelicula->titulo }}</td>
                        <td class="p-3">{{ $funcion->sala->sucursal->nombre }} - {{ $funcion->sala->nombre }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($funcion->fecha)->format('d/m/Y') }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($funcion->hora)->format('h:i A') }}</td>
                        <td class="p-3">${{ number_format($funcion->precio, 2) }}</td>
                        <td class="p-3 flex justify-center gap-2">
                            
                            <a href="/admin/funciones/{{ $funcion->id }}/edit" class="bg-cinecyan/10 border border-cinecyan text-cinecyan hover:bg-cinecyan hover:text-white px-3 py-1 rounded transition" title="Modificar">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="/admin/funciones/{{ $funcion->id }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar y eliminar esta función? Esto la quitará de la cartelera inmediatamente.');">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="bg-[#EB3F35]/10 border border-[#EB3F35] text-[#EB3F35] hover:bg-[#EB3F35] hover:text-white px-3 py-1 rounded transition" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500 italic">
                            No hay funciones programadas actualmente. ¡Haz clic en "Nueva Función" para comenzar!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>
@endsection