<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use App\Models\Genre;
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
        $generos = Genre::all(); 
        return view('admin.peliculas.create', compact('generos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'max:255', 'unique:peliculas,titulo'],
            'genero' => ['required', 'string'],
            'clasificacion' => ['required', 'string'],
            'duracion' => ['required', 'integer', 'min:1'],
            'idioma' => ['required', 'string'],
            'formato' => ['required', 'string'],
            'estatus' => ['required', 'string'],
            'sinopsis' => ['required', 'string', 'min:10'],
            'imagen_url' => ['nullable', 'url'],
        ], [
            'titulo.unique' => '¡Esta película ya se encuentra registrada en la cartelera!',
        ]);

        Pelicula::create($request->all());

        return redirect()->route('peliculas.index')->with('success', '¡La película "' . $request->titulo . '" se guardó con éxito!');
    }

    public function edit(Pelicula $pelicula)
    {
        $generos = Genre::all();
        return view('admin.peliculas.edit', compact('pelicula', 'generos'));
    }

    public function update(Request $request, Pelicula $pelicula)
    {
        $request->validate([
            'titulo' => ['required', 'string', 'max:255', 'unique:peliculas,titulo,' . $pelicula->id],
            'genero' => ['required', 'string'],
            'clasificacion' => ['required', 'string'],
            'duracion' => ['required', 'integer'],
            'idioma' => ['required', 'string'],
            'formato' => ['required', 'string'],
            'estatus' => ['required', 'string'],
            'sinopsis' => ['required', 'string'],
        ]);

        $pelicula->update($request->all());

        return redirect()->route('peliculas.index')->with('success', 'La película se ha actualizado correctamente.');
    }

    public function destroy(Pelicula $pelicula)
    {
        $pelicula->delete();
        return redirect()->route('peliculas.index')->with('success', 'Película eliminada correctamente.');
    }
}