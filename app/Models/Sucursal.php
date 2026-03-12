<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    // LTabla sucursal
    protected $table = 'sucursales';

    // Campos asignables
    protected $fillable = ['nombre'];

    // Tabala salas
    public function salas()
    {
        return $this->hasMany(Sala::class);
    }
}