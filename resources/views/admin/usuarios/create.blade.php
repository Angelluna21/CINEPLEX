<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Empleado - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8 font-sans">

    <div class="max-w-2xl mx-auto mb-6">
        <a href="{{ route('usuarios.index') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Cancelar y Volver</span>
        </a>
    </div>

    <div class="max-w-2xl mx-auto bg-[#151E2E] p-10 rounded-3xl shadow-2xl border border-gray-800">
        
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Agregar Nuevo Empleado</h1>
            <p class="text-gray-400 text-sm mt-2">Completa los datos para dar de alta un nuevo acceso</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500 text-red-400 rounded-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-gray-400 text-xs uppercase font-bold mb-2 ml-1">Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 focus:outline-none focus:border-pink-500 transition-all text-white">
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase font-bold mb-2 ml-1">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 focus:outline-none focus:border-pink-500 transition-all text-white">
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase font-bold mb-2 ml-1">Rol</label>
                <select name="role" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 focus:outline-none focus:border-pink-500 transition-all text-white">
                    <option value="empleado" {{ old('role') == 'empleado' ? 'selected' : '' }}>Empleado</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase font-bold mb-2 ml-1">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 focus:outline-none focus:border-pink-500 transition-all text-white"
                    placeholder="Mínimo 8 caracteres">
            </div>

            <div>
                <label class="block text-gray-400 text-xs uppercase font-bold mb-2 ml-1">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 focus:outline-none focus:border-pink-500 transition-all text-white"
                    placeholder="Repite la contraseña">
            </div>

            <div class="pt-4">
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-[#42A5F5] to-[#E91E63] hover:from-[#E91E63] hover:to-[#42A5F5] text-white font-black py-4 rounded-xl transition-all shadow-lg uppercase tracking-widest active:scale-95">
                    Registrar Empleado
                </button>
            </div>
        </form>
    </div>

</body>
</html>