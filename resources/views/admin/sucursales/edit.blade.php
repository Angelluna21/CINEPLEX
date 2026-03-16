<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Sucursal - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0B0F19] text-white p-8">
    <div class="max-w-xl mx-auto bg-[#151E2E] p-10 rounded-3xl border border-gray-800 shadow-2xl">
        <h2 class="text-2xl font-bold mb-6 text-blue-400">Editar Sucursal</h2>
        
        <form action="{{ route('sucursales.update', $sucursal->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') <div>
                <label class="block text-gray-400 text-sm mb-2">Nombre de la Sucursal</label>
                <input type="text" 
                       name="nombre" 
                       value="{{ old('nombre', $sucursal->nombre) }}" 
                       required 
                       class="w-full bg-[#0B0F19] border @error('nombre') border-red-500 @else border-gray-700 @enderror rounded-xl p-3 text-white focus:border-blue-500 outline-none transition-all">
                
                @error('nombre')
                    <p class="text-red-500 text-xs mt-2 italic">Este nombre ya está en uso por otra sucursal.</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-2">Ubicación / Dirección</label>
                <textarea name="ubicacion" 
                          required 
                          class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl p-3 text-white focus:border-blue-500 outline-none transition-all" 
                          rows="3">{{ old('ubicacion', $sucursal->ubicacion) }}</textarea>
                @error('ubicacion')
                    <p class="text-red-500 text-xs mt-2 italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 py-3 rounded-xl font-bold uppercase tracking-widest transition-colors shadow-lg">
                    Actualizar Sucursal
                </button>
                
                <a href="{{ route('sucursales.index') }}" class="block text-center mt-6 text-gray-500 text-sm hover:text-white transition-colors">
                    Cancelar y Volver
                </a>
            </div>
        </form>
    </div>
</body>
</html>