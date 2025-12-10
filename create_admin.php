
$u = App\Models\User::where('email', 'admin@sena.edu.co')->first();
if (!$u) {
    $u = new App\Models\User();
    $u->name = 'Admin';
    $u->email = 'admin@sena.edu.co';
    $u->password = Illuminate\Support\Facades\Hash::make('password123');
    $u->rol = 'admin';
    $u->estado = 'activo';
    $u->numero_documento = '1234567890';
    $u->tipo_documento = 'CC';
    $u->nombres = 'Admin';
    $u->apellidos = 'Sena';
    $u->telefono = '1234567';
    $u->save();
    echo "Admin user created successfully.\n";
} else {
    $u->password = Illuminate\Support\Facades\Hash::make('password123');
    $u->save();
    echo "Admin user already exists. Password reset.\n";
}
