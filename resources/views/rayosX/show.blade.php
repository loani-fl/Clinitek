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

    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        margin: 40px auto 60px auto;
        border-radius: 1.5rem;
        padding: 2rem 2.5rem;
        position: relative;
        overflow: visible;
        z-index: 1;
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);

        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.85);
        border-radius: 1.5rem;
        z-index: 0;
    }

    .custom-card > * {
        position: relative;
        z-index: 1;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #003366;
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.3rem;
        margin-bottom: 1rem;
    }

    h4 {
        color: #003366;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .datos-paciente-flex {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        list-style: none;
        padding-left: 0;
    }

    .datos-paciente-flex li {
        flex: 1 1 200px;
        padding: 0.3rem 0;
        border-bottom: 1px solid #ccc;
        display: flex;
        gap: 0.5rem;
        color: #222;
        font-weight: 600;
    }

    .datos-paciente-flex li strong {
        width: 80px;
        color: #004080;
    }

    .examen-card {
        margin-bottom: 2.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #007BFF;
    }

    .examen-card:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .examen-nombre {
        font-weight: 700;
        font-size: 1.3rem;
        color: #004080;
        margin-bottom: 0.5rem;
    }

    .img-preview {
        margin-top: 0.8rem;
        max-width: 150px;
        max-height: 110px;
        border-radius: 0.4rem;
        object-fit: contain;
        border: 1px solid #ddd;
    }
</style>

<div class="custom-card">
    <h2 class="section-title">Ver de análisis de Rayos X</h2>

    {{-- Datos del paciente --}}
    <h4>Datos del paciente</h4>
    <ul class="datos-paciente-flex">
        <li><strong>Nombre:</strong> {{ $orden->paciente->nombre ?? $orden->nombres ?? 'N/A' }}</li>
        <li><strong>Apellidos:</strong> {{ $orden->paciente->apellidos ?? $orden->apellidos ?? 'N/A' }}</li>
        <li><strong>Identidad:</strong> {{ $orden->paciente->identidad ?? $orden->identidad ?? 'N/A' }}</li>
        <li><strong>Género:</strong> {{ $orden->paciente->genero ?? $orden->genero ?? 'N/A' }}</li>
    </ul>

    {{-- Médico Analista --}}
    <h4>Médico Analista</h4>
    <p>
        {{ optional($orden->medicoAnalista)->nombre ?? 'N/A' }}
        {{ optional($orden->medicoAnalista)->apellidos ?? '' }}
    </p>

    {{-- Exámenes realizados --}}
    <h4>Exámenes realizados</h4>
    @forelse ($orden->examenes as $examen)
        <div class="examen-card">
            <div class="examen-nombre">
                {{ $examenesNombres[$examen->examen_codigo] ?? $examen->examen_codigo }}
            </div>

            {{-- Imágenes --}}
            @if($examen->imagenes && $examen->imagenes->count() > 0)
                <div class="row">
                    @foreach ($examen->imagenes as $imagen)
                        <div class="col-md-3 mb-2 text-center">
                            <img src="{{ asset('storage/' . $imagen->imagen_ruta) }}" alt="Imagen examen" class="img-preview" />
                        </div>
                    @endforeach
                </div>
            @else
                <p>Sin imágenes registradas</p>
            @endif

            {{-- Descripción --}}
            <div class="mt-2">
                <strong>Descripción:</strong>
                <p>{{ $examen->descripcion ?? 'Sin descripción' }}</p>
            </div>
        </div>
    @empty
        <p>No hay exámenes registrados para esta orden.</p>
    @endforelse

    <div class="mt-4">
        <a href="{{ route('rayosx.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left-circle"></i> Regresar
        </a>
    </div>
</div>
@endsection
