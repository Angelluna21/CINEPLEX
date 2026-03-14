<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Pelicula;
use App\Models\Sucursal;

// VISTAS DEL CLIENTE 

Route::get('/', function (Request $request) {
    // Sucursales que se muestran en el menu del filtro
    $sucursales = Sucursal::all();
    
    // Query para filtracion 
    $sucursal_id = $request->query('sucursal');

    if ($sucursal_id) {
        // Funciojn de filtro de cada sucursal
        $peliculas = Pelicula::whereHas('funciones', function ($query) use ($sucursal_id) {
            $query->whereHas('sala', function ($q) use ($sucursal_id) {
                $q->where('sucursal_id', $sucursal_id);
            });
        })->get();
    } else {

        // Funcion de muestra de todas las peliculas SIN FILTRO
        $peliculas = Pelicula::all();
    }

    return view('welcome', compact('peliculas', 'sucursales', 'sucursal_id'));
});

// PANEL ADMINISTRATIVO (EMPLEADOS)

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/admin/peliculas', function () {
    $peliculas = Pelicula::all();
    return view('admin.peliculas.index', compact('peliculas'));
});

// Crear una nueva película
Route::get('/admin/peliculas/create', function () {
    return view('admin.peliculas.create');
});

// Guardar una nueva película
Route::post('/admin/peliculas', function (Request $request) {
    Pelicula::create($request->all());
    return redirect('/admin/peliculas')->with('success', '¡Película registrada con éxito!');
});

// formulario de Edición
Route::get('/admin/peliculas/{id}/edit', function ($id) {
    $pelicula = Pelicula::findOrFail($id);
    return view('admin.peliculas.edit', compact('pelicula'));
});

// Actualizar
Route::put('/admin/peliculas/{id}', function (Request $request, $id) {
    $pelicula = Pelicula::findOrFail($id);
    $pelicula->update($request->all());
    return redirect('/admin/peliculas')->with('success', '¡Película actualizada correctamente!');
});

// Eliminar películas
Route::delete('/admin/peliculas/{id}', function ($id) {
    $pelicula = Pelicula::findOrFail($id);
    $pelicula->delete();
    return redirect('/admin/peliculas')->with('success', 'La película ha sido eliminada del catálogo.');
});

use App\Models\Funcion;

// Ruta para ver el catálogo de funciones programadas
Route::get('/admin/funciones', function () {
    $funciones = Funcion::with(['pelicula', 'sala.sucursal'])->get();
    return view('admin.funciones.index', compact('funciones'));
});


// MÓDULO DE FUNCIONES

// Ruta para ver el catálogo de funciones programadas
Route::get('/admin/funciones', function () {
    $funciones = App\Models\Funcion::with(['pelicula', 'sala.sucursal'])->get();
    return view('admin.funciones.index', compact('funciones'));
});

// Ruta para mostrar el formulario de nueva funcion
Route::get('/admin/funciones/create', function () {
    $peliculas = App\Models\Pelicula::where('estatus', '!=', 'No disponible')->get(); 
    $salas = App\Models\Sala::with('sucursal')->get(); 
    
    return view('admin.funciones.create', compact('peliculas', 'salas'));
});

// Ruta para Guardar la Funcion en la base de datos

// Ruta para Guardar la Función en la base de datos
Route::post('/admin/funciones', function (Illuminate\Http\Request $request) {
    $datos = $request->all();

    if (isset($datos['hora'])) {
        $datos['hora'] = \Carbon\Carbon::parse($datos['hora'])->format('H:i:s');
    }

    App\Models\Funcion::create($datos);
    return redirect('/admin/funciones')->with('success', '¡Función programada con éxito!');
});