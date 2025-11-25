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

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'numero_documento',
        'tipo_documento',
        'nombres',
        'apellidos',
        'email',
        'password_hash',
        'telefono',
        'rol',
        'estado',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
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
            'password_hash' => 'hashed',
        ];
    }

    /**
     * Get the password attribute for authentication.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the name attribute (compatibility with Laravel default)
     */
    public function getNameAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    /**
     * Get the password attribute (compatibility with Laravel default)
     */
    public function getPasswordAttribute()
    {
        return $this->password_hash;
    }

    /**
     * Set the password attribute (compatibility with Laravel default)
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = $value;
    }

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
