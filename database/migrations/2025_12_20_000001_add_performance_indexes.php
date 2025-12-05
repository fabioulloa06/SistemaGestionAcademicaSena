<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega índices para optimizar consultas frecuentes
     */
    public function up(): void
    {
        // Índices para attendance_lists (consultas frecuentes por fecha y estado)
        if (Schema::hasTable('attendance_lists')) {
            Schema::table('attendance_lists', function (Blueprint $table) {
                if (!$this->hasIndex('attendance_lists', 'attendance_lists_fecha_index')) {
                    $table->index('fecha', 'attendance_lists_fecha_index');
                }
                if (!$this->hasIndex('attendance_lists', 'attendance_lists_estado_index')) {
                    $table->index('estado', 'attendance_lists_estado_index');
                }
                if (!$this->hasIndex('attendance_lists', 'attendance_lists_competencia_id_index')) {
                    $table->index('competencia_id', 'attendance_lists_competencia_id_index');
                }
                // Índice compuesto para consultas por estudiante y fecha
                if (!$this->hasIndex('attendance_lists', 'attendance_lists_student_fecha_index')) {
                    $table->index(['student_id', 'fecha'], 'attendance_lists_student_fecha_index');
                }
            });
        }

        // Índices para students (búsquedas por documento y email)
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (!$this->hasIndex('students', 'students_group_id_index')) {
                    $table->index('group_id', 'students_group_id_index');
                }
                if (!$this->hasIndex('students', 'students_activo_index')) {
                    $table->index('activo', 'students_activo_index');
                }
            });
        }

        // Índices para groups
        if (Schema::hasTable('groups')) {
            Schema::table('groups', function (Blueprint $table) {
                if (!$this->hasIndex('groups', 'groups_program_id_index')) {
                    $table->index('program_id', 'groups_program_id_index');
                }
                if (!$this->hasIndex('groups', 'groups_activo_index')) {
                    $table->index('activo', 'groups_activo_index');
                }
            });
        }

        // Índices para competencias
        if (Schema::hasTable('competencias')) {
            Schema::table('competencias', function (Blueprint $table) {
                if (!$this->hasIndex('competencias', 'competencias_program_id_index')) {
                    $table->index('program_id', 'competencias_program_id_index');
                }
                if (!$this->hasIndex('competencias', 'competencias_activo_index')) {
                    $table->index('activo', 'competencias_activo_index');
                }
            });
        }

        // Índices para learning_outcomes
        if (Schema::hasTable('learning_outcomes')) {
            Schema::table('learning_outcomes', function (Blueprint $table) {
                if (!$this->hasIndex('learning_outcomes', 'learning_outcomes_competencia_id_index')) {
                    $table->index('competencia_id', 'learning_outcomes_competencia_id_index');
                }
                if (!$this->hasIndex('learning_outcomes', 'learning_outcomes_activo_index')) {
                    $table->index('activo', 'learning_outcomes_activo_index');
                }
            });
        }

        // Índices para disciplinary_actions
        if (Schema::hasTable('disciplinary_actions')) {
            Schema::table('disciplinary_actions', function (Blueprint $table) {
                if (!$this->hasIndex('disciplinary_actions', 'disciplinary_actions_student_id_index')) {
                    $table->index('student_id', 'disciplinary_actions_student_id_index');
                }
                if (!$this->hasIndex('disciplinary_actions', 'disciplinary_actions_date_index')) {
                    $table->index('date', 'disciplinary_actions_date_index');
                }
                if (!$this->hasIndex('disciplinary_actions', 'disciplinary_actions_tipo_falta_index')) {
                    $table->index('tipo_falta', 'disciplinary_actions_tipo_falta_index');
                }
            });
        }

        // Índices para improvement_plans
        if (Schema::hasTable('improvement_plans')) {
            Schema::table('improvement_plans', function (Blueprint $table) {
                if (!$this->hasIndex('improvement_plans', 'improvement_plans_student_id_index')) {
                    $table->index('student_id', 'improvement_plans_student_id_index');
                }
                if (!$this->hasIndex('improvement_plans', 'improvement_plans_status_index')) {
                    $table->index('status', 'improvement_plans_status_index');
                }
            });
        }

        // Índices para competencia_group_instructor
        if (Schema::hasTable('competencia_group_instructor')) {
            Schema::table('competencia_group_instructor', function (Blueprint $table) {
                if (!$this->hasIndex('competencia_group_instructor', 'cgi_group_id_index')) {
                    $table->index('group_id', 'cgi_group_id_index');
                }
                if (!$this->hasIndex('competencia_group_instructor', 'cgi_instructor_id_index')) {
                    $table->index('instructor_id', 'cgi_instructor_id_index');
                }
            });
        }

        // Índices para student_learning_outcomes
        if (Schema::hasTable('student_learning_outcomes')) {
            Schema::table('student_learning_outcomes', function (Blueprint $table) {
                if (!$this->hasIndex('student_learning_outcomes', 'slo_estado_index')) {
                    $table->index('estado', 'slo_estado_index');
                }
                if (!$this->hasIndex('student_learning_outcomes', 'slo_fecha_evaluacion_index')) {
                    $table->index('fecha_evaluacion', 'slo_fecha_evaluacion_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('attendance_lists')) {
            Schema::table('attendance_lists', function (Blueprint $table) {
                $table->dropIndex('attendance_lists_fecha_index');
                $table->dropIndex('attendance_lists_estado_index');
                $table->dropIndex('attendance_lists_competencia_id_index');
                $table->dropIndex('attendance_lists_student_fecha_index');
            });
        }

        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropIndex('students_group_id_index');
                $table->dropIndex('students_activo_index');
            });
        }

        if (Schema::hasTable('groups')) {
            Schema::table('groups', function (Blueprint $table) {
                $table->dropIndex('groups_program_id_index');
                $table->dropIndex('groups_activo_index');
            });
        }

        if (Schema::hasTable('competencias')) {
            Schema::table('competencias', function (Blueprint $table) {
                $table->dropIndex('competencias_program_id_index');
                $table->dropIndex('competencias_activo_index');
            });
        }

        if (Schema::hasTable('learning_outcomes')) {
            Schema::table('learning_outcomes', function (Blueprint $table) {
                $table->dropIndex('learning_outcomes_competencia_id_index');
                $table->dropIndex('learning_outcomes_activo_index');
            });
        }

        if (Schema::hasTable('disciplinary_actions')) {
            Schema::table('disciplinary_actions', function (Blueprint $table) {
                $table->dropIndex('disciplinary_actions_student_id_index');
                $table->dropIndex('disciplinary_actions_date_index');
                $table->dropIndex('disciplinary_actions_tipo_falta_index');
            });
        }

        if (Schema::hasTable('improvement_plans')) {
            Schema::table('improvement_plans', function (Blueprint $table) {
                $table->dropIndex('improvement_plans_student_id_index');
                $table->dropIndex('improvement_plans_status_index');
            });
        }

        if (Schema::hasTable('competencia_group_instructor')) {
            Schema::table('competencia_group_instructor', function (Blueprint $table) {
                $table->dropIndex('cgi_group_id_index');
                $table->dropIndex('cgi_instructor_id_index');
            });
        }

        if (Schema::hasTable('student_learning_outcomes')) {
            Schema::table('student_learning_outcomes', function (Blueprint $table) {
                $table->dropIndex('slo_estado_index');
                $table->dropIndex('slo_fecha_evaluacion_index');
            });
        }
    }

    /**
     * Verifica si un índice existe en una tabla
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        if ($connection->getDriverName() === 'mysql') {
            $result = $connection->select(
                "SELECT COUNT(*) as count FROM information_schema.statistics 
                 WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                [$database, $table, $indexName]
            );
            return $result[0]->count > 0;
        } elseif ($connection->getDriverName() === 'sqlite') {
            $indexes = $connection->select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name=? AND name=?", [$table, $indexName]);
            return count($indexes) > 0;
        }
        
        return false;
    }
};

