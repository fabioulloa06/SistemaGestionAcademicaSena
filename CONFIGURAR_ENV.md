# Configurar archivo .env

## Problema actual
El error indica que Laravel está intentando usar SQLite pero necesitas MySQL.

## Solución

Abre tu archivo `.env` y asegúrate de tener estas líneas:

### Para XAMPP (MySQL local):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sena_db
DB_USERNAME=root
DB_PASSWORD=
```

### Para Docker:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sena_db
DB_USERNAME=sena
DB_PASSWORD=secret
```

## Pasos completos:

### 1. Editar .env

Abre `C:\xampp\htdocs\Laravel\app-control-asistencias\.env`

Busca las líneas que empiezan con `DB_` y cámbialas por:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sena_db
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Crear la base de datos

**Opción A: Desde phpMyAdmin (XAMPP)**
1. Abre http://localhost/phpmyadmin
2. Clic en "Nueva" (New)
3. Nombre: `sena_db`
4. Cotejamiento: `utf8mb4_unicode_ci`
5. Clic en "Crear"

**Opción B: Desde línea de comandos**
```bash
mysql -u root -e "CREATE DATABASE sena_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 3. Limpiar caché de configuración

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Ejecutar migraciones

```bash
php artisan migrate --seed
```

### 5. Iniciar la aplicación

```bash
iniciar_sistema.bat
```

## Verificar que MySQL esté corriendo

Antes de todo, asegúrate de que MySQL esté activo en XAMPP:

1. Abre **XAMPP Control Panel**
2. Verifica que **MySQL** tenga el botón verde "Running"
3. Si no está corriendo, haz clic en **Start**

## Credenciales de acceso después de migrar

- **Email:** `instructor@sena.edu.co`
- **Password:** `password123`

---

## Resumen rápido

```bash
# 1. Edita .env y cambia DB_CONNECTION a mysql
# 2. Asegúrate de que MySQL esté corriendo en XAMPP
# 3. Ejecuta:
php artisan config:clear
php artisan migrate:fresh --seed
# 4. Inicia la app:
iniciar_sistema.bat
```

