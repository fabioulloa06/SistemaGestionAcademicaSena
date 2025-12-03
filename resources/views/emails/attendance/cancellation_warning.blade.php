<x-mail::message>
@if(in_array($reason, ['consecutive_warning', 'total_warning']))
# ⚠️ Alerta Temprana: Prevención de Cancelación de Matrícula
@else
# 🚨 Alerta Crítica: Proceso de Cancelación de Matrícula
@endif

Hola **{{ $student->nombre }}**,

@if(in_array($reason, ['consecutive_warning', 'total_warning']))
Se ha detectado que estás **cerca de alcanzar** los límites de inasistencias establecidos por el Reglamento del Aprendiz SENA. Esta es una **alerta temprana** para que puedas tomar acciones preventivas.
@else
Se ha detectado que has **alcanzado o superado** los límites de inasistencias establecidos por el Reglamento del Aprendiz SENA, lo que puede llevar a la **cancelación de tu matrícula**.
@endif

@if($reason === 'consecutive_warning')
<x-mail::panel>
**Alerta Temprana:**
Has acumulado **{{ $details['consecutive'] }} días consecutivos con inasistencias**.

Estás a **1 día** de alcanzar el límite permitido de **{{ $details['limit'] }} días consecutivos**. Si alcanzas este límite, se iniciará el proceso de cancelación de matrícula según el Reglamento del Aprendiz SENA.
</x-mail::panel>
@elseif($reason === 'total_warning')
<x-mail::panel>
**Alerta Temprana:**
Has acumulado **{{ $details['total'] }} inasistencias en total**.

Estás a **1 inasistencia** de alcanzar el límite permitido de **{{ $details['limit'] }} inasistencias totales**. Si alcanzas este límite, se iniciará el proceso de cancelación de matrícula según el Reglamento del Aprendiz SENA.
</x-mail::panel>
@elseif($reason === 'consecutive_limit')
<x-mail::panel>
**Alerta Crítica:**
Has alcanzado **{{ $details['consecutive'] }} días consecutivos con inasistencias**.

Has alcanzado el límite permitido de **{{ $details['limit'] }} días consecutivos**. Se iniciará el proceso de cancelación de matrícula según el Reglamento del Aprendiz SENA.
</x-mail::panel>
@else
<x-mail::panel>
**Alerta Crítica:**
Has alcanzado **{{ $details['total'] }} inasistencias en total**.

Has alcanzado el límite permitido de **{{ $details['limit'] }} inasistencias totales**. Se iniciará el proceso de cancelación de matrícula según el Reglamento del Aprendiz SENA.
</x-mail::panel>
@endif

## 📋 Proceso que se Iniciará

1. **Notificación a Instructores**: Se ha notificado a todos los instructores del programa sobre esta situación.
2. **Revisión del Caso**: Los instructores revisarán tu caso y se comunicarán contigo.
3. **Acción Preventiva o Correctiva**: 
   @if(in_array($reason, ['consecutive_warning', 'total_warning']))
   - **Aún estás a tiempo** de evitar la cancelación contactando a tus instructores
   @else
   - Si no se justifican las inasistencias, se procederá con el proceso de cancelación de matrícula
   @endif

## ⚡ Acciones Inmediatas Requeridas

- **Contacta INMEDIATAMENTE** a tu Instructor Líder o cualquier instructor del programa
- **Justifica tus inasistencias** presentando la documentación correspondiente
- **Acércate a Bienestar al Aprendiz** para recibir orientación
- **Coordina con tus instructores** para evitar alcanzar o superar los límites

<x-mail::button :url="route('student.dashboard')">
Ver Mi Dashboard
</x-mail::button>

@if(in_array($reason, ['consecutive_warning', 'total_warning']))
**Importante:** Esta es una **alerta temprana** del sistema. Aún estás a tiempo de evitar la cancelación de matrícula si tomas las acciones correctivas necesarias.
@else
**Importante:** Esta es una notificación oficial del sistema. Tu matrícula está en riesgo de ser cancelada si no tomas las acciones correctivas necesarias.
@endif

Atentamente,<br>
**Sistema de Gestión Académica SENA**
</x-mail::message>

