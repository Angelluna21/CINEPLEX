<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Pelicula;
use App\Models\Sucursal;
use App\Models\Funcion;
use App\Models\Sala;
use Carbon\Carbon;
use App\Http\Controllers\PeliculaController;

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

Route::resource('admin/peliculas', PeliculaController::class)->parameters([
    'peliculas' => 'pelicula'
]);

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

use App\Http\Controllers\GenreController;

// Catálogo de Géneros
Route::resource('generos', GenreController::class);