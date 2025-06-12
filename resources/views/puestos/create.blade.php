@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        max-width: 87vw;
        background-color: #f0faff;
        border-color: #91cfff;
    }
    label {
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    input, select, textarea {
        font-size: 0.85rem !important;
    }
    .error-text {
        font-size: 0.75rem;
        color: #dc3545;
        margin-top: 0.2rem;
    }
    .valid-feedback {
        display: block;
        font-size: 0.85rem;
        color: #198754;
    }
    .form-control.is-valid {
        border-color: #198754;
        padding-right: 2.5rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23198754' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' class='bi bi-check-lg' viewBox='0 0 16 16'%3e%3cpath d='M13 4.5 6 11.5 3 8.5'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem 1rem;
    }
</style>

<div class="d-flex justify-content-center">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>Registro de un nuevo puesto</h5>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 p-3 mb-4 small">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('puestos.store') }}" novalidate>
                @csrf

                <div class="row g-4 mt-3 px-4">
                    <div class="col-md-3 position-relative">
                        <label for="codigo" class="form-label fw-semibold text-muted">Código <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" id="codigo" autocomplete="off"
                            class="form-control form-control-sm @error('codigo') is-invalid @enderror"
                            maxlength="10"
                            pattern="[A-Za-z0-9\-]{1,10}"
                            title="Máximo 10 caracteres. Solo letras, números y guiones."
                            value="{{ old('codigo') }}" required>
                        @error('codigo')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 position-relative">
                        <label for="nombre" class="form-label fw-semibold text-muted">Nombre del Puesto <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" autocomplete="off"
                            class="form-control form-control-sm @error('nombre') is-invalid @enderror"
                            maxlength="50"
                            pattern="^[\pL\s]+$"
                            title="Solo letras y espacios (puede incluir tildes)."
                            value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-4 mt-3 px-4">
                    <div class="col-md-4 position-relative">
                        <label for="area" class="form-label fw-semibold text-muted">Área / Departamento <span class="text-danger">*</span></label>
                        <select name="area" id="area"
                            class="form-select form-select-sm @error('area') is-invalid @enderror"
                            required onchange="autoFillSueldo()">
                            <option value="">-- Selecciona un área --</option>
                            <option value="Administración" {{ old('area') == 'Administración' ? 'selected' : '' }} data-sueldo="15000">Administración</option>
                            <option value="Recepción" {{ old('area') == 'Recepción' ? 'selected' : '' }} data-sueldo="12000">Recepción</option>
                            <option value="Laboratorio" {{ old('area') == 'Laboratorio' ? 'selected' : '' }} data-sueldo="18000">Laboratorio</option>
                            <option value="Farmacia" {{ old('area') == 'Farmacia' ? 'selected' : '' }} data-sueldo="16000">Farmacia</option>
                            <option value="Enfermería" {{ old('area') == 'Enfermería' ? 'selected' : '' }} data-sueldo="17000">Enfermería</option>
                            <option value="Mantenimiento" {{ old('area') == 'Mantenimiento' ? 'selected' : '' }} data-sueldo="11000">Mantenimiento</option>
                        </select>
                        @error('area')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 position-relative">
                        <label for="sueldo" class="form-label fw-semibold text-muted">Sueldo (Lps.) <span class="text-danger">*</span></label>
                        <input type="text" name="sueldo" id="sueldo" required readonly
                            class="form-control form-control-sm @error('sueldo') is-invalid @enderror"
                            pattern="^\d{1,5}(\.\d{1,2})?$"
                            title="Solo números. Hasta 5 dígitos y 2 decimales."
                            inputmode="decimal"
                            value="{{ old('sueldo') }}">
                        @error('sueldo')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-4 mt-3 px-4">
                    <div class="col-md-5 position-relative">
                        <label for="funcion" class="form-label fw-semibold text-muted">Función del Puesto <span class="text-danger">*</span></label>
                        <textarea name="funcion" id="funcion" rows="3"
                            class="form-control form-control-sm @error('funcion') is-invalid @enderror"
                            required maxlength="50"
                            pattern="^[\pL\pN\s.,áéíóúÁÉÍÓÚñÑ\r\n]+$"
                            title="Puede contener letras (incluye tildes y ñ), números, comas, puntos y espacios. Máximo 50 caracteres.">{{ old('funcion') }}</textarea>
                        @error('funcion')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-4 gap-4 px-4">
                    <button type="submit" class="btn btn-primary btn-sm shadow-sm px-4">
                        <i class="bi bi-plus-circle"></i> Registrar
                    </button>

                    <button type="button" class="btn btn-secondary btn-sm shadow-sm px-4" onclick="limpiarFormulario()">
                        <i class="bi bi-trash"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function autoFillSueldo() {
        const select = document.getElementById('area');
        const sueldoInput = document.getElementById('sueldo');
        const option = select.options[select.selectedIndex];
        const sueldo = option.getAttribute('data-sueldo');

        if (sueldo && (sueldoInput.value === '' || sueldoInput.value === sueldoInput.dataset.prevValue)) {
            sueldoInput.value = sueldo;
            sueldoInput.dataset.prevValue = sueldo;
            formatSueldo();
        }
    }

    function formatSueldo() {
        const sueldoInput = document.getElementById('sueldo');
        let val = sueldoInput.value.replace(',', '.').trim();
        if (val === '') return;
        let num = parseFloat(val);
        if (!isNaN(num)) {
            sueldoInput.value = num.toFixed(2);
            sueldoInput.dataset.prevValue = sueldoInput.value;
        }
    }

    function limpiarFormulario() {
        const form = document.querySelector('form');
        form.reset();
        form.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.tagName.toLowerCase() === 'select') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
            const errorDiv = input.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('error-text')) {
                errorDiv.style.display = 'none';
                errorDiv.textContent = '';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');

        inputs.forEach(input => {
            input.addEventListener('input', () => validateField(input));
            input.addEventListener('change', () => validateField(input));
        });

        function validateField(field) {
            if (field.checkValidity()) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                const errorDiv = field.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-text')) {
                    errorDiv.style.display = 'none';
                    errorDiv.textContent = '';
                }
            } else {
                field.classList.remove('is-valid');
                if (!field.classList.contains('is-invalid')) {
                    field.classList.add('is-invalid');
                }
            }
        }
    });
</script>
@endpush
@endsection
