<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pelicula;
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
        return view('admin.peliculas.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'genero' => 'required|string|max:255',
            'duracion' => 'required|integer|min:1',
            'clasificacion' => 'required|in:A,B,C',
            'estatus' => 'required|in:Estreno,Cartelera,No disponible',
            'imagen_url' => 'nullable|url',
        ]);

        Pelicula::create($validated);

        return redirect('/admin/peliculas')->with('success', 'Película agregada correctamente.');
    }

    public function edit($id)
    {
        $pelicula = Pelicula::findOrFail($id);
        return view('admin.peliculas.edit', compact('pelicula'));
    }

  
    public function update(Request $request, $id)
    {
        $pelicula = Pelicula::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'genero' => 'required|string|max:255',
            'duracion' => 'required|integer|min:1',
            'clasificacion' => 'required|in:A,B,C',
            'estatus' => 'required|in:Estreno,Cartelera,No disponible',
            'imagen_url' => 'nullable|url',
        ]);

        $pelicula->update($validated);

        return redirect('/admin/peliculas')->with('success', 'Película actualizada correctamente.');
    }

  
    public function destroy($id)
    {
        $pelicula = Pelicula::findOrFail($id);

        // Validación: verificar si existen funciones programadas 
        if ($pelicula->funciones()->count() > 0) {
            return redirect('/admin/peliculas')->with('error', 'No se puede eliminar la película porque tiene funciones programadas.');
        }

        $pelicula->delete();

        return redirect('/admin/peliculas')->with('success', 'Película eliminada correctamente.');
    }
}