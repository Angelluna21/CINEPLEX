<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;

    // ESTO CORRIGE EL ERROR DE PLURALIZACIÓN
    protected $table = 'funciones';

    protected $fillable = [
        'pelicula_id', 
        'sala_id', 
        'fecha', 
        'hora', 
        'precio'
    ];

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }
}