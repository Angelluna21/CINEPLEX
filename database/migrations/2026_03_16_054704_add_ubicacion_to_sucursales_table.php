<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sucursales', function (Blueprint $label) {
            // Agregamos la columna que falta
            $label->string('ubicacion')->nullable()->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('sucursales', function (Blueprint $label) {
            $label->dropColumn('ubicacion');
        });
    }
};