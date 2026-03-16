<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Pelicula;
use App\Models\Sucursal;

// CONTROLADORES
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\FuncionController;

// ==========================================
// VISTAS DEL CLIENTE (CARTELERA PÚBLICA)
// ==========================================

Route::get('/', function (Request $request) {
    $sucursales = Sucursal::all();
    $sucursal_id = $request->query('sucursal');

    if ($sucursal_id) {
        $peliculas = Pelicula::whereHas('funciones.sala', function ($q) use ($sucursal_id) {
            $q->where('sucursal_id', $sucursal_id);
        })->with(['funciones' => function ($query) use ($sucursal_id) {
            $query->whereHas('sala', function ($q) use ($sucursal_id) {
                $q->where('sucursal_id', $sucursal_id);
            })->orderBy('fecha')->orderBy('hora');
        }])->get();
    } else {
        $peliculas = Pelicula::with(['funciones' => function ($query) {
            $query->orderBy('fecha')->orderBy('hora');
        }])->get();
    }

    return view('welcome', compact('peliculas', 'sucursales', 'sucursal_id'));
});

// ==========================================
// PANEL ADMINISTRATIVO (EMPLEADOS)
// ==========================================

// Ruta principal del Admin
Route::get('/admin', function () {
    return view('admin.index');
})->name('admin.dashboard');

// Agrupamos todo bajo 'admin/' para que sea ordenado
Route::prefix('admin')->group(function () {
    
    // Módulo de Películas
    Route::resource('peliculas', PeliculaController::class)->names('peliculas');

    // Módulo de Géneros
    Route::resource('generos', GenreController::class)->names('generos');

    // Módulo de Sucursales
    Route::resource('sucursales', SucursalController::class)->names('sucursales');

    // Módulo de Salas
    Route::resource('salas', SalaController::class)->names('salas');

    // Módulo de Funciones
    Route::resource('funciones', FuncionController::class)->names('funciones');
});