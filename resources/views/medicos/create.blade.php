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
</style>

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 56px);">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <h2 class="mb-0">Registrar nuevo médico</h2>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @php
                    $maxNacimiento = now()->subYears(18)->format('Y-m-d');
                    $minIngreso = now()->subMonth()->format('Y-m-d');
                    $maxIngreso = now()->addMonth()->format('Y-m-d');
                @endphp

                <form method="POST" action="{{ route('medicos.store') }}" enctype="multipart/form-data" id="formMedico" novalidate>
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                class="form-control @error('nombre') is-invalid @enderror {{ old('nombre') && !$errors->has('nombre') ? 'is-valid' : '' }}"
                                value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message ?: 'Solo se permiten letras. Ingrese este dato.' }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="apellidos" class="form-label">Apellidos: <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                                class="form-control @error('apellidos') is-invalid @enderror {{ old('apellidos') && !$errors->has('apellidos') ? 'is-valid' : '' }}"
                                value="{{ old('apellidos') }}" required>
                            @error('apellidos')
                                <div class="invalid-feedback">{{ $message ?: 'Solo se permiten letras. Ingrese este dato.' }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="numero_identidad" class="form-label">Identidad: <span class="text-danger">*</span></label>
                            <input type="text" name="numero_identidad" maxlength="13" minlength="13" pattern="\d{13}"
                                class="form-control @error('numero_identidad') is-invalid @enderror {{ old('numero_identidad') && !$errors->has('numero_identidad') ? 'is-valid' : '' }}"
                                value="{{ old('numero_identidad') }}" required>
                            @error('numero_identidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
                            <input type="text" name="telefono" maxlength="8" minlength="8" pattern="[8932]\d{7}"
                                class="form-control @error('telefono') is-invalid @enderror {{ old('telefono') && !$errors->has('telefono') ? 'is-valid' : '' }}"
                                value="{{ old('telefono') }}" required>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="correo" class="form-label">Correo: <span class="text-danger">*</span></label>
                            <input type="email" name="correo" maxlength="50" placeholder="ejemplo@dominio.com"
                                class="form-control @error('correo') is-invalid @enderror {{ old('correo') && !$errors->has('correo') ? 'is-valid' : '' }}"
                                value="{{ old('correo') }}" required>
                            @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="fecha_nacimiento" class="form-label">Nacimiento: <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_nacimiento" max="{{ $maxNacimiento }}"
                                class="form-control @error('fecha_nacimiento') is-invalid @enderror {{ old('fecha_nacimiento') && !$errors->has('fecha_nacimiento') ? 'is-valid' : '' }}"
                                value="{{ old('fecha_nacimiento') }}" required>
                            @error('fecha_nacimiento')
                                <div class="invalid-feedback">Debe tener al menos 18 años. No puede registrarse si es menor de edad.</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="genero" class="form-label">Género: <span class="text-danger">*</span></label>
                            <select name="genero" class="form-select @error('genero') is-invalid @enderror {{ old('genero') && !$errors->has('genero') ? 'is-valid' : '' }}" required>
                                <option value="">Seleccionar</option>
                                <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')
                                <div class="invalid-feedback">Por favor, elija una opción.</div>
                            @else
                                @if (old('genero') === '')
                                    <div class="invalid-feedback d-block">Elija una opción para el campo género.</div>
                                @endif
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="sueldo" class="form-label">Sueldo: <span class="text-danger">*</span></label>
                            <input type="number" name="sueldo" min="0" step="0.01" readonly
                                class="form-control @error('sueldo') is-invalid @enderror {{ old('sueldo') && !$errors->has('sueldo') ? 'is-valid' : '' }}"
                                value="{{ old('sueldo') }}" required>
                            @error('sueldo')
                                <div class="invalid-feedback"></div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="especialidad" class="form-label">Especialidad: <span class="text-danger">*</span></label>
                            <select name="especialidad" class="form-select @error('especialidad') is-invalid @enderror {{ old('especialidad') && !$errors->has('especialidad') ? 'is-valid' : '' }}" required>
                                <option value="">Seleccionar</option>
                                <option value="Cardiología" {{ old('especialidad') == 'Cardiología' ? 'selected' : '' }}>Cardiología</option>
                                <option value="Neurología" {{ old('especialidad') == 'Neurología' ? 'selected' : '' }}>Neurología</option>
                                <option value="Pediatría" {{ old('especialidad') == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
                                <option value="Dermatología" {{ old('especialidad') == 'Dermatología' ? 'selected' : '' }}>Dermatología</option>
                                <option value="Psiquiatría" {{ old('especialidad') == 'Psiquiatría' ? 'selected' : '' }}>Psiquiatría</option>
                                <option value="Radiología" {{ old('especialidad') == 'Radiología' ? 'selected' : '' }}>Radiología</option>
                            </select>
                            @error('especialidad')
                                <div class="invalid-feedback">Seleccione una especialidad.</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso: <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_ingreso" min="{{ $minIngreso }}" max="{{ $maxIngreso }}"
                                class="form-control @error('fecha_ingreso') is-invalid @enderror {{ old('fecha_ingreso') && !$errors->has('fecha_ingreso') ? 'is-valid' : '' }}"
                                value="{{ old('fecha_ingreso') }}" required>
                            @error('fecha_ingreso')
                                <div class="invalid-feedback">La fecha válida es desde un mes antes hasta un mes después de la fecha actual.</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="foto" class="form-label">Foto:</label>
                            <input type="file" name="foto" id="foto" class="form-control form-control-sm @error('foto') is-invalid @enderror" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">Suba una imagen válida. Opcional.</div>
                            @enderror
                            <div class="form-text">Opcional. Formato: JPG, PNG.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones:</label>
                        <textarea name="observaciones" maxlength="200" class="form-control @error('observaciones') is-invalid @enderror" rows="3">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">No puede exceder 200 caracteres.</div>
                        @enderror
                        <div class="form-text">Campo opcional (máx. 200 caracteres).</div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" id="btnLimpiar" class="btn btn-secondary ms-2">Limpiar</button>
                        </div>
                        <a href="{{ route('medicos.index') }}" class="btn btn-outline-dark">← Regresar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script para autocompletar sueldo y limpiar formulario --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const especialidadInput = document.querySelector('select[name="especialidad"]');
        const sueldoInput = document.querySelector('input[name="sueldo"]');

        const sueldosEspecialidad = {
            "Cardiología": 5000,
            "Neurología": 4800,
            "Pediatría": 4500,
            "Dermatología": 4200,
            "Psiquiatría": 4700,
            "Radiología": 4300,
        };

        function actualizarSueldo() {
            const especialidad = especialidadInput.value;
            if (sueldosEspecialidad.hasOwnProperty(especialidad)) {
                sueldoInput.value = sueldosEspecialidad[especialidad];
            } else {
                sueldoInput.value = '';
            }
        }

        especialidadInput.addEventListener('change', actualizarSueldo);
        actualizarSueldo();

        // Botón limpiar formulario
        const btnLimpiar = document.getElementById('btnLimpiar');
        const form = document.getElementById('formMedico');

        btnLimpiar.addEventListener('click', function () {
            form.reset();
            actualizarSueldo();

            // Limpiar clases de validación visual
            form.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
        });
    });
</script>
@endsection
