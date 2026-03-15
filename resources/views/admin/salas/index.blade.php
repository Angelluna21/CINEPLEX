@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-8 max-w-7xl mt-8">
    <div class="py-8">
        <div class="flex flex-row mb-1 sm:mb-0 justify-between w-full">
            <h2 class="text-2xl leading-tight font-bold text-gray-800">
                Catálogo de Salas
            </h2>
            <div class="text-end">
                <a href="{{ route('salas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-200">
                    + Nueva Sala
                </a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-6 rounded-md shadow-sm" role="alert">
                <p class="font-bold">¡Éxito!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto mt-4">
            <div class="inline-block min-w-full shadow-lg rounded-lg overflow-hidden border border-gray-200">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Sucursal
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Sala
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Capacidad
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Estatus
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-800 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salas as $sala)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 font-bold whitespace-no-wrap">{{ $sala->sucursal->nombre }}</p>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap font-medium">Sala {{ $sala->numero }}</p>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-600 whitespace-no-wrap">{{ $sala->nombre }}</p>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-600 whitespace-no-wrap">{{ $sala->capacidad }} asientos</p>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    @if($sala->estatus == 'Disponible')
                                        <span class="relative inline-block px-3 py-1 font-semibold text-green-800 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full border border-green-300"></span>
                                            <span class="relative text-xs">🟢 Disponible</span>
                                        </span>
                                    @else
                                        <span class="relative inline-block px-3 py-1 font-semibold text-red-800 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full border border-red-300"></span>
                                            <span class="relative text-xs">🔴 Mantenimiento</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex space-x-4">
                                        <a href="{{ route('salas.edit', $sala->id) }}" class="text-blue-600 hover:text-blue-900 font-bold transition duration-200">
                                            Editar
                                        </a>
                                        <form action="{{ route('salas.destroy', $sala->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta sala? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold transition duration-200">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($salas->isEmpty())
                    <div class="px-5 py-10 bg-white text-center">
                        <p class="text-gray-500 font-medium">Aún no hay salas registradas en el sistema.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection