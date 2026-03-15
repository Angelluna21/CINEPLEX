@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Nuevo Género</h1>
        <a href="{{ route('generos.index') }}" class="text-blue-600 hover:underline">&larr; Volver</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 border-t-4 border-blue-600">
        <form action="{{ route('generos.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                    Nombre del Género
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    id="nombre" name="nombre" type="text" placeholder="Ej. Ciencia Ficción" 
                    minlength="3" maxlength="50" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" 
                    title="Solo letras, mínimo 3 y máximo 50." required autofocus>
            </div>

            <div class="flex items-center justify-end">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-200" type="submit">
                    Guardar Género
                </button>
            </div>
        </form>
    </div>
</div>
@endsection