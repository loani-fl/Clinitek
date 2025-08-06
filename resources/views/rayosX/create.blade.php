@extends('layouts.app')

@section('content')

<style>
    /* Estilos específicos para la sección paciente según tu última vista */
    
    .section-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        border-bottom: 2px solid #007BFF;
        padding-bottom: 0.25rem;
        color: #003366;
    }
    .patient-data-grid .underline-field {
        border-bottom: 1px solid #000;
        padding-bottom: 2px;
        user-select: none;
        font-weight: 500;
        width: 100%;
    }
    .patient-data-grid .no-select {
        user-select: none;
    }

    /* Mantengo también estilos para la tarjeta general y otras secciones */
    .seccion {
        border: 1px solid #007BFF;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background-color: #f9faff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .examenes-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .examen-item {
        flex: 1 1 250px;
        background: #fff;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: 1px solid #ccc;
        position: relative;
    }

    .examen-item label {
        cursor: pointer;
        user-select: none;
    }

    .examen-item textarea {
        margin-top: 0.3rem;
        width: 100%;
        resize: vertical;
        border-radius: 4px;
        border: 1px solid #ccc;
        padding: 0.3rem 0.5rem;
        font-family: inherit;
        font-size: 0.9rem;
    }

    .guardar-descripcion {
        margin-top: 0.3rem;
        position: absolute;
        right: 1rem;
        bottom: 1rem;
    }

    /* Botones principales centrados */
    .d-flex.justify-content-center.gap-3.mt-4 > * {
        min-width: 140px;
    }

    /* Selector y botón al lado */
    .patient-select-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .patient-select-wrapper select {
        min-width: 300px;
        max-width: 100%;
    }
</style>

<form method="POST" action="{{ route('rayosx.store') }}" id="formOrden">
    @csrf
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Mostrar errores validación arriba --}}
@if ($errors->any())
    <div id="backend-errors" class="alert alert-danger mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Selección paciente con botón para crear paciente nuevo solo Rayos X --}}
<div class="mb-4 row align-items-center">
    <label for="paciente_id" class="col-md-3 col-form-label fw-bold text-end">Seleccione Paciente</label>
    <div class="col-md-6">
        <div class="patient-select-wrapper">

            <select id="paciente_id" name="seleccion" class="form-select" required>
                <option value="" selected disabled>-- Seleccione un paciente --</option>

                {{-- Pacientes Clínica --}}
                @foreach($pacientesClinica as $paciente)
                    <option
                        value="clinica-{{ $paciente->id }}"
                        data-nombre="{{ $paciente->nombre }} {{ $paciente->apellidos }}"
                        data-identidad="{{ $paciente->identidad }}"
                        data-fecha_nacimiento="{{ $paciente->fecha_nacimiento }}"
                        data-edad="{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }}"
                        data-datos_clinicos="{{ e($paciente->datos_clinicos ?? '') }}"
                        {{ (old('seleccion') == "clinica-{$paciente->id}") ? 'selected' : '' }}
                    >
                        {{ $paciente->nombre }} {{ $paciente->apellidos }} - {{ $paciente->identidad }} (Clínica)
                    </option>
                @endforeach

                {{-- Pacientes Rayos X --}}
                @foreach($pacientesRayosX as $paciente)
                    <option
                        value="rayosx-{{ $paciente->id }}"
                        data-nombre="{{ $paciente->nombre }} {{ $paciente->apellidos }}"
                        data-identidad="{{ $paciente->identidad }}"
                        data-fecha_nacimiento="{{ $paciente->fecha_nacimiento }}"
                        data-edad="{{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }}"
                        data-datos_clinicos="{{ e($paciente->datos_clinicos ?? '') }}"
                        {{ (old('seleccion') == "rayosx-{$paciente->id}") ? 'selected' : '' }}
                    >
                        {{ $paciente->nombre }} {{ $paciente->apellidos }} - {{ $paciente->identidad }} (Rayos X)
                    </option>
                @endforeach
            </select>

            <a href="{{ route('pacientes.rayosx.create') }}" class="btn btn-outline-primary" title="Registrar paciente nuevo para Rayos X">
                Registrar Paciente
            </a>
        </div>
    </div>
</div>

{{-- Datos del paciente como texto subrayado --}}
<div id="datosPaciente" class="patient-data-grid mb-4" style="display:none;">
    <div class="section-title">DATOS DEL PACIENTE</div>

    <div class="row">
        <div class="col-md-6 mb-2 d-flex align-items-center">
            <strong class="me-2">Nombre completo:</strong>
            <div class="underline-field no-select" id="dp-nombre"></div>
        </div>
        <div class="col-md-6 mb-2 d-flex align-items-center">
            <strong class="me-2">Identidad:</strong>
            <div class="underline-field no-select" id="dp-identidad"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-2 d-flex align-items-center">
            <strong class="me-2">Edad:</strong>
            <div class="underline-field no-select" id="dp-edad"></div>
        </div>
        <div class="col-md-4 mb-2 d-flex align-items-center">
            <strong class="me-2">Fecha Nacimiento:</strong>
            <div class="underline-field no-select" id="dp-fecha-nac"></div>
        </div>
    </div>
</div>

{{-- Fecha de la orden (IMPORTANTE) --}}
<div class="mb-4 row align-items-center">
    <label for="fecha" class="col-md-3 col-form-label fw-bold text-end">Fecha de la orden</label>
    <div class="col-md-6">
        <input type="date"
            id="fecha"
            name="fecha"
            class="form-control @error('fecha') is-invalid @enderror"
            value="{{ old('fecha', date('Y-m-d')) }}"
            required>
        @error('fecha')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Datos clínicos (si vienen del paciente) --}}
<div class="mb-4 row align-items-center" id="datosClinicosContainer" style="display:none;">
    <label for="datos_clinicos" class="col-md-3 col-form-label fw-bold text-end">Datos Clínicos</label>
    <div class="col-md-6">
        <textarea name="datos_clinicos" id="datos_clinicos" rows="3" class="form-control">{{ old('datos_clinicos') }}</textarea>
    </div>
</div>

{{-- Sección de exámenes --}}
<div class="section-title d-flex justify-content-between align-items-center">
    <span>EXÁMENES</span>

    {{-- Botón Registrar a la par (puedes moverlo donde quieras) --}}
    <button type="submit" class="btn btn-primary btn-sm">
        Registrar Orden
    </button>
</div>

<div class="container px-5">
    <div class="row">
        @foreach($secciones as $titulo => $examenesSeccion)
            <div class="col-md-3 mb-4">
                <div class="seccion h-100">
                    <h5 class="fw-bold text-primary">{{ $titulo }}</h5>
                    <div class="row">
                        @foreach($examenesSeccion as $clave)
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="examenes[]"
                                        value="{{ $clave }}"
                                        id="examen_{{ $clave }}"
                                        {{ (is_array(old('examenes')) && in_array($clave, old('examenes'))) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="examen_{{ $clave }}">
                                        {{ $examenes[$clave] ?? ucfirst(str_replace('_', ' ', $clave)) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
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

            // Mostrar datos clínicos si vienen
            const datosClin = selectedOption.getAttribute('data-datos_clinicos') || '';
            if (datosClin.trim() !== '') {
                datosClinicosTextarea.value = datosClin;
                datosClinicosContainer.style.display = 'flex';
            } else {
                @if(old('datos_clinicos'))
                    datosClinicosTextarea.value = {!! json_encode(old('datos_clinicos')) !!};
                    datosClinicosContainer.style.display = 'flex';
                @else
                    datosClinicosTextarea.value = '';
                    datosClinicosContainer.style.display = 'none';
                @endif
            }
        } else {
            dpNombre.textContent = '';
            dpIdentidad.textContent = '';
            dpFechaNac.textContent = '';
            dpEdad.textContent = '';
            datosPacienteDiv.style.display = 'none';
            datosClinicosContainer.style.display = 'none';
        }
    }

    selectPaciente?.addEventListener('change', mostrarDatosPaciente);

    // Mostrar datos si hay valor previo (old)
    if (selectPaciente && selectPaciente.value) {
        mostrarDatosPaciente();
    }

    // Scroll suave a errores si existen
    const errores = document.getElementById('backend-errors');
    if (errores) {
        errores.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Botón Limpiar
    const btnLimpiar = document.getElementById('btnLimpiar');
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', () => {
            document.getElementById('formOrden').reset();
            datosPacienteDiv.style.display = 'none';
            datosClinicosContainer.style.display = 'none';
            const fecha = document.getElementById('fecha');
            if (fecha) fecha.value = new Date().toISOString().slice(0,10);
        });
    }
});
</script>

@endsection