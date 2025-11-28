<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Planes de Mejoramiento') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-clipboard2-check"></i> Planes de Mejoramiento</h1>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Estudiante</th>
                    <th>Ficha</th>
                    <th>Tipo Falta</th>
                    <th>Gravedad</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Responsable</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td>{{ $plan->disciplinaryAction->student->nombre }}</td>
                        <td>{{ $plan->disciplinaryAction->student->group->numero_ficha }}</td>
                        <td>{{ $plan->disciplinaryAction->tipo_falta }}</td>
                        <td>
                            <span class="badge bg-{{ $plan->disciplinaryAction->gravedad == 'Leve' ? 'warning' : ($plan->disciplinaryAction->gravedad == 'Grave' ? 'danger' : 'dark') }}">
                                {{ $plan->disciplinaryAction->gravedad }}
                            </span>
                        </td>
                        <td>{{ $plan->start_date->format('d/m/Y') }}</td>
                        <td>{{ $plan->end_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $plan->status == 'Cumplido' ? 'success' : ($plan->status == 'Incumplido' ? 'danger' : 'warning') }}">
                                {{ $plan->status }}
                            </span>
                        </td>
                        <td>{{ $plan->instructor->nombre }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('improvement_plans.show', $plan) }}" class="btn btn-info btn-sm" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(!auth()->user()->isCoordinator())
                                <a href="{{ route('improvement_plans.edit', $plan) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                <a href="{{ route('improvement_plans.print', $plan) }}" class="btn btn-secondary btn-sm" title="Imprimir / PDF" target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No hay planes de mejoramiento registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $plans->links() }}
</x-app-layout>
