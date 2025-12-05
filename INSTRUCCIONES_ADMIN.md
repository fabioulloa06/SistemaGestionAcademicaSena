# 👑 Guía para el Administrador

## Estado Actual del Sistema

✅ **Base de datos limpiada** - Solo queda el usuario admin  
✅ **Programa Tecnología en ADS pre-cargado** - Con 20 competencias y 75 resultados de aprendizaje  
✅ **Listo para crear grupos, instructores y estudiantes**

---

## 🔑 Credenciales del Admin

- **Email:** `admin@sena.edu.co`
- **Password:** `password123`

---

## 📋 Orden Recomendado para Crear Todo

### 1. Programas de Formación

✅ **Programa Pre-cargado:**
- **Tecnología en Análisis y Desarrollo de Software** (Código: 228106)
  - 20 competencias
  - 75 resultados de aprendizaje
  - Ya está disponible en el sistema

Si necesitas crear otros programas:

1. Ve a **Programas** en el menú
2. Haz clic en **"Crear Programa"**
3. Completa:
   - Código del programa
   - Nombre del programa
   - Descripción
   - Duración (meses)
   - Nivel (Técnico/Tecnológico)
   - Estado: Activo

### 2. Crear Grupos (Fichas)

1. Ve a **Grupos** en el menú
2. Haz clic en **"Crear Grupo"**
3. Completa:
   - Número de ficha
   - Programa (selecciona el que creaste)
   - **Instructor Líder** (lo crearás después, puedes dejarlo vacío por ahora)
   - Fecha de inicio
   - Fecha de fin
   - Jornada (Mañana/Tarde/Noche)
   - Estado: Activo

### 3. Competencias y Resultados de Aprendizaje

✅ **Para el programa Tecnología en ADS:**
- Las competencias y resultados de aprendizaje ya están creados
- Puedes verlas en: **Programas → Tecnología en Análisis y Desarrollo de Software → Competencias**

Si necesitas crear competencias para otros programas:

1. Ve al programa que creaste
2. Haz clic en **"Competencias"**
3. Haz clic en **"Crear Competencia"**
4. Completa:
   - Código de la competencia
   - Nombre de la competencia
   - Descripción
   - Estado: Activo

### 4. Crear Resultados de Aprendizaje (RAs) - Solo para otros programas

1. Ve a la competencia que creaste
2. Haz clic en **"Resultados de Aprendizaje"**
3. Haz clic en **"Crear RA"**
4. Completa:
   - Código del RA
   - Nombre del RA
   - Descripción
   - Horas asignadas

### 5. Crear Instructores

1. Ve a **Instructores** en el menú
2. Haz clic en **"Crear Instructor"**
3. Completa:
   - Nombre
   - Documento
   - Email
   - Teléfono
   - Especialidad
   - Estado: Activo

4. **Crear Usuario para el Instructor:**
   - Después de crear el instructor, ve a **Usuarios**
   - Crea un usuario con:
     - Email: (el mismo del instructor)
     - Password: (temporal, el instructor puede cambiarlo)
     - Rol: `instructor`

### 6. Asignar Instructor Líder a la Ficha

1. Ve a **Grupos**
2. Edita el grupo (ficha)
3. Selecciona el **Instructor Líder** del dropdown
4. Guarda

### 7. Asignar Instructores a Competencias

1. Ve a **Asignaciones de Instructores**
2. Selecciona:
   - Grupo (ficha)
   - Competencia
   - Instructor
3. Guarda

### 8. Crear Estudiantes

1. Ve a **Estudiantes** en el menú
2. Haz clic en **"Crear Estudiante"**
3. Completa:
   - Nombre
   - Documento
   - Email
   - Teléfono
   - Grupo (selecciona la ficha)
   - Estado: Activo

4. **Nota:** El sistema creará automáticamente un usuario para el estudiante con:
   - Email: (el mismo del estudiante)
   - Password: (igual al documento del estudiante)
   - Rol: `student`

---

## ⚠️ Importante

- **El admin tiene todos los permisos** para crear, editar y eliminar
- **Los estudiantes se crean automáticamente con usuario** (password = documento)
- **Los instructores necesitan usuario creado manualmente** (rol: instructor)
- **El coordinador se crea como usuario** (rol: coordinator) - no necesita perfil de instructor

---

## 🔄 Si Necesitas Limpiar Todo de Nuevo

Ejecuta:
```powershell
php limpiar_base_datos.php
```

Esto eliminará todo excepto el admin.

---

## ✅ Checklist de Creación

- [x] Programa Tecnología en ADS (pre-cargado con competencias y RAs)
- [ ] Otros programas creados (si aplica)
- [ ] Grupos (fichas) creados
- [ ] Competencias creadas (solo para otros programas)
- [ ] Resultados de Aprendizaje creados (solo para otros programas)
- [ ] Instructores creados
- [ ] Usuarios de instructores creados
- [ ] Instructor Líder asignado a cada ficha
- [ ] Instructores asignados a competencias
- [ ] Estudiantes creados

---

**¡Listo para comenzar! 🚀**

