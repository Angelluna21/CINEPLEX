<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cineplex')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cinecyan: '#42A5F5',
                        cinemagenta: '#E91E63',
                        cinecard: '#151E2E'
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0B0F19; }
    </style>
    
    @livewireStyles
    @stack('styles')
</head>
<body class="text-white min-h-screen font-sans flex flex-col">

    <nav class="border-b border-gray-800 bg-[#151E2E] p-4 sm:p-6 mb-8 sm:mb-12 shadow-2xl">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-4">
            
            <a href="/" class="hover:opacity-80 transition-opacity">
                <h1 class="text-3xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-cinecyan to-cinemagenta uppercase">
                    CINEPLEX <span class="text-xs font-light text-gray-500 tracking-widest uppercase ml-2 hidden lg:inline">CARTELERA</span>
                </h1>
            </a>
            
            <div class="flex items-center gap-4">
                @yield('header-actions')
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="mt-20 py-8 border-t border-gray-800 flex justify-center items-center text-gray-600 text-xs text-center w-full">
        © 2026 Cineplex | Todos los derechos reservados.
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>