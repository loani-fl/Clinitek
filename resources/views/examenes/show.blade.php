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
    .patient-data-grid .row > div {
        display: flex;
        align-items: center;
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
    .section-title {
        font-size: 1.1rem;
        margin: 0 0 0.7rem;
        color: rgb(6, 11, 17);
        font-weight: 700;
        line-height: 1.4rem;
    }
    .secciones-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.3rem 0.10rem;
        margin-top: 0.3rem;
    }
    .examenes-grid label {
        font-size: 0.85rem;
        line-height: 1rem;
        user-select: none;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .examenes-grid input[type="checkbox"]:disabled {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        border: 1px solid #ccc; /* borde gris */
        border-radius: 3px;
        position: relative;
        background-color: #f0f0f0; /* fondo gris claro */
    }
    .examenes-grid input[type="checkbox"]:disabled:checked::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 4px;
        width: 5px;
        height: 9px;
        border: solid #000;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }
    .no-examenes {
        grid-column: 1 / -1;
        text-align: center;
        color: #555;
        font-weight: 600;
        padding: 1rem 0;
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
                        ORDEN DE EXÁMENES REGISTRADA
                    </h4>
                </div>
            </div>
        </div>
        <div class="card-body">
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

            <div class="section-title">EXÁMENES SOLICITADOS</div>
            <div class="secciones-container">
                @php
                    $tieneExamenes = false;
                @endphp

                @foreach ($secciones as $tituloSeccion => $examenes)
                    @if(count($examenes) > 0)
                        @php $tieneExamenes = true; @endphp
                        <div class="seccion">
                            <div class="section-title">{{ $tituloSeccion }}</div>
                            <div class="examenes-grid">
                                @foreach($examenes as $examen)
                                    <label>
                                        <input
                                            type="checkbox"
                                            disabled
                                            @if($examen['seleccionado']) checked @endif
                                        >
                                        {{ $examen['nombre'] }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                @if(!$tieneExamenes)
                    <div class="no-examenes">
                        Este paciente no tiene exámenes registrados.
                    </div>
                @endif
            </div>

            <div class="text-center pt-4">
                @if(request()->query('origen') === 'pacientes.show' && request()->query('paciente_id'))
                    <a href="{{ route('pacientes.show', request()->query('paciente_id')) }}"
                       class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2"
                       style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                @else
                    <a href="{{ route('consultas.index') }}"
                       class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2"
                       style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
