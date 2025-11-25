-- ============================================
-- ESQUEMA DE BASE DE DATOS - SISTEMA SENA
-- Para importar en Draw.io (Diagrama ER)
-- ============================================

CREATE DATABASE IF NOT EXISTS sena_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sena_db;

-- ============================================
-- TABLAS DE AUTENTICACIÓN Y USUARIOS
-- ============================================

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    current_team_id BIGINT UNSIGNED NULL,
    profile_photo_path VARCHAR(2048) NULL,
    role ENUM('coordinador', 'instructor_lider', 'instructor', 'aprendiz') NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_tokenable (tokenable_type, tokenable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLAS DE PROGRAMAS Y FICHAS
-- ============================================

CREATE TABLE programs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(255) NULL UNIQUE,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    duracion_meses INT NULL,
    nivel VARCHAR(255) NULL COMMENT 'Técnico, Tecnólogo, etc.',
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_codigo (codigo),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE groups (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero_ficha VARCHAR(255) NOT NULL UNIQUE,
    program_id BIGINT UNSIGNED NOT NULL,
    fecha_inicio DATE NULL,
    fecha_fin DATE NULL,
    jornada VARCHAR(255) NULL COMMENT 'Diurna, Nocturna, etc.',
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE,
    INDEX idx_numero_ficha (numero_ficha),
    INDEX idx_program_id (program_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLAS DE COMPETENCIAS Y RESULTADOS DE APRENDIZAJE
-- ============================================

CREATE TABLE competencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    program_id BIGINT UNSIGNED NOT NULL,
    codigo VARCHAR(255) NOT NULL UNIQUE COMMENT 'Ej: 210101001',
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE,
    INDEX idx_codigo (codigo),
    INDEX idx_program_id (program_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE learning_outcomes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    competencia_id BIGINT UNSIGNED NOT NULL,
    codigo VARCHAR(255) NOT NULL UNIQUE COMMENT 'Ej: 21010100101',
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (competencia_id) REFERENCES competencias(id) ON DELETE CASCADE,
    INDEX idx_codigo (codigo),
    INDEX idx_competencia_id (competencia_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLAS DE INSTRUCTORES Y ESTUDIANTES
-- ============================================

CREATE TABLE instructors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    documento VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(255) NULL,
    especialidad VARCHAR(255) NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_documento (documento),
    INDEX idx_email (email),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    nombre VARCHAR(255) NOT NULL,
    documento VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(255) NULL,
    group_id BIGINT UNSIGNED NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_documento (documento),
    INDEX idx_email (email),
    INDEX idx_group_id (group_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLAS DE RELACIONES INSTRUCTOR-COMPETENCIA
-- ============================================

CREATE TABLE competencia_group_instructor (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    competencia_id BIGINT UNSIGNED NOT NULL,
    group_id BIGINT UNSIGNED NOT NULL,
    instructor_id BIGINT UNSIGNED NOT NULL,
    fecha_asignacion DATE NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (competencia_id) REFERENCES competencias(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE,
    UNIQUE KEY unique_assignment (competencia_id, group_id, instructor_id),
    INDEX idx_competencia_id (competencia_id),
    INDEX idx_group_id (group_id),
    INDEX idx_instructor_id (instructor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE student_competencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    competencia_id BIGINT UNSIGNED NOT NULL,
    estado ENUM('en_progreso', 'aprobado', 'reprobado') NOT NULL DEFAULT 'en_progreso',
    nota_final DECIMAL(5,2) NULL,
    fecha_inicio DATE NULL,
    fecha_fin DATE NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (competencia_id) REFERENCES competencias(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_competencia (student_id, competencia_id),
    INDEX idx_student_id (student_id),
    INDEX idx_competencia_id (competencia_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE student_learning_outcomes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    learning_outcome_id BIGINT UNSIGNED NOT NULL,
    estado ENUM('pendiente', 'en_progreso', 'aprobado', 'reprobado') NOT NULL DEFAULT 'pendiente',
    nota_final DECIMAL(5,2) NULL,
    fecha_inicio DATE NULL,
    fecha_fin DATE NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (learning_outcome_id) REFERENCES learning_outcomes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_learning_outcome (student_id, learning_outcome_id),
    INDEX idx_student_id (student_id),
    INDEX idx_learning_outcome_id (learning_outcome_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLAS DE ASISTENCIAS
-- ============================================

CREATE TABLE attendance_lists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group_id BIGINT UNSIGNED NOT NULL,
    competencia_id BIGINT UNSIGNED NULL,
    instructor_id BIGINT UNSIGNED NOT NULL,
    fecha DATE NOT NULL,
    tema VARCHAR(255) NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    FOREIGN KEY (competencia_id) REFERENCES competencias(id) ON DELETE SET NULL,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE,
    INDEX idx_group_id (group_id),
    INDEX idx_competencia_id (competencia_id),
    INDEX idx_instructor_id (instructor_id),
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLAS DE ACCIONES DISCIPLINARIAS
-- ============================================

CREATE TABLE disciplinary_faults (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(255) NOT NULL UNIQUE,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    tipo ENUM('academica', 'disciplinaria') NOT NULL,
    gravedad ENUM('leve', 'grave', 'muy_grave') NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_codigo (codigo),
    INDEX idx_tipo (tipo),
    INDEX idx_gravedad (gravedad),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE academic_faults (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(255) NOT NULL UNIQUE,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    gravedad ENUM('leve', 'grave', 'muy_grave') NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_codigo (codigo),
    INDEX idx_gravedad (gravedad),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE disciplinary_actions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    instructor_id BIGINT UNSIGNED NOT NULL,
    disciplinary_fault_id BIGINT UNSIGNED NULL,
    academic_fault_id BIGINT UNSIGNED NULL,
    tipo ENUM('llamado_atencion', 'sancion', 'plan_mejoramiento') NOT NULL,
    descripcion TEXT NOT NULL,
    fecha DATE NOT NULL,
    estado ENUM('pendiente', 'en_proceso', 'resuelto', 'cancelado') NOT NULL DEFAULT 'pendiente',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE,
    FOREIGN KEY (disciplinary_fault_id) REFERENCES disciplinary_faults(id) ON DELETE SET NULL,
    FOREIGN KEY (academic_fault_id) REFERENCES academic_faults(id) ON DELETE SET NULL,
    INDEX idx_student_id (student_id),
    INDEX idx_instructor_id (instructor_id),
    INDEX idx_tipo (tipo),
    INDEX idx_estado (estado),
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE improvement_plans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    disciplinary_action_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    instructor_id BIGINT UNSIGNED NOT NULL,
    tipo ENUM('academico', 'disciplinario') NOT NULL DEFAULT 'academico',
    descripcion TEXT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('pendiente', 'en_progreso', 'completado', 'no_completado') NOT NULL DEFAULT 'pendiente',
    observaciones TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (disciplinary_action_id) REFERENCES disciplinary_actions(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES instructors(id) ON DELETE CASCADE,
    INDEX idx_disciplinary_action_id (disciplinary_action_id),
    INDEX idx_student_id (student_id),
    INDEX idx_instructor_id (instructor_id),
    INDEX idx_tipo (tipo),
    INDEX idx_estado (estado),
    INDEX idx_fecha_inicio (fecha_inicio),
    INDEX idx_fecha_fin (fecha_fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLAS DE AUDITORÍA
-- ============================================

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    event VARCHAR(255) NOT NULL COMMENT 'created, updated, deleted, etc.',
    auditable_type VARCHAR(255) NOT NULL COMMENT 'Modelo afectado',
    auditable_id BIGINT UNSIGNED NOT NULL COMMENT 'ID del registro afectado',
    old_values JSON NULL COMMENT 'Valores anteriores',
    new_values JSON NULL COMMENT 'Valores nuevos',
    url VARCHAR(255) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_auditable (auditable_type, auditable_id),
    INDEX idx_event (event),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLAS DEL SISTEMA (JOBS, CACHE)
-- ============================================

CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX idx_queue (queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_uuid (uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL,
    INDEX idx_expiration (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cache_locks (
    `key` VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL,
    INDEX idx_expiration (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- RESUMEN DE RELACIONES PRINCIPALES
-- ============================================
-- 
-- users (1) -----> (N) students
-- users (1) -----> (N) instructors
-- 
-- programs (1) -----> (N) groups
-- programs (1) -----> (N) competencias
-- 
-- competencias (1) -----> (N) learning_outcomes
-- competencias (N) <-----> (N) students (via student_competencias)
-- competencias (N) <-----> (N) instructors + groups (via competencia_group_instructor)
-- 
-- learning_outcomes (N) <-----> (N) students (via student_learning_outcomes)
-- 
-- groups (1) -----> (N) students
-- groups (1) -----> (N) attendance_lists
-- 
-- students (1) -----> (N) disciplinary_actions
-- students (1) -----> (N) improvement_plans
-- students (N) <-----> (N) competencias (via student_competencias)
-- students (N) <-----> (N) learning_outcomes (via student_learning_outcomes)
-- 
-- instructors (1) -----> (N) attendance_lists
-- instructors (1) -----> (N) disciplinary_actions
-- instructors (1) -----> (N) improvement_plans
-- 
-- disciplinary_faults (1) -----> (N) disciplinary_actions
-- academic_faults (1) -----> (N) disciplinary_actions
-- disciplinary_actions (1) -----> (N) improvement_plans
-- 
-- users (1) -----> (N) audit_logs
-- ============================================

