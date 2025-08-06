@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
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
    .patient-data-grid .row > div {
        display: flex;
        align-items: center;
    }
    .section-title {
        font-size: 1.1rem;
        margin: 0 0 0.7rem;
        color: rgb(6, 11, 17);
        font-weight: 700;
        line-height: 1.4rem;
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
    .examenes-grid {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }
    .examenes-grid label {
        font-size: 0.85rem;
        line-height: 1rem;
        user-select: none;
    }
    .btn {
        padding: 0.40rem 0.5rem;
        font-size: 0.95rem;
        line-height: 1.2;
    }
    .btn-success {
        margin-top: 1.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header text-center">
            <h4 class="mb-0" style="font-weight: 600; color: #333;">
                ORDEN DE RAYOS X #{{ $orden->id }}
            </h4>
        </div>

        <div class="card-body">

        @php
    $examenesSeleccionados = $orden->examenes->pluck('examen')->toArray();
@endphp


            @if ($orden->diagnostico)
                {{-- Datos del paciente y diagnóstico --}}
                <div class="section-title">DATOS DEL PACIENTE</div>

                <div class="patient-data-grid mb-4">
                    <div class="row">
                        <div class="col-md-8 mb-2 d-flex align-items-center">
                            <strong class="me-2">Paciente:</strong>
                            <div class="underline-field no-select">
                                {{ $orden->diagnostico?->paciente?->nombre ?? 'Paciente no disponible' }} 
                                {{ $orden->diagnostico?->paciente?->apellidos ?? '' }}
                            </div>
                        </div>
                        <div class="col-md-4 mb-2 d-flex align-items-center">
                            <strong class="me-2">Fecha Diagnóstico:</strong>
                            <div class="underline-field no-select">
                                {{ \Carbon\Carbon::parse($orden->diagnostico?->created_at)->format('d/m/Y') ?? 'Fecha no disponible' }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-2 d-flex align-items-center">
                            <strong class="me-2">Identidad:</strong>
                            <div class="underline-field no-select">
                                {{ $orden->diagnostico?->paciente?->identidad ?? 'N/D' }}
                            </div>
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-center">
                            <strong class="me-2">Edad:</strong>
                            <div class="underline-field no-select">
                                {{ $orden->diagnostico?->paciente?->fecha_nacimiento ? \Carbon\Carbon::parse($orden->diagnostico->paciente->fecha_nacimiento)->age . ' años' : 'N/D' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2 d-flex align-items-center">
                            <strong class="me-2">Médico Solicitante:</strong>
                            <div class="underline-field no-select">
                                {{ $orden->diagnostico?->consulta?->medico?->nombre ?? '' }} 
                                {{ $orden->diagnostico?->consulta?->medico?->apellidos ?? '' }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    No se encontró el diagnóstico asociado a esta orden.
                </div>
            @endif

            {{-- Exámenes seleccionados (checkboxes deshabilitados y marcados solo si están seleccionados) --}}
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
            $examenesSeleccionados = $orden->examenes->pluck('examen')->toArray();
            @endphp

            <div class="section-title">ESTUDIOS SOLICITADOS</div>

            <div class="secciones-container">
                @foreach($secciones as $titulo => $examenes)
                    <div class="seccion">
                        <div class="section-title">{{ $titulo }}</div>
                        <div class="examenes-grid">
                            @foreach($examenes as $key => $label)
                                <label>
                                    <input type="checkbox" disabled
                                        @if(in_array($key, $examenesSeleccionados))
                                            checked
                                        @endif
                                    >
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="{{ route('rayosx.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left-circle"></i> Regresar
            </a>

        </div>
    </div>
</div>
@endsection
