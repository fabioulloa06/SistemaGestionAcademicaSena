<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Plan de Mejoramiento') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h3 class="card-title mb-0">
                Crear Plan de Mejoramiento {{ $type }}
                @if($student)
                    para: {{ $student->nombre }}
                @endif
            </h3>
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

            @if(session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('improvement_plans.store') }}" method="POST">
                @csrf
                
                <input type="hidden" name="type" value="{{ $type }}">
                
                @if($student)
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                @else
                    <div class="alert alert-warning">
                        Advertencia: No se ha seleccionado un estudiante. Este formulario debe accederse desde un Llamado de Atención o Perfil de Estudiante.
                    </div>
                    <!-- Fallback simple input if needed, but ideally should be locked -->
                    <div class="mb-3">
                        <label class="form-label">ID Estudiante</label>
                        <input type="number" name="student_id" class="form-control" required>
                    </div>
                @endif

                @if($disciplinaryAction)
                    <input type="hidden" name="disciplinary_action_id" value="{{ $disciplinaryAction->id }}">
                    <div class="alert alert-secondary">
                        <strong>Vinculado al Llamado de Atención del:</strong> {{ $disciplinaryAction->date->format('d/m/Y') }}<br>
                        <strong>Motivo:</strong> {{ $disciplinaryAction->description }}
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Inicio</label>
                        <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Fin (Límite)</label>
                        <input type="date" name="end_date" class="form-control" required>
                        <small class="text-muted">Máximo 20 días calendario para planes académicos.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Instructor Responsable</label>
                    <select name="instructor_id" class="form-select" required>
                        <option value="">Seleccione un instructor...</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}">{{ $instructor->nombre }} - {{ $instructor->especialidad }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción del Plan (Actividades / Compromisos)</label>
                    <textarea name="description" class="form-control" rows="5" required placeholder="Detalle las actividades de aprendizaje, evidencias requeridas o compromisos comportamentales..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observations" class="form-control" rows="2"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('improvement_plans.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning">Crear Plan de Mejoramiento</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
