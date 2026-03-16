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
    Schema::create('asientos', function (Blueprint $table) {
        $table->id();
        // Conecta el asiento con una sala de tu cine
        $table->foreignId('sala_id')->constrained()->onDelete('cascade');
        
        $table->string('fila', 2); // Ejemplo: 'A', 'B', 'C'
        $table->integer('numero');  // Ejemplo: 1, 2, 3
        
        // Para diferenciar si es un asiento normal o uno VIP (más caro)
        $table->enum('tipo', ['Tradicional', 'VIP', 'Discapacidad'])->default('Tradicional');
        
        // Un asiento no se puede repetir en la misma sala (ej: No puede haber dos A-1 en la Sala 5)
        $table->unique(['sala_id', 'fila', 'numero']);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asientos');
    }
};
