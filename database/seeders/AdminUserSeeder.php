<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear o actualizar usuario administrador
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'numero_documento' => '123456789',
                'tipo_documento' => 'CC',
                'nombres' => 'Administrador',
                'apellidos' => 'Sistema',
                'email' => 'admin@admin.com',
                'password_hash' => Hash::make('fabio123'),
                'rol' => 'coordinador',
                'estado' => 'activo',
            ]
        );
        
        $this->command->info('Usuario administrador creado exitosamente!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Password: fabio123');
    }
}
