<?php

namespace App\Http\Controllers;

use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FuncionController extends Controller
{
    // Tarifario maestro seguro en el Backend
    private $precios = [
        'Tradicional' => 80.00,
        '3D'          => 105.00,
        '4D'          => 120.00,
        'IMAX'        => 135.00
    ];

    public function index(Request $request)
    {
        // Iniciamos la consulta base con sus relaciones
        $query = Funcion::with(['pelicula', 'sala.sucursal']);

        // FILTRO 1: Por Fecha
        if ($request->filled('fecha')) {
            $query->where('fecha', $request->fecha);
        }

        // FILTRO 2: Por Nombre de Película (Búsqueda aproximada)
        if ($request->filled('pelicula')) {
            $query->whereHas('pelicula', function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->pelicula . '%');
            });
        }

        // Ejecutamos la consulta ordenada
        $funciones = $query->orderBy('fecha', 'desc')
                           ->orderBy('hora', 'desc')
                           ->get();
                            
        return view('admin.funciones.index', compact('funciones'));
    }
    public function create()
    {
        $peliculas = Pelicula::whereIn('estatus', ['Estreno', 'Cartelera'])->get(); 
        $salas = Sala::with('sucursal')->where('estatus', 'Disponible')->get(); 
        
        return view('admin.funciones.create', compact('peliculas', 'salas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'sala_id'     => 'required|exists:salas,id',
            'fecha'       => 'required|date|after_or_equal:today',
            'hora'        => 'required'
        ]);

        $hora_formateada = Carbon::parse($request->hora)->format('H:i:s');

        // Validar Empalme
        $conflicto = Funcion::where('sala_id', $request->sala_id)
                            ->where('fecha', $request->fecha)
                            ->where('hora', $hora_formateada)
                            ->first();

        if ($conflicto) {
            return redirect()->back()
                   ->with('error', 'Error: Esta sala ya tiene una función programada en ese horario exacto.')
                   ->withInput();
        }

        // MAGIA DE SEGURIDAD: Obtenemos la sala para saber su tipo y asignar el precio correcto
        $sala = Sala::findOrFail($request->sala_id);
        $precio_calculado = $this->precios[$sala->nombre] ?? 80.00;

        Funcion::create([
            'pelicula_id' => $request->pelicula_id,
            'sala_id'     => $request->sala_id,
            'fecha'       => $request->fecha,
            'hora'        => $hora_formateada,
            'precio'      => $precio_calculado, // ¡AQUÍ GUARDAMOS EL PRECIO!
        ]);

        return redirect()->route('funciones.index')->with('success', '¡Función programada con éxito en la cartelera!');
    }

    public function edit(Funcion $funcion)
    {
        $peliculas = Pelicula::whereIn('estatus', ['Estreno', 'Cartelera'])->get(); 
        $salas = Sala::with('sucursal')->where('estatus', 'Disponible')->get(); 
        
        return view('admin.funciones.edit', compact('funcion', 'peliculas', 'salas'));
    }

    public function update(Request $request, Funcion $funcion)
    {
        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'sala_id'     => 'required|exists:salas,id',
            'fecha'       => 'required|date',
            'hora'        => 'required'
        ]);

        $hora_formateada = Carbon::parse($request->hora)->format('H:i:s');

        $conflicto = Funcion::where('sala_id', $request->sala_id)
                            ->where('fecha', $request->fecha)
                            ->where('hora', $hora_formateada)
                            ->where('id', '!=', $funcion->id)
                            ->first();

        if ($conflicto) {
            return redirect()->back()
                   ->with('error', 'Error: Choque de horarios. La sala ya está ocupada.')
                   ->withInput();
        }

        // Volvemos a calcular el precio por si el empleado cambió de sala
        $sala = Sala::findOrFail($request->sala_id);
        $precio_calculado = $this->precios[$sala->nombre] ?? 80.00;

        $funcion->update([
            'pelicula_id' => $request->pelicula_id,
            'sala_id'     => $request->sala_id,
            'fecha'       => $request->fecha,
            'hora'        => $hora_formateada,
            'precio'      => $precio_calculado, // ¡AQUÍ ACTUALIZAMOS EL PRECIO!
        ]);

        return redirect()->route('funciones.index')->with('success', 'Función actualizada correctamente.');
    }

    public function destroy(Funcion $funcion)
    {
        $funcion->delete();
        return redirect()->route('funciones.index')->with('success', 'Función cancelada y retirada de la cartelera.');
    }
}