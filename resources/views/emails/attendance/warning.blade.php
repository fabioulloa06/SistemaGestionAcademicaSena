<x-mail::message>
# Alerta de Asistencia

Hola **{{ $student->nombre }}**,

Se ha detectado que has alcanzado un número crítico de inasistencias.

**Detalles:**
* **Total de Fallas:** {{ $details['total'] }}
* **Consecutivas:** {{ $details['consecutive'] }}

<x-mail::panel>
Por favor, acércate a coordinación o contacta a tu instructor para justificar estas ausencias y evitar sanciones disciplinarias.
</x-mail::panel>

<x-mail::button :url="route('student.dashboard')">
Ver mi Asistencia
</x-mail::button>

Atentamente,<br>
{{ config('app.name') }}
</x-mail::message>
