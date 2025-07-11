
@extends('layouts.app')

@section('content')
    <style>
        .custom-card {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 1rem 2rem;
            background-color: #fff;
            border: 1px solid #91cfff;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
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
            line-height: 1.4;
        }

        .label {
            font-weight: 700;
            color: #212529;
        }

        .btn-back {
            margin-top: 1.5rem;
        }
    </style>

    <div class="container">
        <div class="custom-card shadow-sm">
            <h2 class="section-title">Detalles del Diagnóstico</h2>

            <p><span class="label">Paciente:</span> {{ $diagnostico->paciente->nombre }} {{ $diagnostico->paciente->apellidos }}</p>

            @if($diagnostico->paciente->fecha_nacimiento)
                <p><span class="label">Edad:</span> {{ \Carbon\Carbon::parse($diagnostico->paciente->fecha_nacimiento)->age }} años</p>
            @endif

            <p><span class="label">Resumen:</span><br> {{ $diagnostico->resumen }}</p>

            <p><span class="label">Descripción:</span><br> {!! nl2br(e($diagnostico->descripcion)) !!}</p>

            <p><span class="label">Tratamiento:</span><br> {!! nl2br(e($diagnostico->tratamiento)) !!}</p>

            <p><span class="label">Observaciones:</span><br> {!! nl2br(e($diagnostico->observaciones)) !!}</p>

            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-back">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </div>
@endsection
