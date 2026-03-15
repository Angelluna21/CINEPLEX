<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Pelicula;
use App\Models\Sucursal;
<<<<<<< HEAD
use App\Models\Funcion;
use App\Models\Sala;
use Carbon\Carbon;
=======
use App\Http\Controllers\PeliculaController;


>>>>>>> bc142e67a12a4bb7ecf5bd93ddf279e1fb4697b1

// ==========================================
// VISTAS DEL CLIENTE 
// ==========================================

Route::get('/', function (Request $request) {
    // Sucursales que se muestran en el menu del filtro
    $sucursales = Sucursal::all();
    
    // Query para filtracion 
    $sucursal_id = $request->query('sucursal');

    if ($sucursal_id) {
        // Filtro de sucursal trayendo funciones ordenadas por fecha y hora
        $peliculas = Pelicula::whereHas('funciones.sala', function ($q) use ($sucursal_id) {
            $q->where('sucursal_id', $sucursal_id);
        })->with(['funciones' => function ($query) use ($sucursal_id) {
            $query->whereHas('sala', function ($q) use ($sucursal_id) {
                $q->where('sucursal_id', $sucursal_id);
            })->orderBy('fecha')->orderBy('hora');
        }])->get();
    } else {
        // Muestra de todas las peliculas con sus horarios ordenados
        $peliculas = Pelicula::with(['funciones' => function ($query) {
            $query->orderBy('fecha')->orderBy('hora');
        }])->get();
    }

    return view('welcome', compact('peliculas', 'sucursales', 'sucursal_id'));
});

// ==========================================
// PANEL ADMINISTRATIVO (EMPLEADOS)
// ==========================================

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

// Formulario de Edición
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

// ==========================================
// MÓDULO DE FUNCIONES
// ==========================================

// Ruta para ver el catálogo de funciones programadas
Route::get('/admin/funciones', function () {
    $funciones = Funcion::with(['pelicula', 'sala.sucursal'])->get();
    return view('admin.funciones.index', compact('funciones'));
});

// Ruta para mostrar el formulario de nueva funcion
Route::get('/admin/funciones/create', function () {
    // REGLA DE NEGOCIO (Pre-condición): Solo películas en Estreno o Cartelera
    $peliculas = Pelicula::whereIn('estatus', ['Estreno', 'Cartelera'])->get(); 
    $salas = Sala::with('sucursal')->get(); 
    
    return view('admin.funciones.create', compact('peliculas', 'salas'));
});

// Ruta para Guardar la Función en la base de datos
Route::post('/admin/funciones', function (Request $request) {
    $datos = $request->all();

    // Aseguramos el formato de la hora para MySQL
    if (isset($datos['hora'])) {
        $datos['hora'] = Carbon::parse($datos['hora'])->format('H:i:s');
    }

    // REGLA DE NEGOCIO: Validar Conflicto de horario y sala (Flujo Alterno)
    $conflicto = Funcion::where('sala_id', $datos['sala_id'])
                        ->where('fecha', $datos['fecha'])
                        ->where('hora', $datos['hora'])
                        ->first();

    if ($conflicto) {
        // Alerta roja
        return redirect('/admin/funciones/create')
               ->with('error', 'Error: La sala ya está ocupada en ese horario. Por favor, elige otra sala u otro horario.')
               ->withInput();
    }

    // Caso de exito
    Funcion::create($datos);
    return redirect('/admin/funciones')->with('success', '¡Función programada con éxito!');
});


// MÓDULO DE FUNCIONES: RUTAS DE EDICIÓN Y ELIMINACIÓN


// Formulario para Editar una Función existente
Route::get('/admin/funciones/{id}/edit', function ($id) {
    $funcion = Funcion::findOrFail($id);
    // REGLA DE NEGOCIO: Solo películas válidas
    $peliculas = Pelicula::whereIn('estatus', ['Estreno', 'Cartelera'])->get(); 
    $salas = Sala::with('sucursal')->get(); 
    
    return view('admin.funciones.edit', compact('funcion', 'peliculas', 'salas'));
});

// Actualizar la Función en la base de datos
Route::put('/admin/funciones/{id}', function (Request $request, $id) {
    $funcion = Funcion::findOrFail($id);
    $datos = $request->all();

    if (isset($datos['hora'])) {
        $datos['hora'] = Carbon::parse($datos['hora'])->format('H:i:s');
    }

<<<<<<< HEAD
    // Emplame de funciones
    $conflicto = Funcion::where('sala_id', $datos['sala_id'])
                        ->where('fecha', $datos['fecha'])
                        ->where('hora', $datos['hora'])
                        ->where('id', '!=', $id) 
                        ->first();

    if ($conflicto) {
        return redirect("/admin/funciones/{$id}/edit")
               ->with('error', 'Error: La sala ya está ocupada en ese horario. Por favor, elige otra sala u otro horario.')
               ->withInput();
    }

    $funcion->update($datos);
    return redirect('/admin/funciones')->with('success', '¡Función actualizada correctamente!');
});

// Eliminar una Función (Flujo Alterno 3.2.3)
Route::delete('/admin/funciones/{id}', function ($id) {
    $funcion = Funcion::findOrFail($id);
    $funcion->delete();
    return redirect('/admin/funciones')->with('success', 'La función ha sido cancelada y eliminada de la cartelera.');
});
=======
    App\Models\Funcion::create($datos);
    return redirect('/admin/funciones')->with('success', '¡Función programada con éxito!');
});

Route::resource('admin/peliculas', PeliculaController::class)->parameters([
    'peliculas' => 'pelicula'
]);
>>>>>>> bc142e67a12a4bb7ecf5bd93ddf279e1fb4697b1
