@extends('layouts.app')

@section('title', 'Elegir Asientos - Cineplex')

@section('content')
<div class="max-w-5xl mx-auto mt-8 px-4 pb-16">
    
    <div class="bg-cinecard rounded-3xl p-6 border border-gray-800 shadow-xl mb-8 flex flex-col md:flex-row items-center gap-6">
        @if($funcion->pelicula->imagen_url)
            <img src="{{ $funcion->pelicula->imagen_url }}" alt="Póster" class="w-24 h-36 object-cover rounded-xl shadow-lg border border-gray-700">
        @else
            <div class="w-24 h-36 bg-[#0B0F19] rounded-xl flex items-center justify-center border border-gray-700"><i class="bi bi-film text-3xl text-gray-600"></i></div>
        @endif
        
        <div class="flex-1 text-center md:text-left">
            <h2 class="text-3xl font-black text-white uppercase tracking-tight mb-2">{{ $funcion->pelicula->titulo }}</h2>
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm font-bold">
                <span class="text-cinecyan bg-cinecyan/10 px-3 py-1 rounded-md"><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($funcion->fecha)->translatedFormat('l, d \d\e F') }}</span>
                <span class="text-cinemagenta bg-cinemagenta/10 px-3 py-1 rounded-md"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($funcion->hora)->format('h:i A') }}</span>
                <span class="text-green-400 bg-green-500/10 px-3 py-1 rounded-md"><i class="bi bi-door-open"></i> Sala {{ $funcion->sala->nombre }}</span>
            </div>
        </div>

        <a href="{{ route('pelicula.detalle', $funcion->pelicula_id) }}" class="text-gray-500 hover:text-white text-sm font-bold transition-colors underline">
            Cambiar función
        </a>
    </div>

    <h3 class="text-2xl font-black mb-6 text-center text-white uppercase tracking-widest">Selecciona tus lugares</h3>
    <livewire:seat-picker :funcionId="$funcion->id" />

</div>
@endsection