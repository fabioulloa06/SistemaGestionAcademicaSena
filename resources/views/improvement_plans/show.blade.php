<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Plan de Mejoramiento') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-file-earmark-text"></i> Plan de Mejoramiento {{ $improvementPlan->type }}</h1>
        <div>
            <a href="{{ route('improvement_plans.print', $improvementPlan) }}" class="btn btn-secondary" target="_blank">
                <i class="bi bi-printer"></i> Imprimir / PDF
            </a>
            <a href="{{ route('improvement_plans.edit', $improvementPlan) }}" class="btn btn-warning ms-2">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('improvement_plans.index') }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información del Plan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Estado:</strong>
                            <span class="badge bg-{{ $improvementPlan->status == 'Cumplido' ? 'success' : ($improvementPlan->status == 'Incumplido' ? 'danger' : 'warning') }}">
                                {{ $improvementPlan->status }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Tipo:</strong> {{ $improvementPlan->type }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Fecha Inicio:</strong> {{ $improvementPlan->start_date->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Fecha Fin (Límite):</strong> {{ $improvementPlan->end_date->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Descripción / Compromisos:</strong>
                        <p class="border p-3 bg-light rounded">{{ $improvementPlan->description }}</p>
                    </div>

                    @if($improvementPlan->observations)
                        <div class="mb-3">
                            <strong>Observaciones:</strong>
                            <p class="border p-3 bg-light rounded">{{ $improvementPlan->observations }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($improvementPlan->disciplinaryAction)
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Antecedentes (Llamado de Atención)</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Fecha:</strong> {{ $improvementPlan->disciplinaryAction->date->format('d/m/Y') }}</p>
                        <p><strong>Tipo:</strong> {{ $improvementPlan->disciplinaryAction->tipo_llamado == 'written' ? 'Escrito' : 'Verbal' }}</p>
                        <p><strong>Gravedad:</strong> {{ $improvementPlan->disciplinaryAction->gravedad }}</p>
                        <p><strong>Motivo:</strong> {{ $improvementPlan->disciplinaryAction->description }}</p>
                        @if($improvementPlan->disciplinaryAction->disciplinaryFault)
                            <div class="alert alert-secondary">
                                <strong>Literal Infringido:</strong><br>
                                {{ $improvementPlan->disciplinaryAction->disciplinaryFault->codigo }} - {{ $improvementPlan->disciplinaryAction->disciplinaryFault->description }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Datos del Aprendiz</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ $improvementPlan->student->nombre }}</p>
                    <p><strong>Documento:</strong> {{ $improvementPlan->student->documento }}</p>
                    <p><strong>Ficha:</strong> {{ $improvementPlan->student->group->numero_ficha }}</p>
                    <p><strong>Programa:</strong> {{ $improvementPlan->student->group->program->nombre }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Responsable</h5>
                </div>
                <div class="card-body">
                    <p><strong>Instructor:</strong> {{ $improvementPlan->instructor->nombre }}</p>
                    <p><strong>Especialidad:</strong> {{ $improvementPlan->instructor->especialidad }}</p>
                    <p><strong>Email:</strong> {{ $improvementPlan->instructor->email }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
