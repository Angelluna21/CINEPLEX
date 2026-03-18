@extends('layouts.app')

@section('title', $pelicula->titulo . ' - Cineplex')

@section('content')
<div class="max-w-6xl mx-auto bg-[#151E2E] rounded-3xl p-6 md:p-10 border border-gray-800 shadow-2xl mt-4 md:mt-8">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div class="md:col-span-1">
            @if($pelicula->imagen_url)
                <img src="{{ $pelicula->imagen_url }}" alt="{{ $pelicula->titulo }}" class="w-full rounded-2xl shadow-[0_0_30px_rgba(66,165,245,0.15)] border border-gray-700 object-cover aspect-[2/3]">
            @else
                <div class="w-full aspect-[2/3] bg-[#0B0F19] rounded-2xl flex items-center justify-center border border-gray-700 shadow-lg">
                    <i class="bi bi-film text-6xl text-gray-600"></i>
                </div>
            @endif
        </div>

        <div class="md:col-span-2 flex flex-col justify-center">
            <h2 class="text-4xl md:text-5xl font-black mb-4 text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63] uppercase tracking-tight">
                {{ $pelicula->titulo }}
            </h2>
            
            <div class="flex flex-wrap gap-3 mb-8">
                <span class="px-4 py-1 bg-gray-800 rounded-full text-sm font-bold border border-gray-600 shadow-sm">{{ $pelicula->clasificacion }}</span>
                <span class="px-4 py-1 bg-[#42A5F5]/10 text-[#42A5F5] rounded-full text-sm font-semibold border border-[#42A5F5]/30">{{ $pelicula->duracion }} min</span>
                <span class="px-4 py-1 bg-[#E91E63]/10 text-[#E91E63] rounded-full text-sm font-semibold border border-[#E91E63]/30">{{ $pelicula->genero }}</span>
                <span class="px-4 py-1 bg-purple-500/10 text-purple-400 rounded-full text-sm font-semibold border border-purple-500/30">{{ $pelicula->idioma }} | {{ $pelicula->formato }}</span>
            </div>

            <h3 class="text-xl font-bold mb-3 text-white border-b border-gray-800 pb-2">Sinopsis</h3>
            <p class="text-gray-400 leading-relaxed mb-10 text-lg">
                {{ $pelicula->sinopsis ?? 'Sin descripción disponible por el momento.' }}
            </p>

            @if($pelicula->funciones->count() > 0)
                @php
                    $funcionesPorSucursal = $pelicula->funciones->groupBy(function($funcion) {
                        return $funcion->sala->sucursal->nombre ?? 'Ubicación desconocida';
                    });
                @endphp

                <div class="mb-8 flex flex-col md:flex-row items-center justify-between gap-4 bg-[#151E2E] p-4 rounded-2xl border border-gray-800 shadow-lg">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="bi bi-ticket-perforated text-[#42A5F5]"></i> Horarios Disponibles
                    </h3>

                    <div class="relative w-full md:w-72">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="bi bi-geo-alt-fill text-[#E91E63]"></i>
                        </div>
                        <select id="filtro-sucursal" class="block w-full pl-10 pr-10 py-3 bg-[#0B0F19] border border-gray-700 rounded-xl text-white font-bold focus:ring-2 focus:ring-[#42A5F5] focus:border-[#42A5F5] appearance-none cursor-pointer transition-all shadow-inner">
                            <option value="todas">Todas las sucursales</option>
                            @foreach($funcionesPorSucursal->keys() as $nombreSucursal)
                                <option value="{{ Str::slug($nombreSucursal) }}">{{ $nombreSucursal }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="bi bi-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div id="lista-sucursales">
                    @foreach($funcionesPorSucursal as $sucursal => $funciones)
                        <div class="sucursal-caja mb-8 bg-[#151E2E] p-5 rounded-2xl border border-gray-800 transition-all duration-300" data-sucursal="{{ Str::slug($sucursal) }}">
                            <h4 class="text-lg font-black text-white mb-4 flex items-center gap-2 tracking-wide uppercase">
                                <i class="bi bi-geo-alt-fill text-[#E91E63]"></i> Cineplex {{ $sucursal }}
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($funciones as $funcion)
                                    <a href="{{ route('comprar.asientos', $funcion->id) }}" class="block bg-[#0B0F19] p-4 rounded-xl border border-gray-700 text-center hover:border-[#42A5F5] hover:shadow-[0_0_15px_rgba(66,165,245,0.2)] transition-all group cursor-pointer relative overflow-hidden">
                                        <p class="text-sm text-gray-400 mb-1 capitalize">{{ \Carbon\Carbon::parse($funcion->fecha)->translatedFormat('d M') }}</p>
                                        <p class="text-2xl font-black text-white group-hover:text-[#42A5F5] transition-colors">{{ \Carbon\Carbon::parse($funcion->hora)->format('H:i') }}</p>
                                        <p class="text-xs text-gray-500 mt-1 font-bold">Sala {{ $funcion->sala->nombre ?? 'X' }}</p>
                                        <div class="mt-3 bg-[#42A5F5]/10 text-[#42A5F5] text-xs py-1.5 rounded-md font-bold group-hover:bg-[#42A5F5] group-hover:text-white transition-colors">
                                            ELEGIR ASIENTOS
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-4 text-orange-400 flex items-center gap-3">
                    <i class="bi bi-info-circle text-xl"></i>
                    <p>No hay funciones programadas para esta película por el momento. ¡Vuelve pronto!</p>
                </div>
            @endif
        </div>
    </div>
    
    <div class="mt-12 text-center md:text-left border-t border-gray-800 pt-6">
        <a href="/" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-xl transition-all shadow-lg">
            <i class="bi bi-arrow-left"></i> Volver a la Cartelera
        </a>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filtro = document.getElementById('filtro-sucursal');
        const cajasSucursales = document.querySelectorAll('.sucursal-caja');

        if(filtro) {
            filtro.addEventListener('change', function() {
                const seleccion = this.value;

                cajasSucursales.forEach(caja => {
                    if (seleccion === 'todas' || caja.dataset.sucursal === seleccion) {
                        caja.style.display = 'block';
                        caja.style.opacity = '0';
                        setTimeout(() => caja.style.opacity = '1', 50);
                    } else {
                        caja.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection