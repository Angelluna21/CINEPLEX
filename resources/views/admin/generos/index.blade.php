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

    <div class="max-w-4xl mx-auto mb-6">
        <a href="/admin" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all duration-300 shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Volver al Panel</span>
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-[#151E2E] p-8 rounded-2xl shadow-2xl border border-gray-800">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">
                    Géneros de Películas
                </h1>
                <p class="text-gray-400 text-sm mt-1">Administra las categorías de tu cartelera</p>
            </div>
            
            <a href="/generos/create" class="bg-[#E91E63] hover:bg-pink-600 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg hover:scale-105 flex items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                Nuevo Género
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-800">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800/50 text-gray-300 text-sm uppercase tracking-wider">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nombre del Género</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($generos as $genero)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors">
                        <td class="p-4 text-gray-500 font-mono">#{{ $genero->id }}</td>
                        <td class="p-4">
                            <span class="font-semibold text-lg text-gray-200">{{ $genero->nombre }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <a href="/generos/{{ $genero->id }}/edit" class="text-blue-400 hover:text-blue-300">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button class="text-red-400 hover:text-red-300">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-gray-500 italic">
                            No hay géneros registrados todavía.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>