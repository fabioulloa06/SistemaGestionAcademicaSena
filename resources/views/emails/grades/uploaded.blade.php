<x-mail::message>
# Nueva Calificación Registrada

Hola **{{ $student->nombre }}**,

Se ha registrado una nueva calificación en tu historial académico.

**Detalles:**
* **Competencia/RA:** {{ $gradeDetails['competencia'] }}
* **Calificación:** {{ $gradeDetails['grade'] }}
* **Fecha:** {{ now()->format('d/m/Y') }}

<x-mail::button :url="route('student.dashboard')">
Ver Mis Calificaciones
</x-mail::button>

Atentamente,<br>
{{ config('app.name') }}
</x-mail::message>
