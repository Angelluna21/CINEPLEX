<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SalaController extends Controller
{
    public function index()
    {
        // Traemos las salas incluyendo la información de su sucursal (Relación)
        $salas = Sala::with('sucursal')->get();
        return view('admin.salas.index', compact('salas'));
    }

    public function create()
    {
        // Traemos las sucursales para el menú desplegable
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
            'estatus'     => ['required', 'string', 'in:Disponible,Fuera de servicio'], // NUEVO CANDADO DE ESTATUS
            'nombre'      => [
                'required', 
                'string',
                // REGLA: No se puede repetir el TIPO de sala en la misma sucursal
                Rule::unique('salas')->where(function ($query) use ($request) {
                    return $query->where('sucursal_id', $request->sucursal_id);
                })
            ]
        ], [
            'nombre.unique' => 'Esta sucursal ya cuenta con una sala de tipo ' . $request->nombre . '.',
        ]);

        // El sistema asigna el número, capacidad y estatus de forma segura
        Sala::create([
            'nombre'      => $request->nombre,
            'sucursal_id' => $request->sucursal_id,
            'numero'      => $caracteristicas[$request->nombre]['numero'],
            'capacidad'   => $caracteristicas[$request->nombre]['capacidad'],
            'estatus'     => $request->estatus, // GUARDAMOS EL ESTATUS
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
        // Usamos el mismo Mapeo Maestro para proteger la edición
        $caracteristicas = [
            'Tradicional' => ['numero' => 1, 'capacidad' => 100],
            '3D'          => ['numero' => 2, 'capacidad' => 120],
            '4D'          => ['numero' => 3, 'capacidad' => 60],
            'IMAX'        => ['numero' => 4, 'capacidad' => 200],
        ];

        $request->validate([
            'sucursal_id' => ['required', 'exists:sucursales,id'],
            'estatus'     => ['required', 'string', 'in:Disponible,Fuera de servicio'], // CANDADO DE ESTATUS AL EDITAR
            'nombre'      => [
                'required', 
                'string', 
                // Ignoramos la sala actual al validar si el tipo se repite
                Rule::unique('salas')->where(function ($query) use ($request) {
                    return $query->where('sucursal_id', $request->sucursal_id);
                })->ignore($sala->id)
            ]
        ], [
            'nombre.unique' => 'Otra sala en esta misma sucursal ya es de este tipo.'
        ]);

        // Actualizamos de forma automática incluyendo el estatus
        $sala->update([
            'nombre'      => $request->nombre,
            'sucursal_id' => $request->sucursal_id,
            'numero'      => $caracteristicas[$request->nombre]['numero'],
            'capacidad'   => $caracteristicas[$request->nombre]['capacidad'],
            'estatus'     => $request->estatus, // ACTUALIZAMOS EL ESTATUS
        ]);

        return redirect()->route('salas.index')->with('success', 'Sala actualizada correctamente.');
    }

    public function destroy(Sala $sala)
    {
        $sala->delete();
        return redirect()->route('salas.index')->with('success', 'Sala eliminada del sistema.');
    }
}