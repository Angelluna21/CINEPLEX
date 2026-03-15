<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Muestra la lista de géneros.
     */
    public function index()
    {
        $generos = Genre::all();
        return view('admin.generos.index', compact('generos'));
    }

    /**
     * Muestra el formulario para crear un nuevo género.
     */
    public function create()
    {
        return view('admin.generos.create');
    }

    /**
     * Guarda un género recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:50', 'unique:genres', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
        ], [
            'nombre.regex' => 'El nombre del género no puede contener números ni símbolos.',
            'nombre.unique' => 'Este género ya existe en el sistema.',
            'nombre.min' => 'El nombre debe tener al menos 3 letras.'
        ]);

        Genre::create($request->only(['nombre']));

        return redirect()->route('generos.index')->with('success', 'Género agregado correctamente.');
    }

    /**
     * Muestra el formulario para editar un género específico.
     */
    public function edit(Genre $genero)
    {
        return view('admin.generos.edit', compact('genero'));
    }

    /**
     * Actualiza un género específico en la base de datos.
     */
    public function update(Request $request, Genre $genero)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:50', 'unique:genres,nombre,' . $genero->id, 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
        ], [
            'nombre.regex' => 'El nombre del género no puede contener números ni símbolos.',
            'nombre.min' => 'El nombre debe tener al menos 3 letras.'
        ]);

        $genero->update($request->only(['nombre']));

        return redirect()->route('generos.index')->with('success', 'Género actualizado con éxito.');
    }

    /**
     * Elimina un género específico de la base de datos.
     */
    public function destroy(Genre $genero)
    {
        $genero->delete();
        return redirect()->route('generos.index')->with('success', 'Género eliminado correctamente.');
    }
}