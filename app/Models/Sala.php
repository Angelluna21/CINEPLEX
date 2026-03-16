<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use HasFactory;

    protected $table = 'salas';

    // Ahora permitimos guardar el nombre, la capacidad y la sucursal
    protected $fillable = ['numero', 'nombre', 'capacidad', 'sucursal_id', 'estatus'];
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function funciones()
{
    // Esto le dice a Laravel que use el modelo Funcion (que ya tiene la tabla 'funciones' definida)
    return $this->hasMany(Funcion::class, 'sala_id');
}
}