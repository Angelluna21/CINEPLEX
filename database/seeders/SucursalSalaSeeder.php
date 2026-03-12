<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SucursalSalaSeeder extends Seeder
{
    public function run(): void
    {
        // Sucursales
        $sucursales = ['CDMX Norte', 'CDMX Oriente', 'Reforma', 'Perisur'];

        foreach ($sucursales as $nombre) {
            // ID sucursales
            $sucursalId = DB::table('sucursales')->insertGetId([
                'nombre' => $nombre,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Asiganacion de sucrsales a salas
            for ($i = 1; $i <= 3; $i++) {
                DB::table('salas')->insert([
                    'numero_sala' => $i,
                    'sucursal_id' => $sucursalId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}