<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicFaultSeeder extends Seeder
{
    public function run(): void
    {
        $faults = [
            [
                'codigo' => 'Razón 1',
                'description' => 'Inasistencia injustificada a las actividades de formación (clases teóricas, prácticas, talleres, laboratorios, etc.)'
            ],
            [
                'codigo' => 'Razón 2',
                'description' => 'Inasistencia reiterada (3 o más faltas) a una misma competencia sin justificación válida'
            ],
            [
                'codigo' => 'Razón 3',
                'description' => 'Bajo rendimiento académico: No alcanzar los resultados de aprendizaje establecidos en el plan de formación'
            ],
            [
                'codigo' => 'Razón 4',
                'description' => 'Incumplimiento en la entrega de evidencias de aprendizaje (trabajos, proyectos, actividades evaluativas) en los plazos establecidos'
            ],
            [
                'codigo' => 'Razón 5',
                'description' => 'No presentación de evaluaciones o actividades de aprendizaje sin justificación válida'
            ],
            [
                'codigo' => 'Razón 6',
                'description' => 'Incumplimiento de compromisos académicos adquiridos en llamados de atención previos o planes de mejoramiento'
            ],
            [
                'codigo' => 'Razón 7',
                'description' => 'Falta de participación activa en las actividades formativas y trabajo en equipo'
            ],
            [
                'codigo' => 'Razón 8',
                'description' => 'No seguimiento de las orientaciones pedagógicas y recomendaciones del instructor'
            ],
            [
                'codigo' => 'Razón 9',
                'description' => 'Desatención o negligencia en el desarrollo de las actividades de aprendizaje'
            ],
            [
                'codigo' => 'Razón 10',
                'description' => 'Incumplimiento de horarios de formación (llegadas tarde reiteradas, salidas anticipadas sin autorización)'
            ],
        ];

        foreach ($faults as $fault) {
            DB::table('academic_faults')->insert([
                'codigo' => $fault['codigo'],
                'description' => $fault['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
