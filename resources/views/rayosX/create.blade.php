@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


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

    .custom-card::before {
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

    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        margin: 40px auto;
        border-radius: 1.5rem;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        z-index: 1;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: transparent !important;
        border-bottom: 3px solid #007BFF;
    }

    .section-title {
        font-size: 1.1rem;
        margin-bottom: 0.7rem;
        font-weight: 700;
        color: #003366;
    }

    .seccion {
        padding: 0.8rem;
        border-radius: 0;
    }

    .underline-field {
        border-bottom: 1px dashed #333;
        flex: 1;
        min-height: 1.5rem;
        padding-left: 4px;
        padding-right: 4px;
        user-select: none;
    }

    .btn-imprimir,
    .btn-danger,
    .btn-warning,
    .btn-primary,
    .btn-success {
        font-size: 0.95rem;
        padding: 0.45rem 0.9rem;
    }

    .patient-select-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .secciones-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.3rem 0.10rem; /* poco espacio entre secciones */
        margin-top: 0.3rem;
    }

    .seccion {
        padding: 0;
    }

    .section-title {
        font-size: 1.5rem; /* más grande */
        margin: 0 0 0.7rem;
        color: rgb(6, 11, 17);
        font-weight: 700;
        line-height: 1.6rem; /* ajusta para que no quede muy apretado */
    }

    .examenes-grid {
        display: flex;
        flex-direction: column;
        gap: 0.15rem; /* menos espacio entre checkboxes */
    }

    .examenes-grid label {
        font-size: 0.85rem;
        line-height: 1rem;
        cursor: pointer;
    }

    .examenes-grid input[type="checkbox"] {
        margin-right: 6px;
    }

    .section-title:first-of-type {
        margin-top: 3rem; /* Ajusta el valor a lo que necesites */
    }

    /* Título general (más grande) */
    .section-title.general {
        font-size: 1.5rem; /* tamaño mayor */
        margin: 0 0 0.7rem;
        color: rgb(6, 11, 17);
        font-weight: 700;
        line-height: 1.6rem;
        text-align: center; /* CENTRAR */
    }

    /* Títulos de secciones (más pequeños) */
    .section-title.seccion {
        font-size: 1.1rem; /* tamaño menor */
        margin-bottom: 0.5rem;
        color: #003366;
        font-weight: 700;
        line-height: 1.3rem;
    }

    /* Estilos para mensajes flash emergentes */
    .mensaje-flash {
        display: none; /* oculto por defecto */
        background-color: #f8d7da; /* rojo suave */
        color: #842029; /* texto rojo oscuro */
        border: 1px solid #f5c2c7;
        padding: 8px 12px;
        border-radius: 0.4rem;
        font-weight: 600;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        max-width: 600px;
        user-select: none;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card">
        <div class="card-header text-center">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ asset('images/logo2.jpg') }}" alt="Logo" style="height: 60px;">
                    <div style="font-size: 1rem; font-weight: 700; color: #555;">
                        Laboratorio Clínico Honduras
                    </div>
                </div>
                <div class="col-md-9 text-center" style="transform: translateX(30%);">
                    <h4 class="mb-0" style="font-size: 1.2rem; font-weight: 600; color: #333;">
                        CREAR ORDEN DE RAYOS X
                    </h4>
                </div>
            </div>
        </div>

        <div class="card-body">
            {{-- Flash messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('rayosx.store') }}" id="formOrden" novalidate>
                @csrf

                <div class="mb-4 row align-items-center">
                    <label for="paciente_id" class="col-md-2 col-form-label fw-bold text-end">Seleccione Paciente</label>
                    <div class="col-md-4">
                        <select id="paciente_id" name="seleccion" class="form-select" required>
                            <option value="" selected disabled>-- Seleccione un paciente --</option>
                            @foreach($pacientesClinica as $paciente)
                                <option value="clinica-{{ $paciente->id }}"
                                    data-nombre="{{ $paciente->nombre }} {{ $paciente->apellidos }}"
                                    data-identidad="{{ $paciente->identidad }}"
                                    data-fecha_nacimiento="{{ $paciente->fecha_nacimiento }}"
                                    data-edad="{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }}"
                                    data-datos_clinicos="{{ e($paciente->datos_clinicos ?? '') }}"
                                    {{ (old('seleccion') == "clinica-{$paciente->id}") ? 'selected' : '' }}>
                                    {{ $paciente->nombre }} {{ $paciente->apellidos }} - {{ $paciente->identidad }} (Clínica)
                                </option>
                            @endforeach
                            @foreach($pacientesRayosX as $paciente)
                                <option value="rayosx-{{ $paciente->id }}"
                                    data-nombre="{{ $paciente->nombre }} {{ $paciente->apellidos }}"
                                    data-identidad="{{ $paciente->identidad }}"
                                    data-fecha_nacimiento="{{ $paciente->fecha_nacimiento }}"
                                    data-edad="{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }}"
                                    data-datos_clinicos="{{ e($paciente->datos_clinicos ?? '') }}"
                                    {{ (old('seleccion') == "rayosx-{$paciente->id}") ? 'selected' : '' }}>
                                    {{ $paciente->nombre }} {{ $paciente->apellidos }} - {{ $paciente->identidad }} (Rayos X)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <label for="fecha" class="col-md-1 col-form-label fw-bold text-end">Fecha</label>
                    <div class="col-md-2">
                        <input type="date" id="fecha" name="fecha" class="form-control"
                            value="{{ old('fecha', date('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ route('pacientes.rayosx.create') }}" class="btn btn-primary w-100">
                            Registrar
                        </a>
                    </div>
                </div>

                {{-- Datos del paciente --}}
                <div id="datosPaciente" class="mb-4" style="display:none;">
                    <div class="section-title">DATOS DEL PACIENTE</div>
                    <div class="row">
                        <div class="col-md-6 mb-2 d-flex align-items-center">
                            <strong class="me-2">Nombre completo:</strong>
                            <div class="underline-field" id="dp-nombre"></div>
                        </div>
                        <div class="col-md-6 mb-2 d-flex align-items-center">
                            <strong class="me-2">Identidad:</strong>
                            <div class="underline-field" id="dp-identidad"></div>
                        </div>
                        <div class="col-md-4 mb-2 d-flex align-items-center">
                            <strong class="me-2">Edad:</strong>
                            <div class="underline-field" id="dp-edad"></div>
                        </div>
                        <div class="col-md-4 mb-2 d-flex align-items-center">
                            <strong class="me-2">Fecha Nacimiento:</strong>
                            <div class="underline-field" id="dp-fecha-nac"></div>
                        </div>
                    </div>
                </div>

                {{-- Mensajes debajo de datos paciente --}}
                <div id="mensajePaciente" class="mensaje-flash"></div>
                <div id="mensajeExamenes" class="mensaje-flash"></div>

                {{-- Datos clínicos --}}
                <div class="mb-4 row align-items-center" id="datosClinicosContainer" style="display:none;">
                    <label for="datos_clinicos" class="col-md-3 col-form-label fw-bold text-end">Datos Clínicos</label>
                    <div class="col-md-6">
                        <textarea name="datos_clinicos" id="datos_clinicos" rows="3" class="form-control">{{ old('datos_clinicos') }}</textarea>
                    </div>
                </div>

                {{-- Exámenes --}}
                <div class="section-title general">Estudios para rayos X</div>

                <div class="secciones-container">
                    @foreach($secciones as $titulo => $examenesSeccion)
                        <div class="seccion">
                            <div class="section-title seccion">{{ $titulo }}</div>
                            <div class="examenes-grid">
                                @foreach($examenesSeccion as $clave)
                                    <label>
                                        <input type="checkbox" name="examenes[]" value="{{ $clave }}"
                                            {{ (is_array(old('examenes')) && in_array($clave, old('examenes'))) ? 'checked' : '' }}>
                                        {{ $examenes[$clave] ?? ucfirst(str_replace('_', ' ', $clave)) }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Total a pagar dinámico --}}
                <div id="totalAPagar" class="mb-3 fw-bold fs-5 text-end">
                    Total a pagar: $0.00
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Orden
                    </button>
                    <button type="button" id="btnLimpiar" class="btn btn-warning">
                        <i class="bi bi-trash"></i> Limpiar
                    </button>
                    <a href="{{ route('rayosx.index') }}" class="btn btn-success">
                        <i class="bi bi-arrow-left-circle"></i> Regresar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectPaciente = document.getElementById('paciente_id');
    const datosPacienteDiv = document.getElementById('datosPaciente');
    const dpNombre = document.getElementById('dp-nombre');
    const dpIdentidad = document.getElementById('dp-identidad');
    const dpEdad = document.getElementById('dp-edad');
    const dpFechaNac = document.getElementById('dp-fecha-nac');
    const datosClinicosContainer = document.getElementById('datosClinicosContainer');
    const datosClinicosTextarea = document.getElementById('datos_clinicos');

    const mensajePaciente = document.getElementById('mensajePaciente');
    const mensajeExamenes = document.getElementById('mensajeExamenes');
    const formOrden = document.getElementById('formOrden');
    const checkboxes = formOrden.querySelectorAll('input[type=checkbox][name="examenes[]"]');
    const MAX_EXAMENES = 7;
    const totalAPagarDiv = document.getElementById('totalAPagar');

    // Objeto con precios por examen (corregido con ':')
    const preciosExamenes = {
        'craneo_anterior_posterior': 120.00,
        'craneo_lateral': 110.00,
        'waters': 100.00,
        'waters_lateral': 100.00,
        'conductos_auditivos': 80.00,
        'cavum': 90.00,
        'senos_paranasales': 85.00,
        'silla_turca': 95.00,
        'huesos_nasales': 75.00,
        'atm_tm': 90.00,
        'mastoides': 88.00,
        'mandibula': 85.00,
        'torax_posteroanterior_pa': 150.00,
        'torax_anteroposterior_ap': 150.00,
        'torax_lateral': 140.00,
        'torax_oblicuo': 130.00,
        'torax_superior': 120.00,
        'torax_inferior': 120.00,
        'costillas_superiores': 110.00,
        'costillas_inferiores': 110.00,
        'esternon_frontal': 100.00,
        'esternon_lateral': 100.00,
        'abdomen_simple': 130.00,
        'abdomen_agudo': 150.00,
        'abdomen_erecto': 140.00,
        'abdomen_decubito': 140.00,
        'clavicula_izquierda': 90.00,
        'clavicula_derecha': 90.00,
        'hombro_anterior': 100.00,
        'hombro_lateral': 100.00,
        'humero_proximal': 110.00,
        'humero_distal': 110.00,
        'codo_anterior': 90.00,
        'codo_lateral': 90.00,
        'antebrazo': 80.00,
        'muneca': 80.00,
        'mano': 80.00,
        'cadera_izquierda': 120.00,
        'cadera_derecha': 120.00,
        'femur_proximal': 130.00,
        'femur_distal': 130.00,
        'rodilla_anterior': 110.00,
        'rodilla_lateral': 110.00,
        'tibia': 100.00,
        'pie': 90.00,
        'calcaneo': 90.00,
        'columna_cervical_lateral': 100.00,
        'columna_cervical_anteroposterior': 100.00,
        'columna_dorsal_lateral': 110.00,
        'columna_dorsal_anteroposterior': 110.00,
        'columna_lumbar_lateral': 110.00,
        'columna_lumbar_anteroposterior': 110.00,
        'sacro_coxis': 100.00,
        'pelvis_anterior_posterior': 120.00,
        'pelvis_oblicua': 120.00,
        'escoliosis': 100.00,
        'arteriograma_simple': 250.00,
        'arteriograma_contraste': 300.00,
        'histerosalpingograma_simple': 230.00,
        'histerosalpingograma_contraste': 280.00,
        'colecistograma_simple': 220.00,
        'colecistograma_contraste': 270.00,
        'fistulograma_simple': 210.00,
        'fistulograma_contraste': 260.00,
        'artrograma_simple': 200.00,
        'artrograma_contraste': 250.00
    };

    // Mostrar datos paciente cuando cambie selección
    function mostrarDatosPaciente() {
        const selectedOption = selectPaciente.options[selectPaciente.selectedIndex];
        if (selectPaciente.value && selectedOption) {
            dpNombre.textContent = selectedOption.getAttribute('data-nombre') || '';
            dpIdentidad.textContent = selectedOption.getAttribute('data-identidad') || '';
            const fechaNacRaw = selectedOption.getAttribute('data-fecha_nacimiento') || '';
            dpFechaNac.textContent = fechaNacRaw ? new Date(fechaNacRaw).toLocaleDateString('es-ES') : '';
            const edad = selectedOption.getAttribute('data-edad') || '';
            dpEdad.textContent = edad ? `${edad} años` : '';
            datosPacienteDiv.style.display = 'block';

            const datosClin = selectedOption.getAttribute('data-datos_clinicos') || '';
            if (datosClin.trim() !== '') {
                datosClinicosTextarea.value = datosClin;
                datosClinicosContainer.style.display = 'flex';
            } else {
                datosClinicosTextarea.value = '';
                datosClinicosContainer.style.display = 'none';
            }
        } else {
            dpNombre.textContent = '';
            dpIdentidad.textContent = '';
            dpFechaNac.textContent = '';
            dpEdad.textContent = '';
            datosPacienteDiv.style.display = 'none';
            datosClinicosContainer.style.display = 'none';
        }
        clearMensaje(mensajePaciente);
    }
    selectPaciente?.addEventListener('change', mostrarDatosPaciente);
    if (selectPaciente && selectPaciente.value) {
        mostrarDatosPaciente();
    }

    // Limpiar formulario
    const btnLimpiar = document.getElementById('btnLimpiar');
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', () => {
            formOrden.reset();
            datosPacienteDiv.style.display = 'none';
            datosClinicosContainer.style.display = 'none';
            const fecha = document.getElementById('fecha');
            if (fecha) fecha.value = new Date().toISOString().slice(0,10);
            clearMensaje(mensajePaciente);
            clearMensaje(mensajeExamenes);
            actualizarTotal();
        });
    }

    // Mostrar mensaje emergente con scroll y autoclose
    function showMensaje(mensajeElem, texto) {
        mensajeElem.textContent = texto;
        mensajeElem.style.display = 'inline-block'; // para estilo tipo etiqueta

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

    // Control de límite de selección de rayos X
    checkboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            const seleccionados = [...checkboxes].filter(c => c.checked);
            if (seleccionados.length > MAX_EXAMENES) {
                // Deseleccionar último y mostrar mensaje
                this.checked = false;
                showMensaje(mensajeExamenes, `Solo se pueden seleccionar máximo ${MAX_EXAMENES} rayos X.`);
            } else {
                clearMensaje(mensajeExamenes);
            }
            actualizarTotal();
        });
    });

    // Función para actualizar total a pagar
    function actualizarTotal() {
        let total = 0;
        checkboxes.forEach(chk => {
            if (chk.checked) {
                const clave = chk.value;
                if (preciosExamenes.hasOwnProperty(clave)) {
                    total += preciosExamenes[clave];
                }
            }
        });
        totalAPagarDiv.textContent = `Total a pagar: $${total.toFixed(2)}`;
    }

    // Ejecutar al cargar para mostrar total si hay checkboxes preseleccionados
    actualizarTotal();

    // Validar formulario antes de enviar
    formOrden.addEventListener('submit', function(e) {
        clearMensaje(mensajePaciente);
        clearMensaje(mensajeExamenes);

        // Verificar paciente seleccionado
        if (!selectPaciente.value) {
            e.preventDefault();
            showMensaje(mensajePaciente, 'Debe seleccionar un paciente antes de guardar.');
            selectPaciente.focus();
            return;
        }

        // Verificar al menos un examen seleccionado
        const seleccionados = [...checkboxes].filter(c => c.checked);
        if (seleccionados.length === 0) {
            e.preventDefault();
            showMensaje(mensajeExamenes, 'Debe seleccionar al menos un examen para guardar.');
            mensajeExamenes.scrollIntoView({behavior: 'smooth', block: 'center'});
            return;
        }
    });

    // Para debug - puedes borrar
    checkboxes.forEach(chk => {
        console.log('Examen:', chk.value);
    });

});
</script>
@endsection
