# 🚀 Opciones de Instalación - Sistema de Gestión Académica SENA

Este proyecto puede ejecutarse de **3 formas diferentes**. Elige la que mejor se adapte a tu entorno:

---

## ✅ Opción 1: SQLite (YA CONFIGURADO - RECOMENDADO PARA PRUEBAS)

**Estado actual:** ✅ **Ya está configurado y funcionando**

### Ventajas:
- ✅ No requiere Docker ni MySQL
- ✅ Más rápido para pruebas locales
- ✅ No necesita configuración adicional
- ✅ Funciona inmediatamente

### Cómo usar:
```powershell
# Ya está todo listo, solo inicia el servidor:
.\start-local.ps1
# O manualmente:
php artisan serve
```

### Acceso:
- **URL:** http://localhost:8000
- **Base de datos:** SQLite (`database/database.sqlite`)

---

## 🐳 Opción 2: Docker con Laravel Sail (MySQL)

**Recomendado si:** Quieres un entorno más similar a producción o necesitas MySQL específicamente.

### Requisitos:
- Docker Desktop instalado y ejecutándose
- Windows 10/11 con WSL2 (recomendado)

### Ventajas:
- ✅ Entorno aislado y reproducible
- ✅ MySQL 8.0 incluido
- ✅ Redis incluido
- ✅ Más similar a producción

### Cómo configurar:
```powershell
# Ejecuta el script de setup:
.\setup-docker-sail.ps1
```

El script:
1. Verifica que Docker esté instalado
2. Configura `.env` para MySQL
3. Inicia los contenedores
4. Ejecuta migraciones y seeders

### Acceso:
- **Aplicación:** http://localhost
- **MySQL:** localhost:3306
- **Redis:** localhost:6379

### Comandos útiles:
```powershell
# Ver logs
docker-compose logs -f

# Detener contenedores
docker-compose down

# Reiniciar
docker-compose restart

# Ejecutar comandos artisan
docker-compose exec laravel.test php artisan migrate
```

---

## 💻 Opción 3: XAMPP (MySQL Local)

**Recomendado si:** Ya tienes XAMPP instalado y prefieres MySQL local.

### Requisitos:
- XAMPP instalado
- MySQL corriendo en XAMPP

### Configuración manual:

1. **Editar `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sena_db
DB_USERNAME=root
DB_PASSWORD=
```

2. **Crear base de datos:**
```sql
CREATE DATABASE sena_db;
```

3. **Ejecutar migraciones:**
```powershell
php artisan migrate --seed
```

4. **Iniciar servidor:**
```powershell
php artisan serve
```

---

## 📊 Comparación Rápida

| Característica | SQLite (Actual) | Docker | XAMPP |
|----------------|-----------------|--------|-------|
| **Configuración** | ✅ Ya lista | ⚙️ Requiere setup | ⚙️ Requiere setup |
| **Velocidad** | ⚡ Muy rápida | 🐢 Más lenta | ⚡ Rápida |
| **MySQL** | ❌ No | ✅ Sí | ✅ Sí |
| **Aislamiento** | ❌ No | ✅ Sí | ❌ No |
| **Requisitos** | Mínimos | Docker Desktop | XAMPP |
| **Recomendado para** | Pruebas rápidas | Desarrollo/Producción | Desarrollo local |

---

## 🎯 ¿Cuál elegir?

### Usa **SQLite (Opción 1)** si:
- ✅ Solo quieres probar el sistema rápidamente
- ✅ No necesitas características específicas de MySQL
- ✅ Quieres la configuración más simple

### Usa **Docker (Opción 2)** si:
- ✅ Quieres un entorno más similar a producción
- ✅ Necesitas MySQL específicamente
- ✅ Trabajas en equipo y quieres consistencia
- ✅ Ya tienes Docker instalado

### Usa **XAMPP (Opción 3)** si:
- ✅ Ya tienes XAMPP instalado
- ✅ Prefieres MySQL local sin Docker
- ✅ Estás familiarizado con XAMPP

---

## 🔄 Cambiar entre opciones

### De SQLite a Docker:
```powershell
.\setup-docker-sail.ps1
```

### De Docker a SQLite:
1. Detener Docker: `docker-compose down`
2. Editar `.env`:
```env
DB_CONNECTION=sqlite
# Comentar líneas de MySQL
```
3. Eliminar `database/database.sqlite` si existe
4. Ejecutar: `php artisan migrate:fresh --seed`

---

## 📝 Notas Importantes

- **Credenciales de prueba** son las mismas en todas las opciones
- **Los datos** se mantienen independientes entre opciones
- **SQLite** es perfecto para desarrollo y pruebas rápidas
- **Docker** es mejor para entornos de producción o cuando necesitas MySQL

---

## 🆘 Solución de Problemas

### Docker no inicia:
- Verifica que Docker Desktop esté ejecutándose
- Asegúrate de tener WSL2 habilitado (Windows)

### Error de conexión a BD:
- Verifica las credenciales en `.env`
- Asegúrate de que MySQL esté corriendo (XAMPP/Docker)

### Puerto ocupado:
- Cambia el puerto en `.env`: `APP_PORT=8001`
- O detén otros servicios que usen el puerto 8000

---

**¿Dudas?** Revisa la documentación en la carpeta raíz del proyecto.


