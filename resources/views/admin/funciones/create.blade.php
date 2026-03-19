@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 px-4 max-w-4xl font-sans text-white">
    
    <div class="mb-6">
        <a href="{{ route('funciones.index') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#E91E63] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Volver a Funciones</span>
        </a>
    </div>

    <div class="bg-[#151E2E] shadow-2xl rounded-3xl overflow-hidden border border-gray-800">
        
        <div class="px-8 py-6 border-b border-gray-800 bg-gray-900/30">
            <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63]">
                Programar Nueva Función
            </h2>
            <p class="text-gray-400 text-sm mt-1">Asigna una película a una sala disponible en tu sucursal.</p>
        </div>

        <form action="{{ route('funciones.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Película <span class="text-red-500">*</span></label>
                <select name="pelicula_id" class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all appearance-none shadow-inner cursor-pointer" required>
                    <option value="" disabled selected>-- Elige película --</option>
                    @foreach($peliculas as $pelicula)
                        <option value="{{ $pelicula->id }}">{{ $pelicula->titulo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Sucursal <span class="text-red-500">*</span></label>
                    <select id="sucursal_selector" class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all appearance-none shadow-inner cursor-pointer" required>
                        <option value="" disabled selected>-- Selecciona Sucursal --</option>
                        @php
                            // Solo sucursales con salas disponibles
                            $sucursales = $salas->pluck('sucursal')->filter()->unique('id');
                        @endphp
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tipo de Sala <span class="text-red-500">*</span></label>
                    <select name="sala_id" id="sala_id" class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-gray-500 focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all appearance-none shadow-inner cursor-not-allowed opacity-50" required disabled>
                        <option value="" disabled selected>-- Primero elige sucursal --</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-gray-900/50 rounded-2xl border border-gray-800">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Fecha de Proyección (Máx. 1 mes) <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha" min="{{ $minDate }}" max="{{ $maxDate }}" class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all shadow-inner cursor-pointer" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Horario <span class="text-red-500">*</span></label>
                    <select name="hora" class="block w-full bg-[#0B0F19] border border-gray-700 rounded-xl py-3 px-4 text-white focus:outline-none focus:ring-1 focus:ring-[#E91E63] focus:border-[#E91E63] transition-all appearance-none shadow-inner cursor-pointer" required>
                        <option value="" disabled selected>-- Seleccione horario --</option>
                        <option value="16:00">04:00 PM</option>
                        <option value="18:00">06:00 PM</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end items-center gap-4 pt-6 border-t border-gray-800 mt-8">
                <a href="{{ route('funciones.index') }}" class="text-gray-400 hover:text-white font-bold py-2 px-4 transition-colors text-sm uppercase tracking-wide">Cancelar</a>
                <button type="submit" class="bg-gradient-to-r from-[#42A5F5] to-[#E91E63] hover:scale-105 text-white font-bold py-3 px-8 rounded-xl shadow-[0_0_20px_rgba(233,30,99,0.3)] text-sm uppercase tracking-wide transition-all">
                    Guardar Función
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sucursalSelect = document.getElementById('sucursal_selector');
        const salaSelect = document.getElementById('sala_id');
        
        // Transformamos todas las salas disponibles desde Laravel a un formato que JavaScript entienda
        const todasLasSalas = @json($salas);

        sucursalSelect.addEventListener('change', function() {
            const selectedSucursal = this.value;
            
            // Paso 1: Vaciamos el menú por completo para evitar cruces
            salaSelect.innerHTML = '<option value="" disabled selected>-- Ahora elige el tipo de sala --</option>';
            
            if (selectedSucursal) {
                // Paso 2: Encendemos el menú visualmente
                salaSelect.disabled = false;
                salaSelect.classList.remove('text-gray-500', 'cursor-not-allowed', 'opacity-50');
                salaSelect.classList.add('text-white', 'cursor-pointer');
                
                // Paso 3: Filtramos de nuestro listado solo las que pertenezcan a la sucursal que se acaba de elegir
                const salasFiltradas = todasLasSalas.filter(sala => sala.sucursal_id == selectedSucursal);
                
                // Paso 4: Construimos y agregamos las opciones nuevas
                salasFiltradas.forEach(sala => {
                    const option = document.createElement('option');
                    option.value = sala.id;
                    option.textContent = sala.nombre; // Mostrará únicamente "Tradicional", "3D", etc.
                    salaSelect.appendChild(option);
                });
            } else {
                // Si deselecciona todo, lo apagamos y vaciamos
                salaSelect.disabled = true;
                salaSelect.classList.add('text-gray-500', 'cursor-not-allowed', 'opacity-50');
                salaSelect.classList.remove('text-white', 'cursor-pointer');
                salaSelect.innerHTML = '<option value="" disabled selected>-- Primero elige sucursal --</option>';
            }
        });
    });
</script>
@endsection