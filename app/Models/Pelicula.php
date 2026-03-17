<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'sinopsis',
        'genero',
        'clasificacion',
        'estatus',
        'duracion',
        'idioma',
        'formato',
        'imagen_url'
    ];
    public function funciones()
    {
        return $this->hasMany(Funcion::class);
    }
}