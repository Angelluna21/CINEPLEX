<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #0B0F19; }
        .glass-card {
            background: rgba(21, 30, 46, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="text-white min-h-screen font-sans">

    <nav class="border-b border-gray-800 bg-[#151E2E] p-6 mb-12 shadow-2xl">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">
                CINEPLEX <span class="text-xs font-light text-gray-500 tracking-widest uppercase ml-2">Panel Administrativo</span>
            </h1>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-400">Bienvenido/a, <span class="text-white font-bold">{{ auth()->user()->name }}</span></span>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-500 to-blue-500 p-0.5 shadow-lg">
                        <div class="w-full h-full rounded-full bg-[#0B0F19] flex items-center justify-center">
                            <i class="bi bi-person-fill text-gray-300"></i>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="group flex items-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-4 py-2 rounded-xl border border-red-500/20 transition-all font-bold text-sm">
                        <i class="bi bi-box-arrow-left text-lg"></i>
                        <span class="hidden md:inline">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 pb-16">
        <header class="mb-12 text-center md:text-left">
            <h2 class="text-4xl font-bold mb-2">Panel de Control</h2>
            <p class="text-gray-400">Selecciona un módulo para gestionar los datos de tu cine.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            
            <a href="{{ route('peliculas.index') }}" class="group glass-card p-10 rounded-3xl transition-all duration-300 hover:border-pink-500 hover:shadow-[0_0_30px_rgba(233,30,99,0.2)] text-center">
                <div class="w-20 h-20 bg-pink-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-pink-500/20 transition-all">
                    <i class="bi bi-film text-4xl text-pink-500"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 uppercase">Películas</h3>
                <p class="text-gray-500 text-sm">Total: {{ $countPeliculas ?? 0 }} registros</p>
            </a>

            <a href="{{ route('generos.index') }}" class="group glass-card p-10 rounded-3xl transition-all duration-300 hover:border-blue-500 hover:shadow-[0_0_30px_rgba(66,165,245,0.2)] text-center">
                <div class="w-20 h-20 bg-blue-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-blue-500/20 transition-all">
                    <i class="bi bi-tags text-4xl text-blue-500"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 uppercase">Géneros</h3>
                <p class="text-gray-500 text-sm">Categorías y etiquetas</p>
            </a>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('sucursales.index') }}" class="group glass-card p-10 rounded-3xl transition-all duration-300 hover:border-green-500 hover:shadow-[0_0_30px_rgba(34,197,94,0.2)] text-center">
                <div class="w-20 h-20 bg-green-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-green-500/20 transition-all">
                    <i class="bi bi-geo-alt text-4xl text-green-500"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 uppercase">Sucursales</h3>
                <p class="text-gray-500 text-sm">Total: {{ $countSucursales ?? 0 }} ubicaciones</p>
            </a>

            <a href="{{ route('salas.index') }}" class="group glass-card p-10 rounded-3xl transition-all duration-300 hover:border-[#00BCD4] hover:shadow-[0_0_30px_rgba(0,188,212,0.2)] text-center">
                <div class="w-20 h-20 bg-[#00BCD4]/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-[#00BCD4]/20 transition-all">
                    <i class="bi bi-door-open text-4xl text-[#00BCD4]"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 uppercase">Salas</h3>
                <p class="text-gray-500 text-sm">Distribución y capacidades</p>
            </a>
            @endif

            <a href="{{ route('funciones.index') }}" class="group glass-card p-10 rounded-3xl transition-all duration-300 hover:border-[#FF9800] hover:shadow-[0_0_30px_rgba(255,152,0,0.2)] text-center">
                <div class="w-20 h-20 bg-[#FF9800]/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-[#FF9800]/20 transition-all">
                    <i class="bi bi-calendar-event text-4xl text-[#FF9800]"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 uppercase">Funciones</h3>
                <p class="text-gray-500 text-sm">Horarios y cartelera</p>
            </a>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('usuarios.index') }}" class="group glass-card p-10 rounded-3xl transition-all duration-300 hover:border-purple-500 hover:shadow-[0_0_30px_rgba(168,85,247,0.2)] text-center">
                <div class="w-20 h-20 bg-purple-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-purple-500/20 transition-all">
                    <i class="bi bi-people text-4xl text-purple-500"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2 uppercase">Usuarios</h3>
                <p class="text-gray-500 text-sm">Total: {{ $countUsuarios ?? 0 }} empleados</p>
            </a>
            @endif

        </div>

        <footer class="mt-20 pt-8 border-t border-gray-800 flex justify-center text-gray-600 text-xs">
            © 2026 Cineplex | Panel Administrativo
        </footer>
    </main>
</body>
</html>