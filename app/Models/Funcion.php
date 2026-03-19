<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function sala()
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'pelicula_id');
    }
}

public function estaAgotada()
{
    // Contamos cuántos asientos tienen estatus 'ocupado' para esta función
    $vendidos = \Illuminate\Support\Facades\DB::table('funcion_asiento')
        ->where('funcion_id', $this->id)
        ->where('status', 'ocupado')
        ->count();

    // Si los vendidos son iguales o mayores a la capacidad de la sala, está agotada
    return $vendidos >= $this->sala->capacidad;
}