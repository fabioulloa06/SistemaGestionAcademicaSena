<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Asignación de Instructores
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Seleccione un Grupo para Asignar Instructores</h3>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Número de Ficha</th>
                                <th>Programa</th>
                                <th>Jornada</th>
                                <th>Fecha Inicio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $group)
                                <tr>
                                    <td><strong>{{ $group->numero_ficha }}</strong></td>
                                    <td>{{ $group->program->nombre }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $group->jornada }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($group->fecha_inicio)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('instructor_assignments.edit', $group) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-person-plus"></i> Asignar Instructores
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No hay grupos activos disponibles.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
