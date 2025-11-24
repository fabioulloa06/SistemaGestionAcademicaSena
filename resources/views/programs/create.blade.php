<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Programa') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('programs.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Nombre del Programa</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Código</label>
                    <input type="text" name="codigo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Duración (Meses)</label>
                    <input type="number" name="duracion_meses" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nivel de Formación</label>
                    <select name="nivel" class="form-select" required>
                        <option value="Tecnico">Técnico</option>
                        <option value="Tecnologo">Tecnólogo</option>
                        <option value="Especializacion">Especialización</option>
                        <option value="Curso Corto">Curso Corto</option>
                    </select>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="activo" class="form-check-input" value="1" checked>
                    <label class="form-check-label">Activo</label>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('programs.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</x-app-layout>
