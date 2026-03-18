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
            <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Registrar Nueva Película</h2>
            <p class="text-gray-400 text-sm mt-1">Ingresa los datos del nuevo título para la cartelera.</p>
        </div>

        <form action="{{ route('peliculas.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div>
                <label for="titulo" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Título de la Película <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" 
                        class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner @error('titulo') border-red-500 ring-1 ring-red-500 @enderror"
                        placeholder="Escribe el nombre aquí..." required>
                    <button type="button" id="btnBuscarTMDB" class="bg-gradient-to-r from-[#42A5F5] to-blue-600 hover:scale-105 text-white font-bold py-3 px-6 rounded-xl shadow-[0_0_15px_rgba(66,165,245,0.3)] transition-all whitespace-nowrap">
                        <i class="bi bi-search mr-2"></i>Buscar en TMDB
                    </button>
                </div>
                
                @error('titulo')
                    <div class="mt-2 text-red-400 text-sm flex items-center gap-2 bg-red-500/10 p-3 rounded-lg border border-red-500/20 font-bold">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <label for="genero" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Género <span class="text-red-500">*</span></label>
                <select name="genero" id="genero" 
                    class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none @error('genero') border-red-500 @enderror" required>
                    <option value="" disabled selected>-- Selecciona un género --</option>
                    @foreach($generos as $g)
                        <option value="{{ $g->nombre }}" {{ old('genero') == $g->nombre ? 'selected' : '' }}>
                            {{ $g->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('genero')
                    <p class="text-red-400 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="clasificacion" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Clasificación <span class="text-red-500">*</span></label>
                    <select name="clasificacion" id="clasificacion" 
                        class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none">
                        <option value="A" {{ old('clasificacion') == 'A' ? 'selected' : '' }}>A (Todo público)</option>
                        <option value="B" {{ old('clasificacion') == 'B' ? 'selected' : '' }}>B (12+ años)</option>
                        <option value="B15" {{ old('clasificacion') == 'B15' ? 'selected' : '' }}>B15 (15+ años)</option>
                        <option value="C" {{ old('clasificacion') == 'C' ? 'selected' : '' }}>C (Adultos)</option>
                    </select>
                </div>

                <div>
                    <label for="duracion" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Duración (minutos) <span class="text-red-500">*</span></label>
                    <input type="number" name="duracion" id="duracion" value="{{ old('duracion') }}" min="1"
                        class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-inner"
                        placeholder="Ej. 120" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-900/50 rounded-2xl border border-gray-800">
                <div>
                    <label for="idioma" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Idioma Audio <span class="text-red-500">*</span></label>
                    <select name="idioma" id="idioma" class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl px-4 py-3 text-white focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition-all appearance-none" required>
                        <option value="" disabled selected>-- Selecciona el idioma --</option>
                        <option value="Español" {{ old('idioma') == 'Español' ? 'selected' : '' }}>Español</option>
                        <option value="Inglés" {{ old('idioma') == 'Inglés' ? 'selected' : '' }}>Inglés</option>
                        <option value="Japonés" {{ old('idioma') == 'Japonés' ? 'selected' : '' }}>Japonés</option>
                        <option value="Otro" {{ old('idioma') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Formato <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-6">
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="radio" name="formato" value="DOB" class="h-5 w-5 text-purple-500 focus:ring-purple-500 border-gray-600 bg-[#0B0F19]" required {{ old('formato') == 'DOB' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Doblada (DOB)</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="radio" name="formato" value="SUB" class="h-5 w-5 text-purple-500 focus:ring-purple-500 border-gray-600 bg-[#0B0F19]" required {{ old('formato') == 'SUB' ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Subtitulada (SUB)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900/50 p-5 rounded-2xl border border-gray-800">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Estatus en Cine</label>
                <div class="flex flex-wrap gap-6">
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="radio" name="estatus" value="Estreno" class="h-5 w-5 text-[#E91E63] focus:ring-[#E91E63] border-gray-600 bg-[#0B0F19]" required {{ old('estatus') == 'Estreno' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Estreno</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="radio" name="estatus" value="Cartelera" class="h-5 w-5 text-[#42A5F5] focus:ring-[#42A5F5] border-gray-600 bg-[#0B0F19]" required {{ old('estatus', 'Cartelera') == 'Cartelera' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Cartelera</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer group">
    <input type="radio" name="estatus" value="Próximamente" class="h-5 w-5 text-purple-500 focus:ring-purple-500 border-gray-600 bg-[#0B0F19]" required {{ old('estatus') == 'Próximamente' ? 'checked' : '' }}>
    <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Próximamente</span>
</label>
                </div>
            </div>
            <div>
                <label for="imagen_url" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">URL del Póster (Imagen)</label>
                <input type="url" name="imagen_url" id="imagen_url" value="{{ old('imagen_url') }}" placeholder="https://ejemplo.com/poster.jpg" 
                    class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-blue-500 font-medium transition-all shadow-inner">
                
                <div id="preview-container" class="mt-4 hidden">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Vista Previa:</p>
                    <div class="relative w-40 aspect-[2/3] rounded-xl overflow-hidden border border-gray-800 shadow-xl bg-gray-900 flex items-center justify-center">
                        <img id="poster-preview" src="#" alt="Vista previa" class="w-full h-full object-cover">
                        <div id="preview-placeholder" class="absolute inset-0 flex items-center justify-center bg-gray-900 text-gray-700 hidden">
                            <i class="bi bi-image text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <label for="sinopsis" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Sinopsis <span class="text-red-500">*</span></label>
                <textarea name="sinopsis" id="sinopsis" rows="4" 
                    class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner @error('sinopsis') border-red-500 ring-1 ring-red-500 @enderror"
                    placeholder="Escribe el resumen de la película aquí..." required>{{ old('sinopsis') }}</textarea>
                @error('sinopsis')
                    <div class="mt-2 text-red-400 text-sm flex items-center gap-2 bg-red-500/10 p-3 rounded-lg border border-red-500/20 font-bold">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="flex justify-end items-center gap-4 pt-6 border-t border-gray-800">
                <a href="{{ route('peliculas.index') }}" class="text-gray-400 hover:text-white font-bold py-2 px-4 transition-colors text-sm uppercase tracking-wide">
                    Cancelar
                </a>
                <button type="submit" class="bg-gradient-to-r from-[#42A5F5] to-[#E91E63] hover:scale-105 text-white font-bold py-3 px-8 rounded-xl shadow-[0_0_20px_rgba(233,30,99,0.3)] text-sm uppercase tracking-wide transition-all">
                    Guardar Película
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
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-2" style="display:inline-block; animation: spin 2s linear infinite;"></i>Buscando...';
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

<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
</style>
@endsection