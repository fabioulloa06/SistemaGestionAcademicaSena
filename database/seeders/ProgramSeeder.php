<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Group;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        // Crear programas
        $programs = [
            [
                'nombre' => 'Técnico en Programación de Software',
                'descripcion' => 'Programa técnico enfocado en desarrollo de software',
                'duracion_meses' => 24,
                'nivel' => 'Técnico'
            ],
            [
                'nombre' => 'Técnico en Sistemas',
                'descripcion' => 'Programa técnico en administración de sistemas',
                'duracion_meses' => 24,
                'nivel' => 'Técnico'
            ],
            [
                'nombre' => 'Técnico en Redes de Computadores',
                'descripcion' => 'Programa técnico en infraestructura de redes',
                'duracion_meses' => 24,
                'nivel' => 'Técnico'
            ]
        ];

        $createdPrograms = [];
        foreach ($programs as $program) {
            $createdPrograms[] = Program::create($program);
        }

        // Crear grupos
        foreach ($createdPrograms as $index => $program) {
            Group::create([
                'numero_ficha' => '22810' . ($index + 6) . '-001',
                'program_id' => $program->id,
                'jornada' => 'Diurna',
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths($program->duracion_meses)
            ]);

            Group::create([
                'numero_ficha' => '22810' . ($index + 6) . '-002',
                'program_id' => $program->id,
                'jornada' => 'Nocturna',
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addMonths($program->duracion_meses)
            ]);
        }

        // Crear instructores
        $instructors = [
            [
                'nombre' => 'Carlos Mendoza',
                'documento' => '12345678',
                'email' => 'carlos.mendoza@instituto.edu',
                'telefono' => '3001234567',
                'especialidad' => 'Programación'
            ],
            [
                'nombre' => 'Ana García',
                'documento' => '87654321',
                'email' => 'ana.garcia@instituto.edu',
                'telefono' => '3007654321',
                'especialidad' => 'Sistemas'
            ],
            [
                'nombre' => 'Luis Rodríguez',
                'documento' => '11223344',
                'email' => 'luis.rodriguez@instituto.edu',
                'telefono' => '3009876543',
                'especialidad' => 'Redes'
            ]
        ];

        foreach ($instructors as $instructor) {
            Instructor::create($instructor);
        }

        // Crear estudiantes
        $groups = Group::all();
        $nombres = ['Juan Pérez', 'María González', 'Pedro López', 'Ana Martínez', 'Carlos Sánchez', 'Laura Díaz', 'Miguel Torres', 'Sofia Ruiz'];
        
        foreach ($groups as $index => $group) {
            for ($i = 0; $i < 5; $i++) {
                $nombreIndex = ($index * 5 + $i) % count($nombres);
                Student::create([
                    'nombre' => $nombres[$nombreIndex],
                    'documento' => 'STU' . str_pad($index * 5 + $i + 1, 4, '0', STR_PAD_LEFT),
                    'email' => 'estudiante' . ($index * 5 + $i + 1) . '@instituto.edu',
                    'telefono' => '300' . str_pad($index * 5 + $i + 1, 7, '0', STR_PAD_LEFT),
                    'group_id' => $group->id
                ]);
            }
        }
    }
}