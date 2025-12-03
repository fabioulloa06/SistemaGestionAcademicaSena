# 🚀 Inicio Rápido - Sistema de Gestión Académica SENA

## ✅ Todo está configurado y listo

No necesitas activar nada adicional. El proyecto usa SQLite, así que **NO necesitas**:
- ❌ XAMPP MySQL
- ❌ Docker
- ❌ Configuración adicional

---

## 🎯 Cómo iniciar el proyecto

### Opción 1: Script Automático (Más fácil)

**En PowerShell:**
```powershell
.\start-local.ps1
```

**O en CMD:**
```cmd
start-local.bat
```

Este script:
- ✅ Verifica que todo esté configurado
- ✅ Inicia el servidor Laravel (puerto 8000)
- ✅ Inicia Vite para assets (puerto 5173)
- ✅ Abre el navegador automáticamente

---

### Opción 2: Manual (Paso a paso)

**Paso 1:** Abre una terminal PowerShell o CMD en la carpeta del proyecto:
```
C:\xampp\htdocs\SistemaGestionSena
```

**Paso 2:** Inicia el servidor Laravel:
```powershell
php artisan serve
```

Verás algo como:
```
INFO  Server running on [http://127.0.0.1:8000]
```

**Paso 3:** Abre tu navegador y ve a:
```
http://localhost:8000
```

---

## 🔑 Credenciales para iniciar sesión

| Rol | Email | Password |
|-----|-------|----------|
| 👑 **Admin** | `admin@sena.edu.co` | `password123` |
| 👤 **Coordinador** | `coordinador@sena.edu.co` | `password123` |
| 👨‍🏫 **Instructor** | `instructor@sena.edu.co` | `password123` |
| 👨‍🎓 **Estudiante** | `estudiante@sena.edu.co` | `password123` |

---

## 📋 Checklist antes de iniciar

- ✅ PHP 8.2+ instalado (Ya lo tienes: PHP 8.2.12)
- ✅ Dependencias de Composer instaladas
- ✅ Base de datos SQLite creada
- ✅ Migraciones ejecutadas
- ✅ Datos de prueba cargados

**Todo está listo. Solo inicia el servidor.**

---

## 🛑 Cómo detener el servidor

Presiona `Ctrl + C` en la terminal donde está corriendo `php artisan serve`

---

## ❓ Problemas comunes

### "Puerto 8000 ya está en uso"
```powershell
# Usa otro puerto:
php artisan serve --port=8001
```

### "No se encuentra php"
- Asegúrate de que PHP esté en el PATH
- O usa la ruta completa: `C:\xampp\php\php.exe artisan serve`

### "Error de permisos"
- Ejecuta PowerShell como Administrador
- O verifica que tengas permisos de escritura en la carpeta

---

## 🎉 ¡Listo!

Una vez que el servidor esté corriendo, simplemente abre:
**http://localhost:8000**

Y usa cualquiera de las credenciales de arriba para iniciar sesión.


