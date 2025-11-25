# Admin User Creation Guide

## Option 1: Using Laravel Tinker (Recommended)

```bash
php artisan tinker
```

Then execute:
```php
\App\Models\User::create([
    'email' => 'admin',
    'password_hash' => \Illuminate\Support\Facades\Hash::make('admin123'),
    'role' => 'admin',
    'name' => 'Administrador'
]);
```

## Option 2: Direct SQL

```sql
INSERT INTO users (email, password_hash, role, name, created_at, updated_at) 
VALUES (
    'admin', 
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'admin', 
    'Administrador',
    NOW(),
    NOW()
);
```

> **Note**: The hash above is for password `password`. For `admin123`, generate a new hash using:
> ```bash
> php artisan tinker --execute="dump(Hash::make('admin123'));"
> ```

## Login Credentials

- **Email**: `admin`
- **Password**: `admin123` (or `password` if using the SQL method above)

## Verification

After creating the admin user, test login at `/login`.
