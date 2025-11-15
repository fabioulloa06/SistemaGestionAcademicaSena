# Guía de Instalación
## Sistema de Gestión Académica SENA

Esta guía te explica paso a paso qué necesitas instalar y cómo configurar el proyecto después de clonarlo.

---

## 📋 Requisitos Previos

Antes de clonar el repositorio, asegúrate de tener instalado:

### 1. **XAMPP** (o servidor web con PHP y MySQL)
- **Descarga:** https://www.apachefriends.org/
- **Versión recomendada:** XAMPP 8.2+ (incluye PHP 8.2+ y MySQL)
- **Verificar instalación:**
  ```bash
  php -v
  ```
  Debe mostrar PHP 8.2 o superior.

### 2. **Composer** (Gestor de dependencias de PHP)
- **Descarga:** https://getcomposer.org/download/
- **Verificar instalación:**
  ```bash
  composer --version
  ```
- **Si no funciona en PowerShell:** Usa `php composer.phar` en lugar de `composer`

### 3. **Node.js y npm** (Para compilar assets frontend)
- **Descarga:** https://nodejs.org/ (versión LTS recomendada)
- **Verificar instalación:**
  ```bash
  node --version
  npm --version
  ```

### 4. **Git** (Para clonar el repositorio)
- **Descarga:** https://git-scm.com/downloads
- **Verificar instalación:**
  ```bash
  git --version
  ```

---

## 🚀 Pasos de Instalación

### Paso 1: Clonar el Repositorio

```bash
# 1. Navegar a la carpeta de XAMPP
cd C:\xampp\htdocs

# 2. Clonar el repositorio
git clone https://github.com/fabioulloa06/SistemaGestionAcademicaSena.git

# 3. Entrar a la carpeta del proyecto
cd SistemaGestionAcademicaSena
```

---

### Paso 2: Instalar Dependencias de PHP (Composer)

```bash
# Instalar todas las dependencias de Laravel
composer install

# Si composer no funciona, usa:
php composer.phar install
```

**⚠️ Si aparece un error sobre la extensión `zip`:** No es crítico, Composer intentará descargar desde el código fuente.

---

### Paso 3: Instalar Dependencias de Node.js (npm)

```bash
# Instalar dependencias de frontend (Vite, Tailwind, etc.)
npm install
```

**⚠️ Si aparece un error:** Asegúrate de tener Node.js instalado correctamente.

---

### Paso 4: Configurar el Archivo .env

El archivo `.env` contiene la configuración del proyecto (base de datos, URLs, etc.).

#### Opción A: Si existe `.env.example`

```bash
# Copiar el archivo de ejemplo
copy .env.example .env
```

#### Opción B: Si no existe `.env.example`, crear `.env` manualmente

Crea un archivo llamado `.env` en la raíz del proyecto con este contenido:

```env
APP_NAME="Sistema Gestión Académica SENA"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=America/Bogota
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sena_db
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

**⚠️ IMPORTANTE:** Ajusta estos valores según tu configuración:
- `DB_DATABASE`: Nombre de tu base de datos MySQL (ej: `sena_db`)
- `DB_USERNAME`: Usuario de MySQL (generalmente `root` en XAMPP)
- `DB_PASSWORD`: Contraseña de MySQL (generalmente vacía en XAMPP)

---

### Paso 5: Generar la Clave de la Aplicación

```bash
php artisan key:generate
```

Esto genera una clave única para tu aplicación y la agrega automáticamente al archivo `.env`.

---

### Paso 6: Crear la Base de Datos

#### Opción A: Usando phpMyAdmin (Recomendado para principiantes)

1. Abre XAMPP Control Panel
2. Inicia Apache y MySQL
3. Abre tu navegador y ve a: `http://localhost/phpmyadmin`
4. Haz clic en "Nueva" (New) en el menú lateral
5. Ingresa el nombre de la base de datos: `sena_db`
6. Selecciona la intercalación: `utf8mb4_unicode_ci`
7. Haz clic en "Crear" (Create)

#### Opción B: Usando MySQL desde la línea de comandos

```bash
# Conectar a MySQL (si tienes contraseña, agrega -p)
mysql -u root

# Crear la base de datos
CREATE DATABASE sena_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Salir de MySQL
exit;
```

---

### Paso 7: Importar la Base de Datos (Opcional)

Si ya tienes un archivo SQL con la estructura y datos iniciales:

#### Opción A: Usando phpMyAdmin

1. Abre phpMyAdmin: `http://localhost/phpmyadmin`
2. Selecciona la base de datos `sena_db`
3. Haz clic en la pestaña "Importar" (Import)
4. Selecciona el archivo SQL: `database/sql/sena_database.sql`
5. Haz clic en "Continuar" (Go)

#### Opción B: Usando MySQL desde la línea de comandos

```bash
mysql -u root sena_db < database/sql/sena_database.sql
```

---

### Paso 8: Ejecutar las Migraciones

Si no importaste el SQL, ejecuta las migraciones para crear las tablas:

```bash
php artisan migrate
```

**⚠️ Si aparece un error:** Asegúrate de que:
- MySQL esté corriendo en XAMPP
- La base de datos `sena_db` exista
- Las credenciales en `.env` sean correctas

---

### Paso 9: Ejecutar los Seeders (Datos Iniciales)

Para crear usuarios de prueba y datos iniciales:

```bash
php artisan db:seed
```

O ejecutar seeders específicos:

```bash
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=InstructorLiderSeeder
```

---

### Paso 10: Compilar Assets Frontend

Para compilar los archivos CSS y JavaScript:

```bash
npm run build
```

O si quieres compilar en modo desarrollo (con recarga automática):

```bash
npm run dev
```

---

### Paso 11: Iniciar el Servidor

#### Opción A: Usando el archivo start.bat (Recomendado)

Simplemente ejecuta:

```bash
start.bat
```

Esto iniciará automáticamente:
- Laravel en: `http://localhost:8000`
- Vite en: `http://localhost:5173`

#### Opción B: Manualmente

**Terminal 1 - Servidor Laravel:**
```bash
php artisan serve
```

**Terminal 2 - Servidor Vite:**
```bash
npm run dev
```

---

## ✅ Verificar que Todo Funciona

1. **Abre tu navegador** y ve a: `http://localhost:8000`

2. **Deberías ver** la página de inicio o el login del sistema

3. **Prueba el login** con las credenciales:
   - Email: `admin@admin.com`
   - Contraseña: `fabio123`

---

## 🔧 Solución de Problemas Comunes

### Error: "Composer no se reconoce como comando"

**Solución:**
- Usa `php composer.phar` en lugar de `composer`
- O agrega Composer al PATH de Windows

### Error: "Vite manifest not found"

**Solución:**
```bash
npm run build
```

### Error: "SQLSTATE[HY000] [1045] Access denied"

**Solución:**
- Verifica las credenciales en `.env`
- Asegúrate de que MySQL esté corriendo en XAMPP
- Verifica que el usuario y contraseña sean correctos

### Error: "SQLSTATE[HY000] [1049] Unknown database 'sena_db'"

**Solución:**
- Crea la base de datos `sena_db` en phpMyAdmin
- O cambia el nombre en `.env` si usas otra base de datos

### Error: "Class 'PDO' not found"

**Solución:**
- En XAMPP, edita `php.ini`
- Descomenta la línea: `extension=pdo_mysql`
- Reinicia Apache

### Error: "npm no se reconoce como comando"

**Solución:**
- Instala Node.js desde https://nodejs.org/
- Reinicia PowerShell/CMD después de instalar
- Verifica con `node --version` y `npm --version`

---

## 📝 Resumen de Comandos

```bash
# 1. Clonar repositorio
cd C:\xampp\htdocs
git clone https://github.com/fabioulloa06/SistemaGestionAcademicaSena.git
cd SistemaGestionAcademicaSena

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar .env
copy .env.example .env
# (Editar .env con tus credenciales de base de datos)

# 4. Generar clave
php artisan key:generate

# 5. Crear base de datos (en phpMyAdmin o MySQL)
# Base de datos: sena_db

# 6. Importar SQL o ejecutar migraciones
php artisan migrate
php artisan db:seed

# 7. Compilar assets
npm run build

# 8. Iniciar servidores
start.bat
# O manualmente:
# php artisan serve (en una terminal)
# npm run dev (en otra terminal)
```

---

## 🎯 Checklist de Instalación

Antes de empezar a trabajar, verifica que tengas:

- [ ] XAMPP instalado y corriendo (Apache y MySQL)
- [ ] Composer instalado (`composer --version`)
- [ ] Node.js y npm instalados (`node --version`, `npm --version`)
- [ ] Git instalado (`git --version`)
- [ ] Repositorio clonado
- [ ] Dependencias instaladas (`composer install`, `npm install`)
- [ ] Archivo `.env` configurado
- [ ] Clave de aplicación generada (`php artisan key:generate`)
- [ ] Base de datos `sena_db` creada
- [ ] Migraciones ejecutadas o SQL importado
- [ ] Seeders ejecutados (usuarios iniciales)
- [ ] Assets compilados (`npm run build`)
- [ ] Servidor funcionando (`http://localhost:8000`)

---

## 📚 Recursos Adicionales

- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Vite](https://vitejs.dev/)
- [Documentación de Tailwind CSS](https://tailwindcss.com/docs)
- [Documentación de Composer](https://getcomposer.org/doc/)

---

**¡Listo! Ya puedes empezar a trabajar en el proyecto.** 🚀

*Última actualización: 2024*

