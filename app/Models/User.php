<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Usar la tabla 'users' por defecto de Laravel
    // protected $table = 'usuarios';
    // protected $primaryKey = 'id_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'current_team_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Métodos comentados - usando estructura estándar de Laravel
    // /**
    //  * Get the password attribute for authentication.
    //  */
    // public function getAuthPassword()
    // {
    //     return $this->password_hash;
    // }

    // /**
    //  * Get the name attribute (compatibility with Laravel default)
    //  */
    // public function getNameAttribute()
    // {
    //     return "{$this->nombres} {$this->apellidos}";
    // }

    // /**
    //  * Get the password attribute (compatibility with Laravel default)
    //  */
    // public function getPasswordAttribute()
    // {
    //     return $this->password_hash;
    // }

    // /**
    //  * Set the password attribute (compatibility with Laravel default)
    //  */
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password_hash'] = $value;
    // }

    // Relaciones
    public function fichasComoCoordinador()
    {
        return $this->hasMany(Ficha::class, 'id_coordinador', 'id_usuario');
    }

    public function fichasComoInstructorLider()
    {
        return $this->hasMany(Ficha::class, 'id_instructor_lider', 'id_usuario');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_aprendiz', 'id_usuario');
    }

    public function planeaciones()
    {
        return $this->hasMany(PlaneacionRa::class, 'id_instructor', 'id_usuario');
    }

    // Helpers
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function isCoordinador(): bool
    {
        return $this->rol === 'coordinador';
    }

    public function isInstructorLider(): bool
    {
        return $this->rol === 'instructor_lider';
    }

    public function isInstructor(): bool
    {
        return $this->rol === 'instructor';
    }

    public function isAprendiz(): bool
    {
        return $this->rol === 'aprendiz';
    }

    public function isActivo(): bool
    {
        return $this->estado === 'activo';
    }
}
