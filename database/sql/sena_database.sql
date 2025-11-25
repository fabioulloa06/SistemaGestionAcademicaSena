-- ============================================
-- SISTEMA DE GESTIÓN ACADÉMICA SENA
-- Versión: 1.0
-- Fecha: 2024-12-20
-- Base de datos: MySQL 8.0+
-- ============================================

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS sena_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sena_db;

-- Deshabilitar verificación de claves foráneas temporalmente
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- LIMPIEZA: Eliminar objetos existentes
-- ============================================

-- Eliminar procedimientos almacenados si existen (uno por uno)
DROP PROCEDURE IF EXISTS sp_actualizar_control_inasistencias;
DROP PROCEDURE IF EXISTS sp_calcular_juicio_ra;

-- Eliminar vistas si existen
DROP VIEW IF EXISTS v_aprendices_riesgo_cancelacion;
DROP VIEW IF EXISTS v_control_inasistencias_detallado;
DROP VIEW IF EXISTS v_resumen_llamados_aprendiz;
DROP VIEW IF EXISTS v_calificaciones_aprendiz;
DROP VIEW IF EXISTS v_actividades_pendientes_aprendiz;
DROP VIEW IF EXISTS v_progreso_aprendiz_ra;
DROP VIEW IF EXISTS v_instructores_ficha;
DROP VIEW IF EXISTS v_aprendices_activos;

-- Eliminar tablas si existen (en orden inverso por dependencias)
-- Si tienes error de tablespace, primero ejecuta manualmente cada DROP TABLE desde phpMyAdmin
DROP TABLE IF EXISTS auditoria;
DROP TABLE IF EXISTS notificaciones;
DROP TABLE IF EXISTS comentarios_evidencias;
DROP TABLE IF EXISTS sanciones;
DROP TABLE IF EXISTS descargos;
DROP TABLE IF EXISTS llamados_atencion;
DROP TABLE IF EXISTS tipos_falta;
DROP TABLE IF EXISTS planes_mejoramiento;
DROP TABLE IF EXISTS evaluaciones_ra;
DROP TABLE IF EXISTS calificaciones_evidencias;
DROP TABLE IF EXISTS entregas_evidencias;
DROP TABLE IF EXISTS actividades_aprendizaje;
DROP TABLE IF EXISTS tipos_evidencia;
DROP TABLE IF EXISTS control_inasistencias;
DROP TABLE IF EXISTS asistencias;
DROP TABLE IF EXISTS sesiones_formacion;
DROP TABLE IF EXISTS planeacion_ra;
DROP TABLE IF EXISTS matriculas;
DROP TABLE IF EXISTS fichas;
DROP TABLE IF EXISTS resultados_aprendizaje;
DROP TABLE IF EXISTS competencias;
DROP TABLE IF EXISTS programas_formacion;
DROP TABLE IF EXISTS centros_formacion;
DROP TABLE IF EXISTS usuarios;

-- ============================================
-- TABLAS PRINCIPALES
-- ============================================

-- Tabla: usuarios
-- Descripción: Almacena todos los usuarios del sistema (coordinadores, instructores, aprendices)
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    numero_documento VARCHAR(20) NOT NULL UNIQUE,
    tipo_documento ENUM('CC', 'CE', 'TI', 'PAS') NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    rol ENUM('coordinador', 'instructor_lider', 'instructor', 'aprendiz') NOT NULL,
    estado ENUM('activo', 'inactivo', 'suspendido', 'cancelado') NOT NULL DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_documento (numero_documento),
    INDEX idx_rol (rol),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla principal de usuarios del sistema';

-- Tabla: centros_formacion
-- Descripción: Centros de formación del SENA
CREATE TABLE centros_formacion (
    id_centro INT AUTO_INCREMENT PRIMARY KEY,
    codigo_centro VARCHAR(20) NOT NULL UNIQUE,
    nombre_centro VARCHAR(200) NOT NULL,
    direccion TEXT,
    ciudad VARCHAR(100),
    departamento VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(150),
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_codigo (codigo_centro),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Centros de formación del SENA';

-- Tabla: programas_formacion
-- Descripción: Programas de formación ofrecidos por el SENA
CREATE TABLE programas_formacion (
    id_programa INT AUTO_INCREMENT PRIMARY KEY,
    codigo_programa VARCHAR(50) NOT NULL UNIQUE,
    nombre_programa VARCHAR(200) NOT NULL,
    nivel_formacion ENUM('técnico', 'tecnólogo', 'especialización') NOT NULL,
    duracion_trimestres INT NOT NULL,
    id_centro INT NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_centro) REFERENCES centros_formacion(id_centro) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_codigo (codigo_programa),
    INDEX idx_centro (id_centro),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Programas de formación del SENA';

-- Tabla: competencias
-- Descripción: Competencias que componen un programa de formación
CREATE TABLE competencias (
    id_competencia INT AUTO_INCREMENT PRIMARY KEY,
    codigo_competencia VARCHAR(50) NOT NULL,
    nombre_competencia VARCHAR(300) NOT NULL,
    descripcion TEXT,
    id_programa INT NOT NULL,
    orden INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_programa) REFERENCES programas_formacion(id_programa) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_programa (id_programa),
    INDEX idx_codigo (codigo_competencia),
    UNIQUE KEY uk_programa_codigo (id_programa, codigo_competencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Competencias del programa de formación';

-- Tabla: resultados_aprendizaje
-- Descripción: Resultados de aprendizaje (RA) que componen una competencia
CREATE TABLE resultados_aprendizaje (
    id_ra INT AUTO_INCREMENT PRIMARY KEY,
    codigo_ra VARCHAR(50) NOT NULL,
    nombre_ra VARCHAR(300) NOT NULL,
    descripcion TEXT,
    id_competencia INT NOT NULL,
    horas_asignadas INT NOT NULL,
    orden INT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_competencia) REFERENCES competencias(id_competencia) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_competencia (id_competencia),
    INDEX idx_codigo (codigo_ra),
    UNIQUE KEY uk_competencia_codigo (id_competencia, codigo_ra)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Resultados de aprendizaje (RA)';

-- Tabla: fichas
-- Descripción: Fichas de caracterización que agrupan aprendices
CREATE TABLE fichas (
    id_ficha INT AUTO_INCREMENT PRIMARY KEY,
    codigo_ficha VARCHAR(50) NOT NULL UNIQUE,
    id_programa INT NOT NULL,
    id_centro INT NOT NULL,
    id_coordinador INT NOT NULL COMMENT 'Usuario con rol coordinador',
    id_instructor_lider INT NOT NULL COMMENT 'Usuario con rol instructor_lider',
    fecha_inicio DATE NOT NULL,
    fecha_fin_lectiva DATE,
    fecha_fin_productiva DATE,
    estado ENUM('activa', 'finalizada', 'cancelada') NOT NULL DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_programa) REFERENCES programas_formacion(id_programa) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_centro) REFERENCES centros_formacion(id_centro) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_coordinador) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_instructor_lider) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_codigo (codigo_ficha),
    INDEX idx_programa (id_programa),
    INDEX idx_coordinador (id_coordinador),
    INDEX idx_instructor_lider (id_instructor_lider),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Fichas de caracterización';

-- Tabla: matriculas
-- Descripción: Matrícula de aprendices en fichas
CREATE TABLE matriculas (
    id_matricula INT AUTO_INCREMENT PRIMARY KEY,
    id_ficha INT NOT NULL,
    id_aprendiz INT NOT NULL COMMENT 'Usuario con rol aprendiz',
    numero_ficha_matricula VARCHAR(50),
    fecha_matricula DATE NOT NULL,
    fecha_cancelacion DATE NULL,
    motivo_cancelacion TEXT,
    estado ENUM('activa', 'cancelada', 'finalizada') NOT NULL DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ficha) REFERENCES fichas(id_ficha) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_aprendiz) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_ficha (id_ficha),
    INDEX idx_aprendiz (id_aprendiz),
    INDEX idx_estado (estado),
    UNIQUE KEY uk_ficha_aprendiz_activa (id_ficha, id_aprendiz, estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Matrículas de aprendices en fichas';

-- Tabla: planeacion_ra
-- Descripción: Asignación de instructores a RA por ficha y trimestre
CREATE TABLE planeacion_ra (
    id_planeacion INT AUTO_INCREMENT PRIMARY KEY,
    id_ficha INT NOT NULL,
    id_ra INT NOT NULL,
    id_instructor INT NOT NULL COMMENT 'Usuario con rol instructor',
    trimestre INT NOT NULL,
    fecha_inicio DATE,
    fecha_fin DATE,
    estado ENUM('planeada', 'en_curso', 'finalizada') NOT NULL DEFAULT 'planeada',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ficha) REFERENCES fichas(id_ficha) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_ra) REFERENCES resultados_aprendizaje(id_ra) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_instructor) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_ficha (id_ficha),
    INDEX idx_ra (id_ra),
    INDEX idx_instructor (id_instructor),
    INDEX idx_trimestre (trimestre),
    INDEX idx_estado (estado),
    UNIQUE KEY uk_ficha_ra_trimestre (id_ficha, id_ra, trimestre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Planeación de RA por instructor, ficha y trimestre';

-- Tabla: sesiones_formacion
-- Descripción: Sesiones de formación (clases) creadas por instructores
CREATE TABLE sesiones_formacion (
    id_sesion INT AUTO_INCREMENT PRIMARY KEY,
    id_planeacion INT NOT NULL,
    numero_sesion INT NOT NULL,
    fecha_sesion DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    tema_sesion VARCHAR(300),
    descripcion TEXT,
    lugar ENUM('presencial', 'virtual', 'empresa') NOT NULL DEFAULT 'presencial',
    observaciones TEXT,
    estado ENUM('programada', 'realizada', 'cancelada') NOT NULL DEFAULT 'programada',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_planeacion) REFERENCES planeacion_ra(id_planeacion) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_planeacion (id_planeacion),
    INDEX idx_fecha (fecha_sesion),
    INDEX idx_estado (estado),
    UNIQUE KEY uk_planeacion_numero (id_planeacion, numero_sesion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sesiones de formación';

-- Tabla: asistencias
-- Descripción: Registro de asistencia de aprendices por sesión
CREATE TABLE asistencias (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_sesion INT NOT NULL,
    id_aprendiz INT NOT NULL COMMENT 'Usuario con rol aprendiz',
    estado_asistencia ENUM('presente', 'ausente', 'tardanza', 'excusa_justificada', 'permiso_aprobado') NOT NULL,
    hora_llegada TIME NULL,
    observaciones TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_registrado_por INT NOT NULL COMMENT 'Usuario que registró la asistencia',
    FOREIGN KEY (id_sesion) REFERENCES sesiones_formacion(id_sesion) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_aprendiz) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_registrado_por) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_sesion (id_sesion),
    INDEX idx_aprendiz (id_aprendiz),
    INDEX idx_estado (estado_asistencia),
    INDEX idx_fecha_registro (fecha_registro),
    UNIQUE KEY uk_sesion_aprendiz (id_sesion, id_aprendiz)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro de asistencias por sesión';

-- Tabla: control_inasistencias
-- Descripción: Control y seguimiento de inasistencias por aprendiz y planeación RA
CREATE TABLE control_inasistencias (
    id_control INT AUTO_INCREMENT PRIMARY KEY,
    id_matricula INT NOT NULL,
    id_planeacion INT NOT NULL,
    total_sesiones_programadas INT NOT NULL DEFAULT 0,
    total_sesiones_asistidas INT NOT NULL DEFAULT 0,
    total_sesiones_ausentes INT NOT NULL DEFAULT 0,
    total_excusas_justificadas INT NOT NULL DEFAULT 0,
    total_permisos_aprobados INT NOT NULL DEFAULT 0,
    porcentaje_inasistencia DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    faltas_consecutivas INT NOT NULL DEFAULT 0 COMMENT 'Número de faltas consecutivas actuales',
    max_faltas_consecutivas INT NOT NULL DEFAULT 0 COMMENT 'Máximo número de faltas consecutivas alcanzado',
    estado_alerta ENUM('sin_alerta', 'preventiva', 'critica', 'causal_sancion', 'cancelacion_automatica') NOT NULL DEFAULT 'sin_alerta',
    fecha_ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_matricula) REFERENCES matriculas(id_matricula) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_planeacion) REFERENCES planeacion_ra(id_planeacion) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_matricula (id_matricula),
    INDEX idx_planeacion (id_planeacion),
    INDEX idx_estado_alerta (estado_alerta),
    INDEX idx_faltas_consecutivas (faltas_consecutivas),
    UNIQUE KEY uk_matricula_planeacion (id_matricula, id_planeacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Control de inasistencias por aprendiz y RA';

-- Tabla: tipos_evidencia
-- Descripción: Catálogo de tipos de evidencia (conocimiento, desempeño, producto)
CREATE TABLE tipos_evidencia (
    id_tipo_evidencia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('conocimiento', 'desempeño', 'producto') NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de tipos de evidencia';

-- Tabla: actividades_aprendizaje
-- Descripción: Actividades de aprendizaje creadas por instructores para sus RA
CREATE TABLE actividades_aprendizaje (
    id_actividad INT AUTO_INCREMENT PRIMARY KEY,
    id_planeacion INT NOT NULL,
    id_tipo_evidencia INT NOT NULL,
    codigo_actividad VARCHAR(50),
    nombre_actividad VARCHAR(300) NOT NULL,
    descripcion TEXT,
    porcentaje_ra DECIMAL(5,2) NOT NULL COMMENT 'Porcentaje que representa del RA (debe sumar 100%)',
    fecha_limite DATE NOT NULL,
    hora_limite TIME,
    archivo_guia VARCHAR(500),
    rubrica JSON COMMENT 'Rúbrica de evaluación en formato JSON',
    instrucciones TEXT,
    estado ENUM('borrador', 'publicada', 'cerrada') NOT NULL DEFAULT 'borrador',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_planeacion) REFERENCES planeacion_ra(id_planeacion) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_tipo_evidencia) REFERENCES tipos_evidencia(id_tipo_evidencia) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_planeacion (id_planeacion),
    INDEX idx_tipo_evidencia (id_tipo_evidencia),
    INDEX idx_fecha_limite (fecha_limite),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Actividades de aprendizaje del RA';

-- Tabla: entregas_evidencias
-- Descripción: Entregas de evidencias por parte de los aprendices
CREATE TABLE entregas_evidencias (
    id_entrega INT AUTO_INCREMENT PRIMARY KEY,
    id_actividad INT NOT NULL,
    id_aprendiz INT NOT NULL COMMENT 'Usuario con rol aprendiz',
    archivo_adjunto VARCHAR(500),
    enlace_evidencia VARCHAR(500),
    comentario_aprendiz TEXT,
    es_entrega_tardia BOOLEAN NOT NULL DEFAULT FALSE,
    estado ENUM('entregada', 'por_calificar', 'calificada', 'reentrega_solicitada', 'rechazada') NOT NULL DEFAULT 'entregada',
    fecha_entrega TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_limite_original DATE NOT NULL,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_actividad) REFERENCES actividades_aprendizaje(id_actividad) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_aprendiz) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_actividad (id_actividad),
    INDEX idx_aprendiz (id_aprendiz),
    INDEX idx_estado (estado),
    INDEX idx_fecha_entrega (fecha_entrega),
    UNIQUE KEY uk_actividad_aprendiz (id_actividad, id_aprendiz)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Entregas de evidencias por aprendices';

-- Tabla: calificaciones_evidencias
-- Descripción: Calificaciones de evidencias con retroalimentación
CREATE TABLE calificaciones_evidencias (
    id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_entrega INT NOT NULL UNIQUE,
    juicio ENUM('A', 'D') NOT NULL COMMENT 'A=Aprobado, D=Deficiente',
    retroalimentacion TEXT NOT NULL,
    aspectos_positivos TEXT,
    aspectos_mejorar TEXT,
    solicitar_reentrega BOOLEAN NOT NULL DEFAULT FALSE,
    fecha_limite_reentrega DATE,
    visibilidad ENUM('borrador', 'publicada') NOT NULL DEFAULT 'borrador',
    fecha_calificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_publicacion TIMESTAMP NULL,
    id_calificado_por INT NOT NULL COMMENT 'Usuario instructor que califica',
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_entrega) REFERENCES entregas_evidencias(id_entrega) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_calificado_por) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_juicio (juicio),
    INDEX idx_visibilidad (visibilidad),
    INDEX idx_fecha_calificacion (fecha_calificacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Calificaciones de evidencias';

-- Tabla: evaluaciones_ra
-- Descripción: Juicio final del RA para cada aprendiz (calculado automáticamente)
CREATE TABLE evaluaciones_ra (
    id_evaluacion INT AUTO_INCREMENT PRIMARY KEY,
    id_matricula INT NOT NULL,
    id_planeacion INT NOT NULL,
    juicio_final ENUM('A', 'D') NOT NULL COMMENT 'A=Aprobado, D=Deficiente',
    porcentaje_aprobado DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    porcentaje_deficiente DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    fecha_calculo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_publicacion TIMESTAMP NULL,
    observaciones TEXT,
    FOREIGN KEY (id_matricula) REFERENCES matriculas(id_matricula) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_planeacion) REFERENCES planeacion_ra(id_planeacion) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_matricula (id_matricula),
    INDEX idx_planeacion (id_planeacion),
    INDEX idx_juicio (juicio_final),
    UNIQUE KEY uk_matricula_planeacion (id_matricula, id_planeacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Evaluación final del RA por aprendiz';

-- Tabla: planes_mejoramiento
-- Descripción: Planes de mejoramiento asignados cuando un aprendiz obtiene "D" en un RA
CREATE TABLE planes_mejoramiento (
    id_plan INT AUTO_INCREMENT PRIMARY KEY,
    id_evaluacion INT NOT NULL,
    actividades TEXT NOT NULL COMMENT 'Actividades específicas del plan',
    recursos TEXT COMMENT 'Recursos necesarios',
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('activo', 'completado', 'vencido') NOT NULL DEFAULT 'activo',
    observaciones_seguimiento TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id_creado_por INT NOT NULL COMMENT 'Usuario que crea el plan',
    FOREIGN KEY (id_evaluacion) REFERENCES evaluaciones_ra(id_evaluacion) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_creado_por) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_evaluacion (id_evaluacion),
    INDEX idx_estado (estado),
    INDEX idx_fecha_fin (fecha_fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Planes de mejoramiento';

-- Tabla: tipos_falta
-- Descripción: Catálogo de tipos de faltas (académicas, disciplinarias)
CREATE TABLE tipos_falta (
    id_tipo_falta INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre_falta VARCHAR(200) NOT NULL,
    categoria ENUM('falta_academica', 'falta_disciplinaria_leve', 'falta_disciplinaria_grave', 'falta_muy_grave') NOT NULL,
    descripcion TEXT,
    articulo_reglamento VARCHAR(100),
    sancion_sugerida VARCHAR(200),
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_codigo (codigo),
    INDEX idx_categoria (categoria),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de tipos de faltas';

-- Tabla: llamados_atencion
-- Descripción: Reportes de faltas por parte de instructores
CREATE TABLE llamados_atencion (
    id_llamado INT AUTO_INCREMENT PRIMARY KEY,
    id_matricula INT NOT NULL,
    id_tipo_falta INT NOT NULL,
    id_reportado_por INT NOT NULL COMMENT 'Usuario instructor que reporta',
    fecha_incidente DATE NOT NULL,
    descripcion_incidente TEXT NOT NULL,
    evidencia_adjunta VARCHAR(500),
    requiere_comite BOOLEAN NOT NULL DEFAULT FALSE,
    estado ENUM('reportado', 'en_descargos', 'en_revision', 'resuelto', 'archivado') NOT NULL DEFAULT 'reportado',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_matricula) REFERENCES matriculas(id_matricula) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_tipo_falta) REFERENCES tipos_falta(id_tipo_falta) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_reportado_por) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_matricula (id_matricula),
    INDEX idx_tipo_falta (id_tipo_falta),
    INDEX idx_estado (estado),
    INDEX idx_fecha_incidente (fecha_incidente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Llamados de atención';

-- Tabla: descargos
-- Descripción: Descargos presentados por aprendices ante un llamado de atención
CREATE TABLE descargos (
    id_descargo INT AUTO_INCREMENT PRIMARY KEY,
    id_llamado INT NOT NULL UNIQUE,
    id_aprendiz INT NOT NULL COMMENT 'Usuario con rol aprendiz',
    texto_descargo TEXT NOT NULL,
    evidencia_adjunta VARCHAR(500),
    fecha_presentacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('presentado', 'en_revision', 'aceptado', 'rechazado') NOT NULL DEFAULT 'presentado',
    FOREIGN KEY (id_llamado) REFERENCES llamados_atencion(id_llamado) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_aprendiz) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_estado (estado),
    INDEX idx_fecha_presentacion (fecha_presentacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Descargos de aprendices';

-- Tabla: sanciones
-- Descripción: Sanciones aplicadas por coordinadores
CREATE TABLE sanciones (
    id_sancion INT AUTO_INCREMENT PRIMARY KEY,
    id_llamado INT NOT NULL,
    id_aplicada_por INT NOT NULL COMMENT 'Usuario coordinador que aplica la sanción',
    tipo_sancion ENUM('amonestacion_escrita', 'plan_mejoramiento', 'condicionamiento_matricula', 'suspension_temporal', 'cancelacion_matricula') NOT NULL,
    descripcion_sancion TEXT NOT NULL,
    fecha_aplicacion DATE NOT NULL,
    fecha_inicio DATE,
    fecha_fin DATE,
    observaciones TEXT,
    estado ENUM('activa', 'cumplida', 'cancelada') NOT NULL DEFAULT 'activa',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_llamado) REFERENCES llamados_atencion(id_llamado) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_aplicada_por) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_llamado (id_llamado),
    INDEX idx_tipo_sancion (tipo_sancion),
    INDEX idx_estado (estado),
    INDEX idx_fecha_aplicacion (fecha_aplicacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sanciones aplicadas';

-- Tabla: comentarios_evidencias
-- Descripción: Comentarios interactivos en evidencias (instructor-aprendiz)
CREATE TABLE comentarios_evidencias (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_entrega INT NOT NULL,
    id_usuario INT NOT NULL,
    comentario TEXT NOT NULL,
    es_respuesta_a INT NULL COMMENT 'ID del comentario al que responde',
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_entrega) REFERENCES entregas_evidencias(id_entrega) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (es_respuesta_a) REFERENCES comentarios_evidencias(id_comentario) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_entrega (id_entrega),
    INDEX idx_usuario (id_usuario),
    INDEX idx_fecha (fecha_comentario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Comentarios en evidencias';

-- Tabla: notificaciones
-- Descripción: Sistema de notificaciones para usuarios
CREATE TABLE notificaciones (
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo_notificacion ENUM('llamado_atencion', 'inasistencia', 'evaluacion', 'sancion', 'general', 'alerta_tres_faltas') NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    enlace VARCHAR(500),
    prioridad ENUM('baja', 'media', 'alta', 'urgente') NOT NULL DEFAULT 'media',
    leida BOOLEAN NOT NULL DEFAULT FALSE,
    fecha_lectura TIMESTAMP NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_usuario (id_usuario),
    INDEX idx_tipo (tipo_notificacion),
    INDEX idx_leida (leida),
    INDEX idx_fecha_creacion (fecha_creacion),
    INDEX idx_prioridad (prioridad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notificaciones del sistema';

-- Tabla: auditoria
-- Descripción: Registro de auditoría de acciones importantes
CREATE TABLE auditoria (
    id_auditoria INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tabla_afectada VARCHAR(100) NOT NULL,
    accion ENUM('INSERT', 'UPDATE', 'DELETE', 'SELECT') NOT NULL,
    registro_id INT,
    datos_anteriores JSON,
    datos_nuevos JSON,
    ip_origen VARCHAR(45),
    user_agent TEXT,
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_usuario (id_usuario),
    INDEX idx_tabla (tabla_afectada),
    INDEX idx_accion (accion),
    INDEX idx_fecha (fecha_accion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Auditoría de acciones del sistema';

-- ============================================
-- ÍNDICES ADICIONALES
-- ============================================

-- Índices compuestos para consultas frecuentes
CREATE INDEX idx_asistencias_aprendiz_fecha ON asistencias(id_aprendiz, fecha_registro);
CREATE INDEX idx_entregas_actividad_estado ON entregas_evidencias(id_actividad, estado);
CREATE INDEX idx_notificaciones_usuario_leida ON notificaciones(id_usuario, leida);

-- ============================================
-- VISTAS ÚTILES
-- ============================================

-- Vista: v_aprendices_activos
-- Descripción: Listado de aprendices activos por ficha
CREATE VIEW v_aprendices_activos AS
SELECT 
    m.id_matricula,
    m.id_ficha,
    f.codigo_ficha,
    m.id_aprendiz,
    u.numero_documento,
    CONCAT(u.nombres, ' ', u.apellidos) AS nombre_completo,
    u.email,
    m.fecha_matricula,
    m.estado AS estado_matricula,
    f.estado AS estado_ficha
FROM matriculas m
INNER JOIN fichas f ON m.id_ficha = f.id_ficha
INNER JOIN usuarios u ON m.id_aprendiz = u.id_usuario
WHERE m.estado = 'activa' AND f.estado = 'activa';

-- Vista: v_instructores_ficha
-- Descripción: Instructores asignados por ficha con sus RA
CREATE VIEW v_instructores_ficha AS
SELECT 
    p.id_planeacion,
    f.id_ficha,
    f.codigo_ficha,
    p.id_instructor,
    CONCAT(ui.nombres, ' ', ui.apellidos) AS nombre_instructor,
    ra.id_ra,
    ra.codigo_ra,
    ra.nombre_ra,
    p.trimestre,
    p.estado AS estado_planeacion
FROM planeacion_ra p
INNER JOIN fichas f ON p.id_ficha = f.id_ficha
INNER JOIN resultados_aprendizaje ra ON p.id_ra = ra.id_ra
INNER JOIN usuarios ui ON p.id_instructor = ui.id_usuario
WHERE f.estado = 'activa';

-- Vista: v_progreso_aprendiz_ra
-- Descripción: Progreso del aprendiz por cada RA
CREATE VIEW v_progreso_aprendiz_ra AS
SELECT 
    m.id_matricula,
    m.id_aprendiz,
    CONCAT(u.nombres, ' ', u.apellidos) AS nombre_aprendiz,
    p.id_planeacion,
    ra.id_ra,
    ra.codigo_ra,
    ra.nombre_ra,
    ev.juicio_final,
    ev.porcentaje_aprobado,
    ev.porcentaje_deficiente,
    COUNT(DISTINCT aa.id_actividad) AS total_actividades,
    COUNT(DISTINCT CASE WHEN en.estado = 'calificada' THEN en.id_entrega END) AS actividades_calificadas,
    COUNT(DISTINCT CASE WHEN cal.juicio = 'A' THEN en.id_entrega END) AS actividades_aprobadas,
    COUNT(DISTINCT CASE WHEN cal.juicio = 'D' THEN en.id_entrega END) AS actividades_deficientes
FROM matriculas m
INNER JOIN usuarios u ON m.id_aprendiz = u.id_usuario
INNER JOIN fichas f ON m.id_ficha = f.id_ficha
INNER JOIN planeacion_ra p ON f.id_ficha = p.id_ficha
INNER JOIN resultados_aprendizaje ra ON p.id_ra = ra.id_ra
LEFT JOIN evaluaciones_ra ev ON m.id_matricula = ev.id_matricula AND p.id_planeacion = ev.id_planeacion
LEFT JOIN actividades_aprendizaje aa ON p.id_planeacion = aa.id_planeacion
LEFT JOIN entregas_evidencias en ON aa.id_actividad = en.id_actividad AND en.id_aprendiz = m.id_aprendiz
LEFT JOIN calificaciones_evidencias cal ON en.id_entrega = cal.id_entrega
WHERE m.estado = 'activa'
GROUP BY m.id_matricula, m.id_aprendiz, p.id_planeacion, ra.id_ra, ev.juicio_final, ev.porcentaje_aprobado, ev.porcentaje_deficiente;

-- Vista: v_actividades_pendientes_aprendiz
-- Descripción: Actividades por entregar con días restantes
CREATE VIEW v_actividades_pendientes_aprendiz AS
SELECT 
    aa.id_actividad,
    aa.nombre_actividad,
    aa.fecha_limite,
    aa.hora_limite,
    DATEDIFF(aa.fecha_limite, CURDATE()) AS dias_restantes,
    m.id_aprendiz,
    CONCAT(u.nombres, ' ', u.apellidos) AS nombre_aprendiz,
    m.id_matricula,
    f.codigo_ficha,
    ra.codigo_ra,
    ra.nombre_ra,
    en.id_entrega,
    en.estado AS estado_entrega,
    CASE 
        WHEN DATEDIFF(aa.fecha_limite, CURDATE()) < 0 THEN 'vencida'
        WHEN DATEDIFF(aa.fecha_limite, CURDATE()) <= 3 THEN 'proxima_vencer'
        ELSE 'pendiente'
    END AS estado_actividad
FROM actividades_aprendizaje aa
INNER JOIN planeacion_ra p ON aa.id_planeacion = p.id_planeacion
INNER JOIN fichas f ON p.id_ficha = f.id_ficha
INNER JOIN matriculas m ON f.id_ficha = m.id_ficha
INNER JOIN usuarios u ON m.id_aprendiz = u.id_usuario
INNER JOIN resultados_aprendizaje ra ON p.id_ra = ra.id_ra
LEFT JOIN entregas_evidencias en ON aa.id_actividad = en.id_actividad AND en.id_aprendiz = m.id_aprendiz
WHERE aa.estado = 'publicada' 
  AND m.estado = 'activa'
  AND f.estado = 'activa'
  AND (en.id_entrega IS NULL OR en.estado IN ('entregada', 'reentrega_solicitada'));

-- Vista: v_calificaciones_aprendiz
-- Descripción: Calificaciones visibles del aprendiz
CREATE VIEW v_calificaciones_aprendiz AS
SELECT 
    cal.id_calificacion,
    en.id_entrega,
    aa.id_actividad,
    aa.nombre_actividad,
    en.id_aprendiz,
    CONCAT(u.nombres, ' ', u.apellidos) AS nombre_aprendiz,
    cal.juicio,
    cal.retroalimentacion,
    cal.aspectos_positivos,
    cal.aspectos_mejorar,
    cal.solicitar_reentrega,
    cal.fecha_limite_reentrega,
    en.fecha_entrega,
    cal.fecha_calificacion,
    cal.fecha_publicacion,
    ra.codigo_ra,
    ra.nombre_ra,
    f.codigo_ficha
FROM calificaciones_evidencias cal
INNER JOIN entregas_evidencias en ON cal.id_entrega = en.id_entrega
INNER JOIN actividades_aprendizaje aa ON en.id_actividad = aa.id_actividad
INNER JOIN usuarios u ON en.id_aprendiz = u.id_usuario
INNER JOIN planeacion_ra p ON aa.id_planeacion = p.id_planeacion
INNER JOIN fichas f ON p.id_ficha = f.id_ficha
INNER JOIN resultados_aprendizaje ra ON p.id_ra = ra.id_ra
WHERE cal.visibilidad = 'publicada';

-- Vista: v_resumen_llamados_aprendiz
-- Descripción: Resumen de llamados por tipo para cada aprendiz
CREATE VIEW v_resumen_llamados_aprendiz AS
SELECT 
    m.id_aprendiz,
    CONCAT(u.nombres, ' ', u.apellidos) AS nombre_aprendiz,
    m.id_matricula,
    tf.categoria,
    tf.nombre_falta,
    COUNT(*) AS total_llamados,
    COUNT(CASE WHEN la.estado = 'resuelto' THEN 1 END) AS llamados_resueltos,
    COUNT(CASE WHEN la.estado = 'en_revision' THEN 1 END) AS llamados_en_revision,
    COUNT(CASE WHEN s.id_sancion IS NOT NULL THEN 1 END) AS sanciones_aplicadas
FROM matriculas m
INNER JOIN usuarios u ON m.id_aprendiz = u.id_usuario
INNER JOIN llamados_atencion la ON m.id_matricula = la.id_matricula
INNER JOIN tipos_falta tf ON la.id_tipo_falta = tf.id_tipo_falta
LEFT JOIN sanciones s ON la.id_llamado = s.id_llamado
WHERE m.estado = 'activa'
GROUP BY m.id_aprendiz, m.id_matricula, tf.categoria, tf.nombre_falta;

-- Vista: v_control_inasistencias_detallado
-- Descripción: Control detallado de inasistencias incluyendo faltas consecutivas y alertas
CREATE VIEW v_control_inasistencias_detallado AS
SELECT 
    ci.id_control,
    ci.id_matricula,
    m.id_aprendiz,
    CONCAT(u.nombres, ' ', u.apellidos) AS nombre_aprendiz,
    f.id_ficha,
    f.codigo_ficha,
    ci.id_planeacion,
    ra.codigo_ra,
    ra.nombre_ra,
    ci.total_sesiones_programadas,
    ci.total_sesiones_asistidas,
    ci.total_sesiones_ausentes,
    ci.total_excusas_justificadas,
    ci.total_permisos_aprobados,
    ci.porcentaje_inasistencia,
    ci.faltas_consecutivas,
    ci.max_faltas_consecutivas,
    ci.estado_alerta,
    CASE 
        WHEN ci.faltas_consecutivas >= 3 THEN 'SI'
        ELSE 'NO'
    END AS causal_cancelacion_automatica,
    ci.fecha_ultima_actualizacion
FROM control_inasistencias ci
INNER JOIN matriculas m ON ci.id_matricula = m.id_matricula
INNER JOIN usuarios u ON m.id_aprendiz = u.id_usuario
INNER JOIN fichas f ON m.id_ficha = f.id_ficha
INNER JOIN planeacion_ra p ON ci.id_planeacion = p.id_planeacion
INNER JOIN resultados_aprendizaje ra ON p.id_ra = ra.id_ra
WHERE m.estado = 'activa';

-- Vista: v_aprendices_riesgo_cancelacion
-- Descripción: Aprendices con 3 o más faltas consecutivas (riesgo de cancelación automática)
CREATE VIEW v_aprendices_riesgo_cancelacion AS
SELECT 
    ci.id_control,
    m.id_matricula,
    m.id_aprendiz,
    CONCAT(u.nombres, ' ', u.apellidos) AS nombre_aprendiz,
    u.numero_documento,
    u.email,
    f.id_ficha,
    f.codigo_ficha,
    ra.codigo_ra,
    ra.nombre_ra,
    ci.faltas_consecutivas,
    ci.max_faltas_consecutivas,
    ci.porcentaje_inasistencia,
    ci.estado_alerta,
    ci.fecha_ultima_actualizacion,
    CASE 
        WHEN ci.faltas_consecutivas >= 3 THEN 'CANCELAR_MATRICULA'
        WHEN ci.faltas_consecutivas = 2 THEN 'ALERTA_CRITICA'
        ELSE 'SIN_RIESGO'
    END AS accion_requerida
FROM control_inasistencias ci
INNER JOIN matriculas m ON ci.id_matricula = m.id_matricula
INNER JOIN usuarios u ON m.id_aprendiz = u.id_usuario
INNER JOIN fichas f ON m.id_ficha = f.id_ficha
INNER JOIN planeacion_ra p ON ci.id_planeacion = p.id_planeacion
INNER JOIN resultados_aprendizaje ra ON p.id_ra = ra.id_ra
WHERE m.estado = 'activa'
  AND ci.faltas_consecutivas >= 2
ORDER BY ci.faltas_consecutivas DESC, ci.porcentaje_inasistencia DESC;

-- ============================================
-- DATOS INICIALES (SEEDERS)
-- ============================================

-- Tipos de Evidencia
INSERT INTO tipos_evidencia (nombre, tipo, descripcion) VALUES
('Cuestionario', 'conocimiento', 'Evaluación de conocimientos teóricos'),
('Prueba práctica', 'conocimiento', 'Evaluación práctica de conocimientos'),
('Observación directa', 'desempeño', 'Evaluación del desempeño en situación real'),
('Simulación', 'desempeño', 'Evaluación del desempeño en situación simulada'),
('Proyecto', 'producto', 'Desarrollo de un proyecto completo'),
('Informe técnico', 'producto', 'Elaboración de documentación técnica'),
('Prototipo', 'producto', 'Desarrollo de un prototipo funcional');

-- Tipos de Faltas
INSERT INTO tipos_falta (codigo, nombre_falta, categoria, descripcion, articulo_reglamento, sancion_sugerida) VALUES
('FA-001', 'Inasistencia no justificada superior al 30%', 'falta_academica', 'El aprendiz acumula más del 30% de inasistencias sin justificación válida', 'Artículo 25, numeral 1', 'Condicionamiento de matrícula'),
('FA-002', 'Tres faltas consecutivas sin justificación', 'falta_academica', 'El aprendiz falta 3 sesiones consecutivas sin presentar excusa válida', 'Artículo 25, numeral 2', 'Cancelación de matrícula'),
('FA-003', 'Incumplimiento académico reiterado', 'falta_academica', 'No entrega de evidencias requeridas en múltiples ocasiones', 'Artículo 25, numeral 3', 'Plan de mejoramiento'),
('FD-001', 'Plagio total o parcial', 'falta_disciplinaria_grave', 'Presentación de trabajos copiados sin citar fuentes', 'Artículo 26, numeral 5', 'Amonestación escrita'),
('FD-002', 'Comportamiento irrespetuoso leve', 'falta_disciplinaria_leve', 'Actitud inadecuada con compañeros o instructores', 'Artículo 27, numeral 1', 'Amonestación verbal'),
('FD-003', 'Daño intencional a infraestructura', 'falta_disciplinaria_grave', 'Destrucción deliberada de bienes del SENA', 'Artículo 26, numeral 8', 'Suspensión temporal'),
('FD-004', 'Fraude en evaluaciones', 'falta_muy_grave', 'Suplantación o ayuda en evaluaciones', 'Artículo 28, numeral 3', 'Cancelación de matrícula'),
('FD-005', 'Porte de sustancias prohibidas', 'falta_muy_grave', 'Porte o consumo de sustancias psicoactivas en el centro', 'Artículo 28, numeral 7', 'Cancelación de matrícula');

-- Usuario administrador inicial
-- Contraseña: Admin123! (hash bcrypt)
INSERT INTO usuarios (numero_documento, tipo_documento, nombres, apellidos, email, password_hash, rol, estado) VALUES
('admin', 'CC', 'Administrador', 'Sistema', 'admin@sena.edu.co', '$2b$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 'coordinador', 'activo');

-- ============================================
-- PROCEDIMIENTOS ALMACENADOS
-- ============================================

-- Procedimiento: Actualizar control de inasistencias
-- Descripción: Actualiza las estadísticas de inasistencia y calcula faltas consecutivas
DELIMITER $$

CREATE PROCEDURE sp_actualizar_control_inasistencias(
    IN p_id_matricula INT,
    IN p_id_planeacion INT
)
BEGIN
    DECLARE v_total_sesiones INT DEFAULT 0;
    DECLARE v_total_asistidas INT DEFAULT 0;
    DECLARE v_total_ausentes INT DEFAULT 0;
    DECLARE v_total_excusas INT DEFAULT 0;
    DECLARE v_total_permisos INT DEFAULT 0;
    DECLARE v_porcentaje DECIMAL(5,2) DEFAULT 0.00;
    DECLARE v_faltas_consecutivas INT DEFAULT 0;
    DECLARE v_max_faltas_consecutivas INT DEFAULT 0;
    DECLARE v_estado_alerta VARCHAR(50) DEFAULT 'sin_alerta';
    DECLARE v_id_aprendiz INT;
    
    -- Obtener ID del aprendiz
    SELECT id_aprendiz INTO v_id_aprendiz FROM matriculas WHERE id_matricula = p_id_matricula;
    
    -- Calcular totales de sesiones
    SELECT 
        COUNT(*),
        COUNT(CASE WHEN a.estado_asistencia IN ('presente', 'tardanza') THEN 1 END),
        COUNT(CASE WHEN a.estado_asistencia = 'ausente' THEN 1 END),
        COUNT(CASE WHEN a.estado_asistencia = 'excusa_justificada' THEN 1 END),
        COUNT(CASE WHEN a.estado_asistencia = 'permiso_aprobado' THEN 1 END)
    INTO v_total_sesiones, v_total_asistidas, v_total_ausentes, v_total_excusas, v_total_permisos
    FROM sesiones_formacion s
    INNER JOIN planeacion_ra p ON s.id_planeacion = p.id_planeacion
    LEFT JOIN asistencias a ON s.id_sesion = a.id_sesion AND a.id_aprendiz = v_id_aprendiz
    WHERE p.id_planeacion = p_id_planeacion AND s.estado = 'realizada';
    
    -- Calcular porcentaje de inasistencia
    IF v_total_sesiones > 0 THEN
        SET v_porcentaje = (v_total_ausentes / v_total_sesiones) * 100;
    END IF;
    
    -- Calcular faltas consecutivas (últimas sesiones en orden cronológico)
    SELECT COALESCE(MAX(consecutive_count), 0)
    INTO v_faltas_consecutivas
    FROM (
        SELECT 
            @consecutive := IF(
                @prev_date IS NOT NULL AND 
                DATEDIFF(s.fecha_sesion, @prev_date) = 1 AND
                a.estado_asistencia = 'ausente',
                @consecutive + 1,
                IF(a.estado_asistencia = 'ausente', 1, 0)
            ) AS consecutive_count,
            @prev_date := s.fecha_sesion,
            @prev_consecutive := @consecutive
        FROM (
            SELECT s.id_sesion, s.fecha_sesion, COALESCE(a.estado_asistencia, 'ausente') AS estado_asistencia
            FROM sesiones_formacion s
            INNER JOIN planeacion_ra p ON s.id_planeacion = p.id_planeacion
            LEFT JOIN asistencias a ON s.id_sesion = a.id_sesion AND a.id_aprendiz = v_id_aprendiz
            WHERE p.id_planeacion = p_id_planeacion AND s.estado = 'realizada'
            ORDER BY s.fecha_sesion DESC
            LIMIT 10
        ) AS s
        CROSS JOIN (SELECT @consecutive := 0, @prev_date := NULL, @prev_consecutive := 0) AS vars
        ORDER BY s.fecha_sesion DESC
    ) AS consecutive_absences
    WHERE consecutive_count > 0;
    
    -- Determinar estado de alerta
    IF v_faltas_consecutivas >= 3 THEN
        SET v_estado_alerta = 'cancelacion_automatica';
    ELSEIF v_porcentaje > 30 THEN
        SET v_estado_alerta = 'causal_sancion';
    ELSEIF v_porcentaje >= 25 THEN
        SET v_estado_alerta = 'critica';
    ELSEIF v_porcentaje >= 15 THEN
        SET v_estado_alerta = 'preventiva';
    ELSE
        SET v_estado_alerta = 'sin_alerta';
    END IF;
    
    -- Insertar o actualizar control de inasistencias
    INSERT INTO control_inasistencias (
        id_matricula,
        id_planeacion,
        total_sesiones_programadas,
        total_sesiones_asistidas,
        total_sesiones_ausentes,
        total_excusas_justificadas,
        total_permisos_aprobados,
        porcentaje_inasistencia,
        faltas_consecutivas,
        max_faltas_consecutivas,
        estado_alerta
    ) VALUES (
        p_id_matricula,
        p_id_planeacion,
        v_total_sesiones,
        v_total_asistidas,
        v_total_ausentes,
        v_total_excusas,
        v_total_permisos,
        v_porcentaje,
        v_faltas_consecutivas,
        GREATEST(v_faltas_consecutivas, COALESCE((SELECT max_faltas_consecutivas FROM control_inasistencias 
                                                  WHERE id_matricula = p_id_matricula AND id_planeacion = p_id_planeacion), 0)),
        v_estado_alerta
    )
    ON DUPLICATE KEY UPDATE
        total_sesiones_programadas = v_total_sesiones,
        total_sesiones_asistidas = v_total_asistidas,
        total_sesiones_ausentes = v_total_ausentes,
        total_excusas_justificadas = v_total_excusas,
        total_permisos_aprobados = v_total_permisos,
        porcentaje_inasistencia = v_porcentaje,
        faltas_consecutivas = v_faltas_consecutivas,
        max_faltas_consecutivas = GREATEST(v_faltas_consecutivas, max_faltas_consecutivas),
        estado_alerta = v_estado_alerta,
        fecha_ultima_actualizacion = CURRENT_TIMESTAMP;
        
    -- Si hay 3 faltas consecutivas, crear notificación
    IF v_faltas_consecutivas >= 3 THEN
        INSERT INTO notificaciones (id_usuario, tipo_notificacion, titulo, mensaje, prioridad)
        VALUES (
            v_id_aprendiz,
            'alerta_tres_faltas',
            'Alerta: 3 faltas consecutivas',
            CONCAT('Has faltado 3 sesiones consecutivas sin justificación. Esto es causal de cancelación automática de matrícula.'),
            'urgente'
        );
        
        -- También notificar al coordinador de la ficha
        INSERT INTO notificaciones (id_usuario, tipo_notificacion, titulo, mensaje, prioridad)
        SELECT 
            f.id_coordinador,
            'alerta_tres_faltas',
            'Alerta: Aprendiz con 3 faltas consecutivas',
            CONCAT('El aprendiz ', u.nombres, ' ', u.apellidos, ' ha acumulado 3 faltas consecutivas sin justificación.'),
            'urgente'
        FROM fichas f
        INNER JOIN matriculas m ON f.id_ficha = m.id_ficha
        INNER JOIN usuarios u ON m.id_aprendiz = u.id_usuario
        WHERE m.id_matricula = p_id_matricula;
    END IF;
END$$

DELIMITER ;

-- Procedimiento: Calcular juicio final del RA
-- Descripción: Calcula el juicio final del RA basado en las calificaciones de actividades
DELIMITER $$

CREATE PROCEDURE sp_calcular_juicio_ra(
    IN p_id_matricula INT,
    IN p_id_planeacion INT
)
BEGIN
    DECLARE v_juicio_final CHAR(1);
    DECLARE v_porcentaje_aprobado DECIMAL(5,2) DEFAULT 0.00;
    DECLARE v_porcentaje_deficiente DECIMAL(5,2) DEFAULT 0.00;
    DECLARE v_tiene_deficiente INT DEFAULT 0;
    
    -- Verificar si hay al menos una actividad con juicio "D"
    SELECT COUNT(*)
    INTO v_tiene_deficiente
    FROM entregas_evidencias en
    INNER JOIN actividades_aprendizaje aa ON en.id_actividad = aa.id_actividad
    INNER JOIN calificaciones_evidencias cal ON en.id_entrega = cal.id_entrega
    INNER JOIN planeacion_ra p ON aa.id_planeacion = p.id_planeacion
    INNER JOIN matriculas m ON en.id_aprendiz = m.id_aprendiz
    WHERE p.id_planeacion = p_id_planeacion 
      AND m.id_matricula = p_id_matricula
      AND cal.visibilidad = 'publicada'
      AND cal.juicio = 'D';
    
    -- Calcular porcentajes
    SELECT 
        COALESCE(SUM(CASE WHEN cal.juicio = 'A' THEN aa.porcentaje_ra ELSE 0 END), 0),
        COALESCE(SUM(CASE WHEN cal.juicio = 'D' THEN aa.porcentaje_ra ELSE 0 END), 0)
    INTO v_porcentaje_aprobado, v_porcentaje_deficiente
    FROM entregas_evidencias en
    INNER JOIN actividades_aprendizaje aa ON en.id_actividad = aa.id_actividad
    INNER JOIN calificaciones_evidencias cal ON en.id_entrega = cal.id_entrega
    INNER JOIN planeacion_ra p ON aa.id_planeacion = p.id_planeacion
    INNER JOIN matriculas m ON en.id_aprendiz = m.id_aprendiz
    WHERE p.id_planeacion = p_id_planeacion 
      AND m.id_matricula = p_id_matricula
      AND cal.visibilidad = 'publicada';
    
    -- Determinar juicio final: Si TODAS las actividades son "A" → RA es "A", si al menos UNA es "D" → RA es "D"
    IF v_tiene_deficiente > 0 THEN
        SET v_juicio_final = 'D';
    ELSE
        SET v_juicio_final = 'A';
    END IF;
    
    -- Insertar o actualizar evaluación del RA
    INSERT INTO evaluaciones_ra (
        id_matricula,
        id_planeacion,
        juicio_final,
        porcentaje_aprobado,
        porcentaje_deficiente
    ) VALUES (
        p_id_matricula,
        p_id_planeacion,
        v_juicio_final,
        v_porcentaje_aprobado,
        v_porcentaje_deficiente
    )
    ON DUPLICATE KEY UPDATE
        juicio_final = v_juicio_final,
        porcentaje_aprobado = v_porcentaje_aprobado,
        porcentaje_deficiente = v_porcentaje_deficiente,
        fecha_calculo = CURRENT_TIMESTAMP;
END$$

DELIMITER ;

-- ============================================
-- QUERY DE EJEMPLO PARA DETECTAR 3 FALTAS CONSECUTIVAS
-- ============================================

/*

-- QUERY PARA DETECTAR APRENDICES CON 3 FALTAS CONSECUTIVAS
-- Ejecutar periódicamente o mediante trigger

SELECT 
    a.id_aprendiz,
    CONCAT(u.nombres, ' ', u.apellidos) AS nombre_completo,
    COUNT(*) AS faltas_consecutivas,
    MIN(s.fecha_sesion) AS primera_falta,
    MAX(s.fecha_sesion) AS ultima_falta,
    f.codigo_ficha,
    ra.codigo_ra,
    ra.nombre_ra
FROM (
    SELECT 
        a1.id_aprendiz,
        a1.id_sesion,
        s1.fecha_sesion,
        @consecutive := IF(
            @prev_aprendiz = a1.id_aprendiz AND a1.estado_asistencia = 'ausente',
            @consecutive + 1,
            IF(a1.estado_asistencia = 'ausente', 1, 0)
        ) AS consecutive_absences,
        @prev_aprendiz := a1.id_aprendiz
    FROM asistencias a1
    INNER JOIN sesiones_formacion s1 ON a1.id_sesion = s1.id_sesion
    CROSS JOIN (SELECT @consecutive := 0, @prev_aprendiz := NULL) vars
    WHERE a1.estado_asistencia IN ('ausente')
    ORDER BY a1.id_aprendiz, s1.fecha_sesion
) AS a
INNER JOIN sesiones_formacion s ON a.id_sesion = s.id_sesion
INNER JOIN planeacion_ra p ON s.id_planeacion = p.id_planeacion
INNER JOIN resultados_aprendizaje ra ON p.id_ra = ra.id_ra
INNER JOIN fichas f ON p.id_ficha = f.id_ficha
INNER JOIN usuarios u ON a.id_aprendiz = u.id_usuario
WHERE a.consecutive_absences >= 3
GROUP BY a.id_aprendiz, f.codigo_ficha, ra.codigo_ra, ra.nombre_ra
HAVING COUNT(*) >= 3;

*/

-- Rehabilitar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;

-- FIN DEL SCRIPT

