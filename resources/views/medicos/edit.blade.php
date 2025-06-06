@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Editar Médico</h4>
                <a href="{{ route('medicos.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver a la lista
                </a>
            </div>

            <div class="card-body">
                {{-- Mostrar errores de validación --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Corrige los siguientes errores:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Formulario de edición --}}
                <form action="{{ route('medicos.update', $medico->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $medico->nombre) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', $medico->apellidos) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="especialidad" class="form-control" value="{{ old('especialidad', $medico->especialidad) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $medico->telefono) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control" value="{{ old('correo', $medico->correo) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $medico->fecha_nacimiento) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fecha de Ingreso</label>
                            <input type="date" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso', $medico->fecha_ingreso) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Género</label>
                            <select name="genero" class="form-select" required>
                                <option value="">Seleccionar</option>
                                <option value="Masculino" {{ old('genero', $medico->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero', $medico->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero', $medico->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $medico->observaciones) }}</textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto actual</label><br>
                        @if ($medico->foto)
                            <img src="{{ asset('storage/' . $medico->foto) }}" alt="Foto actual"
                                 class="img-thumbnail mb-2" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <p class="text-muted">No hay foto disponible</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Cambiar Foto</label>
                        <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror">
                        @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" onclick="confirmarCancelacion()">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </button>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function confirmarCancelacion() {
            if (confirm('¿Deseas cancelar la actualización del médico?')) {
                window.location.href = "{{ route('medicos.index') }}";
            }
        }
    </script>
@endpush


