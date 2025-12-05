<x-mail::message>
@if(in_array($reason, ['consecutive_warning', 'total_warning']))
# ⚠️ Alerta Temprana: Estudiante Cerca de Límites de Inasistencias
@else
# 🚨 Alerta Crítica: Estudiante Alcanzó Límites de Inasistencias
@endif

Estimado/a Instructor,

@if(in_array($reason, ['consecutive_warning', 'total_warning']))
Se ha detectado que un estudiante está **cerca de alcanzar** los límites de inasistencias establecidos por el Reglamento del Aprendiz SENA. Esta es una **alerta temprana** para que pueda intervenir y prevenir la cancelación de matrícula.
@else
Se ha detectado que un estudiante ha **alcanzado o superado** los límites de inasistencias establecidos por el Reglamento del Aprendiz SENA.
@endif

## 📋 Información del Estudiante

- **Nombre:** {{ $student->nombre }}
- **Documento:** {{ $student->documento }}
- **Email:** {{ $student->email }}
- **Grupo/Ficha:** {{ $group->numero_ficha ?? 'N/A' }}
- **Programa:** {{ $group->program->nombre ?? 'N/A' }}

@if($reason === 'consecutive_warning')
<x-mail::panel>
**Alerta Temprana:**
El estudiante ha acumulado **{{ $details['consecutive'] }} días consecutivos con inasistencias**.

Está a **1 día** de alcanzar el límite permitido de **{{ $details['limit'] }} días consecutivos**. Si alcanza este límite, se iniciará el proceso de cancelación de matrícula según el Reglamento del Aprendiz SENA.
</x-mail::panel>
@elseif($reason === 'total_warning')
<x-mail::panel>
**Alerta Temprana:**
El estudiante ha acumulado **{{ $details['total'] }} inasistencias en total**.

Está a **1 inasistencia** de alcanzar el límite permitido de **{{ $details['limit'] }} inasistencias totales**. Si alcanza este límite, se iniciará el proceso de cancelación de matrícula según el Reglamento del Aprendiz SENA.
</x-mail::panel>
@elseif($reason === 'consecutive_limit')
<x-mail::panel>
**Alerta Crítica:**
El estudiante ha alcanzado **{{ $details['consecutive'] }} días consecutivos con inasistencias**.

Ha alcanzado el límite permitido de **{{ $details['limit'] }} días consecutivos**. Se debe iniciar el proceso de cancelación de matrícula según el Reglamento del Aprendiz SENA.
</x-mail::panel>
@else
<x-mail::panel>
**Alerta Crítica:**
El estudiante ha alcanzado **{{ $details['total'] }} inasistencias en total**.

Ha alcanzado el límite permitido de **{{ $details['limit'] }} inasistencias totales**. Se debe iniciar el proceso de cancelación de matrícula según el Reglamento del Aprendiz SENA.
</x-mail::panel>
@endif

## ⚠️ Acción Requerida

@if(in_array($reason, ['consecutive_warning', 'total_warning']))
**ACCIÓN PREVENTIVA URGENTE:**

Debe **contactar al estudiante INMEDIATAMENTE** para:
1. **Conocer las razones** de las inasistencias
2. **Orientar al estudiante** sobre las consecuencias de alcanzar el límite
3. **Evaluar si las inasistencias son justificables** según la normativa SENA
4. **Coordinar con otros instructores** del programa para un seguimiento conjunto
5. **Prevenir que alcance el límite** mediante acciones correctivas

**Aún está a tiempo de evitar la cancelación de matrícula.**
@else
**ACCIÓN CRÍTICA REQUERIDA:**

Debe:
1. **Revisar el caso** del estudiante inmediatamente
2. **Contactar al estudiante** para conocer las razones de las inasistencias
3. **Evaluar si las inasistencias son justificables** según la normativa SENA
4. **Coordinar con el Instructor Líder** de la ficha para tomar decisiones
5. **Iniciar el proceso correspondiente** según el Reglamento del Aprendiz:
   - Si son justificadas: Registrar las justificaciones en el sistema
   - Si no son justificadas: Iniciar el proceso de cancelación de matrícula (coordinado con el Instructor Líder)
@endif

## 📝 Próximos Pasos

- El estudiante ha sido notificado automáticamente sobre esta situación
- El Instructor Líder de la ficha también ha sido notificado
- **Todos los instructores del programa** han sido notificados para un mejor seguimiento
- Debe revisar el historial de inasistencias del estudiante en el sistema
- Debe coordinar con el Instructor Líder y otros instructores del programa para tomar una decisión informada sobre el caso

@if($competencia)
**Competencia Afectada:** {{ $competencia->nombre ?? $competencia->nombre_competencia ?? 'N/A' }}
@endif

<x-mail::button :url="route('students.show', $student->id)">
Ver Detalles del Estudiante
</x-mail::button>

**Importante:** Este es un proceso crítico que requiere su atención inmediata para cumplir con la normativa SENA.

Atentamente,<br>
**Sistema de Gestión Académica SENA**
</x-mail::message>

