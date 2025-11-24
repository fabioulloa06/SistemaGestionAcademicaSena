<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles de la Ficha') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-4">
                    <h3 class="text-lg font-bold">Ficha: {{ $group->numero_ficha }}</h3>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Programa:</strong> {{ $group->program->nombre ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Jornada:</strong> {{ $group->jornada_formateada }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Fecha Inicio:</strong> {{ $group->fecha_inicio->format('d/m/Y') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Fecha Fin:</strong> {{ $group->fecha_fin->format('d/m/Y') }}
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Estado:</strong> 
                    <span class="badge {{ $group->activo ? 'bg-success' : 'bg-danger' }}">
                        {{ $group->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                <div class="mb-4">
                    <strong>Total de Estudiantes:</strong> {{ $group->students->count() }}
                </div>

                <div class="mt-4">
                    <a href="{{ route('groups.edit', $group) }}" class="btn btn-warning">Editar</a>
                    <a href="{{ route('groups.index') }}" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
