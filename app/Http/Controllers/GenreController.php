<?php

namespace App\Http\Controllers;

use App\Models\Genre; 
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        $generos = Genre::all();
        return view('admin.generos.index', compact('generos'));
    }

    public function create()
    {
        return view('admin.generos.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|unique:genres|max:255']);
        Genre::create($request->all());
        return redirect()->route('generos.index')->with('success', 'Género creado correctamente.');
    }

    public function edit($id)
    {
        $genero = Genre::findOrFail($id);
        return view('admin.generos.edit', compact('genero'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required|max:255']);
        $genero = Genre::findOrFail($id);
        $genero->update($request->all());
        return redirect()->route('generos.index')->with('success', 'Género actualizado.');
    }

    public function destroy($id)
    {
        $genero = Genre::findOrFail($id);
        $genero->delete();
        return redirect()->route('generos.index')->with('success', 'Género eliminado.');
    }
}