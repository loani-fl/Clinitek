@extends('layouts.app')

@section('content')
<style>
    /* Reset y estilos generales */
    html, body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
        max-width: 100%;
        background-color: #e8f4fc;
    }

    body {
        padding-top: 40px; /* espacio para navbar fija (ajustar si es necesario) */
        margin: 0;
        overflow-x: hidden;
        max-width: 100%;
    }

    .container {
        padding-left: 15px;
        padding-right: 15px;
        max-width: 100%;
        overflow-x: hidden;
    }

    /* Barra fija / header */
    .fixed-navbar, .header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        background-color: #007BFF;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-sizing: border-box;
        height: 56px; /* igual para ambas */
    }

    /* Ajuste contenido para que no quede oculto bajo navbar */
    .content-wrapper {
        margin-top: 60px; /* igual al alto de la barra */
    }

    /* Tarjeta personalizada */
    .custom-card {
        position: relative;
        max-width: 1000px; /* del segundo estilo, más ancho */
        width: 100%; /* ocupa todo el ancho máximo */
        margin: 2rem auto;
        padding: 30px 20px; /* mantengo padding del primer estilo */
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
        opacity: 0.15; /* uso opacidad del segundo estilo */
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
        border-radius: 1rem;
    }

    /* Estilo para el mensaje de éxito */
    .alert-success-custom {
        background-color: #d1e7dd;
        border: 1px solid #badbcc;
        color: #0f5132;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0, 128, 0, 0.2);
        border-radius: 0.5rem;
        padding: 1rem 1.5rem;
        position: relative;
        z-index: 1200;
        margin-bottom: 1rem;
    }

    /* Formularios, etiquetas e inputs */
    label {
        font-size: 0.85rem;
        font-weight: 600;
        color: rgba(0, 0, 0, 0.6);
        margin-bottom: 0.25rem;
    }

    input, select, textarea {
        max-width: 100%;
        box-sizing: border-box;
        font-size: 0.85rem !important;
        background-color: transparent !important;
        color: #212529;
    }

    /* Cabecera de tarjeta */
.card-header {
    background-color: #fff !important;
    border-bottom: 3px solid #007BFF !important;
    padding: 0.75rem 1rem !important;
}



    .card-header h5 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #000;
        margin: 0;
    }

    /* Botones */
    .btn {
        font-size: 0.9rem;
    }

    /* Validaciones */
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

    /* Formularios */
    form {
        padding-left: 10px;
        padding-right: 10px;
    }

    /* Footer */
    footer {
        position: static;
        width: 100%;
        background-color: #f8f9fa;
        padding: 10px 0;
        text-align: center;
        font-size: 0.9rem;
        color: #6c757d;
        border-top: 1px solid #dee2e6;
        margin-top: 40px;
    }

    /* Responsive para tablas */
    .table-responsive {
        overflow-x: auto;
        max-width: 100%;
    }

    /* Utilidades */
    .no-select {
        user-select: none !important;
        -webkit-user-select: none !important;
        -moz-user-select: none !important;
        -ms-user-select: none !important;
        pointer-events: none !important;
        cursor: default;
    }
</style>


<!-- Barra de navegación fija -->
<!--<div class="fixed-navbar d-flex justify-content-between align-items-center px-3 py-2">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
        <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('empleados.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>-->

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
         <h5 class="mb-0 text-dark text-center" style="font-size: 2.25rem; font-weight: bold;">Registro de un nuevo puesto</h5>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 p-3 mb-4 small">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('puestos.store') }}" novalidate>
                @csrf

               <div class="row row-info gy-3">
    <div class="col-md-3 position-relative">
        <label for="codigo" class="fw-semibold text-muted">
            Código <span class="text-danger">*</span>
        </label>
        <input type="text" name="codigo" id="codigo"
               class="form-control form-control-sm @error('codigo') is-invalid @enderror"
               maxlength="10"
               pattern="[A-Za-zÑñ0-9\-]{1,10}"
               title="Máximo 10 caracteres. Solo letras, números y guiones."
               value="{{ old('codigo', $puesto->codigo ?? '') }}"
               autocomplete="off"
               required
               oninput="this.value=this.value.replace(/[^A-Za-zÑñ0-9\-]/g,'');">
        @error('codigo')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 position-relative">
        <label for="nombre" class="fw-semibold text-muted">
            Nombre <span class="text-danger">*</span>
        </label>
        <input type="text" name="nombre" id="nombre"
               class="form-control form-control-sm @error('nombre') is-invalid @enderror"
               maxlength="50"
               pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
               title="Solo letras (incluye tildes y ñ) y espacios. Máximo 50 caracteres."
               value="{{ old('nombre', $puesto->nombre ?? '') }}"
               autocomplete="off"
               required
               oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'');">
        @error('nombre')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 position-relative">
        <label for="area" class="fw-semibold text-muted">
            Departamento <span class="text-danger">*</span>
        </label>
        <select name="area" id="area"
                class="form-select form-select-sm @error('area') is-invalid @enderror"
                required>
            <option value="" disabled {{ old('area', $puesto->area ?? '') ? '' : 'selected' }}>
               -- Seleccione--
            </option>
            <option value="Administración" data-sueldo="15000" {{ old('area', $puesto->area ?? '') == 'Administración' ? 'selected' : '' }}>Administración</option>
            <option value="Recepción" data-sueldo="12000" {{ old('area', $puesto->area ?? '') == 'Recepción' ? 'selected' : '' }}>Recepción</option>
            <option value="Laboratorio" data-sueldo="18000" {{ old('area', $puesto->area ?? '') == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
            <option value="Farmacia" data-sueldo="16000" {{ old('area', $puesto->area ?? '') == 'Farmacia' ? 'selected' : '' }}>Farmacia</option>
            <option value="Enfermería" data-sueldo="17000" {{ old('area', $puesto->area ?? '') == 'Enfermería' ? 'selected' : '' }}>Enfermería</option>
            <option value="Mantenimiento" data-sueldo="11000" {{ old('area', $puesto->area ?? '') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
        </select>
        @error('area')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 position-relative">
        <label for="sueldo" class="fw-semibold text-muted">
            Sueldo (Lps.) <span class="text-danger">*</span>
        </label>
        <input type="text" name="sueldo" id="sueldo"
               class="form-control form-control-sm @error('sueldo') is-invalid @enderror"
               maxlength="8"
               pattern="^\d{1,5}(\.\d{1,2})?$"
               title="Máximo 5 dígitos enteros y 2 decimales (ejemplo: 12345.67)"
               value="{{ old('sueldo', $puesto->sueldo ?? '') }}"
               autocomplete="off"
               readonly
               required
               inputmode="decimal">
        @error('sueldo')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 position-relative">
        <label for="funcion" class="fw-semibold text-muted">
            Funciones del puesto <span class="text-danger">*</span>
        </label>
        <textarea name="funcion" id="funcion"
                  class="form-control form-control-sm funcion-texto @error('funcion') is-invalid @enderror"
                  rows="3"
                  maxlength="300"
                  required
                  pattern="^[A-Za-z0-9ÁÉÍÓÚáéíóúÑñ\s.,\r\n]+$"
                  title="Puede contener letras (incluye tildes y ñ), números, comas, puntos y espacios. Máximo 300 caracteres."
                  oninput="this.value=this.value.replace(/[^A-Za-z0-9ÁÉÍÓÚáéíóúÑñ\s.,\r\n]/g,'');"
        >{{ old('funcion', $puesto->funcion ?? '') }}</textarea>
        @error('funcion')
            <div class="text-danger small mt-1">{{ $message }}</div>
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
        } else {
            sueldoInput.value = '';
        }
    }

 function limpiarFormulario() {
    const form = document.querySelector('form');

    // Limpiar todos los inputs, selects y textareas
    form.querySelectorAll('input, select, textarea').forEach(el => {
        if (el.tagName === 'SELECT') {
            el.selectedIndex = 0; // opción por defecto
            el.dispatchEvent(new Event('change')); // dispara el cambio para autoFillSueldo
        } else {
            el.value = '';
        }
    });

    // Limpiar clases de validación
    form.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
        el.classList.remove('is-valid', 'is-invalid');
    });

    // Ocultar mensajes de error (si los muestras dinámicamente)
    form.querySelectorAll('.error-text, .text-danger').forEach(div => {
        div.textContent = '';
        div.style.display = 'none';
    });

    // Por si acaso limpiar sueldo de nuevo (readonly)
    const sueldoInput = document.getElementById('sueldo');
    if (sueldoInput) {
        sueldoInput.value = '';
    }
}


    document.addEventListener('DOMContentLoaded', function () {
        autoFillSueldo(); // por si viene seleccionado desde old()
        document.getElementById('area').addEventListener('change', autoFillSueldo);

        // Refuerzo para que no se puedan escribir caracteres inválidos
        const codigoInput = document.getElementById('codigo');
        const nombreInput = document.getElementById('nombre');
        const funcionInput = document.getElementById('funcion');

        if (codigoInput) {
            codigoInput.addEventListener('input', () => {
                codigoInput.value = codigoInput.value.replace(/[^A-Za-zÑñ0-9\-]/g, '');
            });
        }

        if (nombreInput) {
            nombreInput.addEventListener('input', () => {
                nombreInput.value = nombreInput.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
            });
        }

        if (funcionInput) {
            funcionInput.addEventListener('input', () => {
                funcionInput.value = funcionInput.value.replace(/[^A-Za-z0-9ÁÉÍÓÚáéíóúÑñ\s.,\r\n]/g, '');
            });
        }
    });
</script>
@endpush


