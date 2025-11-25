# Documentación Técnica - Sistema de Control de Asistencias

## 1. Arquitectura del Sistema
El sistema está construido sobre **Laravel 10** (PHP Framework) utilizando una arquitectura MVC (Modelo-Vista-Controlador).
*   **Frontend:** Blade Templates, Bootstrap 5, SweetAlert2, Chart.js.
*   **Backend:** PHP 8.2+, MySQL.
*   **Autenticación:** Laravel Jetstream (Sanctum).

## 2. Base de Datos (Esquema Relacional)

### Tablas Principales
*   `users`: Usuarios del sistema (Administradores, Instructores).
*   `students`: Información de los aprendices.
*   `programs`: Programas de formación.
*   `groups`: Fichas de caracterización.
*   `attendances`: Registro de asistencias.

### Módulo Académico
*   `competencias`: Competencias asociadas a un programa.
*   `learning_outcomes`: Resultados de Aprendizaje (RAs) de cada competencia.
*   `student_learning_outcomes`: Tabla pivote para calificaciones (Student <-> LearningOutcome).
    *   Campos: `score` (A/D), `observation`.
*   `student_competencias`: Estado general de la competencia por estudiante.

### Módulo Disciplinario
*   `disciplinary_faults`: Catálogo de faltas (Literales del Reglamento SENA).
*   `disciplinary_actions`: Registro de llamados de atención.
    *   Tipos: `verbal`, `written`.
    *   Faltas: `Académica`, `Disciplinaria`.
    *   Gravedad: `Leve`, `Grave`, `Gravísima`.
*   `improvement_plans`: Planes de mejoramiento.
    *   Relación: `student_id`, `disciplinary_action_id` (opcional), `instructor_id`.
    *   Estados: `Pendiente`, `En Progreso`, `Cumplido`, `Incumplido`.

## 3. Controladores Clave

### `DisciplinaryActionController`
Maneja la lógica de los llamados de atención.
*   **Validaciones:** Impide crear un tercer llamado escrito sin plan de mejoramiento.
*   **Métodos:** `store` (guarda y valida), `print` (genera vista de impresión).

### `ImprovementPlanController`
Gestiona los planes de mejoramiento.
*   **Lógica:** Permite crear planes vinculados a una falta o independientes.
*   **Seguimiento:** Actualización de estados y fechas.

### `DashboardController`
Agrega datos para los gráficos estadísticos.
*   Calcula totales de asistencia, tipos de faltas y estado de planes.

## 4. Frontend y Assets
*   **SweetAlert2:** Integrado en `layouts/app.blade.php` para reemplazar `alert()` y `confirm()`.
*   **Chart.js:** Usado en `dashboard.blade.php` para visualizar métricas.
*   **Estilos:** Personalización de Bootstrap en `resources/css/app.css` (colores institucionales SENA).

## 5. Instalación y Despliegue
1.  Clonar repositorio.
2.  `composer install`
3.  `npm install && npm run build`
4.  Configurar `.env` (Base de datos).
5.  `php artisan migrate --seed`
6.  `php artisan key:generate`
7.  Ejecutar `iniciar_sistema.bat` para levantar el entorno local.
