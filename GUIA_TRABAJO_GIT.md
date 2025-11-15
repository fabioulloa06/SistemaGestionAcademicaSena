# Guía de Trabajo con Git
## Sistema de Gestión Académica SENA

Esta guía explica el flujo de trabajo con Git para el proyecto. **Sigue estos pasos exactamente** para mantener el código organizado.

---

## 📋 Flujo de Trabajo

### Paso 1: Actualizar tu copia local de master

Antes de empezar a trabajar, siempre actualiza tu copia local:

```bash
# Cambia a la rama master
git checkout master

# Descarga los últimos cambios del repositorio remoto
git pull origin master
```

**⚠️ IMPORTANTE:** Siempre haz esto antes de crear una nueva rama.

---

### Paso 2: Crear tu rama de trabajo

Crea una rama nueva para tu módulo. Usa un nombre descriptivo:

```bash
# Crea y cambia a la nueva rama
git checkout -b feature/nombre-del-modulo

# Ejemplos de nombres:
# git checkout -b feature/gestion-usuarios
# git checkout -b feature/sesiones-formacion
# git checkout -b feature/registro-asistencias
```

**Convención de nombres:**
- `feature/` para nuevas funcionalidades
- `fix/` para correcciones de bugs
- `refactor/` para refactorizaciones

---

### Paso 3: Trabajar en tu rama

Ahora puedes trabajar normalmente en tu rama:

```bash
# Haz tus cambios en los archivos
# Edita, crea, modifica según tu módulo

# Ve el estado de tus cambios
git status

# Agrega los archivos que quieres commitear
git add app/Http/Controllers/MiController.php
git add resources/views/mi-modulo/
# O agrega todos los cambios:
git add .

# Haz commit con un mensaje descriptivo
git commit -m "feat: implementa módulo de gestión de usuarios"
```

**Convención de mensajes de commit:**
- `feat:` para nuevas funcionalidades
- `fix:` para correcciones
- `refactor:` para refactorizaciones
- `docs:` para documentación
- `style:` para formato (sin cambios de lógica)

**Ejemplos:**
```bash
git commit -m "feat: agrega CRUD completo de usuarios"
git commit -m "fix: corrige validación de email en registro"
git commit -m "refactor: mejora estructura del controlador de fichas"
```

---

### Paso 4: Subir tu rama al repositorio

Cuando termines tu trabajo (o quieras respaldarlo), sube tu rama:

```bash
# Sube tu rama al repositorio remoto
git push origin feature/nombre-del-modulo
```

Si es la primera vez que subes esta rama, Git te dará un comando. Úsalo:

```bash
git push --set-upstream origin feature/nombre-del-modulo
```

---

### Paso 5: Mergear con la rama desarrollo

Una vez que tu código esté listo y probado:

```bash
# Cambia a la rama desarrollo
git checkout desarrollo

# Actualiza desarrollo con los últimos cambios
git pull origin desarrollo

# Mergea tu rama en desarrollo
git merge feature/nombre-del-modulo

# Si hay conflictos, resuélvelos y luego:
git add .
git commit -m "merge: integra feature/nombre-del-modulo en desarrollo"
```

**⚠️ Si hay conflictos:**
1. Git te mostrará los archivos con conflictos
2. Abre cada archivo y busca las marcas `<<<<<<<`, `=======`, `>>>>>>>`
3. Resuelve los conflictos manualmente
4. Guarda el archivo
5. Ejecuta `git add archivo-resuelto.php`
6. Continúa con `git commit`

---

### Paso 6: Subir desarrollo al repositorio

Después de mergear, sube los cambios:

```bash
# Sube la rama desarrollo actualizada
git push origin desarrollo
```

---

### Paso 7: Crear Pull Request (PR) a master

**En GitHub:**

1. Ve al repositorio en GitHub
2. Verás un banner que dice "Compare & pull request" - haz clic
3. O ve a la pestaña "Pull requests" → "New pull request"
4. Selecciona:
   - **Base:** `master`
   - **Compare:** `feature/tu-rama`
5. Completa el título y descripción del PR:
   - **Título:** Ej: "feat: Implementa módulo de gestión de usuarios"
   - **Descripción:** Explica qué hace tu módulo, qué archivos creaste, etc.
6. Haz clic en "Create pull request"
7. Espera a que alguien revise y apruebe tu PR
8. Una vez aprobado, el PR se mergeará a master

---

## 🔄 Comandos Útiles

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

### Cambiar de rama
```bash
git checkout nombre-de-la-rama
```

### Ver el historial de commits
```bash
git log --oneline
```

### Deshacer cambios no commiteados
```bash
# Descartar cambios en un archivo específico
git restore archivo.php

# Descartar todos los cambios
git restore .
```

### Ver diferencias
```bash
# Ver diferencias en archivos modificados
git diff

# Ver diferencias de un archivo específico
git diff archivo.php
```

---

## ⚠️ Reglas Importantes

### ✅ SÍ puedes hacer:
- Crear ramas desde `master`
- Trabajar en tu rama localmente
- Hacer commits frecuentes
- Subir tu rama cuando quieras respaldarla
- Mergear a `desarrollo` cuando tu código esté listo

### ❌ NO hagas:
- **NO hagas commit directamente a `master`**
- **NO hagas commit directamente a `desarrollo`** (solo mergea)
- **NO elimines ramas de otros compañeros**
- **NO fuerces push a `master` o `desarrollo`** (`git push --force`)
- **NO trabajes directamente en `master` o `desarrollo`**

---

## 🎯 Flujo Completo Resumido

```
1. git checkout master
2. git pull origin master
3. git checkout -b feature/mi-modulo
4. [Trabajas en tu código]
5. git add .
6. git commit -m "feat: descripción"
7. git push origin feature/mi-modulo
8. git checkout desarrollo
9. git pull origin desarrollo
10. git merge feature/mi-modulo
11. git push origin desarrollo
12. [Crear PR en GitHub: feature/mi-modulo → master]
```

---

## 📝 Ejemplo Completo

Imagina que vas a implementar el módulo de "Gestión de Usuarios":

```bash
# 1. Actualizar master
git checkout master
git pull origin master

# 2. Crear rama
git checkout -b feature/gestion-usuarios

# 3. Trabajar (crear archivos, editar, etc.)
# ... haces tus cambios ...

# 4. Commitear
git add app/Http/Controllers/UsuarioController.php
git add resources/views/usuarios/
git add routes/web.php
git commit -m "feat: implementa CRUD completo de usuarios"

# 5. Subir rama
git push origin feature/gestion-usuarios

# 6. Mergear a desarrollo
git checkout desarrollo
git pull origin desarrollo
git merge feature/gestion-usuarios
git push origin desarrollo

# 7. Crear PR en GitHub (desde la interfaz web)
```

---

## 🆘 Solución de Problemas

### Error: "Your branch is behind 'origin/master'"
```bash
git pull origin master
```

### Error: "Merge conflict"
1. Abre los archivos con conflictos
2. Busca las marcas `<<<<<<<`, `=======`, `>>>>>>>`
3. Resuelve manualmente
4. `git add archivo-resuelto.php`
5. `git commit`

### Error: "Cannot merge because you have uncommitted changes"
```bash
# Opción 1: Commitear tus cambios primero
git add .
git commit -m "WIP: trabajo en progreso"

# Opción 2: Guardar cambios temporalmente
git stash
# ... hacer merge ...
git stash pop
```

### Quieres descartar todos tus cambios locales
```bash
git restore .
```

---

## 📚 Recursos Adicionales

- [Documentación oficial de Git](https://git-scm.com/doc)
- [GitHub Guides](https://guides.github.com/)
- [Conventional Commits](https://www.conventionalcommits.org/)

---

**¡Sigue este flujo y mantendremos el código organizado!** 🚀


