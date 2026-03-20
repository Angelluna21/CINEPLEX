<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funciones - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8 font-sans">

    <div class="max-w-7xl mx-auto mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Atrás</span>
        </a>
    </div>

    <div class="max-w-7xl mx-auto bg-[#151E2E] p-8 rounded-3xl shadow-2xl border border-gray-800">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 text-green-400 rounded-xl flex items-center gap-3">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500 uppercase tracking-tight">Cartelera</h1>
                <p class="text-gray-400 text-sm mt-1">Filtrar funciones por película o sala</p>
            </div>

            <div class="flex w-full md:w-auto gap-2 flex-grow max-w-md">
                <div class="relative w-full">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <input type="text" 
                           id="searchInput"
                           placeholder="Buscar película o sala..." 
                           autocomplete="off"
                           class="w-full bg-[#0B0F19] border border-gray-700 rounded-full py-2.5 pl-10 pr-4 text-sm focus:border-purple-500 outline-none transition-all">
                </div>
            </div>
            
            <a href="{{ route('funciones.create') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-pink-600 hover:to-purple-600 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg hover:scale-105 flex items-center gap-2 whitespace-nowrap">
                <i class="bi bi-plus-circle-fill"></i>
                Nueva Función
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-800">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-900/80 text-gray-400 text-xs uppercase tracking-widest">
                        <th class="p-4">ID</th>
                        <th class="p-4">Película</th>
                        <th class="p-4">Sala / Ubicación</th>
                        <th class="p-4">Disponibilidad</th>
                        <th class="p-4">Fecha y Hora</th>
                        <th class="p-4 text-center">Precio</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($funciones as $funcion)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="p-4 text-gray-500 font-mono text-sm">#{{ $funcion->id }}</td>
                        <td class="p-4 font-bold text-gray-200">
                            {{ $funcion->pelicula->titulo ?? 'Sin título' }}
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-gray-200 font-semibold sala-nombre">{{ $funcion->sala->nombre ?? 'N/A' }}</span>
                                <span class="text-[10px] text-gray-500 uppercase italic">
                                    {{ $funcion->sala->sucursal->nombre ?? 'Sucursal no asignada' }}
                                </span>
                            </div>
                            <td class="p-4 border-b border-gray-800">
    @if($funcion->sala->estatus === 'Disponible')
        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/30 shadow-[0_0_10px_rgba(34,197,94,0.1)]">
            <span class="w-2 h-2 rounded-full bg-green-400 shadow-[0_0_5px_#4ade80] animate-pulse"></span>
            Disponible
        </span>
    @else
        <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-orange-500/10 text-orange-400 border border-orange-500/30 shadow-[0_0_10px_rgba(249,115,22,0.1)]">
            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
            Fuera de servicio
        </span>
    @endif
</td>
                        </td>
                        <td class="p-4">
                            <div class="text-purple-400 font-bold text-sm"><i class="bi bi-calendar-event mr-1"></i> {{ date('d/m/Y', strtotime($funcion->fecha)) }}</div>
                            <div class="text-gray-400 text-xs"><i class="bi bi-clock mr-1"></i> {{ date('h:i A', strtotime($funcion->hora)) }}</div>
                        </td>
                        <td class="p-4 font-black text-green-400">
                            ${{ number_format($funcion->precio, 2) }}
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center items-center gap-4">
                                <a href="{{ route('funciones.edit', $funcion->id) }}" class="text-blue-400 hover:text-blue-200 transition-all">
                                    <i class="bi bi-pencil-square text-xl"></i>
                                </a>
                                <form action="{{ route('funciones.destroy', $funcion->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta función?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-300">
                                        <i class="bi bi-trash3-fill text-xl"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="noResultsRow">
                        <td colspan="6" class="p-16 text-center text-gray-500 italic">
                            No hay funciones programadas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr:not(#noResultsRow)');
            
            rows.forEach(row => {
                // Buscamos en el título de la película (segunda celda) y en el nombre de la sala
                const pelicula = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const sala = row.querySelector('.sala-nombre').textContent.toLowerCase();
                
                if (pelicula.includes(searchTerm) || sala.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>

</body>
</html>