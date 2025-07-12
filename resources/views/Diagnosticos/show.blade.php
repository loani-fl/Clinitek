@extends('layouts.app')

@section('content')
<style>
    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 800px;
        height: 800px;
        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.15;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }
    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        border-color: #91cfff;
        position: relative;
        overflow: hidden;
        margin: 2rem auto;
        padding: 1.5rem 2rem;
        border: 1px solid #91cfff;
        border-radius: 12px;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 1rem;
        border-bottom: 3px solid #0d6efd;
        padding-bottom: 0.25rem;
    }

    p {
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 0.75rem;
    }

    .label {
        font-weight: 700;
        color: #212529;
    }
</style>

<!-- Barra fija -->
<div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050; height: 56px;">
    <div class="d-flex justify-content-between align-items-center px-3" style="height: 56px;">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
            <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
        </div>
    </div>
</div>

<div class="container mt-5 pt-3" style="max-width: 1000px;">
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
        <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Detalles del Diagnóstico</h5>
        </div>

        <div class="card-body px-4 py-3">

            <div class="section-title">Información del Paciente</div>
            <p><span class="label">Nombre Completo:</span> {{ $diagnostico->paciente->nombre }} {{ $diagnostico->paciente->apellidos }}</p>
            <p><span class="label">Edad:</span> {{ \Carbon\Carbon::parse($diagnostico->paciente->fecha_nacimiento)->age }} años</p>
            <p><span class="label">Identidad:</span> {{ $diagnostico->paciente->identidad }}</p>
            <p><span class="label">Teléfono:</span> {{ $diagnostico->paciente->telefono ?? 'No especificado' }}</p>
            <p><span class="label">Género:</span> {{ $diagnostico->paciente->genero ?? 'No especificado' }}</p>

            <div class="section-title mt-4">Detalles del Diagnóstico</div>
            <p><span class="label">Resumen:</span><br>{{ $diagnostico->resumen }}</p>
            <p><span class="label">Descripción:</span><br>{!! nl2br(e($diagnostico->descripcion)) !!}</p>
            <p><span class="label">Tratamiento:</span><br>{!! nl2br(e($diagnostico->tratamiento)) !!}</p>
            <p><span class="label">Observaciones:</span><br>{!! nl2br(e($diagnostico->observaciones)) !!}</p>

            <div class="text-center pt-4">
                <a href="{{ route('consultas.index') }}" class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
