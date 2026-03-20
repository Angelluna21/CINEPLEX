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
use App\Http\Controllers\AuthController; 

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
    $sucursales = App\Models\Sucursal::all();
    $sucursal_id = $request->query('sucursal');
    $fecha_filtro = $request->query('fecha');

    $todayDate = \Carbon\Carbon::now()->format('Y-m-d');
    $currentTime = \Carbon\Carbon::now()->format('H:i:s');

    // 1. Fechas válidas para los circulitos verdes del calendario
    $queryFechas = App\Models\Funcion::where(function ($q) use ($todayDate, $currentTime) {
        $q->where('fecha', '>', $todayDate)
          ->orWhere(function ($q2) use ($todayDate, $currentTime) {
              $q2->where('fecha', '=', $todayDate)
                 ->whereTime('hora', '>', $currentTime);
          });
    });
    
    if ($sucursal_id) {
        $queryFechas->whereHas('sala', function($q) use ($sucursal_id) {
            $q->where('sucursal_id', $sucursal_id);
        });
    }
    $fechas_con_funciones = $queryFechas->pluck('fecha')->unique()->values()->toArray();

    // 2. Filtro estricto que borra mágicamente las horas que ya pasaron
    $filtroFunciones = function ($query) use ($sucursal_id, $fecha_filtro, $todayDate, $currentTime) {
        if ($sucursal_id) {
            $query->whereHas('sala', function ($q) use ($sucursal_id) {
                $q->where('sucursal_id', $sucursal_id);
            });
        }

        if ($fecha_filtro) {
            $query->where('fecha', $fecha_filtro);
            if ($fecha_filtro == $todayDate) {
                $query->whereTime('hora', '>', $currentTime);
            }
        } else {
            $query->where(function ($q) use ($todayDate, $currentTime) {
                $q->where('fecha', '>', $todayDate)
                  ->orWhere(function ($q2) use ($todayDate, $currentTime) {
                      $q2->where('fecha', '=', $todayDate)
                         ->whereTime('hora', '>', $currentTime);
                  });
            });
        }
        $query->orderBy('fecha')->orderBy('hora');
    };

    $queryPeliculas = App\Models\Pelicula::with(['funciones' => $filtroFunciones]);

    if ($fecha_filtro || $sucursal_id) {
        $queryPeliculas->whereHas('funciones', $filtroFunciones);
    }

    $peliculas = $queryPeliculas->get();

    return view('welcome', compact('peliculas', 'sucursales', 'sucursal_id', 'fecha_filtro', 'fechas_con_funciones'));
});

Route::get('/pelicula/{id}', function ($id) {
    $pelicula = App\Models\Pelicula::with('funciones.sala.sucursal')->findOrFail($id);
    return view('detalle', compact('pelicula'));
})->name('pelicula.detalle');

Route::get('/comprar/{funcion_id}', function ($funcion_id) {
    $limiteTiempo = \Carbon\Carbon::now()->subMinutes(5);
    \Illuminate\Support\Facades\DB::table('funcion_asiento')
        ->where('status', 'reservado') 
        ->where('created_at', '<', $limiteTiempo)
        ->delete();

    $funcion = App\Models\Funcion::with(['pelicula', 'sala'])->findOrFail($funcion_id);
    return view('comprar', compact('funcion'));
})->name('comprar.asientos');

// ==========================================
// PANEL ADMINISTRATIVO (PROTEGIDO CON CANDADO)
// ==========================================

Route::middleware(['auth'])->group(function () {
    
    // Ruta principal del Admin (SOLO CONTEOS)
    Route::get('/admin', function () {
        $countUsuarios = App\Models\User::count();
        $countPeliculas = App\Models\Pelicula::count();
        $countSucursales = App\Models\Sucursal::count();
        
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