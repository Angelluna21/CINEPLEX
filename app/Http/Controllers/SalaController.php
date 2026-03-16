<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema; // <-- Herramienta para borrado seguro

class SalaController extends Controller
{
    public function index() {
        $salas = \App\Models\Sala::with('sucursal')->get();
        $sucursales = \App\Models\Sucursal::all(); 
        return view('admin.salas.index', compact('salas', 'sucursales'));
    }

    public function create()
    {
        $sucursales = Sucursal::all();
        return view('admin.salas.create', compact('sucursales'));
    }

    public function store(Request $request)
    {
        // Mapeo Maestro: Amarra el tipo de sala con su número fijo y capacidad
        $caracteristicas = [
            'Tradicional' => ['numero' => 1, 'capacidad' => 100],
            '3D'          => ['numero' => 2, 'capacidad' => 120],
            '4D'          => ['numero' => 3, 'capacidad' => 60],
            'IMAX'        => ['numero' => 4, 'capacidad' => 200],
        ];

        $request->validate([
            'sucursal_id' => ['required', 'exists:sucursales,id'],
            'estatus'     => ['required', 'string', 'in:Disponible,Fuera de servicio'], 
            'nombre'      => [
                'required', 
                'string',
                Rule::unique('salas')->where(function ($query) use ($request) {
                    return $query->where('sucursal_id', $request->sucursal_id);
                })
            ]
        ], [
            'nombre.unique' => 'Esta sucursal ya cuenta con una sala de tipo ' . $request->nombre . '.',
        ]);

        Sala::create([
            'nombre'      => $request->nombre,
            'sucursal_id' => $request->sucursal_id,
            'numero'      => $caracteristicas[$request->nombre]['numero'],
            'capacidad'   => $caracteristicas[$request->nombre]['capacidad'],
            'estatus'     => $request->estatus, 
        ]);

        return redirect()->route('salas.index')->with('success', 'Sala registrada con éxito.');
    }

    public function edit(Sala $sala)
    {
        $sucursales = Sucursal::all();
        return view('admin.salas.edit', compact('sala', 'sucursales'));
    }

    public function update(Request $request, Sala $sala)
    {
        $caracteristicas = [
            'Tradicional' => ['numero' => 1, 'capacidad' => 100],
            '3D'          => ['numero' => 2, 'capacidad' => 120],
            '4D'          => ['numero' => 3, 'capacidad' => 60],
            'IMAX'        => ['numero' => 4, 'capacidad' => 200],
        ];

        $request->validate([
            'sucursal_id' => ['required', 'exists:sucursales,id'],
            'estatus'     => ['required', 'string', 'in:Disponible,Fuera de servicio'], 
            'nombre'      => [
                'required', 
                'string', 
                Rule::unique('salas')->where(function ($query) use ($request) {
                    return $query->where('sucursal_id', $request->sucursal_id);
                })->ignore($sala->id)
            ]
        ], [
            'nombre.unique' => 'Otra sala en esta misma sucursal ya es de este tipo.'
        ]);

        $sala->update([
            'nombre'      => $request->nombre,
            'sucursal_id' => $request->sucursal_id,
            'numero'      => $caracteristicas[$request->nombre]['numero'],
            'capacidad'   => $caracteristicas[$request->nombre]['capacidad'],
            'estatus'     => $request->estatus, 
        ]);

        return redirect()->route('salas.index')->with('success', 'Sala actualizada correctamente.');
    }

    public function destroy(Sala $sala)
    {
        // 1. Apagamos la seguridad de SQLite para que no busque "funcions"
        Schema::disableForeignKeyConstraints();
        
        // 2. Borramos la sala a la fuerza
        $sala->delete();

        // 3. Volvemos a encender la seguridad
        Schema::enableForeignKeyConstraints();

        return redirect()->route('salas.index')->with('success', 'Sala eliminada del sistema.');
    }
}