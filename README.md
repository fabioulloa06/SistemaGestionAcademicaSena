# Sistema de Gestión Académica SENA

Sistema web para la gestión académica del Servicio Nacional de Aprendizaje (SENA), desarrollado con Laravel 12.

## 📚 Documentación

- **[Guía de Instalación](GUIA_INSTALACION.md)** - Todo lo que necesitas saber para instalar y configurar el proyecto
- **[Guía de Trabajo con Git](GUIA_TRABAJO_GIT.md)** - Cómo trabajar con Git en este proyecto
- **[Estado del Proyecto](ESTADO_PROYECTO.md)** - Qué está completado y qué falta por hacer

## 🚀 Inicio Rápido

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL
- XAMPP (recomendado para Windows)

### Instalación

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

## 🐳 Entorno Docker (opcional)

> Ideal si quieres aislar el proyecto de XAMPP o si Docker bloquea tus puertos locales.

1. **Prepara el entorno**
   - Duplica `.env.example` a `.env`
   - Ajusta los valores para Docker:
     ```
     APP_URL=http://localhost:8080
     DB_HOST=mysql
     DB_PORT=3306
     DB_USERNAME=sena
     DB_PASSWORD=secret
     ```
2. **Levanta los servicios**
   ```bash
   docker compose up -d --build web vite queue scheduler
   ```
   Esto inicia PHP-FPM, Nginx (puerto 8080), MariaDB (puerto 3307 en tu host) y Vite (5173).

3. **Instala dependencias dentro del contenedor**
   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed
   ```

4. **Compilación de assets**
   - En modo desarrollo ya corre `npm run dev` dentro del servicio `vite`
   - Para un build puntual:
     ```bash
     docker compose exec app npm run build
     ```

5. **Accede a la aplicación**
   - Backend: `http://localhost:8080`
   - Vite HMR: `http://localhost:5173`

6. **Apagar el entorno**
   ```bash
   docker compose down
   ```

Variables útiles:
- `APP_PORT` (default 8080) para cambiar el puerto HTTP
- `DB_FORWARD_PORT` (default 3307) si necesitas exponer MariaDB en otro puerto
- `VITE_PORT` (default 5173) para el servidor de Vite

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

## 📖 Guías Disponibles

- **[GUIA_INSTALACION.md](GUIA_INSTALACION.md)** - Instalación paso a paso
- **[GUIA_TRABAJO_GIT.md](GUIA_TRABAJO_GIT.md)** - Flujo de trabajo con Git
- **[ESTADO_PROYECTO.md](ESTADO_PROYECTO.md)** - Estado actual del desarrollo

## 🤝 Contribuir

Para contribuir al proyecto, sigue el flujo de trabajo descrito en [GUIA_TRABAJO_GIT.md](GUIA_TRABAJO_GIT.md).

## 📄 Licencia

Este proyecto es de uso interno para el SENA.

---

**Desarrollado para el Servicio Nacional de Aprendizaje (SENA)**
