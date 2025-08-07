@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
    $hoy = Carbon::today();
    $maxFechaNacimiento = $hoy->copy()->subYears(18)->format('Y-m-d');
    $minFechaNacimiento = '1940-01-01';
@endphp

<div class="container py-4" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-center">Registrar Paciente para Rayos X</h5>
        </div>
        <div class="card-body">
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
    </div>
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
