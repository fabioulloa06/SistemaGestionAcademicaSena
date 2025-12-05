<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Plan de Mejoramiento') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title mb-0">Actualizar Plan de Mejoramiento</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('improvement_plans.update', $improvementPlan) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Inicio</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $improvementPlan->start_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de Fin</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $improvementPlan->end_date->format('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Estado del Plan</label>
                        <select name="status" class="form-select" required>
                            <option value="Pendiente" {{ $improvementPlan->status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="En Progreso" {{ $improvementPlan->status == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                            <option value="Cumplido" {{ $improvementPlan->status == 'Cumplido' ? 'selected' : '' }}>Cumplido</option>
                            <option value="Incumplido" {{ $improvementPlan->status == 'Incumplido' ? 'selected' : '' }}>Incumplido</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Instructor Responsable</label>
                        <select name="instructor_id" class="form-select" required>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ $improvementPlan->instructor_id == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción del Plan</label>
                    <textarea name="description" class="form-control" rows="6" required>{{ $improvementPlan->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observations" class="form-control" rows="3">{{ $improvementPlan->observations }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('improvement_plans.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Plan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
