# 🚀 Guía de Inicio Local - Sistema de Gestión Académica SENA

Esta guía te ayudará a configurar y ejecutar el proyecto en tu máquina local de forma rápida y sencilla.

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

1. **Docker Desktop** - [Descargar aquí](https://www.docker.com/products/docker-desktop/)
   - Versión 4.0 o superior
   - Debe estar ejecutándose antes de iniciar el proyecto

2. **Node.js y npm** - [Descargar aquí](https://nodejs.org/)
   - Versión 18 o superior
   - Se instala automáticamente con Node.js

3. **Git** - [Descargar aquí](https://git-scm.com/downloads)
   - Para clonar el repositorio

## 🎯 Inicio Rápido (Automático)

### Opción 1: Usando el Script Automático (Recomendado) ⚡

1. **Clonar el repositorio:**
   ```bash
   git clone <url-del-repositorio>
   cd SistemaGestionAcademicaSena
   ```

2. **Copiar el archivo de configuración:**
   ```bash
   copy .env.example .env
   ```
   O en PowerShell:
   ```powershell
   Copy-Item .env.example .env
   ```

3. **Abrir Docker Desktop** y asegurarte de que esté ejecutándose.

4. **Ejecutar el script de inicio:**
   ```bash
   inicio-local.bat
   ```

   El script hará automáticamente:
   - ✅ Verificar que Docker esté corriendo
   - ✅ Instalar dependencias de Composer (si es necesario)
   - ✅ Iniciar los contenedores de Docker
   - ✅ Ejecutar migraciones de base de datos
   - ✅ Instalar dependencias de Node.js
   - ✅ Iniciar Vite en modo desarrollo
   - ✅ Mostrar las URLs de acceso

5. **Acceder a la aplicación:**
   - **Aplicación:** http://localhost
   - **Vite Dev Server:** http://localhost:5173

---

## 🔧 Inicio Manual (Paso a Paso)

Si prefieres hacerlo manualmente o el script automático no funciona:

### Paso 1: Configurar Variables de Entorno

1. Copia el archivo `.env.example` a `.env`:
   ```bash
   copy .env.example .env
   ```

2. Edita el archivo `.env` y verifica estas configuraciones:
   ```env
   APP_NAME="Sistema Gestión Académica SENA"
   APP_URL=http://localhost
   
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=sena_db
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```

### Paso 2: Instalar Dependencias de Composer

```bash
composer install
```

Si no tienes Composer instalado globalmente, puedes usar el que viene con Laravel Sail:
```bash
docker run --rm -v "%cd%:/app" composer install
```

### Paso 3: Iniciar Contenedores Docker

```bash
docker-compose up -d
```

O usando Laravel Sail:
```bash
vendor\bin\sail up -d
```

### Paso 4: Generar Key de Aplicación

```bash
docker-compose exec laravel.test php artisan key:generate
```

O con Sail:
```bash
vendor\bin\sail artisan key:generate
```

### Paso 5: Ejecutar Migraciones

```bash
docker-compose exec laravel.test php artisan migrate
```

O con Sail:
```bash
vendor\bin\sail artisan migrate
```

### Paso 6: Instalar Dependencias de Node.js

```bash
docker-compose exec laravel.test npm install
```

O con Sail:
```bash
vendor\bin\sail npm install
```

### Paso 7: Compilar Assets (Producción) o Iniciar Vite (Desarrollo)

**Para desarrollo (recomendado):**
```bash
docker-compose exec laravel.test npm run dev
```

O con Sail:
```bash
vendor\bin\sail npm run dev
```

**Para producción:**
```bash
docker-compose exec laravel.test npm run build
```

O con Sail:
```bash
vendor\bin\sail npm run build
```

---

## 🛠️ Comandos Útiles

### Detener los Contenedores
```bash
docker-compose down
```

O con Sail:
```bash
vendor\bin\sail down
```

### Ver Logs
```bash
docker-compose logs -f
```

O con Sail:
```bash
vendor\bin\sail logs
```

### Acceder al Contenedor
```bash
docker-compose exec laravel.test bash
```

O con Sail:
```bash
vendor\bin\sail shell
```

### Limpiar Caché
```bash
docker-compose exec laravel.test php artisan cache:clear
docker-compose exec laravel.test php artisan config:clear
docker-compose exec laravel.test php artisan view:clear
```

O con Sail:
```bash
vendor\bin\sail artisan cache:clear
vendor\bin\sail artisan config:clear
vendor\bin\sail artisan view:clear
```

### Ejecutar Migraciones Fresh (Reiniciar BD)
```bash
docker-compose exec laravel.test php artisan migrate:fresh --seed
```

O con Sail:
```bash
vendor\bin\sail artisan migrate:fresh --seed
```

---

## 🐛 Solución de Problemas

### Error: "Docker is not running"
- Abre Docker Desktop y espera a que termine de iniciar
- Verifica que Docker Desktop esté ejecutándose en segundo plano

### Error: "Port already in use"
- Verifica que los puertos 80, 3306, 6379 y 5173 no estén en uso
- Puedes cambiar los puertos en el archivo `.env`:
  ```env
  APP_PORT=8080
  FORWARD_DB_PORT=3307
  FORWARD_REDIS_PORT=6380
  VITE_PORT=5174
  ```

### Error: "Connection refused" en la base de datos
- Espera unos segundos después de iniciar los contenedores
- Verifica que el contenedor `mysql` esté corriendo:
  ```bash
  docker-compose ps
  ```

### Error: `/usr/bin/env: 'bash\r': No such file or directory`
Este es un problema común en Windows con Laravel Sail relacionado con line endings.

**Solución Recomendada:** Usa XAMPP en lugar de Docker:
1. Sigue la guía en `GUIA_INSTALACION.md`
2. Usa `start.bat` para iniciar el servidor
3. Accede a `http://localhost:8000`

**Otras soluciones:**
- Revisa `SOLUCION_DOCKER_ERROR.md` para más opciones
- Configura Git: `git config core.autocrlf false`

### Error: "npm: command not found"
- Asegúrate de tener Node.js instalado
- O usa los comandos dentro del contenedor Docker

### Los cambios en el código no se reflejan
- Limpia la caché de Laravel (ver comandos útiles)
- Reinicia los contenedores:
  ```bash
  docker-compose restart
  ```

---

## 📝 Notas Importantes

1. **Primera vez:** La primera vez que ejecutes el proyecto, puede tardar varios minutos mientras Docker descarga las imágenes y configura todo.

2. **Docker Desktop:** Debe estar ejecutándose siempre que quieras usar el proyecto.

3. **Vite en Desarrollo:** Si usas `npm run dev`, Vite se ejecutará en modo watch y recargará automáticamente los cambios en los assets.

4. **Base de Datos:** Los datos se guardan en volúmenes de Docker, por lo que persistirán aunque detengas los contenedores.

5. **Puertos:** Si tienes conflictos de puertos, puedes cambiarlos en el archivo `.env`.

---

## 🎓 Usuarios de Prueba

Una vez que el proyecto esté corriendo, puedes usar estos usuarios para probar:

- **Admin:**
  - Email: `admin@test.com`
  - Password: `password`

- **Coordinador:**
  - Email: `coordinator@test.com`
  - Password: `password`

- **Instructor:**
  - Email: `instructor@test.com`
  - Password: `password`

- **Estudiante:**
  - Email: `student@test.com`
  - Password: `password`

---

## 📞 Soporte

Si tienes problemas, revisa:
1. Los logs de Docker: `docker-compose logs`
2. La documentación de Laravel: https://laravel.com/docs
3. La documentación de Docker: https://docs.docker.com/

---

**¡Listo! Ya puedes empezar a desarrollar.** 🚀

