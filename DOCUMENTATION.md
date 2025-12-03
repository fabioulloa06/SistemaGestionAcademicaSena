# Documentación Técnica - Sistema de Gestión Académica SENA

## 1. Arquitectura del Sistema

El sistema está construido sobre **Laravel 12** (PHP Framework) utilizando una arquitectura MVC (Modelo-Vista-Controlador).

### Stack Tecnológico
- **Backend**: PHP 8.2+, Laravel 12
- **Frontend**: Blade Templates, Bootstrap 5, SweetAlert2, Chart.js, Tailwind CSS 4
- **Base de Datos**: MySQL 8.0
- **Autenticación**: Laravel Sanctum (Session-based)
- **Assets**: Vite 7
- **Contenedores**: Docker con Laravel Sail (opcional)

### Estructura del Proyecto
```
app/
├── Http/Controllers/     # Controladores de la aplicación
├── Models/              # Modelos Eloquent
├── Mail/                # Clases de correo electrónico
├── Traits/              # Traits reutilizables (Auditable)
└── Helpers/             # Funciones helper

resources/
├── views/               # Vistas Blade
└── css/                 # Estilos CSS

routes/
└── web.php             # Rutas de la aplicación

database/
├── migrations/         # Migraciones de base de datos
└── seeders/            # Seeders para datos iniciales
```

## 2. Base de Datos (Esquema Relacional)

### Tablas Principales

#### Usuarios y Autenticación
- **`users`**: Usuarios del sistema (Administradores, Instructores, Coordinadores, Aprendices)
  - Campos: `id`, `nombres`, `apellidos`, `email`, `password`, `rol`, `estado`
  - Roles: `admin`, `coordinator`, `instructor`, `student`

#### Estructura Académica
- **`programs`**: Programas de formación
- **`groups`**: Fichas de caracterización (grupos de aprendices)
- **`students`**: Información de los aprendices
  - Relación: `group_id`, `user_id`
- **`instructors`**: Información de instructores
- **`competencias`**: Competencias asociadas a un programa
- **`learning_outcomes`**: Resultados de Aprendizaje (RAs) de cada competencia
- **`competencia_group_instructor`**: Tabla pivote para asignar instructores a competencias y grupos

#### Módulo de Asistencias
- **`attendance_lists`**: Registro de asistencias
  - Campos: `student_id`, `group_id`, `instructor_id`, `competencia_id`, `fecha`, `estado`, `observaciones`
  - Estados: `presente`, `ausente`, `tarde`, `justificado`

#### Módulo Académico
- **`student_learning_outcomes`**: Tabla pivote para calificaciones (Student <-> LearningOutcome)
  - Campos: `score` (A/D), `observation`, `fecha_calificacion`
- **`student_competencias`**: Estado general de la competencia por estudiante
  - Campos: `porcentaje_avance`, `estado`

#### Módulo Disciplinario
- **`disciplinary_faults`**: Catálogo de faltas (Literales del Reglamento SENA)
- **`disciplinary_actions`**: Registro de llamados de atención
  - Tipos: `verbal`, `written`
  - Faltas: `Académica`, `Disciplinaria`
  - Gravedad: `Leve`, `Grave`, `Gravísima`
- **`improvement_plans`**: Planes de mejoramiento
  - Relación: `student_id`, `disciplinary_action_id` (opcional), `instructor_id`
  - Estados: `Pendiente`, `En Progreso`, `Cumplido`, `Incumplido`

#### Auditoría
- **`audit_logs`**: Registro de acciones del sistema (si está habilitado)

## 3. Controladores Clave

### `AttendanceController`
Maneja la lógica de asistencias.
- **Métodos principales**:
  - `index()`: Lista de asistencias con filtros
  - `bulkCreate()`: Formulario para tomar asistencia masiva
  - `bulkStore()`: Guarda asistencias y valida límites (envía correos automáticos)
- **Validaciones**: 
  - Verifica 3 ausencias consecutivas o totales
  - Envía correos de advertencia automáticamente

### `DisciplinaryActionController`
Maneja la lógica de los llamados de atención.
- **Validaciones**: 
  - Impide crear un tercer llamado escrito sin plan de mejoramiento
  - Valida permisos por grupo
- **Métodos**: 
  - `store()`: Guarda y valida
  - `print()`: Genera vista de impresión PDF

### `ImprovementPlanController`
Gestiona los planes de mejoramiento.
- **Lógica**: 
  - Permite crear planes vinculados a una falta o independientes
  - Seguimiento de estados y fechas
- **Métodos**: 
  - `create()`, `store()`, `update()`, `print()`

### `DashboardController`
Agrega datos para los gráficos estadísticos.
- Calcula totales de asistencia, tipos de faltas y estado de planes
- Diferentes dashboards según rol del usuario

### `StudentAcademicController`
Gestiona el progreso académico de los estudiantes.
- Muestra competencias, RAs y porcentajes de avance

### `CompetenciaController`
Gestiona competencias y asignaciones de instructores.
- Permite asignar instructores a competencias y grupos

## 4. Modelos y Relaciones

### `User` (app/Models/User.php)
Modelo principal de usuarios con métodos de permisos:
- **Métodos de roles**: `isAdmin()`, `isCoordinator()`, `isInstructor()`, `isStudent()`
- **Métodos de permisos**: 
  - `canManageAttendance()`, `canViewAttendance()`
  - `canManageAcademicStructure()`, `canGrade()`
  - `canCreateDisciplinaryActions()`, `canViewReports()`
- **Métodos de acceso**: `getAccessibleGroupIds()` - Retorna grupos accesibles según rol

### `Student`
- Relación: `belongsTo(Group)`, `belongsTo(User)`
- Relación: `hasMany(Attendance_list)`, `hasMany(DisciplinaryAction)`

### `Group`
- Relación: `belongsTo(Program)`, `hasMany(Student)`

### `Competencia`
- Relación: `belongsTo(Program)`, `hasMany(LearningOutcome)`
- Relación: `belongsToMany(Instructor)` a través de `CompetenciaGroupInstructor`

## 5. Sistema de Permisos

El sistema utiliza un sistema de permisos basado en roles implementado en el modelo `User`:

### Permisos por Rol

**Administrador**:
- Acceso completo a todas las funcionalidades
- Gestión de usuarios, programas, grupos, competencias
- Visualización de auditoría

**Coordinador**:
- Solo lectura de toda la información
- Acceso a reportes
- No puede crear asistencias, llamados ni planes

**Instructor**:
- Gestión de asistencias para grupos asignados
- Calificación de RAs
- Creación de llamados y planes
- Acceso limitado a sus grupos

**Aprendiz**:
- Solo visualización de su propio progreso
- Portal estudiantil

### Middleware de Permisos
- `permission:manage-academic-structure`: Admin y Coordinador
- `permission:manage-attendance`: Admin e Instructores
- `permission:view-reports`: Admin y Coordinador
- `permission:view-audit`: Solo Admin

## 6. Frontend y Assets

### Tecnologías Frontend
- **Blade Templates**: Motor de plantillas de Laravel
- **Bootstrap 5**: Framework CSS
- **Tailwind CSS 4**: Framework CSS utility-first
- **SweetAlert2**: Reemplazo de `alert()` y `confirm()` nativos
- **Chart.js**: Gráficos estadísticos en dashboards
- **Vite**: Bundler y dev server para assets

### Estilos
- Personalización de Bootstrap en `resources/css/app.css` (colores institucionales SENA)
- Variables CSS para temas y colores

### JavaScript
- Axios para peticiones AJAX
- SweetAlert2 integrado en `layouts/app.blade.php`

## 7. Correo Electrónico

### Clases de Correo
- **`AttendanceWarning`**: Enviado cuando un aprendiz alcanza límites de ausencias
- **`DisciplinarySanction`**: Notificaciones de sanciones
- **`GradeUploaded`**: Notificación de calificaciones subidas

### Configuración
- Configurado en `config/mail.php`
- Requiere configuración SMTP en `.env`

## 8. Instalación y Despliegue

### Requisitos
- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL 8.0 o superior
- (Opcional) Docker y Docker Compose

### Instalación Local (XAMPP)

1. **Clonar repositorio**
   ```bash
   git clone <repository-url>
   cd SistemaGestionAcademicaSena
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   npm install
   ```

3. **Configurar entorno**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
   Editar `.env` con configuración de base de datos

4. **Base de datos**
   ```bash
   php artisan migrate --seed
   ```

5. **Compilar assets**
   ```bash
   npm run build
   # O para desarrollo:
   npm run dev
   ```

6. **Iniciar servidor**
   ```bash
   php artisan serve
   ```
   O usar `start-local.bat` (Windows)

### Instalación con Docker

1. **Iniciar contenedores**
   ```bash
   docker-start.bat
   # O manualmente:
   docker-compose up -d
   ```

2. **Ejecutar migraciones**
   ```bash
   docker-compose exec laravel.test php artisan migrate --seed
   ```

3. **Instalar dependencias de Node (si es necesario)**
   ```bash
   docker-compose exec laravel.test npm install
   docker-compose exec laravel.test npm run build
   ```

4. **Acceder a la aplicación**
   - Aplicación: http://localhost
   - MySQL: localhost:3306
   - Redis: localhost:6379

### Scripts Disponibles

#### Windows (.bat)
- **`docker-start.bat`**: Inicia contenedores Docker
- **`start-local.bat`**: Inicia proyecto localmente (vite, artisan serve, abre navegador)

#### Composer
- `composer setup`: Instalación completa del proyecto
- `composer dev`: Inicia servidor, queue, logs y vite en paralelo

#### NPM
- `npm run dev`: Servidor de desarrollo Vite
- `npm run build`: Compila assets para producción

## 9. Rutas Principales

### Autenticación
- `GET /login`: Formulario de login
- `POST /login`: Procesar login
- `POST /logout`: Cerrar sesión

### Dashboards
- `GET /dashboard`: Dashboard administrador
- `GET /coordinator/dashboard`: Dashboard coordinador
- `GET /instructor/dashboard`: Dashboard instructor
- `GET /student/dashboard`: Dashboard aprendiz

### Asistencias
- `GET /attendance-lists`: Lista de asistencias
- `GET /attendance-lists/create`: Formulario de asistencia
- `POST /attendance-lists`: Guardar asistencias

### Disciplinario
- `GET /disciplinary-actions`: Lista global de llamados
- `GET /students/{student}/disciplinary-actions`: Historial de un estudiante
- `POST /students/{student}/disciplinary-actions`: Crear llamado
- `GET /disciplinary-actions/{id}/print`: Imprimir llamado

### Planes de Mejoramiento
- `GET /improvement-plans`: Lista de planes
- `GET /improvement-plans/create`: Crear plan
- `POST /improvement-plans`: Guardar plan
- `GET /improvement-plans/{id}/print`: Imprimir plan

### Académico
- `GET /programs`: Lista de programas
- `GET /groups`: Lista de grupos
- `GET /students`: Lista de estudiantes
- `GET /students/{student}/academic`: Progreso académico

## 10. Validaciones y Reglas de Negocio

### Asistencias
- Alerta automática con 3 ausencias consecutivas o totales
- Envío de correo electrónico de advertencia
- Validación de permisos por grupo

### Llamados de Atención
- No se puede crear un tercer llamado escrito sin plan de mejoramiento
- Validación de permisos por grupo
- Requerimiento de plan después del segundo llamado escrito

### Calificaciones
- Solo instructores asignados pueden calificar
- Validación de RAs por competencia
- Cálculo automático de porcentajes

## 11. Seguridad

### Autenticación
- Laravel Sanctum (Session-based)
- Middleware `auth` en todas las rutas protegidas
- Validación de permisos en controladores

### Validación de Datos
- Validación de formularios con Laravel Validation
- Sanitización de inputs
- Protección CSRF en formularios

### Permisos
- Verificación de permisos en cada acción
- Filtrado de datos según rol del usuario
- Cache de permisos para optimización

## 12. Optimizaciones

### Cache
- Cache de grupos accesibles por usuario
- Cache de asignaciones de competencias
- Limpieza de cache después de cambios

### Consultas
- Uso de `with()` para eager loading
- Índices en base de datos para consultas frecuentes
- Paginación en listados grandes

## 13. Testing

### Estructura de Tests
- Tests en `tests/Feature/` y `tests/Unit/`
- PHPUnit como framework de testing

### Ejecutar Tests
```bash
php artisan test
# O
composer test
```

## 14. Logging y Debugging

### Logs
- Logs en `storage/logs/laravel.log`
- Niveles de log configurables en `.env`

### Debugging
- Laravel Debugbar (si está instalado)
- `dd()`, `dump()` para debugging
- Logs de consultas SQL en desarrollo

## 15. Mantenimiento

### Comandos Útiles
```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimizar
php artisan optimize
php artisan config:cache
php artisan route:cache

# Migraciones
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

---

**Versión de la Documentación**: 1.0  
**Última Actualización**: 2024  
**Framework**: Laravel 12  
**PHP**: 8.2+
