# 🎓 Sistema de Gestión Académica SENA

Sistema completo de gestión académica para el SENA, incluyendo control de asistencias, calificaciones, procesos disciplinarios y procedimientos administrativos según el Acuerdo 009 de 2024.

---

## 🚀 Inicio Rápido

### ¿Cómo quieres ejecutarlo?

#### 🐳 Con Docker (Recomendado - Sin XAMPP)
```powershell
.\setup-docker-sail.bat
```
Ver: `INSTALACION_SIN_XAMPP.md`

#### 💻 Con XAMPP (Tradicional)
```powershell
.\setup-local.bat
```
Ver: `README_INSTALACION.md`

#### ⚡ Con PHP Built-in + SQLite (Más Simple)
```powershell
.\setup-php-sqlite.bat
```
Ver: `INSTALACION_SIN_XAMPP.md`

---

## 📚 Documentación

- **`QUICK_START.md`** - Inicio rápido en 5 minutos
- **`README_INSTALACION.md`** - Guía completa de instalación con XAMPP
- **`INSTALACION_SIN_XAMPP.md`** - Guía para ejecutar sin XAMPP (Docker, SQLite)
- **`PARA_MIS_COMPANEROS.md`** - Guía para el equipo de pruebas
- **`GUIA_DESPLIEGUE_INFINITY_FREE.md`** - Cómo desplegar en Infinity Free
- **`SOLUCION_ERROR_DB.md`** - Solución de problemas de base de datos
- **`SISTEMA_CORREOS.md`** - Documentación del sistema de correos

---

## 🔑 Credenciales de Prueba

| Rol | Email | Password |
|-----|-------|----------|
| 👑 Admin | `admin@sena.edu.co` | `password123` |
| 👤 Coordinador | `coordinador@sena.edu.co` | `password123` |
| 👨‍🏫 Instructor | `instructor@sena.edu.co` | `password123` |
| 👨‍🎓 Estudiante | `estudiante@sena.edu.co` | `password123` |

---

## ✨ Características Principales

- ✅ **Gestión de Asistencias** - Registro de inasistencias con notificaciones automáticas
- ✅ **Sistema Disciplinario** - Llamados de atención y procedimientos administrativos
- ✅ **Gestión Académica** - Programas, competencias, resultados de aprendizaje
- ✅ **Reportes y Estadísticas** - Dashboard con gráficos y métricas
- ✅ **Notificaciones por Email** - Alertas automáticas según reglamento SENA
- ✅ **Procedimientos Administrativos** - Flujo completo según Acuerdo 009 de 2024

---

## 🛠️ Tecnologías

- **Backend:** Laravel 12
- **Frontend:** Blade + Tailwind CSS
- **Base de Datos:** MySQL / SQLite
- **Docker:** Laravel Sail
- **Gráficos:** Chart.js

---

## 📋 Requisitos

- PHP 8.2+
- Composer
- MySQL 8.0+ (o SQLite)
- Node.js 18+ (opcional, para assets)

---

## 🐛 Solución de Problemas

- **Error de conexión a BD:** Ver `SOLUCION_ERROR_DB.md`
- **Problemas con Docker:** Ver `INSTALACION_SIN_XAMPP.md`
- **Emails no se envían:** Ver `SISTEMA_CORREOS.md`

---

## 📞 Soporte

Para problemas o dudas, revisa la documentación en la carpeta raíz del proyecto.

---

**Desarrollado para el SENA** 🎓

