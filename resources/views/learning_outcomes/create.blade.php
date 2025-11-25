<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Resultado de Aprendizaje') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Crear RA para: {{ $competencia->nombre }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('competencias.learning_outcomes.store', $competencia) }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control" required placeholder="Ej: 21010100101">
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
                    <a href="{{ route('competencias.learning_outcomes.index', $competencia) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Crear Resultado de Aprendizaje</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
