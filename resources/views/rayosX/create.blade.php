@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* ... (tu CSS actual) ... */
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
    }
    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 1100;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
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
        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.1;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }
    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        margin: 40px auto 60px auto; 
        border-radius: 1.5rem;
        padding: 1rem 2rem;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 3px solid #007BFF;
    }
    .patient-data-grid {
        background: transparent;
        box-shadow: none;
        border-radius: 0;
        padding: 0;
        margin-bottom: 2rem;
    }
    .patient-data-grid strong {
        color:rgb(3, 12, 22);
        font-weight: 600;
    }
    .underline-field {
        border-bottom: 1px solid #000;
        min-height: 1.4rem;
        line-height: 1.4rem;
        padding-left: 4px;
        padding-right: 4px;
        font-size: 0.95rem;
        flex: 1;
        user-select: none;
    }
    .fixed-name {
        min-width: 400px;
    }
    .patient-data-grid .row > div {
        display: flex;
        align-items: center;
    }
    .btn-imprimir {
        background-color: rgb(97, 98, 99);
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 0.375rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-imprimir:hover {
        background-color: #a59d8f;
        color: #fff;
    }
    .btn {
        padding: 0.40rem 0.5rem;
        font-size: 0.95rem;
        line-height: 1.2;
    }
    #max-examenes-error, #min-examenes-error {
        display: none;
        padding: 0.6rem 1rem;
        margin-bottom: 1rem;
        font-weight: 600;
        border-radius: 0.3rem;
        width: 100%;
    }
    .alert-custom {
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        box-sizing: border-box;
        display: block;
    }
    .alert-error {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
        box-shadow: 0 0 10px rgba(216, 62, 62, 0.5);
    }
    .secciones-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.3rem 0.10rem;
        margin-top: 0.3rem;
    }
    .seccion {
        padding: 0;
    }
    .section-title {
        font-size: 1.1rem;
        margin: 0 0 0.7rem;
        color: rgb(6, 11, 17);
        font-weight: 700;
        line-height: 1.4rem;
    }
    .examenes-grid {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }
    .examenes-grid label {
        font-size: 0.85rem;
        line-height: 1rem;
    }
    .d-flex.justify-content-center.gap-3.mt-4 {
        margin-top: 2rem !important;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
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
        </div>

        <div class="card-body">

            {{-- Mensajes flash --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('rayosx.store') }}" id="formOrden">
                @csrf
<div class="mb-4 row align-items-center">
    <label for="diagnostico_id" class="col-md-3 col-form-label fw-bold text-end">
        Seleccione Diagnóstico
    </label>
    <div class="col-md-5">
        @if($diagnosticos->isEmpty())
            <div class="alert alert-warning" role="alert">
                No hay diagnósticos realizados disponibles.
            </div>
        @else
            <select name="diagnostico_id" id="diagnostico_id" class="form-select form-select-sm" required>
                <option value="" disabled {{ old('diagnostico_id') ? '' : 'selected' }}>-- Seleccione un diagnóstico --</option>
                @foreach($diagnosticos as $diagnostico)
                    @if($diagnostico->consulta)
                        <option value="{{ $diagnostico->id }}"
                            data-nombre="{{ $diagnostico->paciente->nombre }} {{ $diagnostico->paciente->apellidos }}"
                            data-identidad="{{ $diagnostico->paciente->identidad }}"
                            data-fecha="{{ \Carbon\Carbon::parse($diagnostico->created_at)->format('d/m/Y') }}"
                            data-edad="{{ \Carbon\Carbon::parse($diagnostico->paciente->fecha_nacimiento)->age }}"
                            data-medico="{{ $diagnostico->consulta->medico->nombre ?? '' }} {{ $diagnostico->consulta->medico->apellidos ?? '' }}"
                            {{ old('diagnostico_id') == $diagnostico->id ? 'selected' : '' }}>
                            {{ $diagnostico->paciente->nombre }} {{ $diagnostico->paciente->apellidos }} - Diagnóstico #{{ $diagnostico->id }}
                        </option>
                    @endif
                @endforeach
            </select>
        @endif
    </div>
</div>


                {{-- Datos dinámicos del paciente y diagnóstico --}}
                <div class="section-title">DATOS DEL PACIENTE </div>

                <div class="patient-data-grid mb-4" id="datosPaciente" style="display:none;">
                    <div class="row">
                        <div class="col-md-8 mb-2 d-flex align-items-center">
                            <strong class="me-2">Paciente:</strong>
                            <div class="underline-field no-select" id="pacienteNombre"></div>
                        </div>
                        <div class="col-md-4 mb-2 d-flex align-items-center">
                            <strong class="me-2">Fecha Diagnóstico:</strong>
                            <div class="underline-field no-select" id="fechaDiagnostico"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-2 d-flex align-items-center">
                            <strong class="me-2">Identidad:</strong>
                            <div class="underline-field no-select" id="pacienteIdentidad"></div>
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-center">
                            <strong class="me-2">Edad:</strong>
                            <div class="underline-field no-select" id="pacienteEdad"></div>
                        </div>
                        <div class="col-md-6 mb-2 d-flex align-items-center">
                            <strong class="me-2">Médico Solicitante:</strong>
                            <div class="underline-field no-select" id="medicoSolicitante"></div>
                        </div>
                    </div>
                </div>

                <div id="mensajesEmergentes" style="margin-top: 1rem; min-height: 40px;"></div>

                {{-- Secciones con checkboxes para estudios de Rayos X --}}
                @php
                $secciones = [
                    'Rayos X Cabeza' => [
                        'craneo' => 'Cráneo',
                        'waters' => 'Waters',
                        'conductos_auditivos' => 'Conductos Auditivos',
                        'cavum' => 'Cavum',
                        'senos_paranasales' => 'Senos Paranasales',
                        'silla_turca' => 'Silla Turca',
                        'huesos_nasales' => 'Huesos Nasales',
                        'atm_tm' => 'ATM - TM',
                        'mastoides' => 'Mastoides',
                        'mandibula' => 'Mandíbula',
                    ],
                    'Rayos X Tórax' => [
                        'torax_pa' => 'Tórax PA',
                        'torax_pa_lat' => 'Tórax PA Lateral',
                        'costillas' => 'Costillas',
                        'esternon' => 'Esternón',
                    ],
                    'Rayos X Abdomen' => [
                        'abdomen_simple' => 'Abdomen Simple',
                        'abdomen_agudo' => 'Abdomen Agudo',
                    ],
                    'Rayos X Extremidad Superior' => [
                        'clavicula' => 'Clavícula',
                        'hombro' => 'Hombro',
                        'humero' => 'Húmero',
                        'codo' => 'Codo',
                        'antebrazo' => 'Antebrazo',
                        'muneca' => 'Muñeca',
                        'mano' => 'Mano',
                    ],
                    'Rayos X Extremidad Inferior' => [
                        'cadera' => 'Cadera',
                        'femur' => 'Fémur',
                        'rodilla' => 'Rodilla',
                        'tibia' => 'Tibia',
                        'pie' => 'Pie',
                        'calcaneo' => 'Calcáneo',
                    ],
                    'Rayos X Columna y Pelvis' => [
                        'cervical' => 'Cervical',
                        'dorsal' => 'Dorsal',
                        'lumbar' => 'Lumbar',
                        'sacro_coxis' => 'Sacro Coxis',
                        'pelvis' => 'Pelvis',
                        'escoliosis' => 'Escoliosis',
                    ],
                    'Rayos X Estudios Especiales' => [
                        'arteriograma' => 'Arteriograma',
                        'histerosalpingograma' => 'Histerosalpingograma',
                        'colecistograma' => 'Colecistograma',
                        'fistulograma' => 'Fistulograma',
                        'artrograma' => 'Artrógama',
                    ],
                ];
                @endphp

                <div class="secciones-container">
                    @foreach($secciones as $titulo => $examenes)
                        <div class="seccion">
                            <div class="section-title">{{ $titulo }}</div>
                            <div class="examenes-grid">
                                @foreach($examenes as $key => $label)
                                    <label>
                                        <input type="checkbox" name="examenes[{{ $key }}]"
                                            value="1"
                                            {{ old('examenes.' . $key) ? 'checked' : '' }}>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
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

    {{-- Mensaje emergente máximo 5 seleccionados --}}
    <div id="alertMax" class="alert-custom alert-error" style="display:none;">
        Solo puede seleccionar un máximo de 5 Rayos X.
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnLimpiar = document.getElementById('btnLimpiar');
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="examenes"]');
    const form = document.getElementById('formOrden');
    const selectDiagnostico = document.getElementById('diagnostico_id');
    const mensajesContainer = document.getElementById('mensajesEmergentes');

    btnLimpiar.addEventListener('click', () => {
        checkboxes.forEach(cb => cb.checked = false);
        limpiarMensajes();
    });

    // Mostrar datos del diagnóstico seleccionado
    const datosDiv = document.getElementById('datosPaciente');
    const pacienteNombre = document.getElementById('pacienteNombre');
    const pacienteIdentidad = document.getElementById('pacienteIdentidad');
    const fechaDiagnostico = document.getElementById('fechaDiagnostico');
    const pacienteEdad = document.getElementById('pacienteEdad');
    const medicoSolicitante = document.getElementById('medicoSolicitante');

    selectDiagnostico.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if(this.value){
            pacienteNombre.textContent = option.getAttribute('data-nombre');
            pacienteIdentidad.textContent = option.getAttribute('data-identidad');
            fechaDiagnostico.textContent = option.getAttribute('data-fecha');
            pacienteEdad.textContent = option.getAttribute('data-edad') + ' años';
            medicoSolicitante.textContent = option.getAttribute('data-medico');
            datosDiv.style.display = 'block';
        } else {
            datosDiv.style.display = 'none';
        }
        limpiarMensajes();
    });

    // Cargar datos paciente automáticamente si hay diagnóstico seleccionado al cargar la página
    if (selectDiagnostico.value) {
        const option = selectDiagnostico.options[selectDiagnostico.selectedIndex];
        pacienteNombre.textContent = option.getAttribute('data-nombre');
        pacienteIdentidad.textContent = option.getAttribute('data-identidad');
        fechaDiagnostico.textContent = option.getAttribute('data-fecha');
        pacienteEdad.textContent = option.getAttribute('data-edad') + ' años';
        medicoSolicitante.textContent = option.getAttribute('data-medico');
        datosDiv.style.display = 'block';
    }

    // Función para mostrar mensajes en el div mensajesEmergentes
    function mostrarMensaje(mensaje, tipo = 'error') {
        limpiarMensajes();
        const div = document.createElement('div');
        div.textContent = mensaje;
        div.className = tipo === 'error' ? 'alert-custom alert-error' : 'alert-custom alert-success';
        mensajesContainer.appendChild(div);

        // Scroll suave para que el mensaje se vea
        div.scrollIntoView({ behavior: 'smooth', block: 'center' });

        setTimeout(() => {
            div.remove();
        }, 5000);
    }

    function limpiarMensajes() {
        mensajesContainer.innerHTML = '';
    }

    // Validar máximo 5 checkboxes seleccionados
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const checkedCount = Array.from(checkboxes).filter(c => c.checked).length;
            // Solo mostrar mensaje si está marcando y supera el límite
            if (checkedCount > 5 && cb.checked) {
                cb.checked = false; // desmarca el último seleccionado
                mostrarMensaje('Solo puede seleccionar un máximo de 5 Rayos X.', 'error');
            }
        });
    });

    // Validar formulario antes de enviar
    form.addEventListener('submit', (e) => {
        const checkedCount = Array.from(checkboxes).filter(c => c.checked).length;

        limpiarMensajes();

        if (!selectDiagnostico.value) {
            e.preventDefault();
            mostrarMensaje('Debe seleccionar un diagnóstico antes de guardar.', 'error');
            selectDiagnostico.focus();
            return;
        }
        if (checkedCount === 0) {
            e.preventDefault();
            mostrarMensaje('Debe seleccionar al menos un examen de Rayos X.', 'error');
            return;
        }
        if (checkedCount > 5) {
            e.preventDefault();
            mostrarMensaje('No puede seleccionar más de 5 exámenes de Rayos X.', 'error');
            return;
        }
    });
});
</script>
@endsection
