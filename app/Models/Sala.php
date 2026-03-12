<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use HasFactory;

    protected $fillable = ['numero_sala', 'sucursal_id'];

    // Sla a sucrsal
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    // Funciones que se proyectan en esta sala
    public function funciones()
    {
        return $this->hasMany(Funcion::class);
    }
}