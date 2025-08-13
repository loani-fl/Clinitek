@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        position: relative;
    }

    /* Barra fija superior */
    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1100;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
        text-align: center;
    }

    /* Wrapper con margen para que no quede tapado por la barra */
    .content-wrapper {
        margin-top: 60px;
        max-width: 1000px;
        background-color: #fff;
        margin-left: auto;
        margin-right: auto;
        border-radius: 1.5rem;
        padding: 2rem 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Logo translúcido en fondo */
    .content-wrapper::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 2200px;
        height: 2200px;
        background-image: url('{{ asset('images/logo2.jpg') }}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.07;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    /* Para que el contenido esté encima del fondo translúcido */
    .content-inner {
        position: relative;
        z-index: 1;
    }

    h2 {
        color: #003366;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .underline-field {
        border-bottom: 1px dashed #333;
        min-height: 1.5rem;
        padding-left: 4px;
        padding-right: 4px;
        user-select: none;
        flex: 1;
    }
    .datos-paciente-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    .datos-paciente-label {
        font-weight: 700;
        min-width: 120px;
        color: #003366;
        white-space: nowrap;
    }

    /* Cards para secciones de exámenes con sombra y borde azul */
    .card {
        border-radius: 0.6rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }
    .card-header {
        background-color: #007BFF !important;
        color: white !important;
        font-weight: 600;
        font-size: 1.1rem;
        border-bottom: none !important;
        border-top-left-radius: 0.6rem;
        border-top-right-radius: 0.6rem;
        padding: 0.75rem 1rem;
    }
    .card-body {
        padding: 1rem;
    }

    /* Lista de pacientes */
    #listaPacientes {
        z-index: 1150 !important; /* sobre la barra */
    }

    /* Botones */
    button.btn-success, button.btn-primary, button.btn-warning, a.btn {
        font-size: 1rem;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        border-radius: 0.4rem;
        margin-left: 0.5rem;
    }

    button.btn-warning {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
    }
    button.btn-warning:hover {
        color: #212529;
        background-color: #e0a800;
        border-color: #d39e00;
    }

    button.btn-success {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }
    button.btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
        color: white;
    }

    a.btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    a.btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
        color: white;
        text-decoration: none;
    }

    /* Mensajes emergentes estilo flash */
    .mensaje-flash {
        display: none; /* oculto por defecto */
        background-color: #f8d7da; /* rojo suave */
        color: #842029; /* texto rojo oscuro */
        border: 1px solid #f5c2c7;
        padding: 8px 12px;
        border-radius: 0.4rem;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        max-width: 600px;
        user-select: none;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
    }

    /* Estilo personalizado para checkboxes de exámenes */
    .form-check {
        padding-left: 2rem; /* espacio para checkbox */
        position: relative;
        margin-bottom: 0.8rem;
        cursor: pointer;
        user-select: none;
        font-weight: 600;
        color: #003366;
        font-size: 0.95rem;
    }

    .form-check-input {
        position: absolute;
        left: 0;
        top: 0.3rem;
        width: 1.4rem;
        height: 1.4rem;
        cursor: pointer;
        border-radius: 0.25rem;
        border: 2px solid #007BFF;
        background-color: #fff;
        transition: background-color 0.3s, border-color 0.3s;
    }

    .form-check-input:checked {
        background-color: #007BFF;
        border-color: #0056b3;
    }

    .form-check-input:checked::after {
        content: "";
        position: absolute;
        left: 0.45rem;
        top: 0.12rem;
        width: 0.3rem;
        height: 0.7rem;
        border: solid white;
        border-width: 0 0.18rem 0.18rem 0;
        transform: rotate(45deg);
    }

    /* Hover para el checkbox */
    .form-check-input:hover {
        border-color: #0056b3;
    }

    /* Etiqueta para mejor alineación y espaciado */
    .form-check-label {
        cursor: pointer;
        user-select: none;
        padding-left: 0.3rem;
    }

    /* Contenedor para el filtro, botón y fecha en una fila */
    .top-controls {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .top-controls > div,
    .top-controls > a,
    .top-controls > input[type="date"] {
        flex-grow: 1;
        min-width: 180px;
    }

    /* Para el input de búsqueda con lista */
    #buscarPaciente {
        width: 100%;
        box-sizing: border-box;
    }

    /* Ajustar la lista para que quede bajo el input */
    #listaPacientes {
        position: absolute;
        z-index: 1150;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ccc;
        border-radius: 0 0 0.4rem 0.4rem;
        display: none;
    }

    @media (max-width: 767.98px) {
        .top-controls {
            flex-direction: column;
            align-items: stretch;
        }
        .top-controls > div,
        .top-controls > a,
        .top-controls > input[type="date"] {
            flex-grow: unset;
            min-width: 100%;
        }
    }
</style>



<div class="content-wrapper">
    <div class="content-inner">

        {{-- Mensajes de error del servidor --}}
        @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

            <h2 class="fw-bold mb-0 flex-grow-1 text-center">Listado de pacientes</h2>

        {{-- Mensajes emergentes personalizados --}}
        <div id="mensajePaciente" class="mensaje-flash"></div>
        <div id="mensajeExamenes" class="mensaje-flash"></div>

        <form action="{{ route('rayosx.store') }}" method="POST" id="formOrden" novalidate>
            @csrf

            {{-- FILTRO PACIENTE, BOTÓN REGISTRAR, FECHA en misma fila --}}
            <div class="top-controls">
                <div style="position: relative;">
                    <label for="buscarPaciente" class="form-label fw-bold">Buscar paciente <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        id="buscarPaciente" 
                        class="form-control" 
                        placeholder="Escribe para buscar paciente..."
                        autocomplete="off"
                        value="{{ old('paciente_nombre', '') }}"
                        required
                    >
                    <input type="hidden" name="paciente_id" id="paciente_id" value="{{ old('paciente_id') }}">

                    <ul id="listaPacientes" class="list-group">
                        @foreach ($pacientes as $paciente)
                            <li 
                                class="list-group-item list-group-item-action paciente-item" 
                                data-id="{{ $paciente->id }}"
                                data-nombre="{{ $paciente->nombre }}"
                                data-apellidos="{{ $paciente->apellidos ?? '' }}"
                                data-identidad="{{ $paciente->identidad ?? '' }}"
                                data-genero="{{ $paciente->genero ?? '' }}"
                                style="cursor:pointer;"
                            >
                                {{ $paciente->nombre }} {{ $paciente->apellidos ?? '' }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <a href="{{ route('pacientes.create', ['returnUrl' => route('rayosx.create')]) }}" class="btn btn-primary" title="Registrar Paciente" style="white-space: nowrap;">
                    <i class="bi bi-person-plus"></i> Registrar paciente
                </a>

                <div>
                    <label for="fecha" class="form-label fw-bold">Fecha <span class="text-danger">*</span></label>
                    <input type="date" id="fecha" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
                </div>
            </div>

            {{-- DATOS DEL PACIENTE CON ESTILO SUBRAYADO --}}
            <div id="datosPaciente" style="display: {{ old('paciente_id') ? 'block' : 'none' }}; margin-bottom: 1.5rem;">
                <div class="datos-paciente-row">
                    <div class="datos-paciente-label">Nombres:</div>
                    <div id="dp-nombres" class="underline-field">{{ old('nombres') }}</div>
                </div>
                <div class="datos-paciente-row">
                    <div class="datos-paciente-label">Apellidos:</div>
                    <div id="dp-apellidos" class="underline-field">{{ old('apellidos') }}</div>
                </div>
                <div class="datos-paciente-row">
                    <div class="datos-paciente-label">Identidad:</div>
                    <div id="dp-identidad" class="underline-field">{{ old('identidad') }}</div>
                </div>
                <div class="datos-paciente-row">
                    <div class="datos-paciente-label">Género:</div>
                    <div id="dp-genero" class="underline-field">{{ old('genero') }}</div>
                </div>
            </div>

            {{-- SECCIONES Y EXÁMENES --}}
            <div class="mb-3">
                @foreach($secciones as $categoria => $examenes)
                    <div class="card">
                        <div class="card-header">
                            {{ $categoria }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($examenes as $clave => $datos)
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-check">
                                            <input 
                                                class="form-check-input examen-checkbox" 
                                                type="checkbox" 
                                                name="examenes[]" 
                                                value="{{ $clave }}" 
                                                id="examen_{{ $clave }}"
                                                data-precio="{{ $datos['precio'] }}"
                                                {{ (is_array(old('examenes')) && in_array($clave, old('examenes'))) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="examen_{{ $clave }}">
                                                {{ $datos['nombre'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- TOTAL DINÁMICO --}}
                <div class="mb-3 text-end fw-bold fs-5">
                    Total a pagar: L<span id="totalPrecio">0.00</span>
                </div>
            </div>

            {{-- BOTONES GUARDAR, LIMPIAR, VOLVER --}}
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-save"></i> Guardar Orden
                </button>
                <button type="button" id="btnLimpiar" class="btn btn-warning px-4 py-2">
                    <i class="bi bi-x-circle"></i> Limpiar
                </button>
                <a href="{{ route('rayosx.index') }}" class="btn btn-success px-4 py-2">
                    <i class="bi bi-arrow-left-circle"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputBuscar = document.getElementById('buscarPaciente');
    const lista = document.getElementById('listaPacientes');
    const inputHiddenId = document.getElementById('paciente_id');

    // Campos datos paciente estilo subrayado
    const dpNombres = document.getElementById('dp-nombres');
    const dpApellidos = document.getElementById('dp-apellidos');
    const dpIdentidad = document.getElementById('dp-identidad');
    const dpGenero = document.getElementById('dp-genero');

    // Mensajes emergentes
    const mensajePaciente = document.getElementById('mensajePaciente');
    const mensajeExamenes = document.getElementById('mensajeExamenes');

    // Mostrar lista al enfocar input si tiene texto
    inputBuscar.addEventListener('focus', () => {
        if (inputBuscar.value.trim() !== '') {
            lista.style.display = 'block';
        }
    });

    // Filtrar pacientes al escribir
    inputBuscar.addEventListener('input', () => {
        const filtro = inputBuscar.value.toLowerCase();
        let visibleCount = 0;

        Array.from(lista.children).forEach(li => {
            const texto = li.textContent.toLowerCase();
            if (texto.includes(filtro) && filtro !== '') {
                li.style.display = 'block';
                visibleCount++;
            } else {
                li.style.display = 'none';
            }
        });

        lista.style.display = visibleCount > 0 ? 'block' : 'none';

        // Limpiar id y campos si cambia texto
        inputHiddenId.value = '';
        dpNombres.textContent = '';
        dpApellidos.textContent = '';
        dpIdentidad.textContent = '';
        dpGenero.textContent = '';

        document.getElementById('datosPaciente').style.display = 'none';
        clearMensaje(mensajePaciente);
        clearMensaje(mensajeExamenes);
    });

    // Cuando el usuario clickea un paciente, ponerlo en el input y cargar datos
    lista.querySelectorAll('.paciente-item').forEach(item => {
        item.addEventListener('click', () => {
            inputBuscar.value = item.textContent.trim();
            inputHiddenId.value = item.getAttribute('data-id');
            lista.style.display = 'none';

            // Usar atributos data para rellenar sin hacer fetch
            dpNombres.textContent = item.getAttribute('data-nombre') || '';
            dpApellidos.textContent = item.getAttribute('data-apellidos') || '';
            dpIdentidad.textContent = item.getAttribute('data-identidad') || '';
            dpGenero.textContent = item.getAttribute('data-genero') || '';

            document.getElementById('datosPaciente').style.display = 'block';
            clearMensaje(mensajePaciente);
            clearMensaje(mensajeExamenes);
        });
    });

    // Cerrar lista si clic fuera
    document.addEventListener('click', (e) => {
        if (!inputBuscar.contains(e.target) && !lista.contains(e.target)) {
            lista.style.display = 'none';
        }
    });

    // Limitar selección a 7 exámenes
    const checkboxes = document.querySelectorAll('.examen-checkbox');
    const maxSeleccion = 7;

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.examen-checkbox:checked').length;
            if (checkedCount > maxSeleccion) {
                this.checked = false;
                showMensaje(mensajeExamenes, 'No puede seleccionar más de 7 exámenes.');
            } else {
                clearMensaje(mensajeExamenes);
            }
            actualizarTotal();
        });
    });

    // Función para actualizar el total a pagar
    function actualizarTotal() {
        let total = 0;
        checkboxes.forEach(chk => {
            if (chk.checked) {
                total += parseFloat(chk.getAttribute('data-precio')) || 0;
            }
        });
        document.getElementById('totalPrecio').textContent = total.toFixed(2);
    }

    // Actualizar total al cargar la página (para mantener old inputs)
    actualizarTotal();

    // Validar que se haya seleccionado un paciente válido antes de enviar
    const form = document.getElementById('formOrden');
    form.addEventListener('submit', (e) => {
        clearMensaje(mensajePaciente);
        clearMensaje(mensajeExamenes);

        if (!inputHiddenId.value) {
            e.preventDefault();
            showMensaje(mensajePaciente, 'Por favor selecciona un paciente válido de la lista.');
            inputBuscar.focus();
            return;
        }

        const seleccionados = document.querySelectorAll('.examen-checkbox:checked').length;
        if (seleccionados === 0) {
            e.preventDefault();
            showMensaje(mensajeExamenes, 'Debe seleccionar al menos un examen para guardar.');
            return;
        }
    });

    // Botón Limpiar
    document.getElementById('btnLimpiar').addEventListener('click', () => {
        // Limpiar inputs
        inputBuscar.value = '';
        inputHiddenId.value = '';
        dpNombres.textContent = '';
        dpApellidos.textContent = '';
        dpIdentidad.textContent = '';
        dpGenero.textContent = '';
        document.getElementById('datosPaciente').style.display = 'none';

        // Limpiar fecha a hoy
        document.getElementById('fecha').value = new Date().toISOString().slice(0, 10);

        // Limpiar checkboxes
        checkboxes.forEach(chk => chk.checked = false);

        // Actualizar total
        actualizarTotal();

        // Limpiar mensajes
        clearMensaje(mensajePaciente);
        clearMensaje(mensajeExamenes);
    });

    // Funciones para mostrar y limpiar mensajes emergentes
    function showMensaje(mensajeElem, texto) {
        mensajeElem.textContent = texto;
        mensajeElem.style.display = 'inline-block';
        mensajeElem.scrollIntoView({behavior: 'smooth', block: 'center'});

        setTimeout(() => {
            mensajeElem.style.display = 'none';
            mensajeElem.textContent = '';
        }, 4000);
    }
    function clearMensaje(mensajeElem) {
        mensajeElem.textContent = '';
        mensajeElem.style.display = 'none';
    }
});
</script>

@endsection
