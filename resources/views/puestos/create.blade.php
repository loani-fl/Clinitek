@extends('layouts.app')

@section('content')
<style>
    html, body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
        max-width: 100%;
        background-color: #e8f4fc;
    }

    /* Barra fija */
    .fixed-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        background-color: #007BFF;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }

    /* Ajustamos el contenido para que no quede oculto debajo de la barra */
    .content-wrapper {
        margin-top: 60px; /* igual al alto de la barra */
    }

    .custom-card {
        position: relative;
        max-width: 800px;
        margin: 2rem auto;
        padding: 30px 20px;
        background-color: rgba(255, 255, 255, 0.85); /* Fondo translúcido */
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        overflow: hidden;
        z-index: 1;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 800px;
        height: 800px;
        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.12;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
        border-radius: 1rem;
    }

    label {
        font-size: 0.85rem;
        font-weight: 600;
        color: rgba(0, 0, 0, 0.6); /* texto más claro */
        margin-bottom: 0.25rem;
    }

    input, select, textarea {
        font-size: 0.85rem !important;
        background-color: transparent !important; /* fondo transparente para mejor integración */
        color: #212529;
    }

    .card-header {
        background-color: transparent;
        border-bottom: 3px solid #007BFF;
        text-align: center;
        padding: 0.75rem 1rem;
    }

    .card-header h5 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #000;
        margin: 0;
    }

    .btn {
        font-size: 0.9rem;
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

    label.is-invalid {
        color: #dc3545;
        font-weight: 600;
    }

    .error-text {
        font-size: 0.75rem;
        color: #dc3545;
        margin-top: 0.2rem;
    }
</style>

<!-- Barra de navegación fija -->
<div class="fixed-navbar d-flex justify-content-between align-items-center px-3 py-2">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
        <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
            <h5>Registro de un nuevo puesto</h5>
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
                    <div class="col-md-4 position-relative">
                        <label for="codigo" class="form-label fw-semibold text-muted">
                            Código <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="codigo" id="codigo" autocomplete="off"
                            class="form-control form-control-sm @error('codigo') is-invalid @enderror"
                            maxlength="10"
                            pattern="[A-Za-zÑñ0-9\-]{1,10}"
                            title="Máximo 10 caracteres. Solo letras, números y guiones."
                            value="{{ old('codigo') }}" required>
                        @error('codigo')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 position-relative">
                        <label for="nombre" class="form-label fw-semibold text-muted">
                            Nombre del Puesto <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nombre" id="nombre" autocomplete="off"
                            class="form-control form-control-sm @error('nombre') is-invalid @enderror"
                            maxlength="50"
                            pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
                            title="Solo letras (incluye tildes y ñ) y espacios. Máximo 50 caracteres."
                            value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 position-relative">
                        <label for="area" class="form-label fw-semibold text-muted">
                            Área / Departamento <span class="text-danger">*</span>
                        </label>
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
                </div>

                <div class="row g-4 mt-3 px-4">
                    <div class="col-md-4 position-relative">
                        <label for="sueldo" class="form-label fw-semibold text-muted">
                            Sueldo (Lps.) <span class="text-danger">*</span>
                        </label>
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

                    <div class="col-md-6 position-relative">
                        <label for="funcion" class="form-label fw-semibold text-muted">
                            Función del Puesto <span class="text-danger">*</span>
                        </label>
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

                <!-- Botones -->
                <div class="d-flex justify-content-center mt-4 gap-3 px-4">
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
        </div>
    </div>
</div>




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

    function limpiarFormulario() {
        const form = document.querySelector('form');

        // Limpiar todos los inputs, selects y textareas
        form.querySelectorAll('input, select, textarea').forEach(el => {
            if (el.tagName === 'SELECT') {
                el.selectedIndex = 0; // opción por defecto
            } else {
                el.value = '';
            }
        });

        // Limpiar clases de validación
        form.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
            el.classList.remove('is-valid', 'is-invalid');
        });

        // Ocultar mensajes de error
        form.querySelectorAll('.error-text').forEach(div => {
            div.textContent = '';
            div.style.display = 'none';
        });

        // Opcional: actualizar sueldo con valor vacío, o dejarlo vacío
        const sueldoInput = document.getElementById('sueldo');
        if (sueldoInput) {
            sueldoInput.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        autoFillSueldo(); // por si viene seleccionado desde old()
        document.getElementById('area').addEventListener('change', autoFillSueldo);
    });
</script>
@endpush

@endsection

