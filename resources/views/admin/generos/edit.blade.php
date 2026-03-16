<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Géneros - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0B0F19] text-white p-8">

    <div class="max-w-4xl mx-auto mb-6">
        <a href="/admin" class="text-gray-400 hover:text-[#E91E63] flex items-center gap-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Regresar al Panel
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-[#151E2E] p-8 rounded-2xl shadow-xl border border-gray-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-pink-500">Géneros de Películas</h1>
            <a href="/generos/create" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-bold">
                + Nuevo Género
            </a>
        </div>

        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-700 text-gray-400">
                    <th class="p-3">ID</th>
                    <th class="p-3">Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach($generos as $genero)
                <tr class="border-b border-gray-800 hover:bg-gray-800/40">
                    <td class="p-3">#{{ $genero->id }}</td>
                    <td class="p-3 font-medium">{{ $genero->nombre }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>