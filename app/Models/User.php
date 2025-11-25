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

    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'numero_documento',
        'tipo_documento',
        'nombres',
        'apellidos',
        'email',
        'password',
        'password_hash',
        'telefono',
        'rol',
        'role',
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
        if (isset($this->attributes['nombres']) && isset($this->attributes['apellidos'])) {
            return "{$this->nombres} {$this->apellidos}";
        }
        return $this->attributes['name'] ?? '';
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
        $this->attributes['password'] = $value;
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
        if (isset($this->attributes['nombres']) && isset($this->attributes['apellidos'])) {
            return "{$this->nombres} {$this->apellidos}";
        }
        return $this->attributes['name'] ?? '';
    }

    /**
     * Obtiene el rol del usuario normalizado (compatibilidad con ambos campos)
     */
    public function getNormalizedRole()
    {
        // Priorizar 'role' si existe, sino usar 'rol' mapeado
        if (isset($this->attributes['role']) && !empty($this->attributes['role'])) {
            return $this->attributes['role'];
        }
        
        // Mapear roles en español a inglés
        $rolMap = [
            'coordinador' => 'coordinator',
            'instructor' => 'instructor',
            'instructor_lider' => 'instructor',
            'aprendiz' => 'student',
            'admin' => 'admin',
        ];
        
        $rol = $this->attributes['rol'] ?? 'student';
        return $rolMap[$rol] ?? 'student';
    }

    /**
     * Métodos de verificación de roles
     */
    public function isAdmin(): bool
    {
        $normalized = $this->getNormalizedRole();
        return $normalized === 'admin';
    }

    public function isCoordinator(): bool
    {
        $normalized = $this->getNormalizedRole();
        return $normalized === 'coordinator';
    }

    public function isInstructor(): bool
    {
        $normalized = $this->getNormalizedRole();
        return $normalized === 'instructor';
    }

    public function isStudent(): bool
    {
        $normalized = $this->getNormalizedRole();
        return $normalized === 'student';
    }

    // Métodos de compatibilidad (mantener por si acaso)
    public function isCoordinador(): bool
    {
        return $this->isCoordinator();
    }

    public function isInstructorLider(): bool
    {
        return $this->isInstructor();
    }

    public function isAprendiz(): bool
    {
        return $this->isStudent();
    }

    public function isActivo(): bool
    {
        return $this->estado === 'activo';
    }

    /**
     * PERMISOS DEL SISTEMA
     */

    /**
     * ¿Puede ver todo?
     */
    public function canViewAll(): bool
    {
        return $this->isAdmin();
    }

    /**
     * ¿Puede gestionar usuarios?
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin() || $this->isCoordinator();
    }

    /**
     * ¿Puede gestionar programas, grupos, estudiantes, instructores?
     */
    public function canManageAcademicStructure(): bool
    {
        return $this->isAdmin() || $this->isCoordinator();
    }

    /**
     * ¿Puede calificar Resultados de Aprendizaje y Competencias?
     */
    public function canGrade(): bool
    {
        return $this->isAdmin() || $this->isInstructor();
    }

    /**
     * ¿Puede calificar un RA específico? (instructor solo los asignados)
     */
    public function canGradeLearningOutcome($learningOutcomeId): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isInstructor()) {
            // Verificar si tiene la competencia asignada
            $learningOutcome = \App\Models\LearningOutcome::find($learningOutcomeId);
            if (!$learningOutcome) {
                return false;
            }

            return \App\Models\CompetenciaGroupInstructor::where('instructor_id', $this->id)
                ->where('competencia_id', $learningOutcome->competencia_id)
                ->exists();
        }

        return false;
    }

    /**
     * ¿Puede calificar una competencia específica? (instructor solo las asignadas)
     */
    public function canGradeCompetencia($competenciaId): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isInstructor()) {
            return \App\Models\CompetenciaGroupInstructor::where('instructor_id', $this->id)
                ->where('competencia_id', $competenciaId)
                ->exists();
        }

        return false;
    }

    /**
     * ¿Puede gestionar asistencias?
     */
    public function canManageAttendance(): bool
    {
        return $this->isAdmin() || $this->isInstructor();
    }

    /**
     * ¿Puede gestionar asistencias de un grupo específico? (instructor solo los asignados)
     */
    public function canManageAttendanceForGroup($groupId): bool
    {
        if ($this->isAdmin() || $this->isCoordinator()) {
            return true;
        }

        if ($this->isInstructor()) {
            return \App\Models\CompetenciaGroupInstructor::where('instructor_id', $this->id)
                ->where('group_id', $groupId)
                ->exists();
        }

        return false;
    }

    /**
     * ¿Puede crear llamados de atención?
     */
    public function canCreateDisciplinaryActions(): bool
    {
        return $this->isAdmin() || $this->isInstructor();
    }

    /**
     * ¿Puede ver llamados de atención?
     */
    public function canViewDisciplinaryActions(): bool
    {
        return $this->isAdmin() || $this->isCoordinator() || $this->isInstructor();
    }

    /**
     * ¿Puede crear llamado de atención para un estudiante específico? (instructor solo de sus grupos)
     */
    public function canCreateDisciplinaryActionForStudent($studentId): bool
    {
        if ($this->isAdmin() || $this->isCoordinator()) {
            return true;
        }

        if ($this->isInstructor()) {
            $student = \App\Models\Student::find($studentId);
            if (!$student) {
                return false;
            }

            return \App\Models\CompetenciaGroupInstructor::where('instructor_id', $this->id)
                ->where('group_id', $student->group_id)
                ->exists();
        }

        return false;
    }

    /**
     * ¿Puede ver reportes?
     */
    public function canViewReports(): bool
    {
        return $this->isAdmin() || $this->isCoordinator();
    }

    /**
     * ¿Puede ver auditoría?
     */
    public function canViewAudit(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Obtiene los grupos a los que tiene acceso (para instructores solo los asignados)
     * Cachea el resultado para evitar consultas repetidas
     */
    public function getAccessibleGroupIds()
    {
        return cache()->remember("user_{$this->id}_accessible_group_ids", 3600, function () {
            if ($this->isAdmin() || $this->isCoordinator()) {
                return \App\Models\Group::where('activo', true)->pluck('id');
            }

            if ($this->isInstructor()) {
                return \App\Models\CompetenciaGroupInstructor::where('instructor_id', $this->id)
                    ->distinct()
                    ->pluck('group_id');
            }

            if ($this->isStudent()) {
                $student = \App\Models\Student::where('user_id', $this->id)->first();
                return $student && $student->group_id ? collect([$student->group_id]) : collect();
            }

            return collect();
        });
    }
    
    /**
     * Obtiene las asignaciones de competencias y grupos (cacheado para instructores)
     */
    public function getCompetenciaGroupAssignments()
    {
        if (!$this->isInstructor()) {
            return collect();
        }
        
        return cache()->remember("user_{$this->id}_competencia_assignments", 3600, function () {
            return \App\Models\CompetenciaGroupInstructor::where('instructor_id', $this->id)
                ->with(['competencia', 'group'])
                ->get();
        });
    }
    
    /**
     * Limpia el cache de permisos y grupos accesibles (usar después de cambios)
     */
    public function clearPermissionCache()
    {
        cache()->forget("user_{$this->id}_accessible_group_ids");
        cache()->forget("user_{$this->id}_competencia_assignments");
    }
    
    /**
     * Acceso al atributo role normalizado (compatibilidad)
     */
    public function getRole()
    {
        return $this->getNormalizedRole();
    }
}
