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

<!-- Barra de navegación -->
<div class="w-100" style="background-color: #007BFF;">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    <div class="card custom-card shadow-sm border rounded-4 w-100" style="max-width: 700px;">
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

              <div class="row g-4 mt-3 px-4 justify-content-center">
    <div class="col-md-5">
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
        <div id="codigo-error" class="text-danger small mt-1 d-none">Solo letras, números y guiones son permitidos.</div>
    </div>

    <div class="col-md-5">
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
        <div id="nombre-error" class="text-danger small mt-1 d-none">Solo se permiten letras y espacios.</div>
    </div>
</div>

<div class="row g-4 mt-3 px-4 justify-content-center">
    <div class="col-md-5">
        <label for="area" class="form-label fw-semibold text-muted">Área / Departamento <span class="text-danger">*</span></label>
        <select name="area" id="area"
            class="form-select form-select-sm @error('area') is-invalid @enderror"
            required>
            <option value="">-- Selecciona un área --</option>
            <option value="Administración" data-sueldo="15000" {{ old('area') == 'Administración' ? 'selected' : '' }}>Administración</option>
            <option value="Recepción" data-sueldo="12000" {{ old('area') == 'Recepción' ? 'selected' : '' }}>Recepción</option>
            <option value="Laboratorio" data-sueldo="18000" {{ old('area') == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
            <option value="Farmacia" data-sueldo="16000" {{ old('area') == 'Farmacia' ? 'selected' : '' }}>Farmacia</option>
            <option value="Enfermería" data-sueldo="17000" {{ old('area') == 'Enfermería' ? 'selected' : '' }}>Enfermería</option>
            <option value="Mantenimiento" data-sueldo="11000" {{ old('area') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
        </select>
        @error('area')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="sueldo" class="form-label fw-semibold text-muted">Sueldo (Lps.) <span class="text-danger">*</span></label>
        <input type="text" name="sueldo" id="sueldo" readonly required
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

<div class="row g-4 mt-3 px-4 justify-content-center">
    <div class="col-md-10">
        <label for="funcion" class="form-label fw-semibold text-muted">Función del Puesto <span class="text-danger">*</span></label>
        <textarea name="funcion" id="funcion" rows="3"
            class="form-control form-control-sm @error('funcion') is-invalid @enderror"
            required maxlength="300"
            pattern="^[\pL\pN\s.,áéíóúÁÉÍÓÚñÑ\r\n]+$"
            title="Puede contener letras (incluye tildes y ñ), números, comas, puntos y espacios. Máximo 300 caracteres.">{{ old('funcion') }}</textarea>
        @error('funcion')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-center mt-4 gap-4 px-4">
    <button type="submit" class="btn btn-primary btn-sm shadow-sm px-4">
        <i class="bi bi-plus-circle"></i> Registrar
    </button>

    <button type="button" class="btn btn-warning btn-sm shadow-sm px-4" onclick="limpiarFormulario()">
        <i class="bi bi-trash"></i> Limpiar
    </button>

    <a href="{{ route('puestos.index') }}" class="btn btn-success btn-sm shadow-sm px-4">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>
</form>

<script>
function limpiarFormulario() {
    const form = document.querySelector('form');
    form.reset();
    document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
        el.classList.remove('is-valid', 'is-invalid');
    });
    document.querySelectorAll('.error-text, .text-danger.small').forEach(div => {
        div.classList.add('d-none');
    });
}
</script>

@push('scripts')
<script>
    function autoFillSueldo() {
        const select = document.getElementById('area');
        const sueldoInput = document.getElementById('sueldo');
        const sueldo = select.options[select.selectedIndex].getAttribute('data-sueldo');
        if (sueldo) {
            sueldoInput.value = parseFloat(sueldo).toFixed(2);
        }
    }

    function mostrarMensajeError(id) {
        const mensaje = document.getElementById(id);
        mensaje.classList.remove('d-none');
        clearTimeout(mensaje.timer);
        mensaje.timer = setTimeout(() => {
            mensaje.classList.add('d-none');
        }, 2000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        autoFillSueldo();
        document.getElementById('area').addEventListener('change', autoFillSueldo);

        const inputCodigo = document.getElementById('codigo');
        inputCodigo.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            if (!/^[a-zA-Z0-9-]$/.test(char)) {
                e.preventDefault();
                mostrarMensajeError('codigo-error');
            }
        });

        const inputNombre = document.getElementById('nombre');
        inputNombre.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]$/.test(char)) {
                e.preventDefault();
                mostrarMensajeError('nombre-error');
            }
        });
    });
</script>
@endpush
@endsection

