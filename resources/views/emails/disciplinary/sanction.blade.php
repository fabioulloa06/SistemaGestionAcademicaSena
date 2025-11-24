<x-mail::message>
# Notificación de Proceso Disciplinario

Hola **{{ $student->nombre }}**,

Se ha registrado un nuevo reporte disciplinario en tu historial.

**Detalles del Reporte:**
* **Tipo de Falta:** {{ $action->tipo_falta }}
* **Descripción:** {{ $action->descripcion }}
* **Fecha:** {{ $action->created_at->format('d/m/Y') }}

<x-mail::panel>
Este reporte ha sido registrado en el sistema. Si tienes dudas, por favor contacta a Bienestar al Aprendiz.
</x-mail::panel>

<x-mail::button :url="route('student.dashboard')">
Ver Historial Disciplinario
</x-mail::button>

Atentamente,<br>
{{ config('app.name') }}
</x-mail::message>
