@extends('layouts.app')

@section('title', 'Catálogo de Películas - Admin')

@section('header-actions')
<div class="text-white flex items-center gap-4">
    <span class="font-roboto text-sm text-gray-300">
        <i class="bi bi-person-circle mr-1"></i> Empleado
    </span>
    <a href="/" class="text-red-400 hover:text-red-300 font-roboto text-sm transition flex items-center gap-1">
        <i class="bi bi-box-arrow-right"></i> Salir
    </a>
</div>
@endsection

@section('sidebar')
<nav class="flex flex-col gap-2 font-roboto h-full">
    <a href="/admin" class="flex items-center gap-3 text-gray-400 px-4 py-3 rounded-md hover:bg-gray-800 hover:text-white transition">
        <i class="bi bi-house-door"></i> Inicio
    </a>
    <h3 class="text-gray-500 text-xs font-montserrat mt-6 mb-2 uppercase tracking-wider font-bold">Catálogos</h3>
    <a href="/admin/peliculas" class="flex items-center gap-3 text-white bg-gray-800/50 border border-gray-700 px-4 py-2 rounded transition">
        <i class="bi bi-film text-cinecyan"></i> Películas
    </a>
    <a href="#" class="flex items-center gap-3 text-gray-400 px-4 py-2 hover:bg-gray-800 hover:text-white rounded transition">
        <i class="bi bi-geo-alt"></i> Sucursales
    </a>
    <a href="#" class="flex items-center gap-3 text-gray-400 px-4 py-2 hover:bg-gray-800 hover:text-white rounded transition">
        <i class="bi bi-list-ul"></i> Salas
    </a>
    <h3 class="text-gray-500 text-xs font-montserrat mt-4 mb-2 uppercase tracking-wider font-bold">Operaciones</h3>
    <a href="#" class="flex items-center gap-3 text-gray-400 px-4 py-2 hover:bg-gray-800 hover:text-white rounded transition">
        <i class="bi bi-calendar-date text-cinemagenta"></i> Programar Funciones
    </a>
</nav>
@endsection

@section('content')
<div class="bg-cinecard p-6 md:p-8 rounded-lg shadow-lg border border-gray-800">

    <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
        <h2 class="text-2xl font-montserrat font-bold text-white">Catálogo de Películas</h2>
        <a href="/admin/peliculas/create" class="bg-[#4CAF50] hover:bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 font-roboto transition">
            <i class="bi bi-plus-circle"></i> Nueva Película
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left font-roboto text-sm text-gray-300">
            <thead class="bg-[#0B0F19] text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 rounded-tl-lg">ID</th>
                    <th class="px-4 py-3">Póster</th>
                    <th class="px-4 py-3">Título</th>
                    <th class="px-4 py-3">Género</th>
                    <th class="px-4 py-3">Clas.</th>
                    <th class="px-4 py-3">Estatus</th>
                    <th class="px-4 py-3 text-center rounded-tr-lg">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @foreach($peliculas as $pelicula)
                <tr class="hover:bg-gray-800/50 transition">
                    
                    <td class="px-4 py-4">{{ $pelicula->id }}</td>
                    
                    <td class="px-4 py-4">
                        @if($pelicula->imagen_url)
                        <img src="{{ $pelicula->imagen_url }}" alt="Póster" class="w-10 h-14 object-cover rounded border border-gray-600">
                        @else
                        <div class="w-10 h-14 bg-[#0B0F19] flex items-center justify-center rounded border border-gray-700 text-gray-500 font-roboto text-[10px] text-center leading-tight shadow-inner">
                            Sin<br>Imagen
                        </div>
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 font-bold text-white">{{ $pelicula->titulo }}</td>
                    
                    <td class="px-4 py-4">{{ $pelicula->genero }}</td>
                    
                    <td class="px-4 py-4">
                        <span class="bg-gray-700 px-2 py-1 rounded text-xs">{{ $pelicula->clasificacion }}</span>
                    </td>
                    
                    <td class="px-4 py-4">
                        @if($pelicula->estatus == 'Estreno')
                        <span class="text-cinecyan font-bold"><i class="bi bi-star-fill text-xs mr-1"></i>{{ $pelicula->estatus }}</span>
                        @else
                        <span class="text-gray-400">{{ $pelicula->estatus }}</span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        <menu class="flex items-center justify-center gap-3 p-0 m-0">
                            <a href="/admin/peliculas/{{ $pelicula->id }}/edit" class="text-[#FFC107] hover:opacity-80 transition" title="Modificar">
                                <i class="bi bi-pencil-square text-lg"></i>
                            </a>
                            <form action="/admin/peliculas/{{ $pelicula->id }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta película? Esta acción no se puede deshacer.');" class="m-0 p-0">
                                @csrf
                                @method('DELETE') 
                                <button type="submit" class="text-[#EB3F35] hover:opacity-80 transition" title="Eliminar">
                                    <i class="bi bi-trash text-lg"></i>
                                </button>
                            </form>
                        </menu>
                    </td>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection