<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class SimpleProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'codigo' => '228106',
                'nombre' => 'Técnico en Programación de Software',
                'descripcion' => 'Programa técnico enfocado en desarrollo de software',
                'duracion_meses' => 24,
                'nivel' => 'Técnico',
                'activo' => true
            ],
            [
                'codigo' => '228107',
                'nombre' => 'Técnico en Sistemas',
                'descripcion' => 'Programa técnico en administración de sistemas',
                'duracion_meses' => 24,
                'nivel' => 'Técnico',
                'activo' => true
            ],
            [
                'codigo' => '228108',
                'nombre' => 'Técnico en Redes de Computadores',
                'descripcion' => 'Programa técnico en infraestructura de redes',
                'duracion_meses' => 24,
                'nivel' => 'Técnico',
                'activo' => true
            ]
        ];

        foreach ($programs as $program) {
            Program::firstOrCreate(['codigo' => $program['codigo']], $program);
        }
    }
}
