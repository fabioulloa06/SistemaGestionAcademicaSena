<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Llamado de Atención') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header bg-danger text-white">
            <h3 class="card-title mb-0">Registrar Falta para: {{ $student->nombre }}</h3>
        </div>
        <div class="card-body">
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-info">
                <strong>Estado Actual:</strong><br>
                Llamados Académicos Escritos: {{ $academicCalls }} / 2<br>
                Llamados Disciplinarios Escritos: {{ $disciplinaryCalls }} / 2
            </div>

            <form action="{{ route('students.disciplinary_actions.store', $student) }}" method="POST" id="actionForm">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha de la Falta</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Falta</label>
                        <select name="tipo_falta" id="tipo_falta" class="form-select" required onchange="updateForm()">
                            <option value="Académica">Académica</option>
                            <option value="Disciplinaria">Disciplinaria</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Llamado</label>
                        <select name="tipo_llamado" id="tipo_llamado" class="form-select" required onchange="calculateGravity()">
                            <option value="verbal">Verbal (Medida Formativa)</option>
                            <option value="written">Escrito (Formal)</option>
                        </select>
                        <small class="text-muted">Verbal: preventivo. Escrito: cuenta para el límite de 2.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gravedad (Calculada Automáticamente)</label>
                        <select name="gravedad" id="gravedad" class="form-select" required disabled>
                            <option value="Leve">Leve</option>
                            <option value="Grave">Grave</option>
                            <option value="Gravísima">Gravísima</option>
                        </select>
                        <small class="text-muted" id="gravity_explanation">Se calcula según reincidencia y tipo de falta</small>
                    </div>
                </div>

                <!-- Campo para Razones Académicas (Solo Académica) -->
                <div class="mb-3" id="academic_faults_div" style="display: none;">
                    <label class="form-label">Razón Académica</label>
                    <select name="academic_fault_id" id="academic_fault_select" class="form-select">
                        <option value="">Seleccione una razón...</option>
                        @foreach($academicFaults as $fault)
                            <option value="{{ $fault->id }}" title="{{ $fault->description }}">
                                {{ $fault->codigo }} - {{ Str::limit($fault->description, 100) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Seleccione la razón académica que motiva este llamado.</small>
                </div>

                <!-- Campo para Literales (Solo Disciplinaria) -->
                <div class="mb-3" id="faults_div" style="display: none;">
                    <label class="form-label">Literal Infringido (Reglamento del Aprendiz)</label>
                    <select name="disciplinary_fault_id" id="disciplinary_fault_select" class="form-select">
                        <option value="">Seleccione un literal...</option>
                        @foreach($faults as $fault)
                            <option value="{{ $fault->id }}" title="{{ $fault->description }}">
                                {{ $fault->codigo }} - {{ Str::limit($fault->description, 100) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Seleccione el literal del Capítulo IV (Prohibiciones).</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción de la Falta</label>
                    <textarea name="description" class="form-control" rows="4" required placeholder="Describa detalladamente los hechos..."></textarea>
                </div>

                <!-- Campo para Orientaciones (Solo 2do llamado escrito) -->
                <div class="mb-3" id="orientations_div" style="display: none;">
                    <label class="form-label fw-bold text-danger" id="orientations_label">Orientaciones / Recomendaciones (OBLIGATORIO)</label>
                    <textarea name="orientations_or_recommendations" class="form-control" rows="3" placeholder="Escriba las orientaciones académicas o recomendaciones disciplinarias..."></textarea>
                    <small class="text-danger">Este es el segundo llamado. Es obligatorio incluir orientaciones/recomendaciones.</small>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('students.disciplinary_actions.index', $student) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-danger">Registrar Llamado de Atención</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Literales considerados graves o gravísimos
        const graveLiterals = [3, 6, 7, 9, 10]; // Alterar documentos, drogas, armas, delitos, destrucción
        const veryGraveLiterals = [2, 9]; // Suplantar identidad, delitos

        function calculateGravity() {
            const tipoFalta = document.getElementById('tipo_falta').value;
            const tipoLlamado = document.getElementById('tipo_llamado').value;
            const gravedadSelect = document.getElementById('gravedad');
            const explanation = document.getElementById('gravity_explanation');
            
            // Datos del servidor
            const academicCalls = {{ $academicCalls }};
            const disciplinaryCalls = {{ $disciplinaryCalls }};
            
            let currentCount = tipoFalta === 'Académica' ? academicCalls : disciplinaryCalls;
            let gravity = 'Leve';
            let reason = '';

            // Regla 1: Llamados verbales siempre son Leves
            if (tipoLlamado === 'verbal') {
                gravity = 'Leve';
                reason = 'Llamado verbal (medida formativa)';
            }
            // Regla 2: Primer llamado escrito = Leve
            else if (currentCount === 0) {
                gravity = 'Leve';
                reason = 'Primer llamado escrito';
            }
            // Regla 3: Segundo llamado escrito = Grave (reincidencia)
            else if (currentCount === 1) {
                gravity = 'Grave';
                reason = 'Segundo llamado (reincidencia)';
            }
            // Regla 4: Tercer llamado = Gravísima (ya debería estar en plan de mejoramiento)
            else if (currentCount >= 2) {
                gravity = 'Gravísima';
                reason = 'Tercer llamado o más (incumplimiento plan)';
            }

            // Regla 5: Faltas disciplinarias con literales específicos pueden ser más graves
            if (tipoFalta === 'Disciplinaria' && tipoLlamado === 'written') {
                const literalSelect = document.querySelector('select[name="disciplinary_fault_id"]');
                if (literalSelect && literalSelect.value) {
                    const literalId = parseInt(literalSelect.value);
                    if (veryGraveLiterals.includes(literalId)) {
                        gravity = 'Gravísima';
                        reason = 'Literal gravísimo (suplantación/delitos)';
                    } else if (graveLiterals.includes(literalId) && gravity === 'Leve') {
                        gravity = 'Grave';
                        reason = 'Literal grave (armas/drogas/documentos)';
                    }
                }
            }

            // Actualizar el select
            gravedadSelect.value = gravity;
            explanation.textContent = reason;
            
            // Habilitar temporalmente para enviar el valor
            gravedadSelect.disabled = false;
            
            // Llamar a updateForm para mantener la lógica existente
            updateForm();
        }

        function updateForm() {
            const tipoFalta = document.getElementById('tipo_falta').value;
            const tipoLlamado = document.getElementById('tipo_llamado').value;
            const faultsDiv = document.getElementById('faults_div');
            const academicFaultsDiv = document.getElementById('academic_faults_div');
            const orientationsDiv = document.getElementById('orientations_div');
            const orientationsLabel = document.getElementById('orientations_label');

            // Mostrar Razones Académicas o Literales Disciplinarios según el tipo
            if (tipoFalta === 'Académica') {
                academicFaultsDiv.style.display = 'block';
                faultsDiv.style.display = 'none';
            } else {
                academicFaultsDiv.style.display = 'none';
                faultsDiv.style.display = 'block';
            }

            // Lógica para Orientaciones (Simulada en cliente, validada en servidor)
            // Usamos las variables pasadas desde el controlador
            const academicCalls = {{ $academicCalls }};
            const disciplinaryCalls = {{ $disciplinaryCalls }};
            
            let currentCount = 0;
            if (tipoFalta === 'Académica') currentCount = academicCalls;
            else currentCount = disciplinaryCalls;

            // Si es escrito y ya tiene 1, este sería el 2do -> Mostrar Orientaciones
            if (tipoLlamado === 'written' && currentCount === 1) {
                orientationsDiv.style.display = 'block';
                if (tipoFalta === 'Académica') {
                    orientationsLabel.innerText = 'Orientaciones Académicas (OBLIGATORIO)';
                } else {
                    orientationsLabel.innerText = 'Recomendaciones Disciplinarias (OBLIGATORIO)';
                }
            } else {
                orientationsDiv.style.display = 'none';
            }
            
            // Recalcular gravedad
            calculateGravity();
        }

        // Ejecutar al cargar
        document.addEventListener('DOMContentLoaded', function() {
            updateForm();
            
            // Agregar listener al select de literales disciplinarios
            const literalSelect = document.getElementById('disciplinary_fault_select');
            if (literalSelect) {
                literalSelect.addEventListener('change', calculateGravity);
            }
            
            // Agregar listener al select de razones académicas
            const academicSelect = document.getElementById('academic_fault_select');
            if (academicSelect) {
                academicSelect.addEventListener('change', calculateGravity);
            }
            
            // Agregar listener al tipo de falta
            document.getElementById('tipo_falta').addEventListener('change', updateForm);
        });
    </script>
</x-app-layout>

