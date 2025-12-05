<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InstructorLiderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existe un instructor líder
        // La columna en la BD es 'role' y el valor debe ser 'instructor'
        if (!User::where('role', 'instructor')->where('email', 'instructor@sena.edu.co')->exists()) {
            User::create([
                'name' => 'Instructor Líder',
                'email' => 'instructor@sena.edu.co',
                'password' => Hash::make('password123'),
                'role' => 'instructor',
            ]);
            
            $this->command->info('Instructor líder creado exitosamente!');
            $this->command->info('Email: instructor@sena.edu.co');
            $this->command->info('Password: password123');
        } else {
            $this->command->info('Ya existe un instructor líder en el sistema.');
        }
    }
}
