<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llamado de Atención - {{ $disciplinaryAction->student->nombre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .header-text {
            text-align: center;
            flex-grow: 1;
        }
        .header-text h1 {
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
        }
        .header-text p {
            font-size: 10pt;
            margin: 0;
        }
        .meta-info {
            font-size: 9pt;
            text-align: right;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }
        .field {
            margin-bottom: 5px;
        }
        .field-label {
            font-weight: bold;
        }
        .content-box {
            border: 1px solid #000;
            padding: 10px;
            min-height: 100px;
            margin-bottom: 10px;
        }
        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            border-top: 1px solid #000;
            padding-top: 5px;
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            .footer {
                position: fixed;
                bottom: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #fc7323; color: white; border: none; cursor: pointer; font-weight: bold;">IMPRIMIR / GUARDAR PDF</button>
        <a href="{{ route('students.disciplinary_actions.index', $disciplinaryAction->student) }}" style="margin-left: 10px; text-decoration: none; color: #333;">Volver</a>
    </div>

    <div class="header">
        <img src="{{ asset('images/logoSena.png') }}" alt="Logo SENA" class="logo">
        <div class="header-text">
            <h1>Llamado de Atención {{ $disciplinaryAction->tipo_falta }}</h1>
            <p>Proceso: Ejecución de la Formación</p>
            <p>Procedimiento: Gestión de Proyectos Formativos</p>
        </div>
        <div class="meta-info">
            <p>Código: F002-008-25</p>
            <p>Versión: 01</p>
        </div>
    </div>

    <div class="section">
        <div class="field"><span class="field-label">Ciudad y Fecha:</span> Bogotá D.C., {{ $disciplinaryAction->date->format('d/m/Y') }}</div>
        <div class="field"><span class="field-label">Centro de Formación:</span> Centro de Gestión de Mercados, Logística y TI</div>
    </div>

    <div class="section">
        <div class="section-title">DATOS DEL APRENDIZ</div>
        <div class="field"><span class="field-label">Nombre:</span> {{ $disciplinaryAction->student->nombre }}</div>
        <div class="field"><span class="field-label">Identificación:</span> {{ $disciplinaryAction->student->documento }}</div>
        <div class="field"><span class="field-label">Programa:</span> {{ $disciplinaryAction->student->group->program->nombre }}</div>
        <div class="field"><span class="field-label">Ficha:</span> {{ $disciplinaryAction->student->group->numero_ficha }}</div>
        <div class="field"><span class="field-label">Grupo:</span> {{ $disciplinaryAction->student->group->nombre }}</div>
    </div>

    <div class="section">
        <div class="section-title">DETALLES DEL LLAMADO</div>
        <div class="field"><span class="field-label">Tipo de Llamado:</span> {{ $disciplinaryAction->tipo_llamado === 'written' ? 'ESCRITO' : 'VERBAL' }}</div>
        <div class="field"><span class="field-label">Gravedad:</span> {{ $disciplinaryAction->gravedad }}</div>
        
        @if($disciplinaryAction->tipo_falta === 'Disciplinaria' && $disciplinaryAction->disciplinaryFault)
            <div class="field" style="margin-top: 10px;">
                <span class="field-label">Literal Infringido (Capítulo IV):</span><br>
                <strong>{{ $disciplinaryAction->disciplinaryFault->codigo }}:</strong> {{ $disciplinaryAction->disciplinaryFault->description }}
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">DESCRIPCIÓN DE LOS HECHOS / MOTIVO</div>
        <div class="content-box">
            {{ $disciplinaryAction->description }}
        </div>
    </div>

    @if($disciplinaryAction->orientations_or_recommendations)
    <div class="section">
        <div class="section-title">
            {{ $disciplinaryAction->tipo_falta === 'Académica' ? 'ORIENTACIONES ACADÉMICAS' : 'RECOMENDACIONES DISCIPLINARIAS' }}
        </div>
        <div class="content-box">
            {{ $disciplinaryAction->orientations_or_recommendations }}
        </div>
    </div>
    @endif

    <div class="signatures">
        <div class="signature-box">
            <br><br>
            ______________________________________<br>
            <strong>Firma del Instructor / Responsable</strong><br>
            CC.
        </div>
        <div class="signature-box">
            <br><br>
            ______________________________________<br>
            <strong>Firma del Aprendiz</strong><br>
            CC. {{ $disciplinaryAction->student->documento }}
        </div>
    </div>

    <div class="footer">
        Servicio Nacional de Aprendizaje SENA - Dirección General<br>
        Calle 57 No. 8 - 69 Bogotá D.C. (Cundinamarca), Colombia<br>
        www.sena.edu.co - 01 8000 910270
    </div>

</body>
</html>
