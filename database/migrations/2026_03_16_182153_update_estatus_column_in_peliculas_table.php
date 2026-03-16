<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peliculas', function (Blueprint $table) {
            // Cambiamos la regla estricta de 'enum' por un 'string' libre
            $table->string('estatus')->change();
        });
    }

    public function down(): void
    {
        Schema::table('peliculas', function (Blueprint $table) {
            // No es necesario poner nada aquí para este caso
        });
    }
};