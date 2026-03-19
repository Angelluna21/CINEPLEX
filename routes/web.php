<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
use App\Http\Controllers\AuthController; // <-- AÑADIDO: Controlador de Autenticación

// ==========================================
// RUTAS DE AUTENTICACIÓN (LOGIN / LOGOUT)
// ==========================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
Route::get('/pelicula/{id}', function ($id) {
    // Agregamos 'sucursal' a la relación para que viaje con la sala
    $pelicula = App\Models\Pelicula::with('funciones.sala.sucursal')->findOrFail($id);
    
    return view('detalle', compact('pelicula'));
})->name('pelicula.detalle');

Route::get('/comprar/{funcion_id}', function ($funcion_id) {
    // Buscamos la función con los datos de su película y su sala
    $funcion = App\Models\Funcion::with(['pelicula', 'sala'])->findOrFail($funcion_id);
    
    return view('comprar', compact('funcion'));
})->name('comprar.asientos');
// ==========================================
// PANEL ADMINISTRATIVO (PROTEGIDO CON CANDADO)
// ==========================================

Route::middleware(['auth'])->group(function () {
    
    // Ruta principal del Admin con conteos para las tarjetas
    Route::get('/admin', function () {
        $countUsuarios = User::count();
        $countPeliculas = Pelicula::count();
        $countSucursales = Sucursal::count();
        
        return view('admin.index', compact('countUsuarios', 'countPeliculas', 'countSucursales'));
    })->name('admin.dashboard');

    // Agrupamos bajo 'admin/'
    Route::prefix('admin')->group(function () {
        
        Route::get('tmdb/search', [PeliculaController::class, 'searchTmdb'])->name('tmdb.search');
        Route::resource('peliculas', PeliculaController::class)->names('peliculas');
        Route::resource('generos', GenreController::class)->parameters([
            'generos' => 'genre'
        ]);
        Route::resource('funciones', FuncionController::class)->names('funciones');
        
        // Rutas exclusivas del administrador
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('sucursales', SucursalController::class)->names('sucursales');
            Route::resource('salas', SalaController::class)->names('salas');
            Route::resource('usuarios', UserController::class)->names('usuarios');
        });
    });
    
});