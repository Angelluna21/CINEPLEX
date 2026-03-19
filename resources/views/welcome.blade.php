@extends('layouts.app')

@section('title', 'Cartelera - Cineplex')

@section('header-actions')
<form action="/" method="GET" class="m-0 p-0 flex items-center gap-4">
    <select name="sucursal" onchange="this.form.submit()" 
        class="bg-[#0B0F19] text-white border border-gray-700 rounded px-3 py-1 font-roboto outline-none focus:border-cinecyan cursor-pointer text-sm">
        <option value="">Todas las sucursales</option>
        @foreach($sucursales as $sucursal)
            <option value="{{ $sucursal->id }}" {{ request('sucursal') == $sucursal->id ? 'selected' : '' }}>
                {{ $sucursal->nombre }}
            </option>
        @endforeach
    </select>

    <div class="relative group">
        <input type="text" id="calendario-fechas" name="fecha" placeholder="Ver calendario" 
            class="bg-[#0B0F19] text-white border border-gray-700 rounded px-3 py-1 pl-8 font-roboto outline-none focus:border-cinecyan cursor-pointer text-sm w-36 hover:bg-gray-800 transition-colors"
            value="{{ request('fecha') }}" readonly>
        <i class="bi bi-calendar-event absolute left-2.5 top-1/2 transform -translate-y-1/2 text-cinecyan"></i>
        
        @if(request('fecha'))
            <a href="/?sucursal={{ request('sucursal') }}" class="absolute -right-2 -top-2 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] hover:scale-110 transition-transform shadow-lg" title="Quitar fecha">
                <i class="bi bi-x font-bold"></i>
            </a>
        @endif
    </div>
</form>

<a href="/admin" class="flex items-center gap-2 bg-transparent border border-cinecyan text-cinecyan hover:bg-cinecyan hover:text-white px-4 py-2 rounded transition font-roboto ml-4 text-sm">
    <i class="bi bi-box-arrow-in-right"></i> Ingresar
</a>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Recibimos las fechas exactas con funciones desde tu base de datos
        const fechasDisponibles = @json($fechas_con_funciones);

        flatpickr("#calendario-fechas", {
            locale: "es", // Traducido al español
            dateFormat: "Y-m-d",
            minDate: "today", // Prohíbe seleccionar días pasados
            maxDate: new Date().fp_incr(30), // Límite de 30 días a futuro
            disableMobile: "true", // Fuerza el diseño del cuadrito en celulares
            
            // Esta función "dibuja" cada día en el cuadrito
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                // Sacamos la fecha que se está dibujando en formato YYYY-MM-DD
                const year = dayElem.dateObj.getFullYear();
                const month = String(dayElem.dateObj.getMonth() + 1).padStart(2, '0');
                const day = String(dayElem.dateObj.getDate()).padStart(2, '0');
                const formattedDate = `${year}-${month}-${day}`;

                // Si esa fecha está en la base de datos, le ponemos el circulito verde brillante
                if (fechasDisponibles.includes(formattedDate)) {
                    dayElem.innerHTML += '<span class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-green-500 rounded-full shadow-[0_0_5px_rgba(34,197,94,0.8)]"></span>';
                    dayElem.classList.add('font-bold', 'text-white');
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                // Al darle clic a un día, enviamos el formulario y recargamos la página solos
                instance.element.closest('form').submit();
            }
        });
    });
</script>

<style>
    /* Pequeño ajuste en CSS para que el circulito quede abajo del número sin estorbar */
    .flatpickr-day {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-bottom: 5px; 
    }
</style>
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
            
        <a href="{{ route('pelicula.detalle', $pelicula->id) }}" class="relative w-full aspect-[2/3] overflow-hidden bg-gray-900 block group">
                @if($pelicula->imagen_url)
                    <img src="{{ $pelicula->imagen_url }}" alt="{{ $pelicula->titulo }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-700 p-6 text-center group-hover:text-gray-500 transition-colors">
                        <i class="bi bi-film text-6xl mb-2"></i>
                        <span class="text-[10px] uppercase font-bold tracking-widest">Póster no disponible</span>
                    </div>
                @endif
                
                <div class="absolute top-3 right-3 bg-black/80 backdrop-blur-sm border border-gray-700 text-white text-[10px] font-bold px-2 py-1 rounded uppercase z-10">
    {{ $pelicula->clasificacion }}
</div>

                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/80 backdrop-blur-sm border border-cinecyan text-cinecyan text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest z-10 shadow-[0_0_10px_rgba(0,255,255,0.1)] whitespace-nowrap">
                    {{ $pelicula->estatus }}
                </div>
            </a>

            <div class="p-5 flex-1 flex flex-col">
                <div class="h-16 mb-2">
                    <a href="{{ route('pelicula.detalle', $pelicula->id) }}">
                        <h3 class="text-lg font-bold text-white leading-tight line-clamp-2 hover:text-cinecyan transition-colors">{{ $pelicula->titulo }}</h3>
                    </a>
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