# Configuración para Docker

## Problema: "No se puede establecer una conexión"

Este error ocurre porque Laravel intenta conectarse a MySQL pero el servidor no está corriendo.

## Soluciones

### Opción 1: Usar XAMPP (Método actual)

1. Abre el **Panel de Control de XAMPP**
2. Haz clic en **Start** junto a **MySQL**
3. Espera a que el módulo MySQL aparezca en verde
4. Ejecuta `iniciar_sistema.bat`

**Ventaja:** No necesitas Docker  
**Desventaja:** Conflictos de puertos si Docker está corriendo

---

### Opción 2: Usar Docker (Recomendado)

Si tienes Docker Desktop abierto, es mejor usar los contenedores:

#### 1. Actualizar archivo `.env`

Abre tu archivo `.env` y cambia estas líneas:

```env
# Cambiar de:
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sena
DB_USERNAME=root
DB_PASSWORD=

# A:
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sena_db
DB_USERNAME=sena
DB_PASSWORD=secret

# También actualizar:
APP_URL=http://localhost:8080
```

#### 2. Iniciar los contenedores

Ejecuta `start_docker.bat` o manualmente:

```bash
docker compose up -d --build
```

Esto iniciará:
- **MySQL** en puerto 3307 (para evitar conflicto con XAMPP)
- **Nginx** en puerto 8080
- **PHP-FPM** para Laravel
- **Vite** en puerto 5173 para hot reload
- **Queue worker** para trabajos en segundo plano
- **Scheduler** para tareas programadas

#### 3. Configurar la base de datos (solo primera vez)

```bash
# Instalar dependencias
docker compose exec app composer install

# Generar key de aplicación
docker compose exec app php artisan key:generate

# Ejecutar migraciones
docker compose exec app php artisan migrate --seed
```

#### 4. Acceder a la aplicación

- **Aplicación:** http://localhost:8080
- **Vite HMR:** http://localhost:5173
- **MySQL:** localhost:3307 (desde tu máquina)

#### 5. Detener los contenedores

```bash
docker compose down
```

---

## Comandos útiles Docker

```bash
# Ver logs de todos los servicios
docker compose logs -f

# Ver logs de un servicio específico
docker compose logs -f app

# Ejecutar comandos artisan
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan cache:clear

# Acceder al contenedor
docker compose exec app bash

# Reiniciar un servicio
docker compose restart app

# Ver estado de contenedores
docker compose ps
```

---

## Comparación XAMPP vs Docker

| Característica | XAMPP | Docker |
|----------------|-------|--------|
| Facilidad de uso | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ |
| Configuración inicial | Rápida | Requiere setup |
| Aislamiento | No | Sí |
| Portabilidad | No | Sí |
| Producción similar | No | Sí |
| Múltiples proyectos | Conflictos | Sin conflictos |
| Queue workers | Manual | Automático |
| Scheduler | Manual | Automático |

---

## Recomendación

- **Para desarrollo rápido:** Usa XAMPP
- **Para trabajo profesional:** Usa Docker
- **Si Docker está abierto:** Usa Docker (para evitar conflictos de puertos)

---

## Solución rápida al error actual

**Si quieres seguir con XAMPP:**
1. Cierra Docker Desktop
2. Abre XAMPP Control Panel
3. Inicia Apache y MySQL
4. Ejecuta `iniciar_sistema.bat`

**Si quieres cambiar a Docker:**
1. Edita `.env` según la sección "Opción 2" arriba
2. Ejecuta `start_docker.bat`
3. Ejecuta las migraciones: `docker compose exec app php artisan migrate --seed`
4. Accede a http://localhost:8080

