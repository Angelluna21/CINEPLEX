<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Género - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8">
    <div class="max-w-xl mx-auto bg-[#151E2E] p-10 rounded-3xl border border-gray-800">
        <h2 class="text-2xl font-bold mb-6 text-pink-500">Nuevo Género</h2>
        <form action="{{ route('generos.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-400 text-sm mb-2">Nombre</label>
                <input type="text" name="nombre" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-pink-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-pink-600 py-3 rounded-xl font-bold uppercase tracking-widest">Guardar Género</button>
            <a href="{{ route('generos.index') }}" class="block text-center mt-4 text-gray-500 text-sm">Cancelar</a>
        </form>
    </div>
</body>
</html>