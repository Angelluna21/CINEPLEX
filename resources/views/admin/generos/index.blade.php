<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Géneros - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8">

    <div class="max-w-4xl mx-auto mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl"></i>
            <span class="font-bold text-sm uppercase">Volver al Panel</span>
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-[#151E2E] p-8 rounded-2xl shadow-2xl border border-gray-800">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500 text-green-400 rounded-xl flex items-center gap-3">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Géneros</h1>
            <a href="{{ route('generos.create') }}" class="bg-[#E91E63] hover:bg-pink-600 text-white px-6 py-2 rounded-full font-bold transition-all">
                + Nuevo Género
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-800">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-800/50 text-gray-300 uppercase text-xs">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nombre</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($generos as $genero)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30">
                        <td class="p-4 text-gray-500">#{{ $genero->id }}</td>
                        <td class="p-4 font-bold">{{ $genero->nombre }}</td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-4">
                                <a href="{{ route('generos.edit', $genero->id) }}" class="text-blue-400 hover:text-blue-200">
                                    <i class="bi bi-pencil-square text-xl"></i>
                                </a>
                                
                                <form action="{{ route('generos.destroy', $genero->id) }}" method="POST" onsubmit="return confirm('¿Eliminar género?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-200">
                                        <i class="bi bi-trash3 text-xl"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>