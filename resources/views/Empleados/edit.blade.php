@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        max-width: 97%;
        background-color: #f0faff;
        border-color: #91cfff;
    }
    label {
        font-size: 0.85rem;
    }
    input, select, textarea {
        font-size: 0.85rem !important;
    }
    .input-corto {
        width: 100% !important;
    }
</style>

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 56px);">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <h4 class="mb-0">Editar empleado</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    @php
                        $inputClass = 'form-control form-control-sm input-corto';
                        $selectClass = 'form-select form-select-sm input-corto';
                    @endphp

                    <div class="col-md-4">
                        <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres" class="{{ $inputClass }} @error('nombres') is-invalid @enderror" value="{{ old('nombres', $empleado->nombres) }}">
                        @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" name="apellidos" class="{{ $inputClass }} @error('apellidos') is-invalid @enderror" value="{{ old('apellidos', $empleado->apellidos) }}">
                        @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="identidad" class="form-label">Identidad <span class="text-danger">*</span></label>
                        <input type="text" name="identidad" class="{{ $inputClass }} @error('identidad') is-invalid @enderror" value="{{ old('identidad', $empleado->identidad) }}">
                        @error('identidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="correo" class="form-label">Correo <span class="text-danger">*</span></label>
                        <input type="email" name="correo" class="{{ $inputClass }} @error('correo') is-invalid @enderror" value="{{ old('correo', $empleado->correo) }}">
                        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                        <input type="text" name="telefono" class="{{ $inputClass }} @error('telefono') is-invalid @enderror" value="{{ old('telefono', $empleado->telefono) }}">
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="estado_civil" class="form-label">Estado Civil <span class="text-danger">*</span></label>
                        <select name="estado_civil" class="{{ $selectClass }} @error('estado_civil') is-invalid @enderror">
                            <option value="">Seleccione</option>
                            <option value="Casado" {{ old('estado_civil', $empleado->estado_civil) == 'Casado' ? 'selected' : '' }}>Casado</option>
                            <option value="Soltero" {{ old('estado_civil', $empleado->estado_civil) == 'Soltero' ? 'selected' : '' }}>Soltero</option>
                            <option value="Viudo" {{ old('estado_civil', $empleado->estado_civil) == 'Viudo' ? 'selected' : '' }}>Viudo</option>
                            <option value="Divorciado" {{ old('estado_civil', $empleado->estado_civil) == 'Divorciado' ? 'selected' : '' }}>Divorciado</option>
                        </select>
                        @error('estado_civil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
                        <select name="genero" class="{{ $selectClass }} @error('genero') is-invalid @enderror">
                            <option value="">Seleccione</option>
                            <option value="Masculino" {{ old('genero', $empleado->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero', $empleado->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero', $empleado->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_ingreso" class="{{ $inputClass }} @error('fecha_ingreso') is-invalid @enderror" value="{{ old('fecha_ingreso', $empleado->fecha_ingreso) }}">
                        @error('fecha_ingreso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_nacimiento" class="{{ $inputClass }} @error('fecha_nacimiento') is-invalid @enderror" value="{{ old('fecha_nacimiento', $empleado->fecha_nacimiento) }}">
                        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="salario" class="form-label">Salario <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="salario" class="{{ $inputClass }} @error('salario') is-invalid @enderror" value="{{ old('salario', $empleado->salario) }}">
                        @error('salario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="puesto_id" class="form-label">Puesto <span class="text-danger">*</span></label>
                        <select name="puesto_id" class="{{ $selectClass }} @error('puesto_id') is-invalid @enderror">
                            <option value="">Seleccione un puesto</option>
                            @foreach($puestos as $puesto)
                                <option value="{{ $puesto->id }}" {{ old('puesto_id', $empleado->puesto_id) == $puesto->id ? 'selected' : '' }}>
                                    {{ $puesto->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('puesto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="estado" class="{{ $selectClass }} @error('estado') is-invalid @enderror">
                            <option value="Activo" {{ old('estado', $empleado->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ old('estado', $empleado->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="direccion" class="form-label">Dirección <span class="text-danger">*</span></label>
                        <textarea name="direccion" rows="3" class="{{ $inputClass }} @error('direccion') is-invalid @enderror" style="resize: vertical;">{{ old('direccion', $empleado->direccion) }}</textarea>
                        @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea name="observaciones" rows="3" class="{{ $inputClass }} @error('observaciones') is-invalid @enderror" style="resize: vertical;">{{ old('observaciones', $empleado->observaciones) }}</textarea>
                        @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Actualizar
                    </button>
                    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('textarea').forEach(function (el) {
            el.style.overflow = 'hidden';
            el.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            el.dispatchEvent(new Event('input'));
        });
    });
</script>
@endpush
