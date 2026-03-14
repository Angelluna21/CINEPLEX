<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peliculas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('sinopsis');
            $table->string('genero');
            $table->enum('clasificacion', ['A', 'B', 'C']);
            $table->enum('estatus', ['Estreno', 'Cartelera', 'No disponible']);
            $table->string('imagen_url')->nullable();
            $table->integer('duracion'); // Programar en minutos, ejemplo: 120 para 2 horas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peliculas');
    }
};