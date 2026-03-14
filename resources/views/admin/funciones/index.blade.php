@extends('layouts.app')

@section('title', 'Programación de Funciones - Admin')

@section('sidebar')
    <nav class="flex flex-col gap-2 font-roboto h-full">
        <a href="/admin" class="flex items-center gap-3 text-gray-400 px-4 py-3 rounded-md hover:bg-gray-800 transition">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        <h3 class="text-gray-500 text-xs font-montserrat mt-6 mb-2 uppercase tracking-wider font-bold">Catálogos</h3>
        <a href="/admin/peliculas" class="flex items-center gap-3 text-gray-400 px-4 py-2 hover:bg-gray-800 transition rounded">
            <i class="bi bi-film"></i> Películas
        </a>
        <h3 class="text-gray-500 text-xs font-montserrat mt-4 mb-2 uppercase tracking-wider font-bold">Operaciones</h3>
        
        <a href="/admin/funciones" class="flex items-center gap-3 text-white bg-gray-800/50 border border-gray-700 px-4 py-2 rounded transition">
            <i class="bi bi-calendar-date text-cinemagenta"></i> Programar Funciones
        </a>
    </nav>
@endsection

@section('content')
    <section class="bg-cinecard p-6 md:p-8 rounded-lg shadow-lg border border-gray-800">
        
        <header class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
            <h2 class="text-2xl font-montserrat font-bold text-white">Programación de Funciones</h2>
            <a href="/admin/funciones/create" class="bg-[#4CAF50] hover:bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2 font-roboto transition">
                <i class="bi bi-plus-circle"></i> Nueva Función
            </a>
        </header>

        @if(session('success'))
            <aside role="alert" class="bg-[#4CAF50]/10 border border-[#4CAF50] text-[#4CAF50] px-4 py-3 rounded mb-6 flex items-center gap-3 font-roboto animate-fade-in-down">
                <i class="bi bi-check-circle-fill text-xl"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </aside>
        @endif

        <section class="overflow-x-auto">
            <table class="w-full text-left font-roboto text-sm text-gray-300">
                <thead class="bg-[#0B0F19] text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">ID</th>
                        <th class="px-4 py-3">Película</th>
                        <th class="px-4 py-3">Sucursal y Sala</th>
                        <th class="px-4 py-3">Fecha y Hora</th>
                        <th class="px-4 py-3 text-center rounded-tr-lg">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($funciones as $funcion)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-4 py-4">{{ $funcion->id }}</td>
                            <td class="px-4 py-4 font-bold text-white">{{ $funcion->pelicula->titulo }}</td>
                            <td class="px-4 py-4 text-gray-400">
                                {{ $funcion->sala->sucursal->nombre }} <br> 
                                <span class="text-xs text-cinecyan">{{ $funcion->sala->nombre }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <time datetime="{{ $funcion->fecha_hora }}" class="text-cinemagenta font-bold">
                                    {{ \Carbon\Carbon::parse($funcion->fecha_hora)->format('d/m/Y - h:i A') }}
                                </time>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <menu class="flex items-center justify-center gap-3 p-0 m-0">
                                    <a href="/admin/funciones/{{ $funcion->id }}/edit" class="text-[#FFC107] hover:opacity-80 transition" title="Modificar">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </a>
                                    <form action="/admin/funciones/{{ $funcion->id }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta función?');" class="m-0 p-0">
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
        </section>

    </section>
@endsection