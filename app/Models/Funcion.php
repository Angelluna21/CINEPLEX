<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Funcion extends Model
{
    use HasFactory;

    protected $table = 'funciones';

    protected $fillable = [
        'pelicula_id',
        'sala_id',
        'fecha',
        'hora',
        'precio'
    ];

    // ESTO ES LO QUE FALTA:
    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'pelicula_id');
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }
}