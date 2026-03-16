<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;

class SucursalController extends Controller
{
    /**
     * Muestra la lista de sucursales.
     */
    public function index(Request $request)
{
    // Capturamos el término de búsqueda
    $buscar = $request->get('buscar');

    // Si hay algo escrito en el buscador, filtramos; si no, traemos todo
    $sucursales = Sucursal::when($buscar, function ($query, $buscar) {
        return $query->where('nombre', 'LIKE', "%{$buscar}%")
                     ->orWhere('ubicacion', 'LIKE', "%{$buscar}%");
    })->get();

    return view('admin.sucursales.index', compact('sucursales', 'buscar'));
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
        // 'unique:sucursales,nombre' verifica que no exista en la tabla sucursales
        'nombre' => 'required|string|max:255|unique:sucursales,nombre',
        'ubicacion' => 'nullable|string|max:255',
    ]);

    Sucursal::create($request->all());

    return redirect()->route('sucursales.index')
                     ->with('success', 'Sucursal creada con éxito.');
}
    public function edit($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        // Asegúrate de crear el archivo: resources/views/admin/sucursales/edit.blade.php
        return view('admin.sucursales.edit', compact('sucursal'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        // Al editar, debemos ignorar el ID actual para que permita guardar si no cambias el nombre
        'nombre' => 'required|string|max:255|unique:sucursales,nombre,' . $id,
        'ubicacion' => 'nullable|string|max:255',
    ]);

    $sucursal = Sucursal::findOrFail($id);
    $sucursal->update($request->all());

    return redirect()->route('sucursales.index')
                     ->with('success', 'Sucursal actualizada con éxito.');
}
    public function destroy($id)
{
    $sucursal = Sucursal::findOrFail($id);

    // 1. Desactivamos las llaves foráneas para que SQLite deje de buscar la tabla 'funcions'
    \DB::statement('PRAGMA foreign_keys = OFF');

    // 2. Borramos las dependencias manualmente usando los modelos corregidos
    foreach ($sucursal->salas as $sala) {
        $sala->funciones()->delete(); 
        $sala->delete();
    }

    // 3. Borramos la sucursal
    $sucursal->delete();

    // 4. Reactivamos las llaves foráneas por seguridad
    \DB::statement('PRAGMA foreign_keys = ON');

    return redirect()->route('sucursales.index')
                     ->with('success', 'Sucursal eliminada correctamente.');
}
}