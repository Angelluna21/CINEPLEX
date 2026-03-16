<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Género - Cineplex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-[#0B0F19] text-white p-8 font-sans">

    <div class="max-w-2xl mx-auto mb-6">
        <a href="{{ route('generos.index') }}" class="group inline-flex items-center gap-2 bg-gray-800/40 hover:bg-[#42A5F5] text-white px-4 py-2 rounded-xl border border-gray-700 transition-all shadow-lg">
            <i class="bi bi-arrow-left-circle-fill text-xl transition-transform group-hover:-translate-x-1"></i>
            <span class="font-bold text-sm uppercase tracking-wider">Regresar</span>
        </a>
    </div>

    <div class="max-w-2xl mx-auto bg-[#151E2E] p-10 rounded-3xl shadow-2xl border border-gray-800">
        <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#42A5F5] to-[#E91E63] mb-6">Editar Género</h2>

        <form action="{{ route('generos.update', $genre->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Nombre del Género</label>
                <input type="text" name="nombre" value="{{ old('nombre', $genre->nombre) }}" 
                    class="w-full bg-[#0B0F19] border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-1 focus:ring-[#42A5F5] focus:border-[#42A5F5] transition-all shadow-inner @error('nombre') border-red-500 ring-1 ring-red-500 @enderror" 
                    placeholder="Ej: Terror" required autofocus>
                
                @error('nombre')
                    <div class="mt-3 text-red-400 text-xs flex items-center gap-2 bg-red-500/10 p-3 rounded-lg border border-red-500/20 font-bold">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-800">
                <button type="submit" class="bg-gradient-to-r from-[#42A5F5] to-blue-600 hover:scale-105 text-white px-10 py-3 rounded-xl font-bold transition-all shadow-lg uppercase text-xs tracking-widest">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</body>
</html>