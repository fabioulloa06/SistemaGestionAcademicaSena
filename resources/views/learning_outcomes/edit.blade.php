<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Resultado de Aprendizaje') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Editar: {{ $learningOutcome->nombre }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('learning_outcomes.update', $learningOutcome) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control" value="{{ $learningOutcome->codigo }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="activo" class="form-select">
                            <option value="1" {{ $learningOutcome->activo ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !$learningOutcome->activo ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $learningOutcome->nombre }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="4">{{ $learningOutcome->descripcion }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('competencias.learning_outcomes.index', $learningOutcome->competencia) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar RA</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
