<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;

class SucursalController extends Controller
{
    /**
     * Muestra la lista de sucursales.
     */
    public function index()
    {
        // Traemos todas las sucursales de la base de datos
        $sucursales = Sucursal::all();

        // Enviamos los datos a la vista admin.sucursales.index
        return view('admin.sucursales.index', compact('sucursales'));
    }

    /**
     * Muestra el formulario para crear una nueva sucursal.
     */
    public function create()
    {
        return view('admin.sucursales.create');
    }

    /**
     * Almacena una nueva sucursal en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Sucursal::create($request->all());

        return redirect()->route('sucursales.index')
                         ->with('success', 'Sucursal creada con éxito.');
    }

    // Puedes agregar show, edit, update y destroy después si los necesitas
}