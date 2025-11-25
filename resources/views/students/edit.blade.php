<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Estudiante') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('students.update', $student) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" value="{{ $student->nombre }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Documento</label>
                        <input type="text" name="documento" class="form-control" value="{{ $student->documento }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ $student->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ $student->telefono }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Grupo/Ficha</label>
                        <select name="group_id" class="form-select" required>
                            <option value="">Seleccione un grupo</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ $student->group_id == $group->id ? 'selected' : '' }}>
                                    {{ $group->numero_ficha }} - {{ $group->program->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="activo" class="form-check-input" value="1" {{ $student->activo ? 'checked' : '' }}>
                        <label class="form-check-label">Activo</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
