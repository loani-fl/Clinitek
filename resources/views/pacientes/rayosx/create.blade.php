@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
    $hoy = Carbon::today();
    $maxFechaNacimiento = $hoy->copy()->subYears(18)->format('Y-m-d');
    $minFechaNacimiento = '1940-01-01';
@endphp

<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
    }

    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1100;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    }

    .content-wrapper {
        margin-top: 60px;
    }

    .custom-card {
        max-width: 900px;  /* más ancho */
        background-color: #fff;
        margin: 40px auto 60px;
        border-radius: 1.5rem;
        padding: 2.5rem 3rem; /* un poco más espacioso */
        position: relative;
        overflow: hidden;
        z-index: 1;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Fondo con logo solo dentro de la tarjeta */
    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 1100px;  /* ajustado proporcional al nuevo tamaño */
        height: 1100px;
        background-image: url('{{ asset('images/logo2.jpg') }}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.07;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
        border-radius: 1.5rem; /* para que no sobresalga de la tarjeta */
    }

    .card-header {
        background-color: transparent !important;
        border-bottom: 3px solid #007BFF;
        padding: 0.75rem 0;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .card-header h5 {
        color: #003366;
        font-weight: 700;
        text-align: center;
        margin: 0;
        font-size: 1.4rem;
    }

    .section-title {
        font-size: 1.1rem;
        margin-bottom: 0.7rem;
        font-weight: 700;
        color: #003366;
    }

    .alert {
        font-weight: 600;
    }

    label.form-label {
        font-weight: 600;
        color: #003366;
    }

    .form-control:focus {
        border-color: #007BFF;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .btn-primary {
        font-size: 0.95rem;
        padding: 0.45rem 0.9rem;
    }

    .btn-warning, .btn-success {
        font-size: 0.95rem;
        padding: 0.45rem 0.9rem;
    }

    .d-flex.justify-content-center {
        gap: 1rem;
    }

    .invalid-feedback {
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>

<div class="custom-card">
    <div class="card-header">
        <h5>Registrar Paciente para Rayos X</h5>
    </div>

    <div id="mensaje-vacio" class="alert alert-danger" style="display:none;">
        Por favor, complete todos los campos requeridos.
    </div>

    <div id="mensaje-identidad-anio" class="alert alert-danger" style="display:none;">
        El año extraído de la identidad no es válido o está fuera del rango permitido (18–100 años).
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formPaciente" action="{{ route('pacientes.rayosx.store') }}" method="POST" novalidate>
        @csrf

        <div class="row gx-2">
            <div class="col-md-3 mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                       id="nombre" name="nombre" value="{{ old('nombre') }}" required
                       pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$" title="Solo letras y espacios">
                @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control @error('apellidos') is-invalid @enderror"
                       id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required
                       pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$" title="Solo letras y espacios">
                @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="identidad" class="form-label">Número de Identidad</label>
                <input type="text" class="form-control @error('identidad') is-invalid @enderror"
                       id="identidad" name="identidad" value="{{ old('identidad') }}"
                       required maxlength="13" pattern="^\d{13}$"
                       title="Solo números (13 dígitos)" autocomplete="off">
                <div id="identidad-error" class="invalid-feedback" style="display:none;"></div>
                @error('identidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3 mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                       id="telefono" name="telefono" value="{{ old('telefono') }}"
                       maxlength="8" pattern="^[2389]\d{7}$"
                       title="Debe comenzar con 2, 3, 8 o 9 y tener 8 dígitos">
                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Opcional — 8 dígitos, inicia con 2, 3, 8 o 9.</div>
            </div>
        </div>

        <div class="row gx-2">
            <div class="col-md-4 mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                       id="fecha_nacimiento" name="fecha_nacimiento"
                       value="{{ old('fecha_nacimiento') }}" required
                       min="{{ $minFechaNacimiento }}" max="{{ $maxFechaNacimiento }}">
                @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="edad" class="form-label">Edad (años)</label>
                <input type="text" class="form-control" id="edad"
                       name="edad" value="{{ old('edad') }}" readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label for="fecha_orden" class="form-label">Fecha de Orden</label>
                <input type="date" class="form-control @error('fecha_orden') is-invalid @enderror"
                       id="fecha_orden" name="fecha_orden"
                       value="{{ old('fecha_orden') }}" required>
                @error('fecha_orden')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Botones centrados --}}
        <div class="d-flex justify-content-center gap-3 mt-4 w-100">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Registrar
            </button>

            <button type="button" class="btn btn-warning" id="btnLimpiar">
                <i class="bi bi-trash"></i> Limpiar
            </button>

            <a href="{{ route('rayosx.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formPaciente');
    const nombre = document.getElementById('nombre');
    const apellidos = document.getElementById('apellidos');
    const identidad = document.getElementById('identidad');
    const telefono = document.getElementById('telefono');
    const fechaOrden = document.getElementById('fecha_orden');
    const fechaNacimiento = document.getElementById('fecha_nacimiento');
    const edadInput = document.getElementById('edad');
    const mensajeVacio = document.getElementById('mensaje-vacio');
    const identidadError = document.getElementById('identidad-error');
    const mensajeAnio = document.getElementById('mensaje-identidad-anio');
    const btnLimpiar = document.getElementById('btnLimpiar');

    const minFecha = fechaNacimiento.getAttribute('min');
    const maxFecha = fechaNacimiento.getAttribute('max');

    function calcularEdadDesdeFecha(fecha) {
        if (!fecha) return '';
        const hoy = new Date();
        const f = new Date(fecha);
        let edad = hoy.getFullYear() - f.getFullYear();
        const m = hoy.getMonth() - f.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < f.getDate())) edad--;
        return edad >= 0 ? edad : '';
    }

    fechaNacimiento.addEventListener('change', () => {
        edadInput.value = calcularEdadDesdeFecha(fechaNacimiento.value);
    });

    function soloLetrasYEspacios(e) {
        const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]*$/;
        if (!regex.test(e.key) && e.key.length === 1) e.preventDefault();
    }

    function soloNumeros(e) {
        if (!/^\d$/.test(e.key)) e.preventDefault();
    }

    nombre.addEventListener('keypress', soloLetrasYEspacios);
    apellidos.addEventListener('keypress', soloLetrasYEspacios);
    identidad.addEventListener('keypress', soloNumeros);
    telefono.addEventListener('keypress', soloNumeros);

    identidad.addEventListener('blur', () => {
        const val = identidad.value.trim();
        identidad.classList.remove('is-invalid');
        identidadError.style.display = 'none';
        mensajeAnio.style.display = 'none';

        if (!val) return;

        if (!/^\d{13}$/.test(val)) {
            identidad.classList.add('is-invalid');
            identidadError.textContent = 'La identidad debe tener exactamente 13 dígitos.';
            identidadError.style.display = 'block';
            return;
        }

        const anioStr = val.substr(4, 4);
        if (!/^\d{4}$/.test(anioStr)) {
            mensajeAnio.style.display = 'block';
            mensajeAnio.textContent = 'No se pudo extraer un año válido de la identidad.';
        } else {
            const anio = parseInt(anioStr, 10);
            const propuesta = anio + '-01-01';
            if ((minFecha && propuesta < minFecha) || (maxFecha && propuesta > maxFecha)) {
                mensajeAnio.style.display = 'block';
                mensajeAnio.textContent = 'El año extraído de la identidad está fuera del rango permitido (18–100 años).';
            }
        }

        fetch(`/pacientes/validar-identidad/${val}`)
            .then(res => res.json())
            .then(data => {
                if (data.valid_format === false) {
                    identidad.classList.add('is-invalid');
                    identidadError.textContent = 'Formato de identidad inválido.';
                    identidadError.style.display = 'block';
                } else if (data.existe) {
                    identidad.classList.add('is-invalid');
                    identidadError.textContent = 'Este número de identidad ya está registrado.';
                    identidadError.style.display = 'block';
                }
            })
            .catch(() => {});
    });

    form.addEventListener('submit', function (e) {
        if (!nombre.value.trim() || !apellidos.value.trim() || !identidad.value.trim() || !fechaNacimiento.value.trim() || !fechaOrden.value.trim()) {
            e.preventDefault();
            mensajeVacio.style.display = 'block';
            setTimeout(() => mensajeVacio.style.display = 'none', 3000);
            return false;
        }

        const edad = parseInt(edadInput.value, 10);
        if (isNaN(edad) || edad < 18 || edad > 100) {
            e.preventDefault();
            mensajeVacio.style.display = 'block';
            mensajeVacio.textContent = 'La edad calculada debe estar entre 18 y 100 años.';
            setTimeout(() => mensajeVacio.style.display = 'none', 3000);
            return false;
        }
    });

    btnLimpiar.addEventListener('click', () => {
        form.reset();
        edadInput.value = '';
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        identidadError.style.display = 'none';
        mensajeVacio.style.display = 'none';
        mensajeAnio.style.display = 'none';
    });

    if (fechaNacimiento.value) {
        edadInput.value = calcularEdadDesdeFecha(fechaNacimiento.value);
    }
});
</script>
@endsection
