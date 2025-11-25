<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

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

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    // Relaciones
    public function fichasComoCoordinador(): HasMany
    {
        return $this->hasMany(Ficha::class, 'id_coordinador', 'id_usuario');
    }

    public function fichasComoInstructorLider(): HasMany
    {
        return $this->hasMany(Ficha::class, 'id_instructor_lider', 'id_usuario');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'id_aprendiz', 'id_usuario');
    }

    public function planeaciones(): HasMany
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

