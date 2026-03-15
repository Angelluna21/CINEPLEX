<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cineplex - Cartelera')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cinebg: '#0B0F19', /* Fondo oscuro principal  */
                        cinecard: '#151E2E', /* Color neutro para tarjetas/header [cite: 99] */
                        cinecyan: '#42A5F5', /* Acento Cyan  */
                        cinemagenta: '#E91E63', /* Acento Magenta [cite: 97] */
                    },
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'], /* [cite: 104] */
                        roboto: ['Roboto', 'sans-serif'], /* [cite: 105] */
                    }
                }
            }
        }
    </script>
    <style>
        /* Aplicando el Modo Oscuro por defecto  */
        body {
            background-color: #0B0F19; /*  */
            color: #FFFFFF; /* [cite: 99] */
        }
    </style>
</head>
<body class="font-roboto flex flex-col min-h-screen">

    <header class="w-full bg-cinecard p-4 shadow-md flex justify-between items-center border-b border-gray-800">
    <a href="/admin" class="hover:opacity-80 transition duration-300 inline-block m-0 p-0">
    <h1 class="font-montserrat font-bold text-3xl text-transparent bg-clip-text bg-gradient-to-r from-cinecyan to-cinemagenta">
        CINEPLEX
    </h1>
</a>
        
        <div class="flex items-center gap-4">
            @yield('header-actions')
        </div>
    </header>

    <div class="flex flex-1 w-full max-w-7xl mx-auto">

        @hasSection('sidebar')
            <aside class="w-1/4 bg-cinecard p-6 border-r border-gray-800">
                @yield('sidebar')
            </aside>
        @endif

        <main class="flex-1 p-6 w-full">
            @yield('content')
        </main>

    </div>

    <footer class="w-full bg-cinecard py-4 text-center text-sm font-light text-gray-400 mt-auto">
        <p class="font-roboto">© 2026 Cineplex | Todos los derechos reservados.</p> </footer>

</body>
</html>