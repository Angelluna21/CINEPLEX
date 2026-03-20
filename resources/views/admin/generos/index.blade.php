<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géneros - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8 font-sans">

    <div class="max-w-5xl mx-auto mb-6 flex justify-between items-center">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl"></i>
            <span class="font-bold text-xs uppercase tracking-wider">Atrás</span>
        </a>
        <a href="{{ route('generos.create') }}" class="bg-[#E91E63] hover:bg-pink-600 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg hover:scale-105 uppercase tracking-widest text-xs">
            + Nuevo Género
        </a>
    </div>

    <div class="max-w-5xl mx-auto bg-[#151E2E] rounded-3xl shadow-2xl border border-gray-800 overflow-hidden">
        
        <div class="p-8 border-b border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Lista de Géneros</h1>
            
            <div class="relative w-full md:w-80">
                <input type="text" id="buscador" placeholder="Escribe para filtrar..." 
                    class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-2 pl-10 pr-4 text-sm focus:ring-1 focus:ring-[#42A5F5] focus:border-[#42A5F5] outline-none transition-all">
                <i class="bi bi-search absolute left-4 top-2.5 text-gray-500"></i>
            </div>
        </div>

        @if(session('success'))
            <div class="m-6 p-4 bg-green-500/10 border border-green-500/50 text-green-400 rounded-xl flex items-center gap-3">
                <i class="bi bi-check-circle-fill"></i>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="m-6 p-4 bg-red-500/10 border border-red-500/50 text-red-400 rounded-xl flex items-center gap-3 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                <i class="bi bi-shield-fill-x text-xl"></i>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tablaGeneros">
                <thead>
                    <tr class="bg-gray-900/50 text-gray-400 text-[10px] uppercase tracking-[0.2em]">
                        <th class="px-8 py-4 font-black">ID</th>
                        <th class="px-8 py-4 font-black">Nombre del Género</th>
                        <th class="px-8 py-4 font-black text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($generos as $genero)
                    <tr class="hover:bg-white/5 transition-colors group fila-genero">
                        <td class="px-8 py-4 text-gray-500 text-xs italic">#{{ $genero->id }}</td>
                        <td class="px-8 py-4 font-bold text-gray-200 uppercase tracking-wide nombre-genero">{{ $genero->nombre }}</td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('generos.edit', $genero->id) }}" class="text-gray-500 hover:text-[#42A5F5] transition-all p-2 rounded-lg hover:bg-[#42A5F5]/10">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </a>

                                <form action="{{ route('generos.destroy', $genero->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este género de la base de datos?');" class="inline m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-500 transition-all p-2 rounded-lg hover:bg-red-500/10">
                                        <i class="bi bi-trash3-fill text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-10 text-center text-gray-500 italic">No hay géneros registrados.</td>
                    </tr>
                    @endforelse
                    
                    <tr id="sinResultados" style="display: none;">
                        <td colspan="3" class="py-10 text-center text-gray-500 italic">
                            <i class="bi bi-emoji-frown text-2xl mb-2 block"></i> No se encontraron coincidencias.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('buscador').addEventListener('keyup', function() {
            let textoBusqueda = this.value.toLowerCase();
            let filas = document.querySelectorAll('.fila-genero');
            let resultadosVisibles = 0;

            filas.forEach(fila => {
                let nombreGenero = fila.querySelector('.nombre-genero').textContent.toLowerCase();
                if(nombreGenero.includes(textoBusqueda)) {
                    fila.style.display = '';
                    resultadosVisibles++;
                } else {
                    fila.style.display = 'none';
                }
            });

            // Muestra u oculta el mensaje de "No se encontraron coincidencias"
            document.getElementById('sinResultados').style.display = (resultadosVisibles === 0) ? '' : 'none';
        });
    </script>
</body>
</html>