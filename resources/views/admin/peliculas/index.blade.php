<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Películas - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8">

    <div class="max-w-6xl mx-auto mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Volver al Panel</span>
        </a>
    </div>

    <div class="max-w-6xl mx-auto bg-[#151E2E] p-8 rounded-2xl shadow-2xl border border-gray-800">
        
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-500/10 border border-green-500/50 text-green-400 rounded-2xl flex items-center gap-3 shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                <i class="bi bi-check-circle-fill text-2xl"></i>
                <span class="font-bold tracking-wide">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Cartelera</h1>
            <a href="{{ route('peliculas.create') }}" class="bg-[#E91E63] hover:bg-pink-600 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg hover:scale-105">
                + Nueva Película
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($peliculas as $pelicula)
            <div class="bg-[#0B0F19] p-5 rounded-2xl border border-gray-800 hover:border-pink-500/50 transition-all group">
                
                <div class="aspect-[2/3] bg-gray-900 rounded-xl mb-4 flex items-center justify-center border border-gray-800 group-hover:bg-gray-800 transition-colors relative overflow-hidden">
                    @if($pelicula->imagen_url)
                        <img src="{{ $pelicula->imagen_url }}" alt="Póster" class="w-full h-full object-cover">
                    @else
                        <i class="bi bi-film text-gray-700 text-6xl"></i>
                    @endif
                </div>
                <h3 class="text-xl font-bold mb-1">{{ $pelicula->titulo }}</h3>
                <div class="flex gap-2 mb-3">
                    <span class="text-[10px] bg-gray-800 px-2 py-0.5 rounded border border-gray-700 text-gray-400">{{ $pelicula->idioma }}</span>
                    <span class="text-[10px] bg-purple-500/10 px-2 py-0.5 rounded border border-purple-500/30 text-purple-400">{{ $pelicula->formato }}</span>
                </div>
                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-800">
                    <a href="{{ route('peliculas.edit', $pelicula->id) }}" class="text-blue-400 hover:text-blue-200 text-sm font-bold uppercase">Editar</a>
                    
                    <form action="{{ route('peliculas.destroy', $pelicula->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta película?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-200 text-sm font-bold uppercase">Eliminar</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-[#0B0F19] rounded-2xl border border-dashed border-gray-800">
                <i class="bi bi-camera-reels text-5xl text-gray-700 mb-4 block"></i>
                <p class="text-gray-500">No hay películas registradas en la base de datos.</p>
            </div>
            @endforelse
        </div>
    </div>
</body>
</html>