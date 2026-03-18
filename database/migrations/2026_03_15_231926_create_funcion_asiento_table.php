<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('funcion_asiento', function (Blueprint $table) {
            $table->id();
            
            // Relación con la función (Película, Hora, Sala)
            $table->foreignId('funcion_id')->constrained('funciones')->onDelete('cascade');
            
            // Relación con el asiento físico
            $table->foreignId('asiento_id')->constrained()->onDelete('cascade');
            
            // Estado del asiento según tu diagrama:
            // disponible (verde), ocupado (rojo), reservado (amarillo/bloqueado)
            $table->enum('status', ['disponible', 'ocupado', 'reservado'])->default('disponible');
            
            // Usuario que tiene seleccionado el asiento (el color azul de tu dibujo)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcion_asiento');
    }
};