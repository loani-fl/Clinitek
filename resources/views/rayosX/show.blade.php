@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        margin: 40px auto;
        border-radius: 1.5rem;
        padding: 2rem 2.5rem;
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
    }
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #003366;
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.3rem;
        margin-bottom: 1rem;
    }
    .underline-field {
        border-bottom: 1px solid #000;
        min-height: 1.4rem;
        line-height: 1.4rem;
        padding-left: 6px;
        padding-right: 6px;
    }
    .img-preview {
        margin-top: 0.8rem;
        max-width: 200px;
        max-height: 150px;
        border-radius: 0.4rem;
        object-fit: contain;
        border: 1px solid #ddd;
    }
    .examen-card {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #007BFF;
    }
</style>

<div class="custom-card">
    <h2 class="section-title text-center mb-4">Área de radiología - Detalles</h2>

    {{-- Datos del paciente --}}
    @php
        $paciente = $orden->pacienteClinica ?? $orden->pacienteRayosX;
    @endphp

    <div class="section-title">Datos del Paciente</div>
<div class="mb-4">
    <div class="row">
        <div class="col-md-6">
            <div><strong>Nombres - Apellidos:</strong> {{ $paciente->nombre ?? 'N/A' }} {{ $paciente->apellidos ?? '' }}</div>
            <div><strong>Fecha:</strong> {{ $orden->fecha ? \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') : 'N/A' }}</div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-6">
                    <strong>Identidad:</strong> {{ $paciente->identidad ?? 'N/A' }}
                </div>
                <div class="col-6">
                    <strong>Edad:</strong>
                    @if(!empty($paciente->fecha_nacimiento))
                        {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="mt-2">
                <strong>Teléfono:</strong> {{ $paciente->telefono ?? 'N/A' }}
            </div>
        </div>
    </div>
</div>

    {{-- Médico Analista --}}
    <div class="section-title">Médico Analista</div>
    <div class="mb-4">
        {{ optional($orden->medicoAnalista)->nombre ?? 'N/A' }}
        {{ optional($orden->medicoAnalista)->apellidos ?? '' }}
    </div>

    {{-- Exámenes --}}
    <div class="section-title">Rayos X realizados</div>
    @foreach($orden->examenes as $examen)
    <div class="examen-card mb-4">
        <h5>{{ $examenes[$examen->examen_codigo] ?? ucfirst(str_replace('_', ' ', $examen->examen_codigo)) }}</h5>

        @forelse($examen->imagenes as $imagen)
            <div class="card" style="width: 18rem; margin-bottom: 1rem;">
                <img src="{{ asset('storage/' . $imagen->ruta) }}" class="card-img-top" alt="Imagen examen">
                <div class="card-body">
                    <p class="card-text"><strong>Descripción:</strong> {{ $imagen->descripcion ?? 'Sin descripción' }}</p>
                </div>
            </div>
        @empty
            <p>Sin imágenes registradas</p>
        @endforelse
    </div>
@endforeach

    <div class="mt-4">
        <a href="{{ route('rayosx.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left-circle"></i> Regresar
        </a>
    </div>
</div>
@endsection
