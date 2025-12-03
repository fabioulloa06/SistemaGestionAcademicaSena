# Manual de Usuario - Sistema de Gestión Académica SENA

## 1. Introducción

Bienvenido al Sistema de Control de Asistencias y Gestión Académica del SENA. Esta plataforma permite a los instructores, coordinadores y administradores gestionar de manera eficiente y centralizada:

- **Asistencias**: Registro y seguimiento de asistencia de aprendices
- **Calificaciones**: Evaluación de Resultados de Aprendizaje (RAs) y Competencias
- **Procesos Disciplinarios**: Llamados de atención y planes de mejoramiento
- **Gestión Académica**: Programas, grupos, competencias y asignaciones

## 2. Acceso al Sistema

### Inicio Local (XAMPP/Desarrollo)
1. Ejecute el archivo `start-local.bat` en la carpeta del proyecto
2. El sistema abrirá automáticamente el navegador en `http://localhost:8000`
3. Ingrese sus credenciales (Correo y Contraseña)

### Inicio con Docker
1. Ejecute el archivo `docker-start.bat` para iniciar los contenedores
2. Acceda a `http://localhost` en su navegador
3. Ingrese sus credenciales

## 3. Roles del Sistema

### Administrador
- Acceso completo a todas las funcionalidades
- Gestión de usuarios, programas, grupos y competencias
- Visualización de auditoría y reportes completos

### Coordinador
- Visualización de toda la información (solo lectura)
- Revisión y vigilancia de procesos
- Acceso a reportes y estadísticas
- **No puede**: Crear asistencias, llamados de atención ni planes de mejoramiento

### Instructor
- Gestión de asistencias para sus grupos asignados
- Calificación de Resultados de Aprendizaje
- Creación de llamados de atención y planes de mejoramiento
- Acceso limitado a sus grupos y competencias asignadas

### Aprendiz
- Visualización de su propio progreso académico
- Consulta de asistencias y calificaciones
- Acceso a su portal estudiantil

## 4. Dashboard (Panel Principal)

Al ingresar, encontrará un panel con estadísticas en tiempo real según su rol:

### Dashboard Administrador
- **Gráfico de Asistencias**: Resumen mensual de asistencias, fallas y excusas
- **Gráfico de Faltas**: Comparativa entre faltas Académicas y Disciplinarias
- **Planes de Mejoramiento**: Estado de los planes (Abiertos vs Cerrados)
- **Accesos Rápidos**: Botones para ir directamente a módulos principales

### Dashboard Coordinador
- Vista general de todos los grupos y programas
- Estadísticas consolidadas
- Acceso a reportes

### Dashboard Instructor
- Vista de sus grupos asignados
- Estadísticas de sus competencias
- Accesos rápidos a asistencias y calificaciones

## 5. Gestión de Asistencias

### Tomar Asistencia
1. Vaya a **"Asistencias"** en el menú lateral
2. Haga clic en **"Nueva Asistencia"** o **"Tomar Asistencia"**
3. Seleccione el **Grupo** y la **Competencia** que está impartiendo
4. Seleccione la **Fecha** de la sesión
5. Marque el estado para cada aprendiz:
   - **Presente (Verde)**: Asistió a la formación
   - **Ausente (Rojo)**: Ausencia injustificada
   - **Justificado (Amarillo)**: Ausencia justificada
   - **Tarde (Naranja)**: Llegada tarde
6. Agregue observaciones si es necesario
7. Haga clic en **"Guardar Asistencia"**

> **NOTA IMPORTANTE**: El sistema alertará automáticamente si un aprendiz acumula:
> - 3 fallas consecutivas a la misma competencia
> - 3 fallas totales a la misma competencia
> - 3 fallas consecutivas en general
> 
> Se enviará un correo electrónico de advertencia al aprendiz.

### Consultar Asistencias
- Desde el menú **"Asistencias"** puede filtrar por:
  - Fecha
  - Grupo
  - Competencia
  - Estado

## 6. Gestión Académica (Calificaciones)

### Calificar Resultados de Aprendizaje
1. Vaya a **"Calificaciones"** en el menú lateral o desde **Programas > Competencias**
2. Seleccione el **Grupo** y la **Competencia**
3. Verá la lista de aprendices y los Resultados de Aprendizaje (RAs) asociados
4. Ingrese la calificación para cada RA:
   - **A**: Aprobado
   - **D**: Deficiente (No Aprobado)
5. Agregue observaciones si es necesario
6. El sistema calculará automáticamente el porcentaje de avance de la competencia

### Ver Progreso de Aprendices
- Desde **"Estudiantes"** > Seleccione un aprendiz > **"Progreso Académico"**
- Verá un resumen completo de:
  - Competencias y su estado
  - Resultados de Aprendizaje calificados
  - Porcentaje de avance por competencia

## 7. Módulo Disciplinario

### Registrar un Llamado de Atención
1. Busque al aprendiz en **"Estudiantes"** y haga clic en **"Historial Disciplinario"** (icono de alerta)
2. O vaya a **"Llamados de Atención"** en el menú
3. Haga clic en **"Nuevo Llamado de Atención"**
4. Complete el formulario:
   - **Estudiante**: Seleccione el aprendiz
   - **Tipo de Falta**: Académica o Disciplinaria
   - **Tipo de Llamado**: 
     - **Verbal**: Formativo (primer llamado)
     - **Escrito**: Sancionatorio (segundo llamado o más)
   - **Gravedad**: Leve, Grave o Gravísima
   - **Descripción**: Detalle de los hechos
   - **Faltas (Solo Disciplinarias)**: Seleccione el literal del Reglamento del Aprendiz infringido
5. Haga clic en **"Guardar"**

> **VALIDACIÓN IMPORTANTE**: 
> - Si es el segundo llamado escrito del mismo tipo, el sistema le pedirá **obligatoriamente** crear un Plan de Mejoramiento
> - No se puede crear un tercer llamado escrito sin un plan de mejoramiento activo

### Imprimir Llamado de Atención
- Desde el historial del aprendiz, haga clic en el icono de **Impresora** para generar el PDF oficial para firma
- El documento incluye toda la información del llamado y está listo para impresión

## 8. Planes de Mejoramiento

### Crear un Plan
1. Puede crearlo desde un Llamado de Atención (si el sistema lo requiere) o desde el menú **"Planes de Mejoramiento"**
2. Haga clic en **"Nuevo Plan de Mejoramiento"**
3. Complete el formulario:
   - **Estudiante**: Seleccione el aprendiz
   - **Tipo**: Académico o Disciplinario
   - **Llamado de Atención**: (Opcional) Si está vinculado a un llamado
   - **Fechas**: 
     - **Fecha de Inicio**: Cuando inicia el plan
     - **Fecha Límite**: Fecha máxima para cumplir
   - **Actividades**: Compromisos que debe cumplir el aprendiz (una por línea)
   - **Instructor Responsable**: Quién verificará el cumplimiento
4. Haga clic en **"Guardar"**

### Gestionar Planes
- En el listado de planes, puede:
  - **Ver detalles**: Hacer clic en el plan
  - **Editar**: Modificar fechas, actividades o estado
  - **Cambiar estado**: 
    - **Pendiente**: Recién creado
    - **En Progreso**: El aprendiz está trabajando en él
    - **Cumplido**: El aprendiz completó todas las actividades
    - **Incumplido**: El aprendiz no cumplió en el tiempo establecido
  - **Imprimir**: Generar el acta de compromiso en PDF

## 9. Gestión Académica (Administradores y Coordinadores)

### Programas de Formación
- Crear, editar y gestionar programas
- Asociar competencias a programas

### Grupos (Fichas)
- Crear y gestionar grupos de aprendices
- Asignar programas a grupos
- Asignar instructores líderes y coordinadores

### Competencias
- Crear competencias dentro de programas
- Asignar Resultados de Aprendizaje (RAs) a competencias
- Asignar instructores a competencias y grupos

### Estudiantes
- Registrar nuevos aprendices
- Asignar aprendices a grupos
- Ver historial completo de cada aprendiz

### Instructores
- Registrar instructores
- Asignar instructores a competencias y grupos
- Gestionar información de instructores

## 10. Reportes

### Disponibles para Administradores y Coordinadores
- **Reporte de Asistencias**: Resumen de asistencias por grupo, fecha o aprendiz
- **Reporte de Ausencias**: Análisis de ausencias y patrones
- **Reporte de Calificaciones**: Estadísticas de rendimiento académico

## 11. Soporte y Ayuda

### Problemas Técnicos
Si tiene problemas técnicos:
1. Verifique que todos los servicios estén corriendo
2. Revise los logs del sistema
3. Contacte al administrador del sistema

### Preguntas Frecuentes

**¿Por qué no puedo tomar asistencia?**
- Verifique que tenga permisos de instructor o administrador
- Los coordinadores solo pueden ver asistencias, no crearlas
- Asegúrese de tener grupos asignados

**¿Cómo asigno un instructor a una competencia?**
- Vaya a **Programas** > Seleccione el programa > **Competencias** > Seleccione la competencia
- Haga clic en **"Asignar Instructores"**
- Seleccione los instructores y grupos

**¿Puedo editar una asistencia ya guardada?**
- Sí, desde el listado de asistencias, haga clic en **"Editar"**
- Solo puede editar asistencias de sus grupos asignados

## 12. Notas Importantes

- El sistema envía notificaciones por correo electrónico automáticamente en casos críticos
- Todos los cambios quedan registrados en el sistema de auditoría
- Los PDFs generados son oficiales y están listos para impresión
- El sistema valida automáticamente las reglas de negocio (ej: no se puede crear un tercer llamado sin plan)

---

**Versión del Manual**: 1.0  
**Última Actualización**: 2024
