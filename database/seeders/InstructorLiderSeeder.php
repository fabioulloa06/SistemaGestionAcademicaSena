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
        // Verificar si ya existe un instructor
        if (!User::where('role', 'instructor')->exists()) {
            User::create([
                'name' => 'Instructor Líder',
                'email' => 'instructor@sena.edu.co',
                'password' => Hash::make('password123'),
                'role' => 'instructor',
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Instructor creado exitosamente!');
            $this->command->info('Email: instructor@sena.edu.co');
            $this->command->info('Password: password123');
        } else {
            $this->command->info('Ya existe un instructor en el sistema.');
        }
    }
}
