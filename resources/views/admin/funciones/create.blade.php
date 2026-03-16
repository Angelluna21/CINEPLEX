<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Función - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0B0F19] text-white p-8 font-sans">
    <div class="max-w-2xl mx-auto bg-[#151E2E] p-10 rounded-3xl border border-gray-800 shadow-2xl">
        <h2 class="text-2xl font-bold mb-6 text-purple-400 font-sans">Registrar Nueva Función</h2>
        
        <form action="{{ route('funciones.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-400 text-sm mb-2 font-sans">Película</label>
                    <select name="pelicula_id" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-purple-500 outline-none font-sans">
                        <option value="">-- Elige película --</option>
                        @foreach($peliculas as $pelicula)
                            <option value="{{ $pelicula->id }}">{{ $pelicula->titulo }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
    <label class="block text-gray-400 text-sm mb-2 font-sans">Sala y Sucursal</label>
    <select name="sala_id" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-purple-500 outline-none font-sans">
        <option value="">-- Selecciona Sala (Ubicación) --</option>
        @foreach($salas as $sala)
            {{-- Aquí concatenamos el nombre de la sala con el de la sucursal --}}
            <option value="{{ $sala->id }}">
                {{ $sala->nombre }} - {{ $sala->sucursal->nombre ?? 'Sin Sucursal' }}
            </option>
        @endforeach
    </select>
</div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-400 text-sm mb-2 font-sans">Fecha (Máximo 1 mes)</label>
                    <input type="date" name="fecha" 
                           min="{{ $minDate }}" 
                           max="{{ $maxDate }}" 
                           required 
                           class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-purple-500 outline-none font-sans">
                </div>

                <div>
                    <label class="block text-gray-400 text-sm mb-2 font-sans">Horario</label>
                    <select name="hora" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-purple-500 outline-none font-sans">
                        <option value="">-- Seleccione horario --</option>
                        <option value="16:00">04:00 PM</option>
                        <option value="18:00">06:00 PM</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 text-center">
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 py-3 rounded-xl font-bold uppercase tracking-widest hover:scale-[1.02] transition-all font-sans">
                    Guardar Función
                </button>
                <a href="{{ route('funciones.index') }}" class="block mt-4 text-gray-500 text-sm font-sans">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>