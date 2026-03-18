<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8 font-sans">

    <div class="max-w-6xl mx-auto mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Volver al Panel</span>
        </a>
    </div>

    <div class="max-w-6xl mx-auto bg-[#151E2E] p-8 rounded-2xl shadow-2xl border border-gray-800">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 text-green-400 rounded-xl flex items-center gap-3">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#00BCD4] to-[#42A5F5]">Salas de Cine</h1>
                <p class="text-gray-400 text-sm mt-1">Gestión de espacios y capacidades</p>
            </div>
            
            <div class="flex flex-col md:flex-row gap-3 w-full lg:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="bi bi-search text-gray-500"></i>
                    </span>
                    <input type="text" id="salaSearch" placeholder="Buscar sala..." 
                        class="w-full md:w-48 bg-[#0B0F19] border border-gray-700 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:border-cyan-500 transition-all text-sm">
                </div>

                <select id="sucursalFilter" class="bg-[#0B0F19] border border-gray-700 rounded-full py-2 px-4 focus:outline-none focus:border-cyan-500 text-sm text-gray-300">
                    <option value="">Todas las sucursales</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->nombre }}">{{ $sucursal->nombre }}</option>
                    @endforeach
                </select>

                <a href="{{ route('salas.create') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg text-center">
                    + Nueva Sala
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-800">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-800/50 text-gray-300 text-xs uppercase tracking-widest">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nombre de la Sala</th>
                        <th class="p-4">Sucursal</th>
                        <th class="p-4">Capacidad</th>
                        <th class="p-4">Estatus</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="salasTableBody">
                    @forelse($salas as $sala)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors sala-row">
                        <td class="p-4 text-gray-500 font-mono text-sm">#{{ $sala->id }}</td>
                        <td class="p-4 font-bold text-gray-200 sala-name">{{ $sala->nombre }}</td>
                        <td class="p-4 text-gray-400 sala-sucursal">{{ $sala->sucursal->nombre ?? 'N/A' }}</td>
                        <td class="p-4 text-cyan-400 font-bold">{{ $sala->capacidad }} asientos</td>
                        
                        <td class="p-4 whitespace-nowrap">
                            @if($sala->estatus === 'Disponible')
                                <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/30 shadow-[0_0_10px_rgba(34,197,94,0.1)]">
                                    <span class="w-2 h-2 rounded-full bg-green-400 shadow-[0_0_5px_#4ade80] animate-pulse"></span>
                                    Disponible
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/30 shadow-[0_0_10px_rgba(239,68,68,0.1)]">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    Mantenimiento
                                </span>
                            @endif
                        </td>

                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-4">
                                <a href="{{ route('salas.edit', $sala->id) }}" class="text-blue-400 hover:text-blue-200">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </a>
                                
                                <form action="{{ route('salas.destroy', $sala->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta sala?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-200 cursor-pointer bg-transparent border-none p-0 m-0">
                                        <i class="bi bi-trash3 text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-500 italic">No hay salas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const salaSearch = document.getElementById('salaSearch');
        const sucursalFilter = document.getElementById('sucursalFilter');
        const rows = document.querySelectorAll('.sala-row');

        function filterTable() {
            const searchText = salaSearch.value.toLowerCase();
            const selectedSucursal = sucursalFilter.value.toLowerCase();

            rows.forEach(row => {
                const name = row.querySelector('.sala-name').textContent.toLowerCase();
                const sucursal = row.querySelector('.sala-sucursal').textContent.toLowerCase();
                
                const matchesSearch = name.includes(searchText);
                const matchesSucursal = selectedSucursal === "" || sucursal === selectedSucursal;

                if (matchesSearch && matchesSucursal) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        salaSearch.addEventListener('keyup', filterTable);
        sucursalFilter.addEventListener('change', filterTable);
    </script>
</body>
</html>