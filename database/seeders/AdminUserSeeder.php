<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario admin si no existe
        $email = 'admin@sena.edu.co';
        $admin = User::where('email', $email)->first();
        
        $userData = [
            'name' => 'Administrador Sistema',
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ];

        if (!$admin) {
            User::create($userData);
            $this->command->info('Usuario Admin creado: ' . $email . ' / password123');
        } else {
            $admin->update($userData);
            $this->command->info('Usuario Admin actualizado: ' . $email . ' / password123');
        }
    }
}
