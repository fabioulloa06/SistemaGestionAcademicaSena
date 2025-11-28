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
        $admin = User::where('email', 'admin')->first();
        
        if (!$admin) {
            User::create([
                'name' => 'Administrador Sistema',
                'email' => 'admin',
                'password' => Hash::make('admin123'), // Contraseña por defecto
                'role' => 'admin',
            ]);
            $this->command->info('Usuario Admin creado: User: admin, Pass: admin123');
        } else {
            // Asegurar que tenga el rol correcto
            $admin->update([
                'role' => 'admin',
                'password' => Hash::make('admin123'), // Resetear contraseña para asegurar acceso
            ]);
            $this->command->info('Usuario Admin actualizado.');
        }
    }
}
