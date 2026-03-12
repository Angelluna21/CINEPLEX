<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeliculaSeeder extends Seeder
{
    public function run(): void
    {
        $peliculas = [
            [
                'titulo' => 'IT (Eso)',
                'sinopsis' => 'Un grupo de niños marginados se une para destruir a un monstruo que cambia de forma, el cual se disfraza de payaso para cazar a los habitantes de Derry.',
                'genero' => 'Terror',
                'clasificacion' => 'C', // Mayores de 21
                'estatus' => 'Estreno',
                'imagen_url' => 'https://image.tmdb.org/t/p/w600_and_h900_bestv2/9E2y5Q7WlCVNEhP5GiVTjhEhx1o.jpg',
                'duracion' => 135,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Chucky: El Muñeco Diabólico',
                'sinopsis' => 'Un asesino en serie moribundo usa magia oscura para transferir su alma a un muñeco Good Guy, el cual termina en manos de un niño inocente.',
                'genero' => 'Terror',
                'clasificacion' => 'C',
                'estatus' => 'Cartelera',
                'imagen_url' => 'https://image.tmdb.org/t/p/w600_and_h900_bestv2/5E2JkKzK533Z7M38XwIurT0MebI.jpg',
                'duracion' => 87,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Halloween',
                'sinopsis' => 'Quince años después de asesinar a su hermana, Michael Myers escapa de un hospital psiquiátrico y regresa a su pueblo natal para continuar su masacre.',
                'genero' => 'Suspenso',
                'clasificacion' => 'C',
                'estatus' => 'Cartelera',
                'imagen_url' => 'https://image.tmdb.org/t/p/w600_and_h900_bestv2/1H1ydqj292OQ1uX4o7x0iE1Dk1X.jpg',
                'duracion' => 91,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('peliculas')->insert($peliculas);
    }
}