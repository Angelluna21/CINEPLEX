<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peliculas', function (Blueprint $table) {
            // Agregamos la columna para la URL, permitiendo que sea nula por si alguna no tiene póster
            $table->string('imagen_url')->nullable()->after('formato');
        });
    }

    public function down(): void
    {
        Schema::table('peliculas', function (Blueprint $table) {
            $table->dropColumn('imagen_url');
        });
    }
};