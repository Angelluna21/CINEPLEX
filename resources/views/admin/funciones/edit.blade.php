@extends('layouts.app')

@section('title', 'Editar Función - Admin')

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
        <h2 class="text-2xl font-montserrat font-bold text-white">Editar Función Programada</h2>
        <p class="font-roboto text-sm text-gray-400 mt-1">Modifica los datos de la proyección.</p>
    </header>

    @if(session('error'))
        <aside role="alert" class="bg-[#EB3F35]/10 border border-[#EB3F35] text-[#EB3F35] px-4 py-3 rounded mb-6 flex items-center gap-3 font-roboto animate-fade-in-down">
            <i class="bi bi-exclamation-triangle-fill text-xl"></i>
            <p class="font-bold">{{ session('error') }}</p>
        </aside>
    @endif

    <form action="/admin/funciones/{{ $funcion->id }}" method="POST" class="font-roboto flex flex-col gap-5">
        @csrf 
        @method('PUT')

        <fieldset class="grid grid-cols-1 md:grid-cols-2 gap-5 border-none p-0 m-0">
            <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                Película *
                <select name="pelicula_id" required class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal cursor-pointer">
                    @foreach($peliculas as $pelicula)
                        <option value="{{ $pelicula->id }}" {{ $funcion->pelicula_id == $pelicula->id ? 'selected' : '' }}>
                            {{ $pelicula->titulo }} ({{ $pelicula->clasificacion }})
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                Sucursal y Sala *
                <select name="sala_id" required class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal cursor-pointer">
                    @foreach($salas as $sala)
                        <option value="{{ $sala->id }}" {{ $funcion->sala_id == $sala->id ? 'selected' : '' }}>
                            {{ $sala->sucursal->nombre }} - {{ $sala->nombre }}
                        </option>
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
                       value="{{ $funcion->fecha }}"
                       class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal cursor-pointer">
            </label>

            @php 
                // Extraemos la hora para poder marcar el radio button correcto
                $horaExacta = \Carbon\Carbon::parse($funcion->hora)->format('H:i'); 
            @endphp

            <fieldset class="flex flex-col gap-2 border-none p-0 m-0">
                <legend class="text-gray-300 text-sm font-bold mb-1">Hora de la Función *</legend>
                <div class="flex items-center gap-4 bg-[#0B0F19] border border-gray-700 rounded p-2 h-[42px] justify-center">
                    <label class="flex items-center gap-2 text-white cursor-pointer text-sm font-normal">
                        <input type="radio" name="hora" value="16:00" required class="accent-cinecyan w-4 h-4 cursor-pointer" {{ $horaExacta == '16:00' ? 'checked' : '' }}>
                        4:00 PM
                    </label>
                    <label class="flex items-center gap-2 text-white cursor-pointer text-sm font-normal">
                        <input type="radio" name="hora" value="18:00" required class="accent-cinecyan w-4 h-4 cursor-pointer" {{ $horaExacta == '18:00' ? 'checked' : '' }}>
                        6:00 PM
                    </label>
                </div>
            </fieldset>

            <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                Precio del Boleto ($) *
                <select name="precio" required class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal cursor-pointer">
                    <option value="80.00" {{ $funcion->precio == 80.00 ? 'selected' : '' }}>Sala Tradicional ($80.00)</option>
                    <option value="105.00" {{ $funcion->precio == 105.00 ? 'selected' : '' }}>Sala 3D ($105.00)</option>
                    <option value="135.00" {{ $funcion->precio == 135.00 ? 'selected' : '' }}>Sala VIP / MacroXE ($135.00)</option>
                </select>
            </label>
        </fieldset>

        <footer class="mt-4 flex justify-end gap-3">
            <a href="/admin/funciones" class="bg-transparent border border-gray-600 text-gray-300 hover:bg-gray-800 px-4 py-2 rounded transition">
                Cancelar
            </a>
            <button type="submit" class="bg-[#4CAF50] hover:bg-green-600 text-white px-6 py-2 rounded flex items-center gap-2 font-roboto font-bold transition">
                <i class="bi bi-arrow-repeat"></i> Actualizar Función
            </button>
        </footer>
    </form>
</section>
@endsection