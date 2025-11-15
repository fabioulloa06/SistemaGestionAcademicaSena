# Guía de Trabajo con Git
## Sistema de Gestión Académica SENA

Esta guía te explica paso a paso cómo trabajar con Git en este proyecto. **Sigue estos pasos exactamente** para mantener el código organizado.

---

## 📚 Contenido

1. [Configuración Inicial](#-configuración-inicial)
2. [Clonar el Repositorio](#-clonar-el-repositorio)
3. [Flujo de Trabajo Completo](#-flujo-de-trabajo-completo)
4. [Crear Pull Request (PR)](#-crear-pull-request-pr)

---

## ⚙️ Configuración Inicial

### Paso 1: Verificar que Git esté instalado

Abre PowerShell o CMD y ejecuta:

```bash
git --version
```

Si no está instalado, descárgalo desde: https://git-scm.com/downloads

### Paso 2: Configurar tu identidad (solo la primera vez)

```bash
# Configura tu nombre
git config --global user.name "Tu Nombre"

# Configura tu email (usa el mismo de tu cuenta de GitHub)
git config --global user.email "tu.email@ejemplo.com"
```

### Paso 3: Verificar la configuración

```bash
git config user.name
git config user.email
```

---

## 📥 Clonar el Repositorio

### Paso 1: Navegar a la carpeta donde quieres clonar

```bash
cd C:\xampp\htdocs
```

### Paso 2: Clonar el repositorio

```bash
git clone https://github.com/fabioulloa06/SistemaGestionAcademicaSena.git
```

### Paso 3: Entrar a la carpeta del proyecto

```bash
cd SistemaGestionAcademicaSena
```

### Paso 4: Verificar que todo esté bien

```bash
# Ver el estado del repositorio
git status

# Ver las ramas disponibles
git branch -a
```

Deberías ver que estás en la rama `master` y que hay una rama `desarrollo` también.

### ⚠️ IMPORTANTE: Después de Clonar

Una vez que hayas clonado el repositorio, **debes seguir la [Guía de Instalación](GUIA_INSTALACION.md)** para:

- Instalar las dependencias (Composer, npm)
- Configurar el archivo `.env`
- Crear la base de datos
- Ejecutar migraciones y seeders
- Compilar los assets frontend

**No podrás trabajar en el proyecto hasta completar la instalación.**

---

## 🔄 Flujo de Trabajo Completo

Este es el flujo que debes seguir **cada vez** que vayas a trabajar en el proyecto:

### Paso 1: Actualizar tu copia local de master

**⚠️ IMPORTANTE:** Siempre haz esto antes de empezar a trabajar.

```bash
# 1. Cambia a la rama master
git checkout master

# 2. Descarga los últimos cambios del repositorio remoto
git pull origin master

# 3. Verifica que estás actualizado
git status
```

**¿Por qué?** Esto asegura que tu código local esté sincronizado con el repositorio y evita conflictos.

---

### Paso 2: Crear tu rama de trabajo

Nunca trabajes directamente en `master` o `desarrollo`. Siempre crea una rama nueva:

```bash
# Crea y cambia a la nueva rama
git checkout -b feature/nombre-del-modulo
```

**Ejemplos de nombres de ramas:**
```bash
git checkout -b feature/gestion-usuarios
git checkout -b feature/sesiones-formacion
git checkout -b feature/registro-asistencias
git checkout -b fix/validacion-formulario
```

**Verificar que estás en tu rama:**
```bash
git branch
# La rama actual aparecerá con un asterisco (*)
```

---

### Paso 3: Trabajar en tu rama

Ahora puedes trabajar normalmente. Edita archivos, crea nuevos, modifica según tu módulo.

#### Ver qué has cambiado:

```bash
git status
```

#### Agregar tus cambios:

```bash
# Agregar todos los archivos modificados
git add .

# O agregar archivos específicos
git add app/Http/Controllers/MiController.php
```

**⚠️ Cuidado:** Revisa siempre con `git status` antes de hacer commit para asegurarte de no agregar archivos que no deberían estar (como `.env`).

#### Hacer commit (guardar tus cambios):

```bash
git commit -m "feat: implementa módulo de gestión de usuarios"
```

**Formato de mensajes de commit:**

| Tipo | Ejemplo |
|------|---------|
| `feat:` | Nueva funcionalidad: `feat: agrega CRUD de usuarios` |
| `fix:` | Corrección de bugs: `fix: corrige validación de email` |
| `refactor:` | Refactorización: `refactor: mejora estructura del controlador` |
| `docs:` | Documentación: `docs: actualiza README` |

**Ejemplos de buenos mensajes:**
```bash
git commit -m "feat: implementa CRUD completo de usuarios con validaciones"
git commit -m "fix: corrige error al guardar sesiones de formación"
```

**Ejemplos de malos mensajes (evítalos):**
```bash
# ❌ Malo: muy vago
git commit -m "cambios"

# ❌ Malo: sin prefijo
git commit -m "agregué usuarios"
```

---

### Paso 4: Subir tu rama al repositorio

Cuando termines tu trabajo (o quieras respaldarlo), sube tu rama:

```bash
# Si es la primera vez que subes esta rama
git push -u origin feature/nombre-del-modulo

# Después de la primera vez, solo necesitas:
git push
```

---

### Paso 5: Mergear con la rama desarrollo

Una vez que tu código esté listo y probado:

```bash
# 1. Cambia a la rama desarrollo
git checkout desarrollo

# 2. Actualiza desarrollo con los últimos cambios del remoto
git pull origin desarrollo

# 3. Mergea tu rama en desarrollo
git merge feature/nombre-del-modulo

# 4. Si todo salió bien, sube los cambios
git push origin desarrollo
```

**⚠️ Si hay conflictos durante el merge:**

Git te mostrará qué archivos tienen conflictos. Sigue estos pasos:

1. **Ver los archivos con conflictos:**
   ```bash
   git status
   ```

2. **Abre cada archivo con conflictos** y busca las marcas:
   ```
   <<<<<<< HEAD
   Código de la rama desarrollo
   =======
   Tu código de la rama feature
   >>>>>>> feature/nombre-del-modulo
   ```

3. **Resuelve los conflictos manualmente:**
   - Elimina las marcas `<<<<<<<`, `=======`, `>>>>>>>`
   - Decide qué código mantener o combina ambos
   - Guarda el archivo

4. **Marca los conflictos como resueltos:**
   ```bash
   git add archivo-resuelto.php
   ```

5. **Completa el merge:**
   ```bash
   git commit -m "merge: integra feature/nombre-del-modulo en desarrollo"
   ```

6. **Sube los cambios:**
   ```bash
   git push origin desarrollo
   ```

---

## 🔀 Crear Pull Request (PR) a master

Una vez que tu código esté en la rama `desarrollo` y probado, crea un Pull Request para mergearlo a `master`.

### Paso 1: Ir al repositorio en GitHub

Abre tu navegador y ve a:
```
https://github.com/fabioulloa06/SistemaGestionAcademicaSena
```

### Paso 2: Crear el Pull Request

1. **Haz clic en la pestaña "Pull requests"** (arriba del repositorio)

2. **Haz clic en "New pull request"** (botón verde)

3. **Selecciona las ramas:**
   - **Base:** `master` (hacia dónde quieres mergear)
   - **Compare:** `feature/tu-rama` (tu rama con los cambios)

4. **Completa el formulario del PR:**
   
   **Título:** Usa el mismo formato que los commits
   ```
   feat: Implementa módulo de gestión de usuarios
   ```
   
   **Descripción:** Explica detalladamente:
   ```markdown
   ## Descripción
   Implementa el módulo completo de gestión de usuarios con CRUD funcional.
   
   ## Cambios realizados
   - Crea `UsuarioController` con métodos index, create, store, edit, update, destroy
   - Agrega vistas Blade para listar, crear y editar usuarios
   - Registra rutas en `web.php`
   - Agrega validaciones en el Request
   
   ## Archivos modificados
   - `app/Http/Controllers/UsuarioController.php` (nuevo)
   - `resources/views/usuarios/` (nuevo)
   - `routes/web.php` (modificado)
   
   ## Cómo probar
   1. Iniciar sesión como administrador
   2. Ir a "Gestión de Usuarios"
   3. Crear un nuevo usuario
   4. Verificar que se guarda correctamente
   ```

5. **Haz clic en "Create pull request"**

6. **Espera la revisión:**
   - Otros miembros del equipo revisarán tu código
   - Pueden pedir cambios o hacer comentarios
   - Responde a los comentarios y haz los cambios necesarios

7. **Una vez aprobado:**
   - El PR se mergeará a `master`
   - Tu código estará en producción

---

## 🎯 Flujo Completo Resumido

```
┌─────────────────────────────────────────────────────────────┐
│ 1. git checkout master                                      │
│ 2. git pull origin master                                   │
│ 3. git checkout -b feature/mi-modulo                        │
│ 4. [Trabajas en tu código]                                  │
│ 5. git add .                                                │
│ 6. git commit -m "feat: descripción"                        │
│ 7. git push -u origin feature/mi-modulo                     │
│ 8. git checkout desarrollo                                  │
│ 9. git pull origin desarrollo                               │
│ 10. git merge feature/mi-modulo                             │
│ 11. git push origin desarrollo                               │
│ 12. [Crear PR en GitHub: feature/mi-modulo → master]        │
└─────────────────────────────────────────────────────────────┘
```

---

## 📝 Ejemplo Práctico Completo

Vamos a implementar un módulo de ejemplo paso a paso:

```bash
# 1. Actualizar master
git checkout master
git pull origin master

# 2. Crear rama
git checkout -b feature/gestion-usuarios

# 3. Trabajar (crear archivos, editar, etc.)
# ... haces tus cambios en el código ...

# 4. Ver qué has cambiado
git status

# 5. Agregar cambios
git add .

# 6. Hacer commit
git commit -m "feat: crea UsuarioController con métodos CRUD"

# 7. Subir rama
git push -u origin feature/gestion-usuarios

# 8. Mergear a desarrollo
git checkout desarrollo
git pull origin desarrollo
git merge feature/gestion-usuarios
git push origin desarrollo

# 9. Crear PR en GitHub (desde la interfaz web)
```

---

## ✅ Reglas Importantes

### ✅ SÍ puedes hacer:
- ✅ Crear ramas desde `master`
- ✅ Trabajar en tu rama localmente
- ✅ Hacer commits frecuentes
- ✅ Subir tu rama cuando quieras respaldarla
- ✅ Mergear a `desarrollo` cuando tu código esté listo

### ❌ NO hagas:
- ❌ **NO hagas commit directamente a `master`**
- ❌ **NO hagas commit directamente a `desarrollo`** (solo mergea)
- ❌ **NO fuerces push a `master` o `desarrollo`** (`git push --force`)
- ❌ **NO trabajes directamente en `master` o `desarrollo`**

---

## 🆘 Problemas Comunes

### Error: "Your branch is behind 'origin/master'"

**Solución:**
```bash
git pull origin master
```

### Error: "Merge conflict"

**Solución:**
1. Abre el archivo con conflictos
2. Busca las marcas `<<<<<<<`, `=======`, `>>>>>>>`
3. Resuelve manualmente eliminando las marcas y decidiendo qué código mantener
4. Guarda el archivo
5. Ejecuta: `git add archivo-resuelto.php`
6. Ejecuta: `git commit`

### Error: "Cannot merge because you have uncommitted changes"

**Solución:**
```bash
# Opción 1: Commitear tus cambios primero
git add .
git commit -m "WIP: trabajo en progreso"
# Luego intenta el merge de nuevo

# Opción 2: Guardar cambios temporalmente
git stash
# Hacer el merge
git merge otra-rama
# Recuperar tus cambios
git stash pop
```

---

## ✅ Checklist Antes de Crear un PR

Antes de crear un Pull Request, asegúrate de:

- [ ] Tu código funciona correctamente
- [ ] Has probado todas las funcionalidades
- [ ] No hay errores de sintaxis
- [ ] Los mensajes de commit siguen la convención
- [ ] Has mergeado tu rama a `desarrollo`
- [ ] Has resuelto todos los conflictos si los había
- [ ] Has escrito una descripción clara del PR

---

**¡Sigue este flujo y mantendremos el código organizado!** 🚀

*Última actualización: 2024*
