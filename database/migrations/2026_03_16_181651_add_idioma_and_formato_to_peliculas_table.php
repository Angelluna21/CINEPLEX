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
        Schema::table('peliculas', function (Blueprint $table) {
            // Agregamos las dos columnas nuevas. 
            // Las ponemos 'nullable' para que las películas viejas no marquen error por no tenerlas.
            $table->string('idioma')->nullable()->after('duracion');
            $table->string('formato')->nullable()->after('idioma');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peliculas', function (Blueprint $table) {
            $table->dropColumn(['idioma', 'formato']);
        });
    }
};