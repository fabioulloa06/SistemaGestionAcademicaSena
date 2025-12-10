<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'admin@sena.edu.co';
$password = 'admin123';

echo "Attempting to reset password for: $email\n";

try {
    $user = User::where('email', $email)->first();

    if ($user) {
        $user->password = Hash::make($password);
        // Check if 'role' column exists, otherwise use 'rol'
        if (Schema::hasColumn('users', 'role')) {
            $user->role = 'admin'; 
        } else {
            $user->rol = 'admin';
        }
        $user->save();
        echo "SUCCESS: Password reset to '$password' for user '$email'.\n";
    } else {
        echo "User with email '$email' not found.\n";
        echo "Creating user...\n";
        
        $newUser = new User();
        $newUser->name = 'Admin Sistema';
        $newUser->email = $email;
        $newUser->password = Hash::make($password);
        
        // Handle role column mismatch
        if (Schema::hasColumn('users', 'role')) {
             $newUser->role = 'admin';
        } else {
             $newUser->rol = 'admin';
        }
        
        $newUser->tipo_documento = 'CC';
        $newUser->numero_documento = '1234567890';
        $newUser->nombres = 'Admin';
        $newUser->apellidos = 'Sistema';
        $newUser->telefono = '3000000000';
        $newUser->estado = 'activo';

        $newUser->save();
        
        echo "SUCCESS: Created new user '$email' with password '$password'.\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
