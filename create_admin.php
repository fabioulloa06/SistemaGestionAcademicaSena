<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    // Check if admin exists
    $admin = DB::table('users')->where('email', 'admin')->first();
    
    if (!$admin) {
        DB::table('users')->insert([
            'email' => 'admin',
            'password_hash' => Hash::make('admin123'),
            'role' => 'admin',
            'name' => 'Administrador',
        ]);
        echo "✓ Usuario Admin creado exitosamente\n";
        echo "  Email: admin\n";
        echo "  Password: admin123\n";
    } else {
        DB::table('users')->where('email', 'admin')->update([
            'role' => 'admin',
            'password_hash' => Hash::make('admin123'),
        ]);
        echo "✓ Usuario Admin actualizado\n";
        echo "  Email: admin\n";
        echo "  Password: admin123\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
