-- ============================================
-- SCRIPT COMPLETO PARA REINSTALAR LA BASE DE DATOS
-- Este script limpia TODO y recrea la base de datos
-- Ejecuta este script en phpMyAdmin
-- ============================================

-- Primero, eliminar la base de datos completamente si existe
-- Si da error, primero ejecuta limpiar_tablas.sql

DROP DATABASE IF EXISTS sena_db;

-- Crear base de datos nueva
CREATE DATABASE sena_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

SELECT 'Base de datos eliminada y creada nuevamente. Ahora ejecuta sena_database.sql' AS mensaje;

