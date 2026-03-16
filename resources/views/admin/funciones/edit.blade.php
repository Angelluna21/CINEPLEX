<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Función - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-4 md:p-8 font-sans">

    <div class="max-w-2xl mx-auto mb-6">
        <a href="{{ route('funciones.index') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-purple-600 text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider font-sans">Volver a Funciones</span>
        </a>
    </div>

    <div class="max-w-2xl mx-auto bg-[#151E2E] p-6 md:p-10 rounded-3xl border border-gray-800 shadow-2xl">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-purple-400 font-sans">Editar Función</h2>
            <span class="bg-purple-900/30 text-purple-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">ID: #{{ $funcion->id }}</span>
        </div>
        
        <form action="{{ route('funciones.update', $funcion->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-400 text-sm mb-2 font-sans">Película</label>
                    <select name="pelicula_id" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-purple-500 outline-none transition-all font-sans">
                        @foreach($peliculas as $pelicula)
                            <option value="{{ $pelicula->id }}" {{ $funcion->pelicula_id == $pelicula->id ? 'selected' : '' }}>
                                {{ $pelicula->titulo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-400 text-sm mb-2 font-sans">Sala (Ubicación)</label>
                    <select name="sala_id" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-purple-500 outline-none transition-all font-sans">
                        @foreach($salas as $sala)
                            <option value="{{ $sala->id }}" {{ $funcion->sala_id == $sala->id ? 'selected' : '' }}>
                                {{ $sala->nombre }} - {{ $sala->sucursal->nombre ?? 'Sin Sucursal' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-400 text-sm mb-2 font-sans">Fecha Programada</label>
                    <input type="date" name="fecha" value="{{ $funcion->fecha }}"
                           min="{{ $minDate }}" 
                           max="{{ $maxDate }}" 
                           required 
                           class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-purple-500 outline-none transition-all font-sans">
                </div>

                <div>
                    <label class="block text-gray-400 text-sm mb-2 font-sans">Horario</label>
                    <select name="hora" required class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-purple-500 outline-none transition-all font-sans">
                        <option value="16:00" {{ $funcion->hora == '16:00' ? 'selected' : '' }}>04:00 PM</option>
                        <option value="18:00" {{ $funcion->hora == '18:00' ? 'selected' : '' }}>06:00 PM</option>
                    </select>
                </div>
            </div>

            <div class="bg-blue-900/20 border border-blue-800/50 p-4 rounded-xl">
                <p class="text-blue-400 text-xs font-sans">
                    <strong class="uppercase">Nota:</strong> El precio se actualizará automáticamente según el tipo de sala seleccionado al guardar los cambios.
                </p>
            </div>

            <div class="pt-4 text-center">
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 py-3 rounded-xl font-bold uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-purple-500/20 font-sans">
                    Guardar Cambios
                </button>
                
                <a href="{{ route('funciones.index') }}" class="inline-block mt-6 text-gray-500 hover:text-white text-sm transition-colors font-sans">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>