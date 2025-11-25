<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Si la tabla users existe (Laravel default), agregar campos faltantes
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Agregar campos que faltan
                if (!Schema::hasColumn('users', 'numero_documento')) {
                    $table->string('numero_documento', 20)->nullable()->unique()->after('id');
                }
                if (!Schema::hasColumn('users', 'tipo_documento')) {
                    $table->enum('tipo_documento', ['CC', 'CE', 'TI', 'PAS'])->nullable()->after('numero_documento');
                }
                if (!Schema::hasColumn('users', 'nombres')) {
                    $table->string('nombres', 100)->nullable()->after('tipo_documento');
                }
                if (!Schema::hasColumn('users', 'apellidos')) {
                    $table->string('apellidos', 100)->nullable()->after('nombres');
                }
                if (!Schema::hasColumn('users', 'password_hash')) {
                    $table->string('password_hash', 255)->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'telefono')) {
                    $table->string('telefono', 20)->nullable()->after('password_hash');
                }
                if (!Schema::hasColumn('users', 'rol')) {
                    $table->string('rol')->default('aprendiz')->after('telefono');
                }
                if (!Schema::hasColumn('users', 'estado')) {
                    $table->enum('estado', ['activo', 'inactivo', 'suspendido', 'cancelado'])->default('activo')->after('rol');
                }
                if (!Schema::hasColumn('users', 'fecha_creacion')) {
                    $table->timestamp('fecha_creacion')->nullable()->after('estado');
                }
                if (!Schema::hasColumn('users', 'ultima_actualizacion')) {
                    $table->timestamp('ultima_actualizacion')->nullable()->after('fecha_creacion');
                }
            });
        }
        
        // Si la tabla usuarios ya existe (del SQL), solo agregar campos faltantes
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                // Verificar y agregar campos si faltan
                if (!Schema::hasColumn('usuarios', 'remember_token')) {
                    $table->rememberToken()->nullable();
                }
                if (!Schema::hasColumn('usuarios', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revertir para mantener compatibilidad
    }
};

