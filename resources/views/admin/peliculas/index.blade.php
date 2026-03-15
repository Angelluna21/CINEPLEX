@extends('layouts.app')

@section('title', 'Catálogo de Películas - Admin')

@section('sidebar')
<nav class="flex flex-col gap-2 font-roboto h-full">
    <a href="/admin" class="flex items-center gap-3 text-gray-400 px-4 py-3 rounded-md hover:bg-gray-800 transition">
        <i class="bi bi-house-door"></i> Inicio
    </a>
    
    <h3 class="text-gray-500 text-xs font-montserrat mt-6 mb-2 uppercase tracking-wider font-bold">Catálogos</h3>
    <a href="/admin/peliculas" class="flex items-center gap-3 text-white bg-gray-800/50 border border-gray-700 px-4 py-2 rounded transition">
        <i class="bi bi-film text-cinecyan"></i> Películas
    </a>

    <a href="/admin/sucursales" class="flex items-center gap-3 text-gray-400 px-4 py-2 rounded transition hover:text-white hover:bg-gray-800/50">
        <i class="bi bi-geo-alt"></i> Sucursales
    </a>
    <a href="/admin/salas" class="flex items-center gap-3 text-gray-400 px-4 py-2 rounded transition hover:text-white hover:bg-gray-800/50">
        <i class="bi bi-door-open"></i> Salas
    </a>

    <h3 class="text-gray-500 text-xs font-montserrat mt-6 mb-2 uppercase tracking-wider font-bold">Operaciones</h3>
    <a href="/admin/funciones" class="flex items-center gap-3 text-gray-400 px-4 py-2 rounded transition hover:text-white hover:bg-gray-800/50">
        <i class="bi bi-calendar-date"></i> Programar Funciones
    </a>
</nav>
@endsection

@section('content')
<section class="bg-cinecard p-6 md:p-8 rounded-lg shadow-lg border border-gray-800 max-w-5xl mx-auto">

    <header class="mb-6 border-b border-gray-700 pb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-montserrat font-bold text-white">Catálogo de Películas</h2>
            <p class="font-roboto text-sm text-gray-400 mt-1">Gestiona las películas disponibles en el sistema.</p>
        </div>
        <a href="/admin/peliculas/create" class="bg-[#4CAF50] hover:bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 font-roboto font-bold transition text-sm">
            <i class="bi bi-plus-circle"></i> Nueva Película
        </a>
    </header>

    @if(session('success'))
        <aside role="alert" class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6 flex items-center gap-3 font-roboto">
            <i class="bi bi-check-circle-fill"></i>
            <p class="font-bold">{{ session('success') }}</p>
        </aside>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm font-roboto">
            <thead>
                <tr class="bg-gray-800/50 border-b border-gray-700 text-gray-400">
                    <th class="p-3 font-bold uppercase tracking-wider">ID</th>
                    <th class="p-3 font-bold uppercase tracking-wider">Póster</th>
                    <th class="p-3 font-bold uppercase tracking-wider">Título</th>
                    <th class="p-3 font-bold uppercase tracking-wider">Género</th>
                    <th class="p-3 font-bold uppercase tracking-wider text-center">Clas.</th>
                    <th class="p-3 font-bold uppercase tracking-wider">Estatus</th>
                    <th class="p-3 font-bold uppercase tracking-wider text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peliculas as $pelicula)
                    <tr class="border-b border-gray-700/50 hover:bg-gray-800/30 transition text-gray-200">
                        <td class="p-3 text-gray-500">{{ $pelicula->id }}</td>
                        <td class="p-3">
                            <div class="w-12 h-16 bg-gray-900 border border-gray-700 rounded flex items-center justify-center text-[10px] text-gray-600 text-center p-1">
                                Sin Imagen
                            </div>
                        </td>
                        <td class="p-3 font-bold text-white">{{ $pelicula->titulo }}</td>
                        <td class="p-3">{{ $pelicula->genero }}</td>
                        <td class="p-3 text-center">
                            <span class="bg-gray-700 text-gray-300 px-2 py-1 rounded text-xs font-bold">{{ $pelicula->clasificacion }}</span>
                        </td>
                        <td class="p-3">
                            <span class="text-cinecyan flex items-center gap-1">
                                <i class="bi bi-star-fill text-[10px]"></i> {{ $pelicula->estatus }}
                            </span>
                        </td>
                        <td class="p-3">
                            <div class="flex justify-center gap-2">
                                <a href="/admin/peliculas/{{ $pelicula->id }}/edit" class="text-yellow-500 hover:text-yellow-400 transition" title="Editar">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </a>
                                <form action="/admin/peliculas/{{ $pelicula->id }}" method="POST" onsubmit="return confirm('¿Eliminar esta película?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-400 transition" title="Eliminar">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500 italic">No hay películas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</section>
@endsection