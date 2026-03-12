<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 'sinopsis', 'genero', 'clasificacion', 
        'estatus', 'imagen_url', 'duracion'
    ];

    // Pelicula a funciones
    public function funciones()
    {
        return $this->hasMany(Funcion::class);
    }
}