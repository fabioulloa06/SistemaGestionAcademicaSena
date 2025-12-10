<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Coordinador
        User::updateOrCreate(
            ['email' => 'coordinador@sena.edu.co'],
            [
                'name' => 'María González',
                'email' => 'coordinador@sena.edu.co',
                'password' => Hash::make('password123'),
                'role' => 'coordinator',
                'email_verified_at' => now(),
            ]
        );

        // Instructor
        User::updateOrCreate(
            ['email' => 'instructor@sena.edu.co'],
            [
                'name' => 'Carlos Mendoza',
                'email' => 'instructor@sena.edu.co',
                'password' => Hash::make('password123'),
                'role' => 'instructor',
                'email_verified_at' => now(),
            ]
        );

        // Estudiante
        $studentUser = User::updateOrCreate(
            ['email' => 'estudiante@sena.edu.co'],
            [
                'name' => 'Juan Pérez',
                'email' => 'estudiante@sena.edu.co',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]
        );

        // Crear perfil de estudiante asociado
        \App\Models\Student::updateOrCreate(
            ['user_id' => $studentUser->id],
            [
                'user_id' => $studentUser->id,
                'nombre' => 'Juan Pérez',
                'documento' => '1000000001',
                'email' => 'estudiante@sena.edu.co',
                'telefono' => '3001234567',
                'activo' => true,
            ]
        );

        // Admin (por si acaso)
        User::updateOrCreate(
            ['email' => 'admin@sena.edu.co'],
            [
                'name' => 'Admin Sistema',
                'email' => 'admin@sena.edu.co',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Usuarios de prueba creados:');
        $this->command->info('');
        $this->command->info('👤 COORDINADOR:');
        $this->command->info('   Email: coordinador@sena.edu.co');
        $this->command->info('   Password: password123');
        $this->command->info('');
        $this->command->info('👨‍🏫 INSTRUCTOR:');
        $this->command->info('   Email: instructor@sena.edu.co');
        $this->command->info('   Password: password123');
        $this->command->info('');
        $this->command->info('👨‍🎓 ESTUDIANTE:');
        $this->command->info('   Email: estudiante@sena.edu.co');
        $this->command->info('   Password: password123');
        $this->command->info('');
        $this->command->info('👑 ADMIN:');
        $this->command->info('   Email: admin@sena.edu.co');
        $this->command->info('   Password: password123');
    }
}

