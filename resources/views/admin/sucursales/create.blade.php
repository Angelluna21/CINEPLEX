<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Sucursal - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0B0F19] text-white p-8">
    <div class="max-w-xl mx-auto bg-[#151E2E] p-10 rounded-3xl border border-gray-800">
        <h2 class="text-2xl font-bold mb-6 text-green-400">Registrar Sucursal</h2>
        <form action="{{ route('sucursales.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-gray-400 text-sm mb-2">Nombre de la Sucursal</label>
                <input type="text" name="nombre" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-green-500 outline-none">
            </div>
            <div>
                <label class="block text-gray-400 text-sm mb-2">Ubicación / Dirección</label>
                <textarea name="ubicacion" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-green-500 outline-none" rows="3"></textarea>
            </div>
            <button type="submit" class="w-full bg-green-600 py-3 rounded-xl font-bold uppercase tracking-widest">Guardar Sucursal</button>
            <a href="{{ route('sucursales.index') }}" class="block text-center mt-4 text-gray-500 text-sm">Regresar</a>
        </form>
    </div>
</body>
</html>