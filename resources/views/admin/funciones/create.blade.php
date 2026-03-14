@extends('layouts.app')

@section('title', 'Nueva Función - Admin')

@section('sidebar')
<nav class="flex flex-col gap-2 font-roboto h-full">
    <a href="/admin" class="flex items-center gap-3 text-gray-400 px-4 py-3 rounded-md hover:bg-gray-800 transition"><i class="bi bi-house-door"></i> Inicio</a>
    <h3 class="text-gray-500 text-xs font-montserrat mt-6 mb-2 uppercase tracking-wider font-bold">Operaciones</h3>
    <a href="/admin/funciones" class="flex items-center gap-3 text-white bg-gray-800/50 border border-gray-700 px-4 py-2 rounded transition">
        <i class="bi bi-calendar-date text-cinemagenta"></i> Programar Funciones
    </a>
</nav>
@endsection

@section('content')
<section class="bg-cinecard p-6 md:p-8 rounded-lg shadow-lg border border-gray-800 max-w-3xl mx-auto">

    <header class="mb-6 border-b border-gray-700 pb-4">
        <h2 class="text-2xl font-montserrat font-bold text-white">Programar Nueva Función</h2>
        <p class="font-roboto text-sm text-gray-400 mt-1">Selecciona los datos de la proyección. Los campos con * son obligatorios.</p>
    </header>

    <form action="/admin/funciones" method="POST" class="font-roboto flex flex-col gap-5">
            @csrf 

            <fieldset class="grid grid-cols-1 md:grid-cols-2 gap-5 border-none p-0 m-0">
                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Película *
                    <select name="pelicula_id" required class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal cursor-pointer">
                        <option value="">-- Selecciona una Película --</option>
                        @foreach($peliculas as $pelicula)
                            <option value="{{ $pelicula->id }}">{{ $pelicula->titulo }} ({{ $pelicula->clasificacion }})</option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Sucursal y Sala *
                    <select name="sala_id" required class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal cursor-pointer">
                        <option value="">-- Selecciona una Sala --</option>
                        @foreach($salas as $sala)
                            <option value="{{ $sala->id }}">{{ $sala->sucursal->nombre }} - {{ $sala->nombre }}</option>
                        @endforeach
                    </select>
                </label>
            </fieldset>

            <fieldset class="grid grid-cols-1 md:grid-cols-3 gap-5 border-none p-0 m-0">
                
                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Fecha de la Función *
                    <input type="date" name="fecha" required 
                           min="{{ now()->format('Y-m-d') }}" 
                           max="{{ now()->addMonth()->format('Y-m-d') }}"
                           class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal cursor-pointer">
                </label>

                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Hora de la Función *
                    <input type="time" name="hora" required 
                           class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal cursor-pointer">
                </label>

                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Precio del Boleto ($) *
                    <input type="number" name="precio" step="0.50" min="0" required placeholder="Ej. 65.00" 
                           class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal">
                </label>
            </fieldset>

            <footer class="mt-4 flex justify-end gap-3">
                <a href="/admin/funciones" class="bg-transparent border border-gray-600 text-gray-300 hover:bg-gray-800 px-4 py-2 rounded transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-[#4CAF50] hover:bg-green-600 text-white px-6 py-2 rounded flex items-center gap-2 font-roboto font-bold transition">
                    <i class="bi bi-floppy"></i> Agendar Función
                </button>
            </footer>
        </form>
</section>
@endsection