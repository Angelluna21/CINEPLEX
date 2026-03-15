@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-2xl">
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="bg-gray-800 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Registrar Nueva Película</h2>
        </div>

        <form action="{{ route('peliculas.store') }}" method="POST" class="p-6 space-y-4 bg-white">
            @csrf

            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título de la Película</label>
                <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white text-gray-900"
                    placeholder="Escribe el nombre aquí..." required>
                @error('titulo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
    <label for="genero" class="block text-sm font-medium text-gray-700 mb-1">Género</label>
    <select name="genero" id="genero" 
        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-900 @error('genero') border-red-500 @enderror">
        <option value="">-- Selecciona un género --</option>
        @foreach($generos as $g)
            <option value="{{ $g->nombre }}" {{ old('genero') == $g->nombre ? 'selected' : '' }}>
                {{ $g->nombre }}
            </option>
        @endforeach
    </select>
    @error('genero')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="clasificacion" class="block text-sm font-medium text-gray-700 mb-1">Clasificación</label>
                    <select name="clasificacion" id="clasificacion" 
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white text-gray-900">
                        <option value="A" {{ old('clasificacion') == 'A' ? 'selected' : '' }}>A (Todo público)</option>
                        <option value="B" {{ old('clasificacion') == 'B' ? 'selected' : '' }}>B (12+ años)</option>
                        <option value="B15" {{ old('clasificacion') == 'B15' ? 'selected' : '' }}>B15 (15+ años)</option>
                        <option value="C" {{ old('clasificacion') == 'C' ? 'selected' : '' }}>C (Adultos)</option>
                    </select>
                </div>

                <div>
                    <label for="duracion" class="block text-sm font-medium text-gray-700 mb-1">Duración (minutos)</label>
                    <input type="number" name="duracion" id="duracion" value="{{ old('duracion') }}" 
                        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white text-gray-900"
                        placeholder="Ej. 120" required>
                </div>
            </div>

            <div class="bg-gray-50 p-3 rounded-md border border-gray-100">
                <label class="block text-sm font-medium text-gray-700 mb-2">Estatus en Cine</label>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="estatus" value="Estreno" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ old('estatus') == 'Estreno' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-900">Estreno</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="estatus" value="Cartelera" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ old('estatus', 'Cartelera') == 'Cartelera' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-900">Cartelera</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="estatus" value="Próximamente" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ old('estatus') == 'Próximamente' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-900">Próximamente</span>
                    </label>
                </div>
            </div>

            <div class="mt-4">
    <label for="sinopsis" class="block text-sm font-medium text-gray-700 mb-1">Sinopsis</label>
    <textarea name="sinopsis" id="sinopsis" rows="4" 
        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-900 @error('sinopsis') border-red-500 @enderror"
        placeholder="Escribe el resumen de la película aquí..." required>{{ old('sinopsis') }}</textarea>
    @error('sinopsis')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                <a href="{{ route('peliculas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-5 rounded shadow-sm text-sm transition duration-200">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-md text-sm transition duration-200">
                    Guardar Película
                </button>
            </div>
        </form>
    </div>
</div>
@endsection