@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-2xl">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar Película: <span class="ml-2 text-blue-300">{{ $pelicula->titulo }}</span>
            </h2>
        </div>

        <form action="{{ route('peliculas.update', $pelicula->id) }}" method="POST" class="p-6 space-y-6 bg-white">
            @csrf
            @method('PUT')

            <div>
                <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-1 text-black">Título de la Película</label>
                <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $pelicula->titulo) }}" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium" required>
                @error('titulo')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="clasificacion" class="block text-sm font-semibold text-gray-700 mb-1 text-black">Clasificación</label>
                    <select name="clasificacion" id="clasificacion" 
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium cursor-pointer">
                        @foreach(['A', 'B', 'B15', 'C', 'D'] as $opcion)
                            <option value="{{ $opcion }}" {{ old('clasificacion', $pelicula->clasificacion) == $opcion ? 'selected' : '' }}>
                                {{ $opcion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="duracion" class="block text-sm font-semibold text-gray-700 mb-1 text-black">Duración (minutos)</label>
                    <input type="number" name="duracion" id="duracion" value="{{ old('duracion', $pelicula->duracion) }}" 
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium" required>
                </div>

                <div>
                    <label for="genero" class="block text-sm font-semibold text-gray-700 mb-1 text-black">Género</label>
                    <select name="genero" id="genero" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium cursor-pointer" required>
                        <option value="">Seleccione...</option>
                        @foreach($generos as $genero)
                            @php $nombreGenero = $genero->nombre ?? $genero->name; @endphp
                            <option value="{{ $nombreGenero }}" {{ old('genero', $pelicula->genero) == $nombreGenero ? 'selected' : '' }}>
                                {{ $nombreGenero }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="idioma" class="block text-sm font-semibold text-gray-700 mb-1 text-black">Idioma</label>
                    <select name="idioma" id="idioma" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium cursor-pointer" required>
                        <option value="Español Latino" {{ old('idioma', $pelicula->idioma) == 'Español Latino' ? 'selected' : '' }}>Español Latino</option>
                        <option value="Subtitulada" {{ old('idioma', $pelicula->idioma) == 'Subtitulada' ? 'selected' : '' }}>Subtitulada (DOB)</option>
                        <option value="Inglés" {{ old('idioma', $pelicula->idioma) == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="formato" class="block text-sm font-semibold text-gray-700 mb-1 text-black">Formato</label>
                    <select name="formato" id="formato" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium cursor-pointer" required>
                        <option value="2D" {{ old('formato', $pelicula->formato) == '2D' ? 'selected' : '' }}>2D</option>
                        <option value="3D" {{ old('formato', $pelicula->formato) == '3D' ? 'selected' : '' }}>3D</option>
                        <option value="4D" {{ old('formato', $pelicula->formato) == '4D' ? 'selected' : '' }}>4D</option>
                        <option value="IMAX" {{ old('formato', $pelicula->formato) == 'IMAX' ? 'selected' : '' }}>IMAX</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="imagen_url" class="block text-sm font-semibold text-gray-700 mb-1 text-black">URL del Póster (Imagen)</label>
                <input type="url" name="imagen_url" id="imagen_url" value="{{ old('imagen_url', $pelicula->imagen_url ?? '') }}" placeholder="https://ejemplo.com/poster.jpg" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium">
                @error('imagen_url')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sinopsis" class="block text-sm font-semibold text-gray-700 mb-1 text-black">Sinopsis</label>
                <textarea name="sinopsis" id="sinopsis" rows="4" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black leading-relaxed" required>{{ old('sinopsis', $pelicula->sinopsis) }}</textarea>
                @error('sinopsis')
                    <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <label class="block text-sm font-bold text-gray-800 mb-3">Estatus en Cine</label>
                <div class="flex flex-wrap gap-6">
                    @foreach(['Estreno', 'Cartelera', 'Próximamente'] as $estado)
                    <label class="inline-flex items-center group cursor-pointer">
                        <input type="radio" name="estatus" value="{{ $estado }}" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                            {{ old('estatus', $pelicula->estatus) == $estado ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700 group-hover:text-blue-600 transition-colors">{{ $estado }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                <a href="{{ route('peliculas.index') }}" 
                   class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-6 rounded-lg shadow-sm text-sm transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded-lg shadow-lg text-sm transform hover:scale-105 transition duration-200">
                    Actualizar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection