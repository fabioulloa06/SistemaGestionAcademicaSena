<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asignar Instructores') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Asignar Instructores a: {{ $competencia->nombre }}</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle"></i> Seleccione los instructores que dictarán esta competencia. Puede asignar múltiples instructores y cambiarlos cuando sea necesario.
            </div>

            <form action="{{ route('competencias.store_instructors', $competencia) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label"><strong>Instructores Asignados</strong></label>
                    <div class="row">
                        @foreach($instructors as $instructor)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="instructors[]" 
                                           value="{{ $instructor->id }}" 
                                           id="instructor{{ $instructor->id }}"
                                           {{ in_array($instructor->id, $assignedInstructors) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="instructor{{ $instructor->id }}">
                                        {{ $instructor->nombre }}
                                        @if($instructor->especialidad)
                                            <small class="text-muted">({{ $instructor->especialidad }})</small>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('programs.competencias.index', $competencia->program) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Asignación
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
