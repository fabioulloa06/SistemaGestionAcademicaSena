<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calificaciones') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-clipboard-check-fill"></i> Sistema de Calificaciones</h1>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i> Seleccione una ficha para calificar los Resultados de Aprendizaje de sus estudiantes.
    </div>

    <div class="row">
        @forelse($groups as $group)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-collection"></i> Ficha {{ $group->numero_ficha }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Programa:</strong> {{ $group->program->nombre }}</p>
                        <p><strong>Jornada:</strong> {{ $group->jornada }}</p>
                        <p><strong>Estudiantes:</strong> {{ $group->students->count() }}</p>
                        <p><strong>Competencias:</strong> {{ $group->program->competencias->count() }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('grading.grade', $group) }}" class="btn btn-primary w-100">
                            <i class="bi bi-pencil-square"></i> Calificar
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> No hay fichas activas disponibles.
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
