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

/* Wrapper con margen para que no quede tapado por la barra y más grande */
.content-wrapper {
    margin-top: 60px;
    max-width: 1200px;
    background-color: #fff;
    margin-left: auto;
    margin-right: auto;
    border-radius: 1.5rem;
    padding: 3rem 3.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
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

h2 {
    color: #003366;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
}

/* Datos del paciente en línea recta */
.patient-data-grid {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    margin-bottom: 2rem;
}

.patient-data-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.patient-data-field {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 200px;
    flex: 1;
}

.patient-data-field strong {
    min-width: 120px;
    font-weight: 600;
    color: #003366;
}

.underline-field {
    border-bottom: 1px dashed #333;
    min-height: 1.5rem;
    padding-left: 4px;
    padding-right: 4px;
    user-select: none;
    flex: 1;
    font-size: 1rem;
}

/* Lista de pacientes */
#listaPacientes {
    z-index: 1150 !important;
    position: absolute;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ccc;
    border-radius: 0 0 0.4rem 0.4rem;
    display: none;
}

/* Botones */
button.btn-success, button.btn-primary, button.btn-warning, a.btn {
    font-size: 1.1rem;
    padding: 0.6rem 1.5rem;
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

/* Mensajes emergentes */
.mensaje-flash {
    display: none;
    background-color: #f8d7da;
    color: #842029;
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

/* Hover para checkbox */
.form-check-input:hover {
    border-color: #0056b3;
}

.form-check-label {
    cursor: pointer;
    user-select: none;
    padding-left: 0.3rem;
}

/* Checkboxes cuadrados */
.rayosx-grid input[type="checkbox"],
.examenes-grid input[type="checkbox"] {
    appearance: none;
    width: 18px;
    height: 18px;
    border: 2px solid #bbb;
    border-radius: 1px;
    background-color: #fff;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    vertical-align: middle;
}

.rayosx-grid input[type="checkbox"]:hover,
.examenes-grid input[type="checkbox"]:hover {
    border-color: #007bff;
}

.rayosx-grid input[type="checkbox"]:checked,
.examenes-grid input[type="checkbox"]:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.rayosx-grid input[type="checkbox"]:checked::after,
.examenes-grid input[type="checkbox"]:checked::after {
    content: "✔";
    color: white;
    font-size: 12px;
    position: absolute;
    top: 0;
    left: 2px;
}

/* Labels de checkboxes más grandes */
.rayosx-grid label,
.examenes-grid label {
    font-size: 1.1rem;
    line-height: 1rem;
    cursor: pointer;
    user-select: none;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

/* Contenedor Rayos X en grid */
.rayosx-grid,
.secciones-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.3rem 0.10rem;
    margin-top: 0.3rem;
}

.seccion {
    padding: 0;
}

.section-title {
    font-size: 1.5rem;
    margin: 0 0 0.7rem;
    color: rgb(6, 11, 17);
    font-weight: 700;
    line-height: 1.5rem;
}

.examenes-grid {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}

#totalPrecio {
    font-size: 1.3rem;
}

.linea-azul {
    height: 4px;
    background-color: #007BFF;
    width: 100%;
    border-radius: 2px;
    margin: 2rem 0 1rem 0;
}

.underline-field-solid {
    border-bottom: 1px solid #333;
    min-height: 1.5rem;
    padding-left: 4px;
    padding-right: 4px;
    user-select: none;
    flex: 1;
    font-size: 1rem;
}

/* Top controls - filtro, fecha, botón */
.top-controls {
    display: flex;
    flex-wrap: nowrap;
    gap: 0.5rem;
    justify-content: center;
    align-items: flex-end;
    margin-bottom: 1.5rem;
}

.top-controls > div {
    flex: 0 0 auto;
}

#buscarPaciente {
    width: 300px; /* más grande */
}

#fecha {
    width: 130px; /* más compacto */
}

a.btn-primary {
    width: 160px; /* tamaño fijo */
}

/* Responsive */
@media (max-width: 767.98px) {
    .top-controls {
        flex-direction: column;
        align-items: center;
    }
    #buscarPaciente, #fecha, a.btn-primary {
        width: 90%;
    }
}




</style>





<div class="content-wrapper">


            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                     <img src="{{ asset('images/logo2.jpg') }}" alt="Logo Clinitek" style="height: 60px; width: auto;">
                    <div style="font-size: 1rem; font-weight: 700; color: #555;">
                        Laboratorio Clínico Honduras
                    </div>
                </div>
                <div class="col-md-9 text-center" style="transform: translateX(30%);">
                    <h4 class="mb-0" style="font-size: 1.2rem; font-weight: 600; color: #333; line-height: 1.3;">
                        CREAR ORDEN DE RAYOS X
                    </h4>
                </div>
            </div>
<div class="linea-azul"></div>

               <div class="card-body">
       

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

          

        {{-- Mensajes emergentes personalizados --}}
        <div id="mensajePaciente" class="mensaje-flash"></div>
        <div id="mensajeExamenes" class="mensaje-flash"></div>

        <form action="{{ route('rayosx.store') }}" method="POST" id="formOrden" novalidate>
            @csrf

            {{-- FILTRO PACIENTE, BOTÓN REGISTRAR, FECHA en misma fila --}}
            <div class="top-controls">
    <!-- Buscar paciente -->
    <div style="position: relative;">
        <label for="buscarPaciente" class="form-label fw-bold">Buscar paciente <span class="text-danger">*</span></label>
        <input type="text" id="buscarPaciente" class="form-control" placeholder="Escribe para buscar paciente..." autocomplete="off" value="{{ old('paciente_nombre', '') }}" required>
        <input type="hidden" name="paciente_id" id="paciente_id" value="{{ old('paciente_id') }}">
        <ul id="listaPacientes" class="list-group">
            @foreach ($pacientes as $paciente)
                <li class="list-group-item list-group-item-action paciente-item" data-id="{{ $paciente->id }}" data-nombre="{{ $paciente->nombre }}" data-apellidos="{{ $paciente->apellidos ?? '' }}" data-identidad="{{ $paciente->identidad ?? '' }}" data-genero="{{ $paciente->genero ?? '' }}" style="cursor:pointer;">
                    {{ $paciente->nombre }} {{ $paciente->apellidos ?? '' }}
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Fecha -->
  <!-- Fecha -->
<div>
    <label for="fecha" class="form-label fw-bold">Fecha <span class="text-danger">*</span></label>
    <input type="date" 
           id="fecha" 
           name="fecha" 
           class="form-control" 
           value="{{ old('fecha', date('Y-m-d')) }}" 
           required>
</div>


    <!-- Botón registrar paciente -->
    <div>
        <label class="d-block">&nbsp;</label>
        <a href="{{ route('pacientes.create', ['returnUrl' => route('rayosx.create')]) }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Registrar
        </a>
    </div>
</div>

                
{{-- DATOS DEL PACIENTE CON ESTILO SUBRAYADO --}}
<div id="datosPaciente" style="display: {{ old('paciente_id') ? 'block' : 'none' }}; margin-bottom: 1.5rem;">

    <!-- Primera fila: Nombres y Apellidos -->
    <div class="patient-data-row">
        <div class="patient-data-field">
            <strong>Nombres:</strong>
            <div id="dp-nombres" class="underline-field-solid">{{ old('nombres') }}</div>
        </div>
        <div class="patient-data-field">
            <strong>Apellidos:</strong>
            <div id="dp-apellidos" class="underline-field-solid">{{ old('apellidos') }}</div>
        </div>
    </div>

    <!-- Segunda fila: Identidad y Género -->
    <div class="patient-data-row">
        <div class="patient-data-field">
            <strong>Identidad:</strong>
            <div id="dp-identidad" class="underline-field-solid">{{ old('identidad') }}</div>
        </div>
        <div class="patient-data-field">
            <strong>Género:</strong>
            <div id="dp-genero" class="underline-field-solid">{{ old('genero') }}</div>
        </div>
    </div>

</div>


{{-- SECCIONES Y EXÁMENES EN GRID DE 3 COLUMNAS --}}
<div class="secciones-container">
    @foreach($secciones as $categoria => $examenes)
        <div class="seccion">
            <div class="section-title">{{ $categoria }}</div>
            <div class="examenes-grid">
                @foreach($examenes as $clave => $datos)
                    <label>
                        <input 
                            type="checkbox" 
                            class="examen-checkbox" 
                            name="examenes[]" 
                            value="{{ $clave }}" 
                            data-precio="{{ $datos['precio'] }}"
                            {{ (is_array(old('examenes')) && in_array($clave, old('examenes'))) ? 'checked' : '' }}
                        >
                        {{ $datos['nombre'] }}
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>


    {{-- TOTAL DINÁMICO --}}
    <div class="mb-3 text-end fw-bold fs-5">
        Total a pagar: L<span id="totalPrecio">0.00</span>
    </div>
</div>



            {{-- BOTONES GUARDAR, LIMPIAR, VOLVER --}}
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-save"></i> Guardar e imprimir
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

// Bloquear fechas anteriores al día de hoy





// Fecha mínima y máxima
const fechaInput = document.getElementById('fecha');
const hoy = new Date();
const dd = String(hoy.getDate()).padStart(2, '0');
const mm = String(hoy.getMonth() + 1).padStart(2, '0'); // Mes actual (0-11)
const yyyy = hoy.getFullYear();
const fechaHoy = `${yyyy}-${mm}-${dd}`;
fechaInput.setAttribute('min', fechaHoy);

// Fecha máxima = 3 meses después
const fechaMax = new Date();
fechaMax.setMonth(fechaMax.getMonth() + 3);
const ddMax = String(fechaMax.getDate()).padStart(2, '0');
const mmMax = String(fechaMax.getMonth() + 1).padStart(2, '0');
const yyyyMax = fechaMax.getFullYear();
const fechaLimite = `${yyyyMax}-${mmMax}-${ddMax}`;
fechaInput.setAttribute('max', fechaLimite);

// Poner hoy por defecto si no hay old()
fechaInput.value = fechaInput.value || fechaHoy;


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
