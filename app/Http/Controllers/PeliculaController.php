<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use App\Models\Genre; // Importamos el modelo de Géneros
use Illuminate\Http\Request;

class PeliculaController extends Controller
{
    public function index()
    {
        $peliculas = Pelicula::all(); 
        return view('admin.peliculas.index', compact('peliculas'));
    }

    public function create()
    {
        // Traemos todos los géneros para el select
        $generos = Genre::all(); 
        return view('admin.peliculas.create', compact('generos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'max:255', 'unique:peliculas'],
            'genero' => ['required', 'string'], // Validamos el género
            'clasificacion' => ['required', 'string'],
            'duracion' => ['required', 'integer', 'min:1'],
            'estatus' => ['required', 'string'],
            'sinopsis' => ['required', 'string', 'min:10'],
        ]);

        Pelicula::create($request->all());

        return redirect()->route('peliculas.index')->with('success', 'Película creada con éxito.');
    }
    // ... resto de funciones


    public function edit(Pelicula $pelicula)
    {
        return view('admin.peliculas.edit', compact('pelicula'));
    }

    public function update(Request $request, Pelicula $pelicula)
{
    $request->validate([
        // Ignora el ID actual para que permita guardar si no cambiaste el título
        'titulo' => ['required', 'string', 'max:255', 'unique:peliculas,titulo,' . $pelicula->id],
        'clasificacion' => ['required', 'string'],
        'duracion' => ['required', 'integer'],
        'estatus' => ['required', 'string'],
    ]);

    $pelicula->update($request->all());

    return redirect()->route('peliculas.index')->with('success', 'Película actualizada.');
}

    public function destroy(Pelicula $pelicula)
    {
        $pelicula->delete();
        return redirect()->route('peliculas.index')->with('success', 'Película eliminada correctamente.');
    }
}