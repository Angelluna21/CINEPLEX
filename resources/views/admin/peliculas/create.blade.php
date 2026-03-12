@extends('layouts.app')

@section('title', 'Nueva Película - Admin')

@section('sidebar')
    <nav class="flex flex-col gap-2 font-roboto h-full">
        <a href="/admin" class="flex items-center gap-3 text-gray-400 px-4 py-3 rounded hover:bg-gray-800 transition">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        <a href="/admin/peliculas" class="flex items-center gap-3 text-white bg-gray-800/50 border border-gray-700 px-4 py-2 rounded transition">
            <i class="bi bi-film text-cinecyan"></i> Películas
        </a>
    </nav>
@endsection

@section('content')
    <section class="bg-cinecard p-6 md:p-8 rounded-lg shadow-lg border border-gray-800 max-w-3xl mx-auto">
        
        <header class="mb-6 border-b border-gray-700 pb-4">
            <h2 class="text-2xl font-montserrat font-bold text-white">Registrar Nueva Película</h2>
            <p class="font-roboto text-sm text-gray-400 mt-1">Llena los detalles del nuevo estreno. Los campos con * son obligatorios.</p>
        </header>

        <form action="/admin/peliculas" method="POST" class="font-roboto flex flex-col gap-5">
            @csrf 
            
            <fieldset class="border-none p-0 m-0">
                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Título *
                    <input type="text" id="titulo" name="titulo" required placeholder="Ej. El Resplandor" 
                           class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal">
                </label>
            </fieldset>

            <fieldset class="border-none p-0 m-0">
                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Sinopsis *
                    <textarea id="sinopsis" name="sinopsis" rows="4" required placeholder="Descripción de la película..."
                              class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal"></textarea>
                </label>
            </fieldset>

            <fieldset class="grid grid-cols-1 md:grid-cols-2 gap-5 border-none p-0 m-0">
                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Género *
                    <input type="text" id="genero" name="genero" required placeholder="Ej. Terror" 
                           class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal">
                </label>

                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Duración (minutos) *
                    <input type="number" id="duracion" name="duracion" required min="1" placeholder="Ej. 120" 
                           class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal">
                </label>
            </fieldset>

            <fieldset class="grid grid-cols-1 md:grid-cols-2 gap-5 border-none p-0 m-0">
                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Clasificación *
                    <select id="clasificacion" name="clasificacion" required class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal">
                        <option value="A">Clasificación A (Todo público)</option>
                        <option value="B">Clasificación B (12+ años)</option>
                        <option value="C">Clasificación C (Adultos)</option>
                    </select>
                </label>

                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    Estatus *
                    <select id="estatus" name="estatus" required class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal">
                        <option value="Estreno">Estreno</option>
                        <option value="Cartelera">Cartelera</option>
                        <option value="No disponible">No disponible</option>
                    </select>
                </label>
            </fieldset>

            <fieldset class="border-none p-0 m-0">
                <label class="flex flex-col gap-2 text-gray-300 text-sm font-bold">
                    URL del Póster
                    <input type="url" id="imagen_url" name="imagen_url" placeholder="https://ejemplo.com/poster.jpg" 
                           class="bg-[#0B0F19] text-white border border-gray-700 rounded p-2 focus:outline-none focus:border-cinecyan font-normal">
                </label>
            </fieldset>

            <footer class="mt-4 flex justify-end gap-3">
                <a href="/admin/peliculas" class="bg-transparent border border-gray-600 text-gray-300 hover:bg-gray-800 px-4 py-2 rounded transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-[#4CAF50] hover:bg-green-600 text-white px-6 py-2 rounded flex items-center gap-2 font-roboto transition">
                    <i class="bi bi-floppy"></i> Guardar Película
                </button>
            </footer>
        </form>
    </section>
@endsection