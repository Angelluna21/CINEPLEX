<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8 font-sans">

    <div class="max-w-6xl mx-auto mb-6">
        <a href="{{ route('admin.dashboard') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Atrás</span>
        </a>
    </div>

    <div class="max-w-6xl mx-auto bg-[#151E2E] p-8 rounded-2xl shadow-2xl border border-gray-800">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Usuarios</h1>
                <p class="text-gray-400 text-sm">Gestión de personal y accesos del sistema</p>
            </div>
            
            <div class="relative w-full md:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="bi bi-search text-gray-500"></i>
                </span>
                <input type="text" id="searchInput" placeholder="Filtrar por nombre o email..." 
                    class="w-full bg-[#0B0F19] border border-gray-700 rounded-full py-2.5 pl-10 pr-4 focus:outline-none focus:border-pink-500 transition-all text-sm text-white">
            </div>

            <a href="{{ route('usuarios.create') }}" class="bg-[#E91E63] hover:bg-pink-600 text-white px-6 py-2.5 rounded-full font-bold transition-all shadow-lg hover:scale-105">
                + Nuevo Usuario
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-800">
            <table class="w-full text-left" id="userTable">
                <thead>
                    <tr class="bg-gray-800/50 text-gray-300 text-xs uppercase tracking-widest">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nombre</th>
                        <th class="p-4">Email</th>
                        <th class="p-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @forelse($usuarios as $user)
                    <tr class="border-b border-gray-800 hover:bg-gray-800/30 transition-colors user-row">
                        <td class="p-4 text-gray-500 font-mono text-sm">#{{ $user->id }}</td>
                        <td class="p-4 font-bold text-gray-200 user-name">{{ $user->name }}</td>
                        <td class="p-4 text-gray-400 user-email">{{ $user->email }}</td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-4">
                                <a href="{{ route('usuarios.edit', $user->id) }}" class="text-blue-400 hover:text-blue-200" title="Editar">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </a>
                                <form action="{{ route('usuarios.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar a este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-200" title="Eliminar">
                                        <i class="bi bi-trash3 text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="noResults" style="display: none;">
                        <td colspan="4" class="p-12 text-center text-gray-500 italic">No se encontraron usuarios.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


</body>
</html>