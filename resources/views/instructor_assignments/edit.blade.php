<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Asignar Instructores - Ficha {{ $group->numero_ficha }}
            </h2>
            <a href="{{ route('instructor_assignments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4">
                    <h4 class="font-semibold text-lg">{{ $group->program->nombre }}</h4>
                    <p class="text-gray-600">
                        <strong>Ficha:</strong> {{ $group->numero_ficha }} | 
                        <strong>Jornada:</strong> {{ $group->jornada }} |
                        <strong>Inicio:</strong> {{ \Carbon\Carbon::parse($group->fecha_inicio)->format('d/m/Y') }}
                    </p>
                </div>

                <form action="{{ route('instructor_assignments.update', $group) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i>
                        <strong>Instrucciones:</strong> Seleccione un instructor para cada competencia. 
                        Puede dejar competencias sin asignar si aún no tienen instructor.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10%">Código</th>
                                    <th style="width: 50%">Competencia</th>
                                    <th style="width: 40%">Instructor Asignado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($competencias as $competencia)
                                    <tr>
                                        <td><strong>{{ $competencia->codigo }}</strong></td>
                                        <td>
                                            <div class="text-sm">{{ $competencia->nombre }}</div>
                                        </td>
                                        <td>
                                            <select name="assignments[{{ $competencia->id }}]" 
                                                    class="form-select">
                                                <option value="">-- Sin asignar --</option>
                                                @foreach($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}"
                                                        {{ isset($assignments[$competencia->id]) && $assignments[$competencia->id]->instructor_id == $instructor->id ? 'selected' : '' }}>
                                                        {{ $instructor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            Este programa no tiene competencias registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($competencias->count() > 0)
                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('instructor_assignments.index') }}" class="btn btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Guardar Asignaciones
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
