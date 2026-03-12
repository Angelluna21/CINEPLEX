<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;

    protected $table = 'funciones';

    protected $fillable = [
        'pelicula_id', 'sala_id', 'fecha', 'hora', 'precio'
    ];

    // Pelicula en especifico
    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class);
    }

    //Funcion  de sala en específico
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }
}