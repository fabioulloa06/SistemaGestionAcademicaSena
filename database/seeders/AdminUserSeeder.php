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
                'password_hash' => Hash::make('admin123'), // Contraseña por defecto
                'role' => 'admin',
                'rol' => 'admin', // Compatibilidad
                'nombres' => 'Administrador',
                'apellidos' => 'Sistema',
                'numero_documento' => '000000000',
                'tipo_documento' => 'CC',
                'estado' => 'activo',
            ]);
            $this->command->info('Usuario Admin creado: User: admin, Pass: admin123');
        } else {
            // Asegurar que tenga el rol correcto
            $admin->update([
                'role' => 'admin',
                'rol' => 'admin',
                'password_hash' => Hash::make('admin123'), // Resetear contraseña para asegurar acceso
            ]);
            $this->command->info('Usuario Admin actualizado.');
        }
    }
}
