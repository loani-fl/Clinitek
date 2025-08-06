@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
    $hoy = Carbon::today();
    $maxFechaNacimiento = $hoy->copy()->subYears(18)->format('Y-m-d');   // fecha más reciente permitida
    $minFechaNacimiento = $hoy->copy()->subYears(100)->format('Y-m-d');  // fecha más antigua permitida
@endphp

<div class="container py-4" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Registrar Paciente para Rayos X</h5>
        </div>
        <div class="card-body">

            {{-- Mensaje general para formulario vacío --}}
            <div id="mensaje-vacio" class="alert alert-danger" style="display:none;">
                Por favor, complete todos los campos requeridos.
            </div>

            {{-- Mensaje para año de identidad inválido --}}
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

                <!-- FILA 1: Nombres | Apellidos | Identidad | Teléfono -->
                <div class="row gx-2">
                    <div class="col-md-3 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               required
                               pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
                               title="Solo letras y espacios">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text"
                               class="form-control @error('apellidos') is-invalid @enderror"
                               id="apellidos"
                               name="apellidos"
                               value="{{ old('apellidos') }}"
                               required
                               pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
                               title="Solo letras y espacios">
                        @error('apellidos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="identidad" class="form-label">Número de Identidad</label>
                        <input type="text"
                               class="form-control @error('identidad') is-invalid @enderror"
                               id="identidad"
                               name="identidad"
                               value="{{ old('identidad') }}"
                               required
                               maxlength="13"
                               pattern="^\d{13}$"
                               title="Solo números (13 dígitos)"
                               autocomplete="off">
                        <div id="identidad-error" class="invalid-feedback" style="display:none;">
                            Este número de identidad ya está registrado o es inválido.
                        </div>
                        @error('identidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text"
                               class="form-control @error('telefono') is-invalid @enderror"
                               id="telefono"
                               name="telefono"
                               value="{{ old('telefono') }}"
                               maxlength="15"
                               pattern="^\d+$"
                               title="Sólo números (opcional)">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Opcional — sólo números.</div>
                    </div>
                </div>

                <!-- FILA 2: Fecha de nacimiento | Edad | Fecha de orden -->
                <div class="row gx-2">
                    <div class="col-md-4 mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date"
                               class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                               id="fecha_nacimiento"
                               name="fecha_nacimiento"
                               value="{{ old('fecha_nacimiento') }}"
                               required
                               min="{{ $minFechaNacimiento }}"
                               max="{{ $maxFechaNacimiento }}">
                        <div class="form-text">La fecha debe estar entre {{ \Carbon\Carbon::parse($minFechaNacimiento)->format('d/m/Y') }}
                            y {{ \Carbon\Carbon::parse($maxFechaNacimiento)->format('d/m/Y') }} (18–100 años).</div>
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="edad" class="form-label">Edad (años)</label>
                        <input type="text"
                               class="form-control"
                               id="edad"
                               name="edad"
                               value="{{ old('edad') }}"
                               readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fecha_orden" class="form-label">Fecha de Orden</label>
                        <input type="date"
                               class="form-control @error('fecha_orden') is-invalid @enderror"
                               id="fecha_orden"
                               name="fecha_orden"
                               value="{{ old('fecha_orden') ?? date('Y-m-d') }}"
                               required>
                        @error('fecha_orden')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('rayosx.create') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Registrar Paciente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    const minFecha = fechaNacimiento.getAttribute('min');
    const maxFecha = fechaNacimiento.getAttribute('max');

    // calcular edad desde fecha (yyyy-mm-dd)
    function calcularEdadDesdeFecha(fecha) {
        if (!fecha) return '';
        const hoy = new Date();
        const f = new Date(fecha);
        let edad = hoy.getFullYear() - f.getFullYear();
        const m = hoy.getMonth() - f.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < f.getDate())) edad--;
        return edad >= 0 ? edad : '';
    }

    // actualizar edad si cambia fecha_nacimiento
    fechaNacimiento.addEventListener('change', () => {
        edadInput.value = calcularEdadDesdeFecha(fechaNacimiento.value);
    });

    // solo letras en nombre/apellidos
    function soloLetrasYEspacios(e) {
        const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]*$/;
        if (!regex.test(e.key) && e.key.length === 1) e.preventDefault();
    }
    nombre.addEventListener('keypress', soloLetrasYEspacios);
    apellidos.addEventListener('keypress', soloLetrasYEspacios);

    // solo números en identidad y teléfono
    function soloNumeros(e) {
        const regex = /^[0-9]*$/;
        if (!regex.test(e.key) && e.key.length === 1) e.preventDefault();
    }
    identidad.addEventListener('keypress', soloNumeros);
    telefono.addEventListener('keypress', soloNumeros);

    // on blur identidad: validar formato, extraer año y AJAX existencia
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

        const anioStr = val.substr(4,4);
        if (!/^\d{4}$/.test(anioStr)) {
            mensajeAnio.style.display = 'block';
            mensajeAnio.textContent = 'No se pudo extraer un año válido de la identidad.';
        } else {
            const anio = parseInt(anioStr, 10);
            const propuesta = anio + '-01-01';

            if ((minFecha && propuesta < minFecha) || (maxFecha && propuesta > maxFecha)) {
                mensajeAnio.style.display = 'block';
                mensajeAnio.textContent = 'El año extraído de la identidad está fuera del rango permitido (18–100 años).';
            } else {
                if (!fechaNacimiento.value) {
                    fechaNacimiento.value = propuesta;
                    edadInput.value = calcularEdadDesdeFecha(propuesta);
                } else {
                    const edadDesdeFecha = calcularEdadDesdeFecha(fechaNacimiento.value);
                    const edadDesdeIdentidad = (new Date()).getFullYear() - anio;
                    if (Math.abs(edadDesdeFecha - edadDesdeIdentidad) > 2) {
                        mensajeAnio.style.display = 'block';
                        mensajeAnio.textContent = 'La edad calculada desde la identidad difiere de la fecha de nacimiento ingresada.';
                    }
                }
            }
        }

        // AJAX: validar identidad única (ajusta la ruta si es distinta)
        fetch(`/pacientes/validar-identidad/${val}`)
            .then(res => res.json())
            .then(data => {
                if (data.valid_format === false) {
                    identidad.classList.add('is-invalid');
                    identidadError.textContent = 'Formato de identidad inválido.';
                    identidadError.style.display = 'block';
                    return;
                }

                if (data.existe) {
                    identidad.classList.add('is-invalid');
                    identidadError.textContent = 'Este número de identidad ya está registrado.';
                    identidadError.style.display = 'block';
                } else {
                    identidad.classList.remove('is-invalid');
                    identidadError.style.display = 'none';
                }
            })
            .catch(() => {
                // Ignorar errores fetch en cliente
            });
    });

    // Envío del formulario: validaciones cliente
    form.addEventListener('submit', function(e) {
        // campos requeridos
        if (!nombre.value.trim() || !apellidos.value.trim() || !identidad.value.trim() || !fechaNacimiento.value.trim() || !fechaOrden.value.trim()) {
            e.preventDefault();
            mensajeVacio.style.display = 'block';
            setTimeout(() => { mensajeVacio.style.display = 'none'; }, 3000);
            return false;
        }

        // identidad 13 dígitos
        if (!/^\d{13}$/.test(identidad.value.trim())) {
            e.preventDefault();
            identidad.classList.add('is-invalid');
            identidadError.textContent = 'La identidad debe tener exactamente 13 dígitos.';
            identidadError.style.display = 'block';
            setTimeout(() => { identidadError.style.display = 'none'; identidad.classList.remove('is-invalid'); }, 3000);
            return false;
        }

        // edad calculada desde fecha_nacimiento debe estar entre 18 y 100
        const edad = parseInt(edadInput.value, 10);
        if (isNaN(edad) || edad < 18 || edad > 100) {
            e.preventDefault();
            mensajeVacio.style.display = 'block';
            mensajeVacio.textContent = 'La edad calculada debe estar entre 18 y 100 años.';
            setTimeout(() => {
                mensajeVacio.style.display = 'none';
                mensajeVacio.textContent = 'Por favor, complete todos los campos requeridos.';
            }, 3000);
            return false;
        }

        // bloqueo si identidad marcada invalida por AJAX
        if (identidad.classList.contains('is-invalid')) {
            e.preventDefault();
            identidad.focus();
            return false;
        }
    });

    // Si al cargar ya hay fecha de nacimiento calculamos edad
    if (fechaNacimiento.value) {
        edadInput.value = calcularEdadDesdeFecha(fechaNacimiento.value);
    }
});
</script>
@endsection

