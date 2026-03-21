@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-2xl font-sans text-white">

    <div class="mb-6">
        <a href="{{ route('usuarios.index') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Atrás</span>
        </a>
    </div>

    <div class="bg-[#151E2E] p-8 rounded-3xl shadow-2xl border border-gray-800">

        <div class="mb-8 border-b border-gray-800 pb-4">
            <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">Editar Usuario</h1>
            <p class="text-gray-400 text-sm mt-1">Actualiza los datos y accesos del personal</p>
        </div>

        @if ($errors->any())
        <div class="mb-8 p-4 bg-red-500/10 border border-red-500/50 text-red-400 rounded-2xl shadow-[0_0_15px_rgba(239,68,68,0.2)]">
            <div class="flex items-center gap-3 mb-2">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                <strong class="font-bold tracking-wide">¡Oops! Revisa los siguientes errores:</strong>
            </div>
            <ul class="list-disc ml-8 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2 uppercase tracking-wider" for="name">
                    Nombre Completo
                </label>
                <div class="relative">
                    <i class="bi bi-person absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner placeholder-gray-600"
                        id="name" name="name" type="text" value="{{ old('name', $usuario->name) }}"
                        minlength="3" maxlength="50" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios, mínimo 3 caracteres." placeholder="Ej. Juan Pérez" required>
                </div>
            </div>

            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2 uppercase tracking-wider" for="email">
                    Correo Electrónico
                </label>
                <div class="relative">
                    <i class="bi bi-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner placeholder-gray-600"
                        id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}" placeholder="correo@cineplex.com" required>
                </div>
            </div>

            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2 uppercase tracking-wider" for="role">
                    Rol en el Sistema
                </label>
                <div class="relative">
                    <i class="bi bi-person-badge absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <select id="role" name="role" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 pl-10 pr-10 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner appearance-none cursor-pointer">
                        <option value="empleado" {{ old('role', $usuario->role) == 'empleado' ? 'selected' : '' }}>Empleado</option>
                        <option value="admin" {{ old('role', $usuario->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2 uppercase tracking-wider" for="password">
                    Nueva Contraseña (Opcional)
                </label>
                <div class="relative">
                    <i class="bi bi-key absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 pl-10 pr-4 text-white focus:outline-none focus:ring-1 focus:ring-[#42A5F5] focus:border-[#42A5F5] transition-all shadow-inner placeholder-gray-600"
                        id="password" name="password" type="password" placeholder="Déjalo en blanco para no cambiarla" minlength="8">
                </div>
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                    <i class="bi bi-info-circle"></i> Si no deseas cambiar la contraseña, deja este campo vacío.
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-800 flex justify-end">
                <button type="submit" class="bg-gradient-to-r from-[#42A5F5] to-[#E91E63] hover:scale-105 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-[0_0_20px_rgba(233,30,99,0.4)] uppercase tracking-wide flex items-center gap-2">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection