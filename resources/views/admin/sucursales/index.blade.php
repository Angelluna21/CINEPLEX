<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sucursales - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8">

    <div class="max-w-4xl mx-auto mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Volver al Panel</span>
        </a>
    </div>

    <div class="max-w-4xl mx-auto bg-[#151E2E] p-8 rounded-2xl shadow-2xl border border-gray-800">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Sucursales</h1>
            <a href="{{ route('sucursales.create') }}" class="bg-[#E91E63] hover:bg-pink-600 text-white px-6 py-2 rounded-full font-bold transition-all">
                + Nueva Sucursal
            </a>
        </div>

        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-800/50 text-gray-400">
                    <th class="p-4">ID</th>
                    <th class="p-4">Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sucursales as $sucursal)
                <tr class="border-b border-gray-800 hover:bg-gray-800/30">
                    <td class="p-4">#{{ $sucursal->id }}</td>
                    <td class="p-4 font-bold text-lg">{{ $sucursal->nombre }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>