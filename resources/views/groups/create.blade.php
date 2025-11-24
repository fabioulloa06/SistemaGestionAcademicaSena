<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Ficha') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('groups.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Número de Ficha</label>
                        <input type="text" name="numero_ficha" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Programa de Formación</label>
                        <select name="program_id" id="program_id" class="form-select" required>
                            <option value="">Seleccione un programa</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" data-duration="{{ $program->duracion_meses }}">
                                    {{ $program->nombre }} ({{ $program->duracion_meses }} meses)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jornada</label>
                        <select name="jornada" class="form-select" required>
                            <option value="mañana">Mañana</option>
                            <option value="tarde">Tarde</option>
                            <option value="noche">Noche</option>
                            <option value="mixta">Mixta</option>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Fin (Calculada Automáticamente)</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                            <small class="text-muted">Se calcula automáticamente según la duración del programa</small>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="activo" class="form-check-input" value="1" checked>
                        <label class="form-check-label">Activo</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const programSelect = document.getElementById('program_id');
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');

            function calculateEndDate() {
                const selectedOption = programSelect.options[programSelect.selectedIndex];
                const duration = selectedOption.getAttribute('data-duration');
                const startDate = fechaInicio.value;

                if (duration && startDate) {
                    const start = new Date(startDate);
                    start.setMonth(start.getMonth() + parseInt(duration));
                    
                    const year = start.getFullYear();
                    const month = String(start.getMonth() + 1).padStart(2, '0');
                    const day = String(start.getDate()).padStart(2, '0');
                    
                    fechaFin.value = `${year}-${month}-${day}`;
                }
            }

            programSelect.addEventListener('change', calculateEndDate);
            fechaInicio.addEventListener('change', calculateEndDate);
        });
    </script>
</x-app-layout>
