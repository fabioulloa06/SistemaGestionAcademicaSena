# Sistema de Gestión Académica SENA

Sistema web desarrollado con Laravel para gestionar las asistencias de los aprendices y realizar llamados de atención conforme a la normatividad vigente del SENA.

## 🚀 Guía de Instalación y Configuración

### Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **PHP** >= 8.2
- **Composer** (gestor de dependencias de PHP)
- **MySQL/MariaDB** >= 8.0
- **XAMPP** (recomendado) o servidor web con MySQL
- **Git**

---

## 📥 Paso 1: Clonar el Repositorio

1. Abre tu terminal (PowerShell, CMD o Git Bash)

2. Navega a la carpeta donde quieres guardar el proyecto:
   ```bash
   cd C:\xampp\htdocs
   ```

3. Clona el repositorio:
   ```bash
   git clone https://github.com/fabioulloa06/SistemaGestionAcademicaSena.git
   ```

4. Entra a la carpeta del proyecto:
   ```bash
   cd SistemaGestionAcademicaSena
   ```

5. Cambia a la rama de desarrollo:
   ```bash
   git checkout desarrollo
   ```

---

## 🗄️ Paso 2: Crear la Base de Datos en MySQL

### Opción A: Usando phpMyAdmin (Recomendado)

1. Inicia XAMPP y asegúrate de que **Apache** y **MySQL** estén corriendo (verde)

2. Abre phpMyAdmin: http://localhost/phpmyadmin

3. Haz clic en la pestaña **"SQL"** en la parte superior

4. Copia y pega el contenido completo del archivo `database/sql/sena_database.sql`

5. Haz clic en **"Continuar"** o presiona **Ctrl+Enter**

6. Verifica que se hayan creado:
   - ✅ 24 tablas
   - ✅ 8 vistas
   - ✅ 2 procedimientos almacenados

### Opción B: Usando la Línea de Comandos

1. Abre PowerShell o CMD

2. Ejecuta:
   ```bash
   cd C:\xampp\htdocs\SistemaGestionAcademicaSena\database\sql
   type sena_database.sql | C:\xampp\mysql\bin\mysql.exe -u root
   ```

---

## ⚙️ Paso 3: Configurar el Archivo .env

1. En la raíz del proyecto, copia el archivo `.env.example` y renómbralo a `.env`:
   ```bash
   copy .env.example .env
   ```

2. Abre el archivo `.env` con tu editor de código (VS Code, Notepad++, etc.)

3. Configura las siguientes variables:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sena_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   **Nota:** Si tu MySQL tiene contraseña, ponla en `DB_PASSWORD`

4. Genera la clave de aplicación de Laravel:
   ```bash
   php artisan key:generate
   ```

---

## 📦 Paso 4: Instalar Dependencias

1. Si tienes Composer instalado globalmente:
   ```bash
   composer install
   ```

2. Si NO tienes Composer instalado, usa el que viene en el proyecto:
   ```bash
   php composer.phar install
   ```

---

## ✅ Paso 5: Verificar la Instalación

1. Limpia la caché de configuración:
   ```bash
   php artisan config:clear
   ```

2. Verifica la conexión a la base de datos:
   ```bash
   php artisan migrate:status
   ```

   Si ves un error, revisa la configuración del `.env` y asegúrate de que MySQL esté corriendo.

3. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

4. Abre tu navegador y ve a: **http://localhost:8000**

   Si ves la página de bienvenida de Laravel, ¡todo está funcionando correctamente! 🎉

---

## 🔄 Flujo de Trabajo con Git

### 📋 Resumen del Flujo

1. **Crear una rama nueva** para tu cambio
2. **Desarrollar** tu funcionalidad
3. **Hacer commit y push** de tu rama
4. **Cambiar a desarrollo** y hacer merge de tu rama
5. **Crear un Pull Request (PR)** para mergear tu rama a master

---

## 📝 Paso a Paso: Implementar un Cambio

### Paso 1: Crear una Nueva Rama

Antes de hacer cualquier cambio, SIEMPRE crea una rama nueva:

1. Asegúrate de estar en la rama `desarrollo` y actualizada:
   ```bash
   git checkout desarrollo
   git pull origin desarrollo
   ```

2. Crea una nueva rama con un nombre descriptivo:
   ```bash
   git checkout -b nombre-del-cambio
   ```

   **Ejemplos de buenos nombres:**
   - `feature/login-usuario`
   - `feature/registro-asistencias`
   - `fix/error-calificaciones`
   - `feat/menu-dashboard`

   **Importante:** Usa nombres en minúsculas y separados por guiones.

---

### Paso 2: Desarrollar tu Funcionalidad

1. Haz tus cambios en los archivos necesarios

2. Verifica que todo funcione correctamente

3. Cuando termines, revisa qué archivos has modificado:
   ```bash
   git status
   ```

---

### Paso 3: Hacer Commit y Push de tu Rama

1. Agrega los archivos modificados:
   ```bash
   git add .
   ```

   O si solo quieres agregar archivos específicos:
   ```bash
   git add ruta/del/archivo.php
   ```

2. Haz commit con un mensaje descriptivo:
   ```bash
   git commit -m "Descripción clara del cambio realizado"
   ```

   **Ejemplos de buenos mensajes:**
   - `"Agregar funcionalidad de login de usuarios"`
   - `"Implementar registro de asistencias"`
   - `"Corregir error en cálculo de calificaciones"`

3. Sube tu rama al repositorio remoto:
   ```bash
   git push origin nombre-de-tu-rama
   ```

---

### Paso 4: Mergear a Desarrollo

1. Cambia a la rama `desarrollo`:
   ```bash
   git checkout desarrollo
   ```

2. Actualiza `desarrollo` con los últimos cambios:
   ```bash
   git pull origin desarrollo
   ```

3. Mergea tu rama en `desarrollo`:
   ```bash
   git merge nombre-de-tu-rama
   ```

4. Resuelve conflictos si los hay (si Git te lo pide)

5. Sube los cambios a `desarrollo`:
   ```bash
   git push origin desarrollo
   ```

---

### Paso 5: Crear Pull Request (PR) a Master

1. Ve a GitHub: https://github.com/fabioulloa06/SistemaGestionAcademicaSena

2. Haz clic en **"Pull requests"** en la parte superior

3. Haz clic en **"New pull request"**

4. En la página del PR:
   - **Base:** selecciona `master` (la rama a la que quieres mergear)
   - **Compare:** selecciona `nombre-de-tu-rama` (tu rama)

5. Llena la información del PR:
   - **Título:** Descripción breve del cambio
   - **Descripción:** Explica qué hace tu cambio, cómo lo probaste, etc.

   **Ejemplo de descripción:**
   ```markdown
   ## ¿Qué hace este cambio?
   Implementa el sistema de login de usuarios con autenticación.

   ## ¿Cómo se probó?
   - Se probó el login con usuario válido ✓
   - Se probó con credenciales incorrectas ✓
   - Se verificó la redirección después del login ✓

   ## Capturas de pantalla
   [Si aplica, incluye imágenes]
   ```

6. Haz clic en **"Create pull request"**

7. Espera a que otro compañero revise tu código

8. Una vez aprobado, el líder del proyecto hará el merge a `master`

---

## 🔍 Comandos Git Útiles

### Ver el estado actual
```bash
git status
```

### Ver qué rama estás usando
```bash
git branch
```

### Ver todas las ramas (locales y remotas)
```bash
git branch -a
```

### Actualizar una rama
```bash
git pull origin nombre-rama
```

### Ver los commits recientes
```bash
git log --oneline -10
```

### Descartar cambios locales no guardados
```bash
git restore nombre-archivo.php
```

### Ver diferencias antes de hacer commit
```bash
git diff
```

---

## 🚨 Solución de Problemas Comunes

### Error: "Unknown database 'sena_db'"
- **Solución:** Ejecuta el script SQL `database/sql/sena_database.sql` en phpMyAdmin

### Error: "No such file or directory .env"
- **Solución:** Copia `.env.example` a `.env` y configura las variables

### Error: "Connection refused" o "Can't connect to MySQL"
- **Solución:** Asegúrate de que MySQL esté corriendo en XAMPP

### Error al hacer merge: "Merge conflict"
- **Solución:** 
  1. Abre los archivos con conflictos
  2. Busca las líneas marcadas con `<<<<<<<`, `=======`, `>>>>>>>`
  3. Decide qué código mantener
  4. Elimina las marcas de conflicto
  5. Haz `git add .` y `git commit`

### Quiero descartar todos mis cambios y empezar de nuevo
```bash
git checkout desarrollo
git pull origin desarrollo
git branch -D nombre-de-tu-rama
```

---

## 📚 Estructura del Proyecto

```
SistemaGestionAcademicaSena/
├── app/                    # Lógica de la aplicación
│   ├── Http/
│   │   └── Controllers/    # Controladores
│   └── Models/             # Modelos de la base de datos
├── config/                 # Archivos de configuración
├── database/
│   ├── migrations/         # Migraciones de Laravel
│   ├── seeders/           # Seeders de datos
│   └── sql/
│       └── sena_database.sql  # Script SQL completo
├── public/                 # Archivos públicos (punto de entrada)
├── resources/
│   ├── views/             # Vistas Blade
│   ├── css/               # Estilos CSS
│   └── js/                # JavaScript
├── routes/                 # Rutas de la aplicación
├── storage/                # Archivos almacenados
└── tests/                  # Pruebas automatizadas
```

---

## 👥 Equipo de Desarrollo

- **Fabio Ulloa** - Líder del Proyecto
- **Isabella**
- **Jhaniliz**
- **JJ Tovar**

---

## 📞 Contacto y Soporte

Si tienes problemas o dudas:
1. Revisa esta documentación
2. Consulta con el equipo
3. Busca en los issues de GitHub

---

## 📝 Notas Importantes

- ⚠️ **NUNCA** hagas commit directamente a `master` o `desarrollo`
- ⚠️ **SIEMPRE** crea una rama nueva para tus cambios
- ✅ **SIEMPRE** prueba tu código antes de hacer commit
- ✅ **SIEMPRE** escribe mensajes de commit descriptivos
- ✅ **SIEMPRE** actualiza `desarrollo` antes de crear una nueva rama

---

## 🎯 Checklist Antes de Crear un PR

- [ ] Mi código funciona correctamente
- [ ] No hay errores de sintaxis
- [ ] He probado la funcionalidad manualmente
- [ ] He actualizado `desarrollo` y mergeado mi rama
- [ ] He hecho push de mi rama
- [ ] He creado el Pull Request con una descripción clara
- [ ] He revisado que no haya conflictos

---

## 📌 Versión

- **Laravel:** 12.38.1
- **PHP:** >= 8.2
- **MySQL:** >= 8.0

---

¡Éxito en el desarrollo! 🚀
