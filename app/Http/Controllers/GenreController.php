<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        // Ya no filtramos aquí. Mandamos todos y dejamos que JavaScript haga la magia rápida.
        $generos = Genre::orderBy('nombre', 'asc')->get();
        return view('admin.generos.index', compact('generos'));
    }

    public function create()
    {
        return view('admin.generos.create');
    }

    public function store(Request $request)
    {
        $nombreFormateado = ucfirst(strtolower(trim($request->nombre)));
        $request->merge(['nombre' => $nombreFormateado]);

        $request->validate([
            'nombre' => ['required', 'string', 'max:50', 'unique:genres,nombre', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
        ], [
            'nombre.unique' => '¡Ese género ya existe!',
            'nombre.regex' => 'Solo se permiten letras y espacios.',
        ]);

        Genre::create(['nombre' => $nombreFormateado]);

        return redirect()->route('generos.index')->with('success', '¡Género agregado!');
    }

    // --- NUEVO: Función para mostrar el formulario de edición ---
    public function edit(Genre $genre)
    {
        return view('admin.generos.edit', compact('genre'));
    }

    public function update(Request $request, Genre $genre)
    {
        $nombreFormateado = ucfirst(strtolower(trim($request->nombre)));
        $request->merge(['nombre' => $nombreFormateado]);

        $request->validate([
            'nombre' => [
                'required', 'string', 'max:50', 
                'unique:genres,nombre,' . $genre->id, // Usa $genre en inglés
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'
            ],
        ], [
            'nombre.unique' => '¡Ese género ya existe!',
            'nombre.regex' => 'Solo se permiten letras y espacios.',
        ]);

        $genre->update(['nombre' => $nombreFormateado]);

        return redirect()->route('generos.index')->with('success', 'Género actualizado correctamente.');
    }

    public function destroy(Genre $genre)
    {
        //Revisamos si alguna película tiene el nombre de este género
        $enUso = \App\Models\Pelicula::where('genero', $genre->nombre)->exists();

        //Si está en uso, abortamos la misión y mandamos error
        if ($enUso) {
            return redirect()->route('generos.index')
                ->with('error', '¡No puedes eliminar el género ' . $genre->nombre . ' porque hay películas en cartelera que lo están usando.');
        }

        // 3. Si nadie lo usa, lo eliminamos
        $genre->delete();
        
        return redirect()->route('generos.index')->with('success', 'Género eliminado de la base de datos.');
    }
}