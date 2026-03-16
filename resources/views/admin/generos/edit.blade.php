<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Género - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0B0F19] text-white p-8">
    <div class="max-w-xl mx-auto bg-[#151E2E] p-10 rounded-3xl border border-gray-800 shadow-2xl">
        <h2 class="text-2xl font-bold mb-6 text-blue-400">Editar Género</h2>
        <form action="{{ route('generos.update', $genero->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label class="block text-gray-400 text-sm mb-2">Nombre del Género</label>
                <input type="text" name="nombre" value="{{ $genero->nombre }}" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-blue-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-blue-600 py-3 rounded-xl font-bold uppercase tracking-widest">Actualizar</button>
            <a href="{{ route('generos.index') }}" class="block text-center mt-4 text-gray-500 text-sm">Regresar</a>
        </form>
    </div>
</body>
</html>