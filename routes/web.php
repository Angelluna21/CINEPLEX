<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pelicula;

Route::get('/', function () {
    // Datos de ejemplo
    $peliculas = Pelicula::all();
    
    // Retorno de las peliculas 
    return view('welcome', compact('peliculas'));
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/admin/peliculas', function () {
    $peliculas = App\Models\Pelicula::all();
    return view('admin.peliculas.index', compact('peliculas'));
});

use Illuminate\Http\Request;

// Ruta de forimulario para crear una nueva peliculas
Route::get('/admin/peliculas/create', function () {
    return view('admin.peliculas.create');
});

// Ruta de guardado de nueva peliculas
Route::post('/admin/peliculas', function (Request $request) {
    App\Models\Pelicula::create($request->all());
    return redirect('/admin/peliculas');
});