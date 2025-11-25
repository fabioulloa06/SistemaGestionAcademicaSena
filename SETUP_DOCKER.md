# Setup completo con Docker

## Paso 1: Editar archivo .env

Abre tu archivo `.env` y cambia estas líneas:

```env
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sena_db
DB_USERNAME=sena
DB_PASSWORD=secret
```

**Importante:** `DB_HOST=mysql` (nombre del contenedor, NO `127.0.0.1`)

## Paso 2: Iniciar los contenedores

Ejecuta el script:

```bash
start_docker.bat
```

O manualmente:

```bash
docker compose up -d --build
```

Esto iniciará todos los servicios:
- ✅ MySQL (puerto 3307 en tu máquina)
- ✅ PHP-FPM
- ✅ Nginx (puerto 8080)
- ✅ Vite (puerto 5173)
- ✅ Queue Worker
- ✅ Scheduler

## Paso 3: Instalar dependencias (primera vez)

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
```

## Paso 4: Ejecutar migraciones y seeders

```bash
docker compose exec app php artisan migrate:fresh --seed
```

## Paso 5: Acceder a la aplicación

Abre tu navegador en:
- **Aplicación:** http://localhost:8080
- **Vite HMR:** http://localhost:5173

## Credenciales de acceso

- **Email:** `instructor@sena.edu.co`
- **Password:** `password123`

---

## Comandos útiles

### Ver logs en tiempo real
```bash
docker compose logs -f
```

### Ver logs de un servicio específico
```bash
docker compose logs -f app
docker compose logs -f mysql
docker compose logs -f vite
```

### Ejecutar comandos artisan
```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
```

### Acceder al contenedor
```bash
docker compose exec app bash
```

### Reiniciar servicios
```bash
docker compose restart app
docker compose restart mysql
```

### Detener todo
```bash
docker compose down
```

### Detener y eliminar volúmenes (cuidado: borra la BD)
```bash
docker compose down -v
```

### Ver estado de contenedores
```bash
docker compose ps
```

---

## Solución de problemas

### Si la aplicación no carga
```bash
docker compose logs -f app
docker compose logs -f web
```

### Si hay error de permisos
```bash
docker compose exec app chmod -R 777 storage bootstrap/cache
```

### Si necesitas reconstruir las imágenes
```bash
docker compose down
docker compose up -d --build --force-recreate
```

### Acceder a MySQL desde tu máquina
- **Host:** localhost
- **Puerto:** 3307 (no 3306)
- **Usuario:** sena
- **Contraseña:** secret
- **Base de datos:** sena_db

Puedes usar phpMyAdmin, MySQL Workbench, o DBeaver con estos datos.

---

## Resumen de puertos

| Servicio | Puerto interno | Puerto externo |
|----------|---------------|----------------|
| Nginx (Web) | 80 | 8080 |
| Vite | 5173 | 5173 |
| MySQL | 3306 | 3307 |

---

## Workflow diario

```bash
# Iniciar
start_docker.bat

# Trabajar normalmente...
# La aplicación está en http://localhost:8080
# Vite hace hot reload automático

# Detener al terminar
docker compose down
```

