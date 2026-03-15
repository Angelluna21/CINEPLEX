<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;

    // Le indicamos a Laravel el nombre exacto de la tabla
    protected $table = 'funciones';

    // ¡AQUÍ ESTÁ LA SOLUCIÓN! 
    // Los campos permitidos para guardarse en la base de datos, incluyendo 'precio'
    protected $fillable = [
        'pelicula_id', 
        'sala_id', 
        'fecha', 
        'hora', 
        'precio' 
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Una función pertenece a una película específica.
     */
    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class);
    }

    /**
     * Una función se proyecta en una sala específica.
     */
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }
}