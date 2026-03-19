<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    use HasFactory;

    // ESTA ES LA LÍNEA MÁGICA QUE FALTA:
    // Aquí le decimos a Laravel qué columnas sí puede llenar automáticamente
    protected $fillable = [
        'sala_id',
        'fila',
        'numero',
        'tipo'
    ];

    // Relación con la sala (opcional, pero útil)
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }
}