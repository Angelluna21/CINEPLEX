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
                <div class="flex gap-2">
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $pelicula->titulo) }}" 
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium" required>
                    <button type="button" id="btnBuscarTMDB" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition-all whitespace-nowrap">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Buscar en TMDB
                    </button>
                </div>

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
            
            <div>
                <label for="imagen_url" class="block text-sm font-semibold text-gray-700 mb-1 text-black">URL del Póster (Imagen)</label>
                <input type="url" name="imagen_url" id="imagen_url" value="{{ old('imagen_url', $pelicula->imagen_url ?? '') }}" placeholder="https://ejemplo.com/poster.jpg" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-black font-medium">
                
                <div id="preview-container" class="mt-4 {{ $pelicula->imagen_url ? '' : 'hidden' }}">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Vista Previa:</p>
                    <div class="relative w-40 aspect-[2/3] rounded-xl overflow-hidden border border-gray-200 shadow-lg bg-gray-100 flex items-center justify-center">
                        <img id="poster-preview" src="{{ $pelicula->imagen_url ?? '#' }}" alt="Vista previa" class="w-full h-full object-cover">
                    </div>
                </div>

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

<script>
    const posterInput = document.getElementById('imagen_url');
    const previewContainer = document.getElementById('preview-container');
    const posterPreview = document.getElementById('poster-preview');

    function updatePreview(url) {
        if (url) {
            posterPreview.src = url;
            previewContainer.classList.remove('hidden');
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    posterInput.addEventListener('input', (e) => updatePreview(e.target.value));

    document.getElementById('btnBuscarTMDB').addEventListener('click', async function() {
        const titulo = document.getElementById('titulo').value;
        if (!titulo) {
            alert('Por favor, ingresa el título de la película para buscar en TMDB.');
            return;
        }

        const btn = this;
        const oHtml = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4 inline-block mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Buscando...';
        btn.disabled = true;

        try {
            const response = await fetch(`{{ route('tmdb.search') }}?query=${encodeURIComponent(titulo)}`);
            const data = await response.json();

            if (response.ok) {
                // Autocompletar campos
                document.getElementById('titulo').value = data.titulo || titulo;
                document.getElementById('sinopsis').value = data.sinopsis || '';
                
                if (data.duracion) {
                    document.getElementById('duracion').value = data.duracion;
                }
                
                if (data.imagen_url) {
                    posterInput.value = data.imagen_url;
                    updatePreview(data.imagen_url);
                }
                
                if (data.genero) {
                    const select = document.getElementById('genero');
                    for (let i = 0; i < select.options.length; i++) {
                        if (select.options[i].text.toLowerCase().includes(data.genero.toLowerCase()) || 
                            data.genero.toLowerCase().includes(select.options[i].text.toLowerCase())) {
                            select.selectedIndex = i;
                            break;
                        }
                    }
                }
            } else {
                alert(data.error || 'Error al buscar en TMDB');
            }
        } catch (error) {
            console.error(error);
            alert('Hubo un error de conexión con el servidor.');
        } finally {
            btn.innerHTML = oHtml;
            btn.disabled = false;
        }
    });
</script>
@endsection