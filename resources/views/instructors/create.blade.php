<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Instructor') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('instructors.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Documento</label>
                    <input type="text" name="documento" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Especialidad</label>
                    <input type="text" name="especialidad" class="form-control">
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="activo" class="form-check-input" value="1" checked>
                    <label class="form-check-label">Activo</label>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('instructors.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</x-app-layout>
