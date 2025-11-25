<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Competencia') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Crear Competencia para: {{ $program->nombre }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('programs.competencias.store', $program) }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control" required placeholder="Ej: 210101001">
                        <small class="text-muted">Código único de la competencia</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="activo" class="form-select">
                            <option value="1" selected>Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="4"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('programs.competencias.index', $program) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Crear Competencia</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
