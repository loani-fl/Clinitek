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
        margin: 40px auto;
        border-radius: 1.5rem;
        padding: 1rem;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 3px solid #007BFF;
    }

    .section-title {
        font-weight: bold;
        margin: 15px 0 5px;
        color: #000;
    }

    .patient-data-grid {
        background: transparent;
        box-shadow: none;
        border-radius: 0;
        padding: 0;
        margin-bottom: 2rem;
    }

    .patient-data-grid strong {
        color: #003366;
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
    }

    .fixed-name {
        min-width: 400px;
    }

    .patient-data-grid .row > div {
        display: flex;
    }

    .secciones-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem 3rem;
    }

    .seccion {
        padding: 0.5rem;
    }

    .examenes-grid {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .examenes-grid label {
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .no-select {
    user-select: none;
    pointer-events: none;
}
.btn-imprimir {
    background-color:rgb(97, 98, 99);
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
    background-color: #a59d8f; /* un poco más oscuro al pasar el mouse */
    color: #fff;
}
.btn {
    padding: 0.40rem 0.5rem; /* menos espacio dentro */
    font-size: 0.95rem;      /* letra más pequeña */
    line-height: 1.2;
}

</style>

{{-- Barra superior --}}
<div class="header d-flex justify-content-between align-items-center px-3 py-2">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto;">
        <div class="fw-bold text-white" style="font-size: 1.5rem; margin-left: 8px;">Clinitek</div>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="nav-link text-white">Crear Puesto</a>
        <a href="{{ route('medicos.create') }}" class="nav-link text-white">Registro Médicos</a>
        <a href="{{ route('pacientes.index') }}" class="nav-link text-white">Registro Pacientes</a>
    </div>
</div>

{{-- Contenido --}}
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
                        ORDEN DE EXAMEN<br>
                        PARA LABORATORIO CLÍNICO
                    </h4>
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- Datos del paciente --}}
            <div class="section-title">DATOS DEL PACIENTE</div>

            <div class="patient-data-grid mb-4">
                <div class="row">
                    <div class="col-md-8 mb-2 d-flex align-items-center">
                        <strong class="me-2">Nombres - Apellidos:</strong>
                        <div class="underline-field no-select">
                            {{ $paciente->nombre }} {{ $paciente->apellidos }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 d-flex align-items-center">
                        <strong class="me-2">Fecha:</strong>
                        <div class="underline-field no-select">
                            {{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-2 d-flex align-items-center">
                        <strong class="me-2">Identidad:</strong>
                        <div class="underline-field no-select">
                            {{ $paciente->identidad }}
                        </div>
                    </div>
                    <div class="col-md-2 mb-2 d-flex align-items-center">
                        <strong class="me-2">Edad:</strong>
                        <div class="underline-field no-select">
                            {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años
                        </div>
                    </div>
                    <div class="col-md-6 mb-2 d-flex align-items-center">
                        <strong class="me-2">Médico Solicitante:</strong>
                        <div class="underline-field no-select">
                            {{ $consulta->medico->nombre ?? '' }} {{ $consulta->medico->apellidos ?? '' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mensaje error máximo exámenes (frontend) --}}
            <div id="max-examenes-error" class="alert alert-danger" style="display:none;">
                Solo puede seleccionar un máximo de 10 exámenes.
            </div>

            {{-- Mensajes backend --}}
            @if ($errors->any())
                <div id="backend-errors" class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
<form method="POST" action="{{ route('examenes.store', [$paciente->id, $consulta->id]) }}" target="_blank">

                @csrf

                @php
                $secciones = [
                    'HEMATOLOGÍA' => [ 'Hemograma Completo', 'Frotis en sangre periférica', 'Reticulocitos', 'Eritrosedimentación', 'Grupo Sanguíneo', 'P. Coombs Directa', 'P. Coombs Indirecta', 'Plasmodium (Gota Gruesa)', 'Plasmodium (Anticuerpos V Y F)' ],
                    'PERFIL DIABETES' => [ 'Péptido C', 'Índice peptídico', 'Insulina', 'Homa-IR', 'Homa-IR (post prandial)', 'Fructosamina', 'Hemoglobina Glicosilada' ],
                    'PERFIL DE ANEMIA' => [ 'Hierro Sérico', 'Capacidad de fijación de Hierro', 'Transferrina', 'Ferritina', 'Vitamina B12', 'Ácido Fólico', 'Eritropoyetina', 'Haptoglobina', 'Electroforesis de hemoglobina', 'Glucosa 6 Fosfato', 'Fragilidad Osmótica de Hematias' ],
                    'BIOQUÍMICOS' => [ 'Urea', 'Bun', 'Creatinina', 'Ácido Úrico', 'Glucosa', 'Glucosa post-prandial 2H', 'C. Tolerancia a Glucosa 2H', 'C. Tolerancia a Glucosa 4H', 'Bilirrubina Total y Fracciones', 'Proteínas Totales', 'Albúmina / Globulina', 'Electroforesis de Proteínas', 'Cistatina C + Creatinina (TFG)', 'Diabetes Gestacional (50g basal y 1h)' ],
                    'MARCADORES TUMORALES' => [ 'A.F Proteína', 'A.C. Embrionario', 'CA125', 'He4', 'Índice Roma (He4+ca125)', 'CA15-3', 'CA19-9', 'CA72-4', 'CYFRA 21-1', 'Beta 2 Microglobulina', 'Enolasa Neuroespecífica (NSE)', 'Antígeno Prostático (PSA)', 'PSA Libre' ],
                    'INMUNOLOGÍA Y AUTOINMUNIDAD' => [ 'IgA', 'IgG', 'IgM', 'IgE', 'Complemento C3', 'Complemento C4', 'Vitamina D', 'Ac Antinucleares' ],
                    'ORINA Y FLUIDOS' => [ 'Examen General de Orina', 'Cultivo de Orina', 'Orina de 24 horas', 'Prueba de Embarazo', 'Líquido Cefalorraquídeo', 'Líquido Pleural', 'Líquido Peritoneal', 'Líquido Articular', 'Espermograma' ],
                    'HORMONAS' => [ 'Hormona Estimulante de Tiroides (TSH)', 'Hormona Luteinizante (LH)', 'Hormona Folículo Estimulante (FSH)', 'Cortisol', 'Prolactina', 'Testosterona', 'Estradiol', 'Progesterona', 'Beta HCG (Embarazo)' ],
                      'INFECCIOSOS' => ['HIV 1 y 2','Hepatitis B','Hepatitis C','Sífilis (VDRL o RPR)','Citomegalovirus (CMV)'],
                ];

                @endphp

                <div class="secciones-container">
                    @foreach ($secciones as $title => $examenes)
                        <div class="seccion">
                            <div class="section-title">{{ $title }}</div>
                            <div class="examenes-grid">
                                @foreach($examenes as $examen)
                                    <label>
                                        <input type="checkbox" name="examenes[]" value="{{ $examen }}">
                                        {{ $examen }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Botones centrados -->
    <div class="d-flex justify-content-center gap-3 mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Guardar Orden
        </button>
        <button type="button" id="btnLimpiar" class="btn btn-warning">
            <i class="bi bi-trash"></i> Limpiar
        </button>
       <a href="{{ route('consultas.show', $consulta->id) }}" class="btn btn-imprimir">
      <i class="bi bi-printer"></i> Imprimir
</a>

    </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maxSeleccion = 10;
    const checkboxes = document.querySelectorAll('input[name="examenes[]"]');
    const frontendError = document.getElementById('max-examenes-error');
    const backendErrors = document.getElementById('backend-errors');

    function mostrarMensaje(mensajeElemento) {
        mensajeElemento.style.display = 'block';
        mensajeElemento.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => {
            mensajeElemento.style.display = 'none';
        }, 4000);
    }

    if (backendErrors) {
        // Si hay error de backend, mostrarlo y ocultar el error frontend
        frontendError.style.display = 'none';
        mostrarMensaje(backendErrors);
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const seleccionados = document.querySelectorAll('input[name="examenes[]"]:checked');
            if (seleccionados.length > maxSeleccion) {
                // Desmarcar la casilla que causó el exceso
                this.checked = false;
                // Mostrar mensaje error frontend
                mostrarMensaje(frontendError);
            } else {
                frontendError.style.display = 'none';
            }
        });
    });

    // Evitar envío si hay más de maxSeleccion seleccionados (por seguridad)
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const seleccionados = document.querySelectorAll('input[name="examenes[]"]:checked');
        if (seleccionados.length > maxSeleccion) {
            e.preventDefault();
            mostrarMensaje(frontendError);
        }
    });

    // Botón Limpiar: desmarca todos los checkboxes y oculta errores
    const btnLimpiar = document.getElementById('btnLimpiar');
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', () => {
            checkboxes.forEach(chk => chk.checked = false);
            if (frontendError) frontendError.style.display = 'none';
            if (backendErrors) backendErrors.style.display = 'none';
        });
    }
});
</script>
@endpush
