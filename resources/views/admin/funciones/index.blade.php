<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera y Funciones - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8 font-sans">

    <div class="max-w-7xl mx-auto mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Volver al Panel</span>
        </a>
    </div>

    <div class="max-w-7xl mx-auto bg-[#151E2E] p-8 rounded-3xl shadow-2xl border border-gray-800">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 text-green-400 rounded-xl flex items-center gap-3">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500">Programación de Funciones</h1>
                <p class="text-gray-400 text-sm mt-1">Asigna películas, horarios y precios a las salas</p>
            </div>
            
            <a href="{{ route('funciones.create') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-pink-600 hover:to-purple-600 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg hover:scale-105 uppercase tracking-wide text-sm">
                + Nueva Función
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-800">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-900/80 text-gray-400 text-xs uppercase tracking-widest">
                        <th class="p-4">ID</th>
                        <th class="p-4">Película</th>
                        <th class="p-4">Sala Asignada</th>
                        <th class="p-4">Fecha y Hora</th>
                        <th class="p-4">Precio</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($funciones as $funcion)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="p-4 text-gray-500 font-mono text-sm">#{{ $funcion->id }}</td>
                        <td class="p-4 font-bold text-gray-200">
                            Película ID: {{ $funcion->pelicula_id }}
                        </td>
                        <td class="p-4 text-gray-400">
                            <span class="bg-gray-800 px-3 py-1 rounded-full text-xs border border-gray-700">Sala #{{ $funcion->sala_id }}</span>
                        </td>
                        <td class="p-4">
                            <div class="text-purple-400 font-bold"><i class="bi bi-calendar-event mr-1"></i> {{ $funcion->fecha }}</div>
                            <div class="text-gray-400 text-sm"><i class="bi bi-clock mr-1"></i> {{ $funcion->hora }}</div>
                        </td>
                        <td class="p-4 font-black text-green-400">
                            ${{ number_format($funcion->precio, 2) }}
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-4">
                                <a href="{{ route('funciones.edit', $funcion->id) }}" class="text-blue-400 hover:text-blue-200 transition-all">
                                    <i class="bi bi-pencil-square text-xl"></i>
                                </a>
                                
                                <form action="{{ route('funciones.destroy', $funcion->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este horario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-300 transition-all">
                                        <i class="bi bi-trash3-fill text-xl"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-16 text-center text-gray-500">
                            <i class="bi bi-camera-reels text-4xl block mb-2 text-gray-600"></i>
                            <p class="italic">No hay funciones programadas en la cartelera.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>