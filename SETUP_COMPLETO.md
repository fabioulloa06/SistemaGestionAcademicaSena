# ✅ Setup Docker Completado

## 🎉 ¡Listo! Tu aplicación está corriendo en Docker

### 📍 Acceder a la aplicación

Abre tu navegador en: **http://localhost:8080**

### 🔑 Credenciales de acceso

- **Email:** `instructor@sena.edu.co`
- **Password:** `password123`
- **Rol:** Instructor

---

## 📊 Estado de los servicios

```bash
docker compose ps
```

Servicios corriendo:
- ✅ **MySQL** (puerto 3307)
- ✅ **PHP-FPM** (app)
- ✅ **Nginx** (puerto 8080)
- ✅ **Queue Worker**
- ✅ **Scheduler**
- ⚠️ **Vite** (opcional - usa assets compilados)

---

## 🛠️ Comandos útiles

### Ver logs
```bash
docker compose logs -f app
docker compose logs -f mysql
```

### Ejecutar comandos artisan
```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
```

### Detener servicios
```bash
docker compose down
```

### Reiniciar servicios
```bash
docker compose restart app
docker compose restart mysql
```

---

## 🔧 Si necesitas recompilar assets

Desde tu máquina (no en Docker):
```bash
npm run build
```

---

## 📝 Resumen de lo configurado

1. ✅ Docker Compose con todos los servicios
2. ✅ Base de datos MySQL configurada
3. ✅ Usuario `sena` creado con permisos
4. ✅ Base de datos `sena_db` creada
5. ✅ Migraciones ejecutadas
6. ✅ Seeder ejecutado (usuario instructor creado)
7. ✅ Modelo User actualizado para tabla `users`
8. ✅ Assets compilados con Tailwind

---

## 🚀 Workflow diario

```bash
# Iniciar todo
start_docker.bat

# Trabajar normalmente en http://localhost:8080

# Detener al terminar
docker compose down
```

---

## 🐛 Solución de problemas

### La aplicación no carga
```bash
docker compose logs -f web
docker compose logs -f app
```

### Error de base de datos
```bash
docker compose exec app php artisan config:clear
docker compose restart app
```

### Cambios en .env no se reflejan
```bash
docker compose restart app queue scheduler
```

---

## 📚 Archivos importantes

- `docker-compose.yml` - Configuración de servicios
- `docker/Dockerfile` - Imagen PHP personalizada
- `docker/nginx/default.conf` - Configuración Nginx
- `.env` - Variables de entorno (DB_HOST=mysql)
- `start_docker.bat` - Script para iniciar todo
- `configurar_env_docker.bat` - Script para configurar .env

---

## ✨ ¡Todo listo!

Tu aplicación está corriendo en Docker. Accede a:

**http://localhost:8080**

Usuario: `instructor@sena.edu.co`  
Contraseña: `password123`

