<?php

namespace App\Http\Controllers;

use App\Models\Funcion;
use App\Models\Sala;
use App\Models\Pelicula;
use Illuminate\Http\Request;

class FuncionController extends Controller
{
    public function index()
    {
        $funciones = Funcion::with(['sala', 'pelicula'])->get();
        return view('admin.funciones.index', compact('funciones'));
    }

    public function create()
    {
        // Traemos todas las películas
        $peliculas = Pelicula::all();
        
        // EL CANDADO: Solo traemos las salas que estén 'Disponible'
        $salas = Sala::where('estatus', 'Disponible')->get(); 

        // Creamos la fecha de hoy (mínima) y la fecha máxima (1 mes en el futuro)
        $minDate = \Carbon\Carbon::now()->format('Y-m-d');
        $maxDate = \Carbon\Carbon::now()->addMonth()->format('Y-m-d'); // <-- AQUÍ ESTÁ LA NUEVA VARIABLE

        // IMPORTANTE: Agregamos 'maxDate' al compact para que viaje a la vista
        return view('admin.funciones.create', compact('peliculas', 'salas', 'minDate', 'maxDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'sala_id'     => 'required|exists:salas,id',
            'fecha'       => 'required|date|after_or_equal:today|before_or_equal:' . now()->addMonth()->format('Y-m-d'),
            'hora'        => 'required',
        ]);

        $sala = Sala::findOrFail($request->sala_id);

        $preciosPorTipo = [
            'Tradicional' => 60.00,
            '3D'          => 90.00,
            '4D'          => 150.00,
            'IMAX'        => 200.00,
        ];

        $precioFinal = $preciosPorTipo[$sala->nombre] ?? 50.00;

        Funcion::create([
            'pelicula_id' => $request->pelicula_id,
            'sala_id'     => $request->sala_id,
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'precio'      => $precioFinal,
        ]);

        return redirect()->route('funciones.index')->with('success', 'Función programada con éxito.');
    }

    public function edit($id)
    {
        $funcion = Funcion::findOrFail($id);
        $peliculas = Pelicula::all();
        $salas = Sala::with('sucursal')->get();

        $minDate = now()->format('Y-m-d');
        $maxDate = now()->addMonth()->format('Y-m-d');

        return view('admin.funciones.edit', compact('funcion', 'peliculas', 'salas', 'minDate', 'maxDate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'sala_id'     => 'required|exists:salas,id',
            'fecha'       => 'required|date',
            'hora'        => 'required',
        ]);

        $funcion = Funcion::findOrFail($id);
        $sala = Sala::findOrFail($request->sala_id);

        $preciosPorTipo = [
            'Tradicional' => 60.00,
            '3D'          => 90.00,
            '4D'          => 150.00,
            'IMAX'        => 200.00,
        ];

        $precioFinal = $preciosPorTipo[$sala->nombre] ?? 50.00;

        $funcion->update([
            'pelicula_id' => $request->pelicula_id,
            'sala_id'     => $request->sala_id,
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'precio'      => $precioFinal,
        ]);

        return redirect()->route('funciones.index')->with('success', 'Función actualizada correctamente.');
    }

    public function destroy($id)
    {
        // Buscamos la función por su ID
        $funcion = Funcion::findOrFail($id);
        
        // Ejecutamos la eliminación
        $funcion->delete();

        // Redirigimos con mensaje de éxito
        return redirect()->route('funciones.index')
                         ->with('success', 'La función ha sido eliminada de la cartelera correctamente.');
    }

}