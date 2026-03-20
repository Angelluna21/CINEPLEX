<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucursales - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8 font-sans">

    <div class="max-w-5xl mx-auto mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Atrás</span>
        </a>
    </div>

    <div class="max-w-5xl mx-auto bg-[#151E2E] p-8 rounded-2xl shadow-2xl border border-gray-800">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 text-green-400 rounded-xl flex items-center gap-3">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Sucursales</h1>
                <p class="text-gray-400 text-sm mt-1">Gestión de complejos físicos y ubicaciones</p>
            </div>

            <form id="searchForm" action="{{ route('sucursales.index') }}" method="GET" class="flex w-full md:w-auto gap-2">
                <div class="relative w-full md:w-64">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <input type="text" 
                           id="searchInput"
                           name="buscar" 
                           value="{{ $buscar ?? '' }}" 
                           placeholder="Buscar sucursal..." 
                           autocomplete="off"
                           class="w-full bg-[#0B0F19] border border-gray-700 rounded-full py-2 pl-10 pr-4 text-sm focus:border-[#42A5F5] outline-none transition-all">
                </div>
                @if(request('buscar'))
                    <a href="{{ route('sucursales.index') }}" class="bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white p-2 rounded-full transition-all" title="Limpiar búsqueda">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>

            <a href="{{ route('sucursales.create') }}" class="bg-[#E91E63] hover:bg-pink-600 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg hover:scale-105 flex items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                Nueva
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-800">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800/50 text-gray-300 text-sm uppercase tracking-wider">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nombre de la Sucursal</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($sucursales as $sucursal)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="p-4 text-gray-500 font-mono">#{{ $sucursal->id }}</td>
                        <td class="p-4">
                            <span class="font-bold text-lg text-gray-200">{{ $sucursal->nombre }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-4">
                                <a href="{{ route('sucursales.edit', $sucursal->id) }}" class="text-blue-400 hover:text-blue-300 transition-colors text-xl" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                <form action="{{ route('sucursales.destroy', $sucursal->id) }}" method="POST" onsubmit="return confirm('¿Estás segura de eliminar esta sucursal?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors text-xl" title="Eliminar">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-12 text-center text-gray-500 italic">
                            <i class="bi bi-geo-alt text-4xl block mb-2 opacity-20"></i>
                            No hay sucursales registradas todavía.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                // Obtenemos el texto del nombre (segunda celda)
                let nameElement = row.querySelector('td:nth-child(2)');
                if (nameElement) {
                    let text = nameElement.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? "" : "none";
                }
            });
        });
    </script>

</body>
</html>