# Sistema de Gestión Académica SENA

Sistema web para la gestión académica del Servicio Nacional de Aprendizaje (SENA), desarrollado con Laravel 12.

## 📚 Documentación

- **[Guía de Inicio Local](GUIA_INICIO_LOCAL.md)** - Guía completa para iniciar el proyecto en local
- **[Manual de Usuario](MANUAL_USUARIO.md)** - Manual completo para usuarios del sistema
- **[Documentación Técnica](DOCUMENTATION.md)** - Documentación técnica del sistema
- **[Esquema de Base de Datos](DATABASE_SCHEMA.md)** - Estructura y relaciones de la base de datos

## 🚀 Inicio Rápido

> **📖 Para una guía completa y detallada, consulta [GUIA_INICIO_LOCAL.md](GUIA_INICIO_LOCAL.md)**

### Opción 1: Inicio Automático con Script (Recomendado) ⚡

**Windows:**
```bash
# 1. Copiar archivo de configuración
copy .env.example .env

# 2. Ejecutar script de inicio
inicio-local.bat
```

El script iniciará automáticamente:
- ✅ Docker y contenedores
- ✅ Base de datos y migraciones
- ✅ Dependencias de Node.js
- ✅ Vite en modo desarrollo

**Acceder:** `http://localhost`

---

### Opción 2: Con Docker (Manual) 🐳

**Requisitos:**
- Docker Desktop

**Instalación:**
```bash
# 1. Instalar dependencias
composer install

# 2. Configurar Sail (si es necesario)
php artisan sail:install

# 3. Iniciar contenedores
./vendor/bin/sail up -d
# O en Windows: vendor\bin\sail up -d

# 4. Ejecutar migraciones
./vendor/bin/sail artisan migrate

# 5. Instalar dependencias de Node
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

**Acceder:** `http://localhost`

📖 **Ver [Guía completa de inicio local](GUIA_INICIO_LOCAL.md)**

---

### Opción 2: Con XAMPP (Tradicional) 💻

**Requisitos Previos:**
- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL
- XAMPP (recomendado para Windows)

**Instalación:**

1. **Clonar el repositorio:**
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/fabioulloa06/SistemaGestionAcademicaSena.git
   cd SistemaGestionAcademicaSena
   ```

2. **Instalar dependencias:**
   ```bash
   composer install
   npm install
   ```

3. **Configurar el proyecto:**
   - Copiar `.env.example` a `.env` (o crear `.env` manualmente)
   - Configurar las credenciales de la base de datos en `.env`
   - Ejecutar `php artisan key:generate`

4. **Configurar la base de datos:**
   - Crear la base de datos `sena_db` en MySQL
   - Importar `database/sql/sena_database.sql` o ejecutar `php artisan migrate`
   - Ejecutar `php artisan db:seed` para datos iniciales

5. **Compilar assets:**
   ```bash
   npm run build
   ```

6. **Iniciar servidores:**
   ```bash
   start.bat
   ```
   O manualmente:
   ```bash
   php artisan serve    # Terminal 1
   npm run dev          # Terminal 2
   ```

7. **Acceder a la aplicación:**
   - Abre tu navegador en: `http://localhost:8000`
   - Credenciales por defecto:
     - Email: `admin@admin.com`
     - Contraseña: `fabio123`

**📖 Para más detalles, consulta la [Guía de Instalación Completa](GUIA_INSTALACION.md)**

## 🛠️ Tecnologías Utilizadas

- **Backend:** Laravel 12
- **Frontend:** Blade Templates, Tailwind CSS
- **Base de Datos:** MySQL
- **Build Tool:** Vite
- **Gestión de Dependencias:** Composer, npm

## 👥 Roles del Sistema

- **Coordinador:** Gestiona programas, crea fichas, asigna instructores
- **Instructor Líder:** Responsable de una ficha, coordina instructores
- **Instructor:** Dicta RA, registra asistencias, califica evidencias
- **Aprendiz:** Matriculado en ficha, entrega evidencias, ve calificaciones

## 📋 Funcionalidades Principales

- ✅ Autenticación de usuarios
- ✅ Gestión de aprendices (registro por instructor líder)
- ✅ Dashboard personalizado por rol
- 🔄 Gestión de fichas y programas de formación
- 🔄 Registro de asistencias
- 🔄 Gestión de evidencias y calificaciones
- 🔄 Sistema de llamados de atención y sanciones
- 🔄 Planes de mejoramiento

## 📝 Estructura del Proyecto

```
SistemaGestionAcademicaSena/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controladores
│   │   └── Middleware/      # Middleware personalizado
│   ├── Models/             # Modelos Eloquent
│   └── Helpers/            # Funciones auxiliares
├── database/
│   ├── migrations/         # Migraciones de base de datos
│   ├── seeders/           # Seeders para datos iniciales
│   └── sql/               # Scripts SQL
├── resources/
│   ├── views/             # Vistas Blade
│   └── css/               # Estilos CSS
├── routes/
│   └── web.php            # Rutas web
└── public/                # Archivos públicos
```

## 🔧 Comandos Útiles

```bash
# Iniciar servidor de desarrollo
php artisan serve

# Compilar assets para producción
npm run build

# Compilar assets en modo desarrollo
npm run dev

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📖 Documentación Disponible

- **[GUIA_INICIO_LOCAL.md](GUIA_INICIO_LOCAL.md)** - Guía completa de inicio local
- **[MANUAL_USUARIO.md](MANUAL_USUARIO.md)** - Manual de usuario del sistema
- **[DOCUMENTATION.md](DOCUMENTATION.md)** - Documentación técnica
- **[DATABASE_SCHEMA.md](DATABASE_SCHEMA.md)** - Esquema de base de datos
- **[GUIA_INSTALACION.md](GUIA_INSTALACION.md)** - Guía de instalación (alternativa)
- **[ESTADO_PROYECTO.md](ESTADO_PROYECTO.md)** - Estado actual del desarrollo

## 🤝 Contribuir

Para contribuir al proyecto, contacta al equipo de desarrollo.

## 📄 Licencia

Este proyecto es de uso interno para el SENA.

---

**Desarrollado para el Servicio Nacional de Aprendizaje (SENA)**
