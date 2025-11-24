# Manual de Usuario - Sistema de Control de Asistencias y Académico SENA

## 1. Introducción
Bienvenido al Sistema de Control de Asistencias y Gestión Académica. Esta plataforma permite a los instructores del SENA gestionar la asistencia, calificaciones y procesos disciplinarios de los aprendices de manera eficiente y centralizada.

## 2. Acceso al Sistema
1.  Abra el archivo `iniciar_sistema.bat` en su escritorio o carpeta del proyecto.
2.  El sistema abrirá automáticamente el navegador en `http://127.0.0.1:8000`.
3.  Ingrese sus credenciales (Correo y Contraseña).

## 3. Dashboard (Panel Principal)
Al ingresar, encontrará un panel con estadísticas en tiempo real:
*   **Gráfico de Asistencias:** Resumen mensual de asistencias, fallas y excusas.
*   **Gráfico de Faltas:** Comparativa entre faltas Académicas y Disciplinarias.
*   **Planes de Mejoramiento:** Estado de los planes (Abiertos vs Cerrados).
*   **Accesos Rápidos:** Botones para ir directamente a Tomar Asistencia, Gestionar Aprendices, Faltas o Planes.

## 4. Gestión de Asistencias
### Tomar Asistencia
1.  Vaya a **"Asistencias"** en el menú lateral.
2.  Seleccione el **Grupo** y la **Competencia** que está impartiendo.
3.  Marque el estado para cada aprendiz:
    *   **Asistió (Verde):** Presente en la formación.
    *   **Falla (Rojo):** Ausencia injustificada.
    *   **Excusa (Amarillo):** Ausencia justificada.
    *   **Retardo (Naranja):** Llegada tarde.
4.  Haga clic en **"Guardar Asistencia"**.

> **NOTA:** El sistema alertará automáticamente si un aprendiz acumula 3 fallas consecutivas.

## 5. Gestión Académica (Calificaciones)
### Calificar Resultados de Aprendizaje
1.  Vaya a **"Calificaciones"** en el menú lateral (o desde Programas > Competencias).
2.  Seleccione el Grupo y la Competencia.
3.  Verá la lista de aprendices y los Resultados de Aprendizaje (RAs) asociados.
4.  Ingrese la calificación para cada RA:
    *   **A:** Aprobado.
    *   **D:** Deficiente (No Aprobado).
5.  El sistema calculará automáticamente el porcentaje de avance de la competencia.

## 6. Módulo Disciplinario
### Registrar un Llamado de Atención
1.  Busque al aprendiz en **"Estudiantes"** y haga clic en **"Historial Disciplinario"** (icono de alerta).
2.  Haga clic en **"Nuevo Llamado de Atención"**.
3.  Complete el formulario:
    *   **Tipo de Falta:** Académica o Disciplinaria.
    *   **Tipo de Llamado:** Verbal (Formativo) o Escrito (Sancionatorio).
    *   **Gravedad:** Leve, Grave o Gravísima.
    *   **Descripción:** Detalle de los hechos.
    *   **Faltas (Solo Disciplinarias):** Seleccione el literal del Reglamento del Aprendiz infringido.
4.  **Validación:** Si es el segundo llamado escrito del mismo tipo, el sistema le pedirá obligatoriamente crear un Plan de Mejoramiento.

### Imprimir Llamado de Atención
*   Desde el historial del aprendiz, haga clic en el icono de **Impresora** para generar el PDF oficial para firma.

## 7. Planes de Mejoramiento
### Crear un Plan
1.  Puede crearlo desde un Llamado de Atención o desde el menú **"Planes de Mejoramiento"**.
2.  Defina:
    *   **Tipo:** Académico o Disciplinario.
    *   **Fechas:** Inicio y Fin (Límite).
    *   **Actividades:** Compromisos que debe cumplir el aprendiz.
    *   **Instructor Responsable:** Quién verificará el cumplimiento.

### Gestionar Planes
*   En el listado de planes, puede cambiar el estado a **"En Progreso"**, **"Cumplido"** o **"Incumplido"**.
*   Use el botón de **Impresora** para generar el acta de compromiso.

## 8. Soporte
Si tiene problemas técnicos, contacte al administrador del sistema.
