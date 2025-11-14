-- ============================================
-- SCRIPT PARA LIMPIAR LA BASE DE DATOS
-- Ejecutar este script primero si la BD ya existe
-- ============================================

USE sena_db;

-- Deshabilitar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 0;

-- Eliminar procedimientos almacenados (uno por uno)
DROP PROCEDURE IF EXISTS sp_actualizar_control_inasistencias;
DROP PROCEDURE IF EXISTS sp_calcular_juicio_ra;

-- Eliminar vistas (una por una)
DROP VIEW IF EXISTS v_aprendices_riesgo_cancelacion;
DROP VIEW IF EXISTS v_control_inasistencias_detallado;
DROP VIEW IF EXISTS v_resumen_llamados_aprendiz;
DROP VIEW IF EXISTS v_calificaciones_aprendiz;
DROP VIEW IF EXISTS v_actividades_pendientes_aprendiz;
DROP VIEW IF EXISTS v_progreso_aprendiz_ra;
DROP VIEW IF EXISTS v_instructores_ficha;
DROP VIEW IF EXISTS v_aprendices_activos;

-- Eliminar tablas (en orden inverso por dependencias)
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

-- Rehabilitar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;

-- Mensaje de confirmación
SELECT 'Base de datos limpiada correctamente. Ahora ejecuta sena_database.sql' AS mensaje;

