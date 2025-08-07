@extends('layouts.app')

@section('content')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        border: none;
    }
    .section-title {
        font-weight: bold;
        color: #0d6efd;
        margin-top: 15px;
    }
    .form-check-label {
        font-weight: 500;
    }
    .description-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px;
        border-radius: 6px;
        font-size: 0.95rem;
        margin-top: 5px;
    }
</style>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detalle de Orden de Rayos X</h5>
            <a href="{{ route('rayosx.index') }}" class="btn btn-light btn-sm">← Volver</a>
        </div>
        <div class="card-body">

            {{-- Datos del paciente / diagnóstico --}}
            <h6 class="section-title">Datos del Paciente</h6>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Nombre:</strong>
                    @if ($orden->nombres || $orden->apellidos)
                        {{ $orden->nombres }} {{ $orden->apellidos }}
                    @elseif ($orden->pacienteClinica)
                        {{ $orden->pacienteClinica->nombre }} {{ $orden->pacienteClinica->apellidos }}
                    @elseif ($orden->pacienteRayosX)
                        {{ $orden->pacienteRayosX->nombre }} {{ $orden->pacienteRayosX->apellidos }}
                    @else
                        N/A
                    @endif
                </div>
                <div class="col-md-3"><strong>Identidad:</strong>
                    @if ($orden->identidad)
                        {{ $orden->identidad }}
                    @elseif ($orden->pacienteClinica)
                        {{ $orden->pacienteClinica->identidad }}
                    @elseif ($orden->pacienteRayosX)
                        {{ $orden->pacienteRayosX->identidad }}
                    @else
                        N/A
                    @endif
                </div>
                <div class="col-md-2"><strong>Edad:</strong> {{ $orden->edad ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</div>
            </div>

            @if($orden->diagnostico)
                <div class="mb-3">
                    <strong>Diagnóstico asociado:</strong> {{ $orden->diagnostico->descripcion ?? 'N/A' }}
                </div>
            @endif

            @if($orden->datos_clinicos)
                <div class="mb-3">
                    <strong>Datos clínicos:</strong> {{ $orden->datos_clinicos }}
                </div>
            @endif

            {{-- Lista de exámenes --}}
            <h6 class="section-title">Exámenes solicitados</h6>
            <div class="row">
                @forelse($orden->examenes as $examen)
                    <div class="col-md-4 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <label class="form-check-label">{{ $examen->examen }}</label>
                        </div>
                        @php
                            // Obtener nombre completo paciente para buscar descripción
                            $pacienteNombre = trim(
                                ($orden->nombres && $orden->apellidos)
                                    ? $orden->nombres . ' ' . $orden->apellidos
                                    : ($orden->pacienteClinica
                                        ? $orden->pacienteClinica->nombre . ' ' . $orden->pacienteClinica->apellidos
                                        : ($orden->pacienteRayosX
                                            ? $orden->pacienteRayosX->nombre . ' ' . $orden->pacienteRayosX->apellidos
                                            : '')
                                    )
                            );

                            $descripcion = DB::table('rayosx_descripciones')
                                ->where('paciente', $pacienteNombre)
                                ->where('examen', $examen->examen)
                                ->value('descripcion');
                        @endphp
                        @if($descripcion)
                            <div class="description-box">
                                {{ $descripcion }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">No hay exámenes registrados para esta orden.</p>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection
