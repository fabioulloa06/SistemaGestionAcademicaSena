<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Ficha') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('groups.update', $group) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Número de Ficha</label>
                        <input type="text" name="numero_ficha" class="form-control" value="{{ $group->numero_ficha }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Programa de Formación</label>
                        <select name="program_id" class="form-select" required>
                            <option value="">Seleccione un programa</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ $group->program_id == $program->id ? 'selected' : '' }}>
                                    {{ $program->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jornada</label>
                        <select name="jornada" class="form-select" required>
                            <option value="mañana" {{ $group->jornada == 'mañana' ? 'selected' : '' }}>Mañana</option>
                            <option value="tarde" {{ $group->jornada == 'tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="noche" {{ $group->jornada == 'noche' ? 'selected' : '' }}>Noche</option>
                            <option value="mixta" {{ $group->jornada == 'mixta' ? 'selected' : '' }}>Mixta</option>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="{{ $group->fecha_inicio->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" value="{{ $group->fecha_fin->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="activo" class="form-check-input" value="1" {{ $group->activo ? 'checked' : '' }}>
                        <label class="form-check-label">Activo</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
