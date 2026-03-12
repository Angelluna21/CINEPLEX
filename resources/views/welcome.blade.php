@extends('layouts.app')

@section('title', 'Cartelera - Cineplex')

@section('header-actions')
    <select class="bg-cinebg text-white border border-gray-700 rounded px-3 py-1 font-roboto outline-none focus:border-cinecyan">
        <option value="">Todas las sucursales</option>
        <option value="1">CDMX Norte</option>
        <option value="2">CDMX Oriente</option>
        <option value="3">Reforma</option>
        <option value="4">Perisur</option>
    </select>

    <button class="flex items-center gap-2 bg-transparent border border-cinecyan text-cinecyan hover:bg-cinecyan hover:text-white px-4 py-2 rounded transition font-roboto">
        <i class="bi bi-box-arrow-in-right"></i> Ingresar
    </button>
@endsection

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-montserrat font-medium text-white mb-6 border-b border-gray-800 pb-2">
            EN CARTELERA
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            
            @foreach($peliculas as $pelicula)
                <div class="bg-cinecard rounded-lg overflow-hidden shadow-lg hover:shadow-cinecyan/20 transition-shadow duration-300 flex flex-col">
                    <img src="{{ $pelicula->imagen_url }}" alt="Póster de {{ $pelicula->titulo }}" 
                         class="w-full aspect-[2/3] object-cover">
                    
                    <div class="p-4 flex-1 flex flex-col justify-between items-center text-center">
                        <div>
                            <h3 class="font-montserrat font-bold text-lg text-white mb-1">{{ $pelicula->titulo }}</h3>
                            <p class="font-roboto text-sm text-gray-400 mb-2">{{ $pelicula->genero }}</p>
                        </div>
                        
                        <div class="flex items-center justify-center gap-3 mt-3 w-full">
                            <span class="flex items-center gap-1 text-xs text-gray-300 font-roboto">
                                <i class="bi bi-clock"></i> {{ $pelicula->duracion }} min
                            </span>
                            <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded border border-gray-600">
                                Clas. {{ $pelicula->clasificacion }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection