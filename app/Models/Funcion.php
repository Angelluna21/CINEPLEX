<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Funcion extends Model
{
    use HasFactory;

<<<<<<< HEAD
=======
    // ESTO CORRIGE EL ERROR DE PLURALIZACIÓN (¡Perfectamente aplicado!)
>>>>>>> a706bc7 (feat: login completo, protección de rutas y refactor de sucursales)
    protected $table = 'funciones';

    protected $fillable = [
        'pelicula_id',
        'sala_id',
        'fecha',
        'hora',
        'precio'
    ];

<<<<<<< HEAD
    // ESTO ES LO QUE FALTA:
    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'pelicula_id');
    }

=======
    // Relación con Sala (Ya la tenías, excelente)
>>>>>>> a706bc7 (feat: login completo, protección de rutas y refactor de sucursales)
    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    // RELACIÓN AÑADIDA: Para poder traer los datos de la película de esta función
    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'pelicula_id');
    }
}