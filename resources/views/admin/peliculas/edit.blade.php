@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-4xl font-sans text-white">
    
    <div class="mb-6">
        <a href="{{ route('peliculas.index') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Volver a Cartelera</span>
        </a>
    </div>

    <div class="bg-[#151E2E] shadow-2xl rounded-3xl overflow-hidden border border-gray-800">
        
        <div class="px-8 py-6 border-b border-gray-800 bg-gray-900/30">
            <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">
                Editar Película: <span class="text-white">{{ $pelicula->titulo }}</span>
            </h2>
            <p class="text-gray-400 text-sm mt-1">Actualiza los datos de la película en cartelera.</p>
        </div>

        <form action="{{ route('peliculas.update', $pelicula->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="titulo" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Título de la Película <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $pelicula->titulo) }}" 
                        class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner" required>
                    <button type="button" id="btnBuscarTMDB" class="bg-gradient-to-r from-[#42A5F5] to-blue-600 hover:scale-105 text-white font-bold py-3 px-6 rounded-xl shadow-[0_0_15px_rgba(66,165,245,0.3)] transition-all whitespace-nowrap">
                        <i class="bi bi-search mr-2"></i> Buscar
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="clasificacion" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Clasificación</label>
                    <select name="clasificacion" id="clasificacion" 
                        class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all appearance-none shadow-inner cursor-pointer">
                        @foreach(['A', 'B', 'B15', 'C', 'D'] as $opcion)
                            <option value="{{ $opcion }}" {{ old('clasificacion', $pelicula->clasificacion) == $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="duracion" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Duración (min)</label>
                    <input type="number" name="duracion" id="duracion" value="{{ old('duracion', $pelicula->duracion) }}" 
                        class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner" required>
                </div>

                <div>
                    <label for="genero" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Género</label>
                    <select name="genero" id="genero" class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all appearance-none shadow-inner cursor-pointer" required>
                        <option value="" disabled>Seleccione...</option>
                        @foreach($generos as $genero)
                            <option value="{{ $genero->nombre }}" {{ old('genero', $pelicula->genero) == $genero->nombre ? 'selected' : '' }}>{{ $genero->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-900/50 rounded-2xl border border-gray-800">
                
                <div>
                    <label for="idioma" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Idioma Audio <span class="text-red-500">*</span></label>
                    <select name="idioma" id="idioma" class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl px-4 py-3 text-white focus:border-[#42A5F5] focus:ring-1 focus:ring-[#42A5F5] outline-none transition-all appearance-none cursor-pointer shadow-inner" required>
                        <option value="Español" {{ old('idioma', $pelicula->idioma) == 'Español' ? 'selected' : '' }}>Español</option>
                        <option value="Inglés" {{ old('idioma', $pelicula->idioma) == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                        <option value="Japonés" {{ old('idioma', $pelicula->idioma) == 'Japonés' ? 'selected' : '' }}>Japonés</option>
                        <option value="Otro" {{ old('idioma', $pelicula->idioma) == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Versión <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-6">
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="radio" name="formato" value="DOB" class="h-5 w-5 text-purple-500 focus:ring-purple-500 border-gray-600 bg-[#0B0F19]" required {{ old('formato', $pelicula->formato ?? '') == 'DOB' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Doblada (DOB)</span>
                        </label>
                        
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="radio" name="formato" value="SUB" class="h-5 w-5 text-purple-500 focus:ring-purple-500 border-gray-600 bg-[#0B0F19]" required {{ old('formato', $pelicula->formato ?? '') == 'SUB' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Subtitulada (SUB)</span>
                        </label>

                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="radio" name="formato" value="ORIG" class="h-5 w-5 text-purple-500 focus:ring-purple-500 border-gray-600 bg-[#0B0F19]" required {{ old('formato', $pelicula->formato ?? '') == 'ORIG' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Original (ORIG)</span>
                        </label>
                    </div>
                </div>
            </div> <div>
                <label for="imagen_url" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">URL del Póster (Imagen)</label>
                <input type="url" name="imagen_url" id="imagen_url" value="{{ old('imagen_url', $pelicula->imagen_url) }}" placeholder="https://ejemplo.com/poster.jpg" 
                    class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner">
                
                <div id="preview-container" class="mt-4 {{ $pelicula->imagen_url ? '' : 'hidden' }}">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Vista Previa:</p>
                    <div class="relative w-40 aspect-[2/3] rounded-xl overflow-hidden border border-gray-700 shadow-2xl bg-[#0B0F19] flex items-center justify-center group">
                        <img id="poster-preview" src="{{ $pelicula->imagen_url ?? '#' }}" alt="Vista previa" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            </div>

            <div>
                <label for="sinopsis" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Sinopsis</label>
                <textarea name="sinopsis" id="sinopsis" rows="4" 
                    class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner leading-relaxed" required>{{ old('sinopsis', $pelicula->sinopsis) }}</textarea>
            </div>

            <div class="bg-gray-900/50 p-5 rounded-2xl border border-gray-800">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Estatus en Cine</label>
                <div class="flex flex-wrap gap-6">
                    @foreach(['Estreno', 'Cartelera', 'Próximamente'] as $estado)
                    <label class="inline-flex items-center group cursor-pointer">
                        <input type="radio" name="estatus" value="{{ $estado }}" 
                            class="h-5 w-5 text-[#E91E63] focus:ring-[#E91E63] border-gray-600 bg-[#0B0F19]" 
                            {{ old('estatus', $pelicula->estatus) == $estado ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">{{ $estado }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end items-center gap-4 pt-6 border-t border-gray-800 mt-8">
                <a href="{{ route('peliculas.index') }}" class="text-gray-400 hover:text-white font-bold py-2 px-4 transition-colors text-sm uppercase tracking-wide">Cancelar</a>
                <button type="submit" class="bg-gradient-to-r from-[#42A5F5] to-[#E91E63] hover:scale-105 text-white font-bold py-3 px-8 rounded-xl shadow-[0_0_20px_rgba(233,30,99,0.3)] text-sm uppercase tracking-wide transition-all">
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
        if (!titulo) return alert('Por favor, ingresa el título de la película para buscar en TMDB.');

        const btn = this;
        const oHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-2" style="display:inline-block; animation: spin 2s linear infinite;"></i> Buscando...';
        btn.disabled = true;

        try {
            const response = await fetch(`{{ route('tmdb.search') }}?query=${encodeURIComponent(titulo)}`);
            const data = await response.json();

            if (response.ok) {
                document.getElementById('titulo').value = data.titulo || titulo;
                document.getElementById('sinopsis').value = data.sinopsis || '';
                if (data.duracion) document.getElementById('duracion').value = data.duracion;
                
                if (data.imagen_url) {
                    posterInput.value = data.imagen_url;
                    updatePreview(data.imagen_url);
                }
                
                if (data.genero) {
                    const select = document.getElementById('genero');
                    for (let i = 0; i < select.options.length; i++) {
                        if (select.options[i].text.toLowerCase().includes(data.genero.toLowerCase())) {
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
            alert('Hubo un error de conexión.');
        } finally {
            btn.innerHTML = oHtml;
            btn.disabled = false;
        }
    });
</script>
<style>@keyframes spin { 100% { transform: rotate(360deg); } }</style>
@endsection