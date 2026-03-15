<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    // Aquí le decimos a Laravel que el campo 'nombre' es seguro para guardar
    protected $fillable = ['nombre'];
}