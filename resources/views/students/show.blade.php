<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1>👁 Detalle del Estudiante</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $student->nombre }}</p>
            <p><strong>Documento:</strong> {{ $student->documento }}</p>
            <p><strong>Email:</strong> {{ $student->email }}</p>
            <p><strong>Teléfono:</strong> {{ $student->telefono }}</p>
            <p><strong>Grupo:</strong> {{ $student->grupo_id }}</p>
        </div>
    </div>

    <a href="{{ route('students.index') }}" class="btn btn-primary mt-3">⬅ Volver</a>
</div>

</body>
</html>
