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
        color: rgb(3, 12, 22);
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

    /* Botón para registrar paciente nuevo junto al select */
    .patient-select-wrapper {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    /* Select más grande y con mejor padding */
.select-large {
    min-width: 460px;      /* ancho mayor */
    max-width: 720px;      /* evita que se extienda demasiado */
    width: 100%;           /* ocupa el espacio disponible dentro del contenedor flexible */
    font-size: 1rem;
    padding: 0.6rem 0.75rem;
    border-radius: 0.5rem;
    border: 1px solid rgba(0,0,0,0.12);
    box-shadow: 0 2px 6px rgba(3, 12, 22, 0.04);
}

/* Wrapper: hace una fila con gap y que el select ocupe todo el espacio restante */
.patient-select-wrapper {
    display: flex;
    gap: 0.6rem;
    width: 50%;
    align-items: center;
}

/* Botón estilizado con gradiente y sombra */
.boton-rayos {
    background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);
    color: #fff;
    border: none;
    padding: 0.45rem 0.9rem;
    border-radius: 0.6rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    white-space: nowrap;
    transition: transform .08s ease, box-shadow .12s ease, opacity .12s ease;
    box-shadow: 0 6px 18px rgba(13, 110, 253, 0.14);
}

/* Hover / focus */
.boton-rayos:hover, .boton-rayos:focus {
    transform: translateY(-1px);
    box-shadow: 0 10px 22px rgba(13, 110, 253, 0.18);
    text-decoration: none;
    color: #fff;
}

/* Si quieres un estado deshabilitado visual */
.boton-rayos.boton-inactivo {
    pointer-events: none;
    opacity: 0.6;
}

/* Ajustes al icono */
.icon-rayos svg { display:block; margin-right:0.15rem; }

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

          <form method="POST" action="{{ route('rayosx.store') }}" id="formOrden">
    @csrf

    {{-- Selección paciente con botón para crear paciente nuevo solo Rayos X --}}
    <div class="mb-4 row align-items-center">
        <label for="paciente_id" class="col-md-3 col-form-label fw-bold text-end">
            Seleccione Paciente
        </label>
        <div class="col-md-5">
            @php
                $hasAny = (isset($pacientesClinica) && $pacientesClinica->isNotEmpty()) || (isset($pacientesRayosX) && $pacientesRayosX->isNotEmpty());
            @endphp

            @if(!$hasAny)
                <div class="alert alert-warning" role="alert">
                    No hay pacientes disponibles.
                </div>
            @else
                <div class="patient-select-wrapper" style="align-items:center; width:80%;">
                    <select name="seleccion" id="paciente_id" class="form-select select-large" required>
                        <option value="" disabled {{ old('seleccion') || ($seleccion ?? null) ? '' : 'selected' }}>
                            -- Seleccione un paciente o diagnóstico --
                        </option>

                        <optgroup label="Pacientes internos">
                            @foreach($pacientesClinica as $p)
                                @php $val = 'clinica-' . $p->id; @endphp
                                <option value="{{ $val }}"
                                    data-identidad="{{ $p->identidad }}"
                                    data-fecha_nacimiento="{{ $p->fecha_nacimiento }}"
                                    {{ (old('seleccion') == $val || (isset($seleccion) && $seleccion == $val)) ? 'selected' : '' }}>
                                    {{ $p->nombre }} {{ $p->apellidos }} - {{ $p->identidad }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="Pacientes externos">
                            @foreach($pacientesRayosX as $p)
                                @php $val = 'rayosx-' . $p->id; @endphp
                                <option value="{{ $val }}"
                                    data-identidad="{{ $p->identidad }}"
                                    data-fecha_nacimiento="{{ $p->fecha_nacimiento }}"
                                    {{ (old('seleccion') == $val || (isset($seleccion) && $seleccion == $val)) ? 'selected' : '' }}>
                                    {{ $p->nombre }} {{ $p->apellidos }} - {{ $p->identidad }} (externo)
                                </option>
                            @endforeach
                        </optgroup>
                    </select>

                    <a href="{{ route('pacientes.rayosx.create') }}"
                       class="btn boton-rayos d-inline-flex align-items-center gap-2 shadow-sm"
                       title="Registrar paciente nuevo para Rayos X">
                        <span class="icon-rayos" aria-hidden="true" style="display:inline-flex;align-items:center;">
                            <!-- svg -->
                        </span>
                        <span>Registrar Paciente</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Datos dinámicos del paciente --}}
    <div class="section-title">DATOS DEL PACIENTE </div>

    <div class="patient-data-grid mb-4" id="datosPaciente" style="display:none;">
        <div class="row">
            <div class="col-md-8 mb-2 d-flex align-items-center">
                <strong class="me-2">Paciente:</strong>
                <div class="underline-field no-select" id="pacienteNombre"></div>
            </div>
            <div class="col-md-4 mb-2 d-flex align-items-center">
                <strong class="me-2">Fecha Nacimiento:</strong>
                <div class="underline-field no-select" id="fechaNacimiento"></div>
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
        </div>
    </div>

    <div id="mensajesEmergentes" style="margin-top: 1rem; min-height: 40px;">
        @if ($errors->any())
            <div class="alert-custom alert-error" id="erroresLaravel">
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="mb-4 row align-items-center">
        <label for="fecha" class="col-md-3 col-form-label fw-bold text-end">Fecha de la orden</label>
        <div class="col-md-5">
            <input type="date" 
                   id="fecha" 
                   name="fecha" 
                   class="form-control @error('fecha') is-invalid @enderror" 
                   value="{{ old('fecha') }}" 
                   required>
            @error('fecha')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

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
            <div class="seccion mb-3">
                <div class="section-title fw-bold mb-2">{{ $titulo }}</div>
                <div class="examenes-grid">
                    @foreach($examenes as $key => $label)
                        <div class="examen-item mb-2" data-examen-key="{{ $key }}">
                            <label style="font-weight: normal; cursor: pointer;">
                                <input type="checkbox" name="examenes[{{ $key }}]"
                                    value="1"
                                    {{ old('examenes.' . $key) ? 'checked' : '' }}>
                                {{ $label }}
                            </label>

                            <textarea
                                name="descripciones[{{ $key }}]"
                                placeholder="Descripción para {{ $label }}"
                                style="display: {{ old('examenes.' . $key) ? 'block' : 'none' }}; margin-top: 0.3rem; width: 80%; resize: vertical;"
                                rows="2"
                            >{{ old('descripciones.' . $key) ?? '' }}</textarea>

                            <button type="button" class="btn btn-sm btn-success guardar-descripcion" 
                                    style="margin-left: 0.5rem; display: {{ old('examenes.' . $key) ? 'inline-block' : 'none' }};">
                                Guardar
                            </button>
                        </div>
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

{{-- Mensaje emergente máximo 5 seleccionados --}}
<div id="alertMax" class="alert-custom alert-error" style="display:none;">
    Solo puede seleccionar un máximo de 5 Rayos X.
</div>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnLimpiar = document.getElementById('btnLimpiar');
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="examenes"]');
    const form = document.getElementById('formOrden');
    const selectPaciente = document.getElementById('paciente_id');
    const mensajesContainer = document.getElementById('mensajesEmergentes');

    btnLimpiar.addEventListener('click', () => {
        checkboxes.forEach(cb => {
            cb.checked = false;
            const textarea = cb.closest('.examen-item').querySelector('textarea');
            if (textarea) {
                textarea.style.display = 'none';
                textarea.value = '';
            }
            const btnGuardar = cb.closest('.examen-item').querySelector('.guardar-descripcion');
            if (btnGuardar) btnGuardar.style.display = 'none';
        });
        limpiarMensajes();
    });

    // Datos paciente (mostrar)
    const datosDiv = document.getElementById('datosPaciente');
    const pacienteNombre = document.getElementById('pacienteNombre');
    const pacienteIdentidad = document.getElementById('pacienteIdentidad');
    const fechaNacimiento = document.getElementById('fechaNacimiento');
    const pacienteEdad = document.getElementById('pacienteEdad');

    function calcularEdad(fecha) {
        if (!fecha) return '';
        const hoy = new Date();
        const nacimiento = new Date(fecha);
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const m = hoy.getMonth() - nacimiento.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) edad--;
        return edad;
    }

    selectPaciente.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (this.value && option) {
            pacienteNombre.textContent = option.text;
            pacienteIdentidad.textContent = option.getAttribute('data-identidad') || '';
            const fechaNac = option.getAttribute('data-fecha_nacimiento');
            fechaNacimiento.textContent = fechaNac ? new Date(fechaNac).toLocaleDateString() : '';
            pacienteEdad.textContent = fechaNac ? calcularEdad(fechaNac) + ' años' : '';
            datosDiv.style.display = 'block';
        } else {
            datosDiv.style.display = 'none';
        }
        limpiarMensajes();
    });

    if (selectPaciente.value) {
        selectPaciente.dispatchEvent(new Event('change'));
    }

    function mostrarMensaje(mensaje, tipo = 'error') {
        limpiarMensajes();
        const div = document.createElement('div');
        div.textContent = mensaje;
        div.className = tipo === 'error' ? 'alert-custom alert-error' : 'alert-custom alert-success';
        mensajesContainer.appendChild(div);
        div.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => div.remove(), 5000);
    }

    function limpiarMensajes() {
        mensajesContainer.innerHTML = '';
    }

    // Mostrar/ocultar textarea y botón guardar según checkbox
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const checkedCount = Array.from(checkboxes).filter(c => c.checked).length;
            if (checkedCount > 5 && cb.checked) {
                cb.checked = false;
                mostrarMensaje('Solo puede seleccionar un máximo de 5 Rayos X.', 'error');
                return;
            }
            const container = cb.closest('.examen-item');
            const textarea = container.querySelector('textarea');
            const btnGuardar = container.querySelector('.guardar-descripcion');
            if (cb.checked) {
                if (textarea) textarea.style.display = 'block';
                if (btnGuardar) btnGuardar.style.display = 'inline-block';
                if (textarea) textarea.focus();
            } else {
                if (textarea) { textarea.style.display = 'none'; textarea.value = ''; }
                if (btnGuardar) btnGuardar.style.display = 'none';
            }
        });
    });

    // Validar formulario principal antes de enviar
    form.addEventListener('submit', (e) => {
        const checkedCount = Array.from(checkboxes).filter(c => c.checked).length;
        limpiarMensajes();
        if (!selectPaciente.value) {
            e.preventDefault();
            mostrarMensaje('Debe seleccionar un paciente antes de guardar.', 'error');
            selectPaciente.focus();
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
        // (Opcional) ocultamos los textareas para limpiar la vista al enviar
        checkboxes.forEach(cb => {
            const ta = cb.closest('.examen-item').querySelector('textarea');
            if (ta) ta.style.display = 'none';
        });
    });

    // Desvanecer mensajes de error de Laravel
    const erroresDiv = document.getElementById('erroresLaravel');
    if (erroresDiv) {
        setTimeout(() => {
            erroresDiv.style.transition = 'opacity 0.7s ease';
            erroresDiv.style.opacity = '0';
            setTimeout(() => erroresDiv.style.display = 'none', 700);
        }, 4000);
    }

    // Guardar descripción individual via AJAX
    document.querySelectorAll('.guardar-descripcion').forEach(btn => {
        btn.addEventListener('click', async function() {
            const container = this.closest('.examen-item');
            if (!container) return;
            const checkbox = container.querySelector('input[type="checkbox"]');
            const descripcionTextarea = container.querySelector('textarea');
            const examenKey = container.getAttribute('data-examen-key');
            const pacienteId = selectPaciente.value;

            if (!checkbox || !descripcionTextarea || !examenKey) {
                mostrarMensaje('Error en el formulario. Recargue la página.', 'error');
                return;
            }

            if (!checkbox.checked) {
                mostrarMensaje('Debe seleccionar el examen antes de guardar la descripción.', 'error');
                return;
            }

            if (!descripcionTextarea.value.trim()) {
                mostrarMensaje('La descripción no puede estar vacía.', 'error');
                descripcionTextarea.focus();
                return;
            }

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch("{{ route('rayosx.descripcion.guardar') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        examen: examenKey,
                        descripcion: descripcionTextarea.value.trim(),
                        paciente: pacienteId,
                    }),
                });

                if (!response.ok) {
                    const text = await response.text();
                    throw new Error('Error al guardar la descripción. ' + text);
                }

                const data = await response.json();

                if (data.success) {
                    mostrarMensaje('Descripción guardada exitosamente.', 'success');
                    // Ocultar textarea, botón y bloquear checkbox
                    if (descripcionTextarea) descripcionTextarea.style.display = 'none';
                    checkbox.disabled = true;
                    this.style.display = 'none';
                    const label = container.querySelector('label');
                    if (label) label.style.display = 'none';
                } else {
                    mostrarMensaje(data.message || 'Error al guardar la descripción.', 'error');
                }

            } catch (error) {
                mostrarMensaje(error.message || 'Error en la petición.', 'error');
            }
        });
    });

});
</script>

@endsection