<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Esto le da la orden directa a PostgreSQL de romper el candado viejo
        DB::statement('ALTER TABLE peliculas DROP CONSTRAINT IF EXISTS peliculas_estatus_check');
    }

    public function down(): void
    {
        // No necesitamos hacer nada aquí
    }
};