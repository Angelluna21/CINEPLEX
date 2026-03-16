<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funciones - Cineplex</title>
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
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#FF9800] to-[#F44336]">Programación de Funciones</h1>
                <p class="text-gray-400 text-sm mt-1">Horarios de cartelera y asignación de películas</p>
            </div>
            <a href="{{ route('funciones.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg hover:scale-105">
                + Nueva Función
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-800">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-800/50 text-gray-300 text-xs uppercase">
                        <th class="p-4">Película</th>
                        <th class="p-4">Sala / Sucursal</th>
                        <th class="p-4">Fecha y Hora</th>
                        <th class="p-4">Precio</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($funciones as $funcion)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="p-4">
                            <span class="font-bold text-gray-200 block">{{ $funcion->pelicula->titulo }}</span>
                            <span class="text-xs text-gray-500 uppercase">{{ $funcion->pelicula->clasificacion }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-gray-300">{{ $funcion->sala->nombre }}</span>
                            <span class="text-xs text-gray-500 block">{{ $funcion->sala->sucursal->nombre }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-orange-400 font-bold italic">{{ $funcion->fecha }}</span>
                                <span class="text-sm text-gray-400">{{ $funcion->hora }} hrs</span>
                            </div>
                        </td>
                        <td class="p-4 text-green-400 font-mono font-bold">${{ number_format($funcion->precio, 2) }}</td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-4">
                                <button class="text-red-400 hover:text-red-200"><i class="bi bi-trash3 text-lg"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-gray-500 italic">No hay funciones programadas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>