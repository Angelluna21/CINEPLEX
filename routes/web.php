<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Pelicula;
use App\Models\Sucursal;

// CONTROLADORES IMPORTADOS
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\FuncionController; // NUEVO: Importamos el controlador de Funciones

// ==========================================
// VISTAS DEL CLIENTE (CARTELERA PÚBLICA)
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

// MÓDULO DE PELÍCULAS
Route::resource('admin/peliculas', PeliculaController::class)->parameters([
    'peliculas' => 'pelicula'
]);

// MÓDULO DE GÉNEROS
Route::resource('generos', GenreController::class);

// MÓDULO DE SUCURSALES
Route::resource('admin/sucursales', SucursalController::class);

// MÓDULO DE SALAS
Route::resource('admin/salas', SalaController::class)->parameters([
    'salas' => 'sala'
]);

// ==========================================
// MÓDULO DE FUNCIONES (CARTELERA)
// ==========================================
// Toda la lógica pesada ahora vive en FuncionController
Route::resource('admin/funciones', FuncionController::class)->parameters([
    'funciones' => 'funcion'
]);