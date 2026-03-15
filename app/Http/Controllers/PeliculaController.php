<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pelicula;
use Illuminate\Http\Request;

class PeliculaController extends Controller
{
    /**
     * Flujo Básico: Consultar Película
     * Recupera todas las películas y despliega la lista[cite: 29, 31, 32].
     */
    public function index()
    {
        $peliculas = Pelicula::all();
        return view('admin.peliculas.index', compact('peliculas'));
    }

    /**
     * Muestra el formulario para Agregar Película[cite: 34].
     */
    public function create()
    {
        return view('admin.peliculas.create');
    }

    /**
     * Flujo Alterno: Agregar Película
     * Guarda los campos obligatorios y valida restricciones.
     */
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

    /**
     * Muestra el formulario con información precargada para Modificar[cite: 36, 37].
     */
    public function edit($id)
    {
        $pelicula = Pelicula::findOrFail($id);
        return view('admin.peliculas.edit', compact('pelicula'));
    }

    /**
     * Flujo Alterno: Modificar Película
     * Actualiza la información en la base de datos[cite: 36, 37].
     */
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

    /**
     * Flujo Alterno: Eliminar Película
     * Verifica que no existan funciones programadas antes de borrar[cite: 38, 39].
     */
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