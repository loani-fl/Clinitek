@extends('layouts.app')

@section('title', 'Editar Puesto')

@push('styles')
<style>
    html, body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
        max-width: 100%;
        background-color: #e8f4fc;
    }

    .custom-card {
        position: relative;
        max-width: 1000px;
        width: 96%;
        margin: 60px auto 40px;
        padding: 30px 40px 40px 40px;
        background-color: rgba(255, 255, 255, 0.85); /* Fondo translúcido */
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 1;
        border: 1px solid #91cfff;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 800px;
        height: 800px;
        background-image: url('{{ asset('images/logo2.jpg') }}');
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
        background-color: transparent;
        color: rgba(0, 0, 0, 0.6);
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    input, select, textarea {
        position: relative;
        z-index: 1;
        background-color: transparent !important;
        color: #212529;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        width: 100%;
        box-sizing: border-box;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #007BFF;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .funcion-texto {
        min-height: 3.5rem; /* menos alto */
        padding: 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        white-space: pre-wrap;
        resize: vertical;
    }

    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100vw;
        z-index: 1050;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header {
    background-color: #fff !important;
    border-bottom: 3px solid #007BFF !important;
    padding: 0.75rem 1rem !important;
}


    .header img {
        height: 40px;
        margin-right: 8px;
    }

    .header .fw-bold {
        font-size: 1.5rem;
        color: white;
    }

    .nav-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .nav-buttons a {
        color: white !important;
        font-weight: 600;
        text-decoration: none;
    }

    .nav-buttons a:hover {
        text-decoration: underline;
    }

    .custom-card-header {
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.75rem;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
        color: #000;
        font-weight: 700;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    @media (max-width: 576px) {
        .custom-card {
            padding: 1rem;
            margin: 80px 1rem 2rem 1rem;
        }

        .custom-card-header {
            font-size: 1.2rem;
        }
    }
</style>
@endpush

@section('content')

<!-- Barra de navegación superior -->
<!--<div class="header">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
        <span class="fw-bold">Clinitek</span>
    </div>

    <div class="nav-buttons">
        <a href="{{ route('puestos.create') }}">Crear puesto</a>
        <a href="{{ route('empleados.create') }}">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}">Registrar médico</a>
    </div>
</div>
-->
<!-- Contenedor principal -->
<div class="custom-card">

    <div class="custom-card-header">
        Editar Puesto
    </div>

    <form action="{{ route('puestos.update', $puesto->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="row row-info gy-3">
            <div class="col-md-3">
                <label for="codigo" class="fw-semibold text-muted">
                    Código <span class="text-danger">*</span>
                    @error('codigo')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </label>
                <input type="text" name="codigo" id="codigo"
                       class="form-control @error('codigo') is-invalid @enderror"
                       maxlength="10"
                       value="{{ old('codigo', $puesto->codigo) }}"
                       required>
            </div>

            <div class="col-md-3">
                <label for="nombre" class="fw-semibold text-muted">
                    Nombre <span class="text-danger">*</span>
                    @error('nombre')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </label>
                <input type="text" name="nombre" id="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       maxlength="50"
                       value="{{ old('nombre', $puesto->nombre) }}"
                       required>
            </div>

            <div class="col-md-3">
                <label for="area" class="fw-semibold text-muted">
                    Departamento <span class="text-danger">*</span>
                    @error('area')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </label>
                <select name="area" id="area"
                        class="form-select @error('area') is-invalid @enderror"
                        required>
                    <option value="" disabled {{ old('area', $puesto->area) ? '' : 'selected' }}>
                        --Seleccione--
                    </option>
                    @foreach(['Administración', 'Recepción', 'Laboratorio', 'Farmacia', 'Enfermería', 'Mantenimiento'] as $area)
                        <option value="{{ $area }}" {{ old('area', $puesto->area) == $area ? 'selected' : '' }}>
                            {{ $area }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="sueldo" class="fw-semibold text-muted">
                    Sueldo (Lps.) <span class="text-danger">*</span>
                    @error('sueldo')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </label>
                <input type="text" name="sueldo" id="sueldo"
                       class="form-control @error('sueldo') is-invalid @enderror"
                       maxlength="8"
                       value="{{ old('sueldo', $puesto->sueldo) }}"
                       required
                       pattern="^\d{1,5}(\.\d{1,2})?$"
                       title="Máximo 5 dígitos enteros y 2 decimales (ejemplo: 12345.67)">
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <label for="funcion" class="fw-semibold text-muted">
                    Funciones del puesto <span class="text-danger">*</span>
                    @error('funcion')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </label>
                <textarea name="funcion" id="funcion"
                          class="form-control funcion-texto @error('funcion') is-invalid @enderror"
                          rows="2"
                          maxlength="300"
                          required>{{ old('funcion', $puesto->funcion) }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-save"></i> Actualizar
            </button>
            <button type="reset" class="btn btn-warning px-4 shadow-sm">
                <i class="bi bi-arrow-counterclockwise"></i> Restablecer
            </button>
            <a href="{{ route('puestos.index') }}" class="btn btn-success px-4 shadow-sm">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const resetBtn = form.querySelector('button[type="reset"]');
    const alert = document.querySelector('.alert-danger');

    // Guardar los valores originales al cargar la página
    const originalValues = {
        codigo: '{{ $puesto->codigo }}',
        nombre: '{{ $puesto->nombre }}',
        area: '{{ $puesto->area }}',
        sueldo: '{{ $puesto->sueldo }}',
        funcion: `{{ str_replace(["\r", "\n"], ["", "\\n"], $puesto->funcion) }}`
    };

    resetBtn.addEventListener('click', (e) => {
        e.preventDefault(); // prevenimos el reset automático de HTML

        // Restaurar los valores originales
        form.codigo.value = originalValues.codigo;
        form.nombre.value = originalValues.nombre;
        form.area.value = originalValues.area;
        form.sueldo.value = originalValues.sueldo;
        form.funcion.value = originalValues.funcion;

        // Quitar la alerta general si existe
        if (alert) {
            alert.remove();
        }

        // Quitar los mensajes de error por campo y las clases is-invalid
        form.querySelectorAll('.text-danger.small').forEach(el => el.remove());
        form.querySelectorAll('.is-invalid').forEach(input => input.classList.remove('is-invalid'));

        // También quitar validaciones del navegador si las hubiera
        form.classList.remove('was-validated');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const resetBtn = form.querySelector('button[type="reset"]');
    const alert = document.querySelector('.alert-danger');

    const originalValues = {
        codigo: '{{ $puesto->codigo }}',
        nombre: '{{ $puesto->nombre }}',
        area: '{{ $puesto->area }}',
        sueldo: '{{ $puesto->sueldo }}',
        funcion: `{{ str_replace(["\r", "\n"], ["", "\\n"], $puesto->funcion) }}`
    };

    resetBtn.addEventListener('click', (e) => {
        e.preventDefault();

        form.codigo.value = originalValues.codigo;
        form.nombre.value = originalValues.nombre;
        form.area.value = originalValues.area;
        form.sueldo.value = originalValues.sueldo;
        form.funcion.value = originalValues.funcion;

        if (alert) {
            alert.remove();
        }

        form.querySelectorAll('.text-danger.small').forEach(el => el.remove());
        form.querySelectorAll('.is-invalid').forEach(input => input.classList.remove('is-invalid'));

        form.classList.remove('was-validated');
    });

    const nombreInput = document.getElementById('nombre');
    const sueldoInput = document.getElementById('sueldo');
    const codigoInput = document.getElementById('codigo');
    const funcionInput = document.getElementById('funcion');

    // nombre: letras, espacios, ñ y acentos
    nombreInput.addEventListener('input', () => {
        nombreInput.value = nombreInput.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    });

    // sueldo: números y un solo punto
    sueldoInput.addEventListener('input', () => {
        sueldoInput.value = sueldoInput.value.replace(/[^0-9.]/g, '');
    });

    // código: letras, números y guiones
    codigoInput.addEventListener('input', () => {
        codigoInput.value = codigoInput.value.replace(/[^a-zA-Z0-9\-]/g, '');
    });

    // funcion: letras, espacios, ñ y ;:., únicamente
    funcionInput.addEventListener('input', () => {
        funcionInput.value = funcionInput.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s;:.,]/g, '');
    });
});
</script>
@endpush




