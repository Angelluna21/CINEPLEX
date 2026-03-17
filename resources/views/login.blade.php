<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - CinePlex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Roboto:wght@300;400;700&display=swap');
        body { font-family: 'Roboto', sans-serif; }
        .font-montserrat { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-[#0B0F19] text-white flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-[#161C2D] rounded-2xl shadow-2xl border border-gray-800 p-8 md:p-10 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500"></div>

        <header class="text-center mb-10">
            <h1 class="text-4xl font-black font-montserrat tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 mb-2">
                CINEPLEX
            </h1>
            <p class="text-gray-400 text-sm uppercase tracking-widest font-bold">Acceso Empleados</p>
        </header>

        @error('email')
            <div class="bg-red-500/10 border border-red-500 text-red-500 text-xs p-3 rounded-lg mb-6 flex items-center gap-2">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror

        <form action="{{ route('login.post') }}" method="POST" class="flex flex-col gap-6">
            @csrf
            
            <div class="flex flex-col gap-2">
                <label for="email" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Correo Electrónico</label>
                <div class="relative">
                    <i class="bi bi-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                           class="w-full bg-[#0B0F19] border @error('email') border-red-500 @else border-gray-700 @enderror rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-cyan-500 transition-colors placeholder-gray-600"
                           placeholder="ejemplo@cineplex.com">
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label for="password" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contraseña</label>
                <div class="relative">
                    <i class="bi bi-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <input type="password" name="password" id="password" required 
                           class="w-full bg-[#0B0F19] border @error('email') border-red-500 @else border-gray-700 @enderror rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:border-cyan-500 transition-colors placeholder-gray-600"
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" 
                    class="mt-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold py-3 rounded-lg shadow-lg shadow-cyan-500/20 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                <i class="bi bi-box-arrow-in-right"></i>
                Ingresar al Sistema
            </button>
        </form>

        <footer class="mt-10 text-center border-t border-gray-800 pt-6">
            <p class="text-[10px] text-gray-600 uppercase tracking-[0.2em]">
                Módulo Administrativo <br>
                <span class="text-gray-500">Debug & Go © 2026</span>
            </p>
        </footer>
    </div>
</body>
</html>