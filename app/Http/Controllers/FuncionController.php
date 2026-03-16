<?php

namespace App\Http\Controllers;

use App\Models\Funcion;
use App\Models\Sala;
// use App\Models\Pelicula; // Lo dejamos listo por si tienes modelo de películas
use Illuminate\Http\Request;

class FuncionController extends Controller
{
    public function index()
    {
        // Traemos todas las funciones registradas
        $funciones = Funcion::all();
        return view('admin.funciones.index', compact('funciones'));
    }

    public function create()
    {
        // Esta vista la crearemos en el siguiente paso
        return view('admin.funciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelicula_id' => 'required|integer',
            'sala_id' => 'required|exists:salas,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'precio' => 'required|numeric'
        ]);

        Funcion::create($request->all());
        return redirect()->route('funciones.index')->with('success', 'Función programada con éxito.');
    }

    public function edit($id)
    {
        $funcion = Funcion::findOrFail($id);
        return view('admin.funciones.edit', compact('funcion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pelicula_id' => 'required|integer',
            'sala_id' => 'required|exists:salas,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'precio' => 'required|numeric'
        ]);

        $funcion = Funcion::findOrFail($id);
        $funcion->update($request->all());

        return redirect()->route('funciones.index')->with('success', 'Horario de función actualizado.');
    }

    public function destroy($id)
    {
        $funcion = Funcion::findOrFail($id);
        $funcion->delete();

        return redirect()->route('funciones.index')->with('success', 'Función eliminada de la cartelera.');
    }
}