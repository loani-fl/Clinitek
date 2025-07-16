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
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
        padding: 1rem;
        background-color: #fff;
        border-radius: 1rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .section-title {
        font-weight: bold;
        margin: 1.5rem 0 1rem;
        color: #003366;
        border-bottom: 2px solid #007BFF;
        padding-bottom: 0.25rem;
        font-size: 1.25rem;
    }
    .patient-data-grid {
        margin-bottom: 2rem;
        font-size: 1rem;
        color: #222;
    }
    .patient-data-grid strong {
        min-width: 140px;
        display: inline-block;
        color: #003366;
    }
    .seccion {
        margin-bottom: 1.5rem;
    }
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill,minmax(220px,1fr));
        gap: 0.5rem 1rem;
    }
    .form-check-label {
        text-transform: capitalize;
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
        margin-top: 2rem;
    }
    .btn-imprimir:hover {
        background-color: #a59d8f;
        color: #fff;
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

    <div>
        <h3 class="mb-4 text-center" style="color:#003366;">Orden de Examen</h3>

        {{-- Datos del paciente y consulta --}}
        <div class="patient-data-grid">
            <div><strong>Nombre completo:</strong> {{ $paciente->nombre }} {{ $paciente->apellidos }}</div>
            <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</div>
            <div><strong>Identidad:</strong> {{ $paciente->identidad }}</div>
            <div><strong>Edad:</strong> {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años</div>
            <div><strong>Médico Solicitante:</strong> {{ $consulta->medico->nombre ?? '' }} {{ $consulta->medico->apellidos ?? '' }}</div>
        </div>

        {{-- Secciones con checkboxes --}}
        @foreach($datosSecciones as $seccion => $campos)
            <div class="seccion">
                <div class="section-title">{{ $seccion }}</div>
                <div class="checkbox-grid">
                    @foreach($campos as $campo => $valor)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled {{ $valor ? 'checked' : '' }} id="{{ $seccion }}_{{ $campo }}">
                            <label class="form-check-label" for="{{ $seccion }}_{{ $campo }}">
                                {{ str_replace('_', ' ', $campo) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Botón imprimir --}}
        <div class="text-center">
            <a href="{{ route('consultas.show', $consulta->id) }}" target="_blank" class="btn-imprimir">
                <i class="bi bi-printer"></i> Imprimir Consulta
            </a>
        </div>
    </div>

</div>
@endsection
