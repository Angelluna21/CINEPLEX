@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-2xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Modificar Empleado</h1>
        <a href="{{ route('usuarios.index') }}" class="text-blue-600 hover:underline">&larr; Volver a la lista</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">¡Oops! Revisa los siguientes errores:</strong>
            <ul class="list-disc mt-2 ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nombre Completo
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="name" name="name" type="text" value="{{ old('name', $usuario->name) }}" 
                    minlength="3" maxlength="50" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios, mínimo 3 caracteres." required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Correo Electrónico
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Nueva Contraseña (Opcional)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" 
                    id="password" name="password" type="password" placeholder="Déjalo en blanco para no cambiarla" minlength="8">
                <p class="text-xs text-gray-500">Si no deseas cambiar la contraseña de este empleado, deja este campo vacío.</p>
            </div>

            <div class="flex items-center justify-end">
                <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection