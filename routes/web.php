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

// Ruta para guardar una nueva pelicula
Route::post('/admin/peliculas', function (Request $request) {
    App\Models\Pelicula::create($request->all());
    return redirect('/admin/peliculas');
});

// Ruta para mostrar el formulario de Edición (Modificar)
Route::get('/admin/peliculas/{id}/edit', function ($id) {
    $pelicula = App\Models\Pelicula::findOrFail($id);
    return view('admin.peliculas.edit', compact('pelicula'));
});

// Ruta para guardar los cambios (Actualizar)
Route::put('/admin/peliculas/{id}', function (Illuminate\Http\Request $request, $id) {
    $pelicula = App\Models\Pelicula::findOrFail($id);
    $pelicula->update($request->all());
    return redirect('/admin/peliculas');
});

// Ruta para Eliminar peliculas
Route::delete('/admin/peliculas/{id}', function ($id) {
    $pelicula = App\Models\Pelicula::findOrFail($id);
    $pelicula->delete();
    return redirect('/admin/peliculas');
});