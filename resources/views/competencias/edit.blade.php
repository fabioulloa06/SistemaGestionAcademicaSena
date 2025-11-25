<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Competencia') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Editar: {{ $competencia->nombre }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('competencias.update', $competencia) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control" value="{{ $competencia->codigo }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="activo" class="form-select">
                            <option value="1" {{ $competencia->activo ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !$competencia->activo ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $competencia->nombre }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="4">{{ $competencia->descripcion }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('programs.competencias.index', $competencia->program) }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Competencia</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
