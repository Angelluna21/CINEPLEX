@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-6xl font-sans text-white">

    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Atrás</span>
        </a>
    </div>

    <div class="bg-[#151E2E] p-8 rounded-3xl shadow-2xl border border-gray-800">
        
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-500/10 border border-green-500/50 text-green-400 rounded-2xl flex items-center gap-3 shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                <i class="bi bi-check-circle-fill text-2xl"></i>
                <span class="font-bold tracking-wide">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Cartelera de Películas</h1>
                <p class="text-gray-400 text-sm mt-1">Administra los títulos, formatos y disponibilidad.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <div class="relative flex-grow sm:flex-grow-0">
                    <input type="text" id="buscadorPeliculas" placeholder="Buscar por título..." 
                        class="w-full sm:w-64 bg-[#0B0F19] border border-gray-700 rounded-xl py-2.5 pl-10 pr-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner placeholder-gray-500 autocomplete-off">
                    <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                </div>

                <a href="{{ route('peliculas.create') }}" class="bg-gradient-to-r from-[#42A5F5] to-[#E91E63] hover:scale-105 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-[0_0_15px_rgba(233,30,99,0.3)] uppercase tracking-wide text-sm flex items-center justify-center gap-2 whitespace-nowrap">
                    <i class="bi bi-plus-lg"></i> Nueva Película
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="contenedorPeliculas">
        @forelse($peliculas as $pelicula)
            <div class="tarjeta-pelicula bg-[#0B0F19] rounded-2xl border border-gray-800 hover:border-[#E91E63]/50 transition-all group flex flex-col overflow-hidden shadow-lg" data-titulo="{{ strtolower($pelicula->titulo) }}">
                
                <div class="aspect-[2/3] bg-gray-900 flex items-center justify-center relative overflow-hidden">
                    @if($pelicula->imagen_url)
                        <img src="{{ $pelicula->imagen_url }}" alt="Póster" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <i class="bi bi-film text-gray-700 text-6xl"></i>
                    @endif
                    
                    <div class="absolute top-3 right-3 bg-black/80 backdrop-blur-sm border border-gray-700 text-white text-[10px] font-bold px-2 py-1 rounded uppercase shadow-lg z-10">
                        {{ $pelicula->clasificacion }}
                    </div>
                </div>
                
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold mb-3 leading-tight line-clamp-2 hover:text-[#42A5F5] transition-colors">{{ $pelicula->titulo }}</h3>
                    
                    <div class="flex flex-col gap-2 mb-4">
                        @if($pelicula->idioma && $pelicula->formato)
                            <div class="flex">
                                <span class="text-[11px] bg-purple-500/10 px-3 py-1.5 rounded-md border border-purple-500/30 text-purple-400 font-black uppercase tracking-widest flex items-center gap-2 shadow-inner">
                                    <i class="bi bi-translate text-sm"></i> 
                                    {{ $pelicula->idioma }} 
                                    <span class="text-purple-500/40 px-1">|</span> 
                                    {{ $pelicula->formato }}
                                </span>
                            </div>
                        @endif
                        
                        <div class="flex mt-1">
                            <span class="bg-[#E91E63]/10 border border-[#E91E63]/50 text-[#E91E63] text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">
                                <i class="bi bi-info-circle mr-1"></i> {{ $pelicula->estatus }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-auto flex justify-between items-center pt-4 border-t border-gray-800">
                        <a href="{{ route('peliculas.edit', $pelicula->id) }}" class="text-[#42A5F5] hover:text-white text-xs font-bold uppercase tracking-wider flex items-center gap-1 transition-colors">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        
                        <form action="{{ route('peliculas.destroy', $pelicula->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta película? Perderás las funciones asociadas.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-300 text-xs font-bold uppercase tracking-wider flex items-center gap-1 transition-colors">
                                <i class="bi bi-trash3-fill"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-[#0B0F19] rounded-2xl border border-dashed border-gray-800">
                <div class="w-20 h-20 mx-auto bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-camera-reels text-4xl text-gray-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-300 mb-2">Cartelera Vacía</h3>
                <p class="text-gray-500 max-w-md mx-auto">Aún no hay películas registradas en la base de datos.</p>
            </div>
            @endforelse

            <div id="mensajeSinResultados" class="hidden col-span-full py-20 text-center bg-[#0B0F19] rounded-2xl border border-dashed border-gray-800">
                <div class="w-20 h-20 mx-auto bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-search text-4xl text-gray-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-300 mb-2">No se encontraron resultados</h3>
                <p class="text-gray-500 max-w-md mx-auto">No hay ninguna película que coincida con tu búsqueda.</p>
            </div>

        </div>
    </div>
</div>

@endsection