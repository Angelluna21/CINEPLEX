@extends('layouts.app')

@section('title', 'Cartelera - Cineplex')

@section('header-actions')
<form action="/" method="GET" class="m-0 p-0 flex items-center">
    <select name="sucursal" onchange="this.form.submit()" 
        class="bg-[#0B0F19] text-white border border-gray-700 rounded px-3 py-1 font-roboto outline-none focus:border-cinecyan cursor-pointer text-sm">
        <option value="">Todas las sucursales</option>
        @foreach($sucursales as $sucursal)
            <option value="{{ $sucursal->id }}" {{ isset($sucursal_id) && $sucursal_id == $sucursal->id ? 'selected' : '' }}>
                {{ $sucursal->nombre }}
            </option>
        @endforeach
    </select>
</form>

<a href="/admin" class="flex items-center gap-2 bg-transparent border border-cinecyan text-cinecyan hover:bg-cinecyan hover:text-white px-4 py-2 rounded transition font-roboto ml-4 text-sm">
    <i class="bi bi-box-arrow-in-right"></i> Ingresar
</a>
@endsection

@section('content')
<section class="container mx-auto px-4 mt-8 mb-12">
    <header class="mb-8 border-b border-gray-800 pb-4">
        <h2 class="text-3xl font-montserrat font-bold text-white uppercase tracking-wider">En Cartelera</h2>
        <p class="text-gray-400 font-roboto mt-1">Selecciona tu función y reserva tus lugares.</p>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach($peliculas as $pelicula)
        <article class="bg-[#1a202c] rounded-xl overflow-hidden shadow-2xl border border-gray-800 flex flex-col hover:border-cinecyan/50 transition-all duration-300">
            
            <figure class="relative h-[400px] overflow-hidden bg-gray-900">
                @if($pelicula->imagen_url)
                    <img src="{{ $pelicula->imagen_url }}" alt="{{ $pelicula->titulo }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-700 p-6 text-center">
                        <i class="bi bi-film text-6xl mb-2"></i>
                        <span class="text-[10px] uppercase font-bold tracking-widest">Póster no disponible</span>
                    </div>
                @endif
                <div class="absolute top-3 right-3 bg-black/80 backdrop-blur-sm border border-gray-700 text-white text-[10px] font-bold px-2 py-1 rounded uppercase">
                    {{ $pelicula->clasificacion }}
                </div>
            </figure>

            <div class="p-5 flex-1 flex flex-col">
                <div class="h-16 mb-2">
                    <h3 class="text-lg font-bold text-white leading-tight line-clamp-2">{{ $pelicula->titulo }}</h3>
                    <p class="text-cinecyan text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $pelicula->genero }}</p>
                </div>
                
                <div class="flex items-center gap-4 text-[11px] text-gray-500 mb-4 font-mono">
                    <span class="flex items-center gap-1"><i class="bi bi-clock"></i> {{ $pelicula->duracion }} MIN</span>
                </div>

                <div class="mt-auto border-t border-gray-800 pt-4">
                    <p class="text-[9px] uppercase tracking-[0.2em] text-gray-500 font-black mb-3 text-center">Próximas Funciones</p>
                    
                    @if($pelicula->funciones->count() > 0)
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($pelicula->funciones->take(4) as $funcion)
                                <a href="#" class="flex flex-col items-center justify-center bg-gray-900 border border-gray-700 hover:border-cinemagenta hover:bg-cinemagenta/10 py-2 rounded-lg transition group">
                                    <span class="text-white font-black text-sm">{{ \Carbon\Carbon::parse($funcion->hora)->format('h:i A') }}</span>
                                    <span class="text-[8px] text-gray-500 uppercase font-bold group-hover:text-cinemagenta">{{ $funcion->sala->nombre }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-900/30 border border-dashed border-gray-800 rounded-lg py-3 text-center">
                            <p class="text-[10px] text-gray-600 italic uppercase">Sin funciones hoy</p>
                        </div>
                    @endif
                </div>
            </div>
        </article>
        @endforeach
    </div>
</section>
@endsection