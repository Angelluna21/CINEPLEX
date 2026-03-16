<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';
    

    // Debes agregar 'ubicacion' aquí:
    protected $fillable = ['nombre', 'ubicacion'];

    public function salas()
    {
        return $this->hasMany(Sala::class);
    }
}