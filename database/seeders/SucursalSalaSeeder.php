<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sucursal;
use App\Models\Sala;

class SucursalSalaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos 3 Sucursales de prueba
        $sucursales = [
            ['nombre' => 'Cineplex Reforma'],
            ['nombre' => 'Cineplex Perisur'],
            ['nombre' => 'Cineplex Centro'],
        ];

        foreach ($sucursales as $s) {
            $sucursal = Sucursal::create($s);

            // 2. Creamos las 4 salas obligatorias para esta sucursal (Cumpliendo el Caso de Uso)
            Sala::create([
                'sucursal_id' => $sucursal->id,
                'numero' => 1,
                'nombre' => 'Tradicional',
                'capacidad' => 100,
            ]);

            Sala::create([
                'sucursal_id' => $sucursal->id,
                'numero' => 2,
                'nombre' => '3D',
                'capacidad' => 120,
            ]);

            Sala::create([
                'sucursal_id' => $sucursal->id,
                'numero' => 3,
                'nombre' => '4D',
                'capacidad' => 60,
            ]);

            Sala::create([
                'sucursal_id' => $sucursal->id,
                'numero' => 4,
                'nombre' => 'IMAX',
                'capacidad' => 200,
            ]);
        }
    }
}