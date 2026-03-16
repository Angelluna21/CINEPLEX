<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- IMPORTANTE: Esta línea evita errores

// MODELOS
use App\Models\Pelicula;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Genre; 

// CONTROLADORES
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\FuncionController;
use App\Http\Controllers\UserController;

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

// Ruta principal del Admin con conteos para las tarjetas
Route::get('/admin', function () {
    $countUsuarios = User::count();
    $countPeliculas = Pelicula::count();
    $countSucursales = Sucursal::count();
    
    return view('admin.index', compact('countUsuarios', 'countPeliculas', 'countSucursales'));
})->name('admin.dashboard');

// Agrupamos bajo 'admin/'
Route::prefix('admin')->group(function () {
    
    Route::resource('peliculas', PeliculaController::class)->names('peliculas');
    Route::resource('generos', GenreController::class)->parameters([
        'generos' => 'genre'
    ]);
    Route::resource('sucursales', SucursalController::class)->names('sucursales');
    Route::resource('salas', SalaController::class)->names('salas');
    Route::resource('usuarios', UserController::class)->names('usuarios');
    Route::resource('funciones', FuncionController::class)->names('funciones');
    
});

// ==========================================
// RUTA DE CIERRE DE SESIÓN (FUERA DEL PREFIJO ADMIN)
// ==========================================
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/'); 
})->name('logout');