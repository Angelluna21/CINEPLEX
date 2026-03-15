@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Módulo de Usuarios (Gerente)</h1>
        <a href="{{ route('usuarios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            + Agregar Nuevo Usuario
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm text-left">Nombre</th>
                    <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm text-left">Correo Electrónico</th>
                    <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm text-left">Fecha de Registro</th>
                    <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($usuarios as $usuario)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-4">{{ $usuario->name }}</td>
                    <td class="py-3 px-4">{{ $usuario->email }}</td>
                    <td class="py-3 px-4">{{ $usuario->created_at->format('d/m/Y') }}</td>
                    <td class="py-3 px-4 text-center flex justify-center space-x-2">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-sm">
                            Modificar
                        </a>
                        
                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas dar de baja a este empleado?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection