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
        margin: 40px auto 60px auto; /* MÁS espacio en la parte de abajo */
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

    /* Alertas */
    #max-examenes-error {
        display: none;
    }

 .secciones-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.3rem 0.10rem; /* aún menos espacio horizontal y vertical */
        margin-top: 0.3rem;
    }

    .seccion {
        padding: 0;
    }

   .section-title {
    font-size: 1.1rem;         /* más grande */
    margin: 0 0 0.7rem;
    color: rgb(6, 11, 17);
    font-weight: 700;          /* más negrita */
    line-height: 1.4rem;       /* un poco más alto */
}


    .examenes-grid {
        display: flex;
        flex-direction: column;
        gap: 0.15rem; /* menos espacio entre checkboxes */
    }

    .examenes-grid label {
        font-size: 0.85rem;
        line-height: 1rem;
    }
 .d-flex.justify-content-center.gap-3.mt-4 {
        margin-top: 2rem !important; /* para separarlos bien de las secciones */
    }
    #max-examenes-error,
#min-examenes-error {
    display: none;
    padding: 0.6rem 1rem;
    margin-bottom: 1rem;
    font-weight: 600;
    border-radius: 0.3rem;
    width: 100%;
}


</style>

{{-- Barra superior fija --}}
<!--  <div class="header d-flex justify-content-between align-items-center px-3 py-2">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto;">
        <div class="fw-bold text-white" style="font-size: 1.5rem; margin-left: 8px;">Clinitek</div>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="nav-link text-white">Crear Puesto</a>
        <a href="{{ route('medicos.create') }}" class="nav-link text-white">Registro Médicos</a>
        <a href="{{ route('pacientes.index') }}" class="nav-link text-white">Registro Pacientes</a>
    </div>
</div>-->

{{-- Contenedor principal --}}
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
                        CREAR ORDEN DE EXÁMENES
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

          <div id="max-examenes-error" class="alert alert-danger" style="display:none;">
    Solo puede seleccionar un máximo de 15 exámenes.
</div>

<div id="min-examenes-error" class="alert alert-danger" style="display:none;">
    Debe seleccionar al menos un examen.
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

            <form method="POST" action="{{ route('examenes.store', [$paciente->id, $consulta->diagnostico->id ?? 0]) }}">

            @csrf

               @php
$secciones = [
    'HEMATOLOGÍA' => [
        'hemograma_completo', 'frotis_en_sangre_periferica', 'reticulocitos', 'eritrosedimentacion',
        'grupo_sanguineo', 'p_coombs_directa', 'p_coombs_indirecta', 'plasmodium_gota_gruesa',
        'plasmodium_anticuerpos'
    ],

    'HORMONAS' => [
        'hormona_luteinizante_lh', 'hormona_foliculo_estimulante_fsh', 'cortisol', 'prolactina',
        'testosterona', 'estradiol', 'progesterona', 'beta_hcg_embarazo'
    ],

    'ORINA Y FLUIDOS' => [
        'examen_general_orina', 'cultivo_orina', 'orina_24_horas', 'prueba_embarazo',
        'liquido_cefalorraquideo', 'liquido_pleural', 'liquido_peritoneal', 'liquido_articular',
        'espermograma'
    ],

    'BIOQUÍMICOS' => [
        'urea', 'bun', 'creatinina', 'acido_urico', 'glucosa', 'glucosa_post_prandial_2h',
        'c_tolencia_glucosa_2h', 'c_tolencia_glucosa_4h', 'bilirrubina_total_y_fracciones',
        'proteinas_totales', 'albumina_globulina', 'electroforesis_proteinas',
        'cistatina_c_creatinina_tfg', 'diabetes_gestacional'
    ],

    'MARCADORES TUMORALES' => [
        'af_proteina', 'ac_embrionario', 'ca125', 'he4', 'indice_roma', 'ca15_3', 'ca19_9',
        'ca72_4', 'cyfra_21_1', 'beta_2_microglobulina', 'enolasa_neuroespecifica',
        'antigeno_prostatico_psa', 'psa_libre'
    ],

    'PERFIL DE ANEMIA' => [
        'hierro_serico', 'capacidad_fijacion_hierro', 'transferrina', 'ferritina', 'vitamina_b12',
        'acido_folico', 'eritropoyetina', 'haptoglobina', 'electroforesis_hemoglobina',
        'glucosa_6_fosfato', 'fragilidad_osmotica_hematias'
    ],

    'PERFIL DIABETES' => [
        'peptido_c', 'indice_peptidico', 'insulina', 'homa_ir', 'homa_ir_post_prandial',
        'fructosamina', 'hemoglobina_glicosilada'
    ],

    'INMUNOLOGÍA Y AUTOINMUNIDAD' => [
        'iga', 'igg', 'igm', 'ige', 'complemento_c3', 'complemento_c4', 'vitamina_d',
        'ac_antinucleares'
    ],

    'INFECCIOSOS' => [
        'hiv_1_y_2', 'hepatitis_b', 'hepatitis_c', 'sifilis_vdrl_o_rpr', 'citomegalovirus_cmv'
    ],
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

  {{-- Botones centrados --}}
<div class="d-flex justify-content-center gap-3 mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Guardar e imprimir
    </button>

    <button type="button" id="btnLimpiar" class="btn btn-warning">
        <i class="bi bi-trash"></i> Limpiar
    </button>

<a href="{{ route('consultas.show', $consulta->diagnostico->id) }}" class="btn btn-success">
    <i class="bi bi-arrow-left-circle"></i> Regresar
</a>


        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos comunes
    const maxSeleccion = 15;
    const checkboxes = document.querySelectorAll('input[name="examenes[]"]');
    const maxExamenesError = document.getElementById('max-examenes-error');
    const minExamenesError = document.getElementById('min-examenes-error');
    const form = document.querySelector('form');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const btnGuardarImprimir = document.querySelector('a.btn-imprimir');

    function mostrarMensaje(elemento) {
        elemento.style.display = 'block';
        elemento.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => {
            elemento.style.display = 'none';
        }, 4000);
    }

    // Botón Limpiar
    btnLimpiar.addEventListener('click', function() {
        checkboxes.forEach(cb => cb.checked = false);
        maxExamenesError.style.display = 'none';
        minExamenesError.style.display = 'none';
    });

    // Limitar selección a máximo 15
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const seleccionados = document.querySelectorAll('input[name="examenes[]"]:checked').length;
            if (seleccionados > maxSeleccion) {
                cb.checked = false;
                mostrarMensaje(maxExamenesError);
            } else {
                maxExamenesError.style.display = 'none';
            }
        });
    });

    // Validar al enviar formulario
    form.addEventListener('submit', e => {
        const seleccionados = document.querySelectorAll('input[name="examenes[]"]:checked').length;
        if (seleccionados === 0) {
            e.preventDefault();
            mostrarMensaje(minExamenesError);
            return;
        }
        if (seleccionados > maxSeleccion) {
            e.preventDefault();
            mostrarMensaje(maxExamenesError);
        }
    });

    // Validar en "Guardar e Imprimir"
    btnGuardarImprimir.addEventListener('click', e => {
        const seleccionados = document.querySelectorAll('input[name="examenes[]"]:checked').length;
        if (seleccionados === 0) {
            e.preventDefault(); // evita abrir nueva pestaña
            mostrarMensaje(minExamenesError);
            return;
        }
        if (seleccionados > maxSeleccion) {
            e.preventDefault();
            mostrarMensaje(maxExamenesError);
        }
        // Si pasa la validación, abrirá normalmente
    });
});

</script>

@endsection




