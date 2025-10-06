@extends('layouts.app')

@section('title', 'Detalles del Paciente en Emergencias')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Carbon\Carbon;

    $paciente = $emergencia->paciente; // Relación paciente_id
    $mostrarFoto = isset($paciente->foto) && Storage::exists('public/' . $paciente->foto);

    // Calcular edad automáticamente si no existe en emergencia pero hay paciente con fecha_nacimiento
    if(!$emergencia->edad && $paciente?->fecha_nacimiento) {
        $edad = Carbon::parse($paciente->fecha_nacimiento)->age;
    } else {
        $edad = $emergencia->edad;
    }
@endphp

<style>
    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%; left: 50%;
        width: 800px; height: 800px;
        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.12;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        border: 1px solid #91cfff;
        border-radius: 12px;
        margin: 1.5rem auto;
        padding: 1.2rem;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .card-header h5 { font-size: 1.9rem; }

    .paciente-img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .info-label { font-weight: 700; font-size: 0.98rem; color: #003366; display:block; }
    .info-value { font-size: 1.03rem; color: #222; margin-top: 4px; }
    .info-block { padding: 6px 8px; }

    .row.gy-2 > [class*="col-"] { margin-bottom: 0; padding-bottom: 0.25rem; }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-top: 0.9rem;
        margin-bottom: 0.4rem;
        color: #0b5ed7;
    }

    @media (max-width: 768px) {
        .paciente-img { width: 110px; height: 110px; }
        .card-header h5 { font-size: 1.5rem; }
        .info-value { font-size: 1rem; }
    }
</style>

<div class="container mt-3">
    <div class="card custom-card shadow-sm border rounded-4">
        <div class="card-header text-center py-2" style="background-color:#fff; border-bottom:4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark">Detalles del Paciente en Emergencias</h5>
        </div>

        <div class="card-body px-3 py-3">
            <div class="row align-items-start">
                {{-- Foto --}}
                @if($mostrarFoto)
                    <div class="col-md-3 text-center mb-3 mb-md-0 d-flex align-items-start justify-content-center">
                        <img src="{{ asset('storage/' . $paciente->foto) }}"
                             alt="Foto del paciente"
                             class="paciente-img"
                             data-bs-toggle="modal"
                             data-bs-target="#fotoModal">
                    </div>
                @endif

                {{-- Información del paciente --}}
                <div class="{{ $mostrarFoto ? 'col-md-9' : 'col-12' }}">
                    <div class="row gy-2">
                        <div class="col-md-3 info-block">
                            <span class="info-label">Nombres:</span>
                            <div class="info-value">{{ $paciente->nombre ?? '—' }}</div>
                        </div>

                        <div class="col-md-3 info-block">
                            <span class="info-label">Apellidos:</span>
                            <div class="info-value">{{ $paciente->apellido ?? '—' }}</div>
                        </div>

                        <div class="col-md-3 info-block">
                            <span class="info-label">Identidad:</span>
                            <div class="info-value">{{ $paciente->identidad ?? '—' }}</div>
                        </div>

                        <div class="col-md-3 info-block">
                            <span class="info-label">Edad:</span>
                            <div class="info-value">{{ $edad ?? '—' }}</div>
                        </div>

                        <div class="col-md-3 info-block">
                            <span class="info-label">Sexo:</span>
                            <div class="info-value">{{ $paciente->genero ?? '—' }}</div>
                        </div>

                        <div class="col-md-3 info-block">
                            <span class="info-label">Teléfono:</span>
                            <div class="info-value">{{ $paciente->telefono ?? '—' }}</div>
                        </div>

                        <div class="col-md-3 info-block">
                            <span class="info-label">Tipo de Sangre:</span>
                            <div class="info-value">{{ $paciente->tipo_sangre ?? '—' }}</div>
                        </div>

                        <div class="col-md-6 info-block">
                            <span class="info-label">Dirección:</span>
                            <div class="info-value">{{ $paciente->direccion ?? $emergencia->direccion ?? '—' }}</div>
                        </div>

                        {{-- Detalles de Emergencia --}}
                        <div class="col-12">
                            <div class="section-title">Detalles de Emergencia</div>
                        </div>

                        <div class="col-md-3 info-block">
                            <span class="info-label">Fecha:</span>
                            <div class="info-value">{{ \Carbon\Carbon::parse($emergencia->fecha)->format('d/m/Y') }}</div>
                        </div>

                        <div class="col-md-3 info-block">
                            <span class="info-label">Hora:</span>
                            <div class="info-value">{{ \Carbon\Carbon::parse($emergencia->hora)->format('h:i A') }}</div>
                        </div>

                        <div class="col-md-6 info-block">
                            <span class="info-label">Motivo de la Emergencia:</span>
                            <div class="info-value" style="white-space: pre-line;">{{ $emergencia->motivo ?? '—' }}</div>
                        </div>

                        {{-- Signos Vitales --}}
                        <div class="col-12">
                            <div class="section-title">Signos Vitales</div>
                        </div>

                        <div class="col-md-4 info-block">
                            <span class="info-label">Presión Arterial:</span>
                            <div class="info-value">{{ $emergencia->pa ?? '—' }}</div>
                        </div>

                        <div class="col-md-4 info-block">
                            <span class="info-label">Frecuencia Cardíaca:</span>
                            <div class="info-value">{{ $emergencia->fc ?? '—' }}</div>
                        </div>

                        <div class="col-md-4 info-block">
                            <span class="info-label">Temperatura:</span>
                            <div class="info-value">{{ $emergencia->temp ? $emergencia->temp . ' °C' : '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="text-center pt-3">
                <a href="{{ route('emergencias.index') }}" class="btn btn-success btn-sm px-4 shadow-sm">← Regresar</a>
                <button type="button" class="btn btn-primary btn-sm px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#historialModal">Ver Historial</button>
                <a href="{{ route('hospitalizaciones.create', ['emergencia_id' => $emergencia->id]) }}"
                class="btn btn-warning btn-sm px-4 shadow-sm">
                Transferir a Hospitalización
                </a>


            </div>
        </div>
    </div>
</div>

{{-- Modal Historial --}}
<div class="modal fade" id="historialModal" tabindex="-1" aria-labelledby="historialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="historialModalLabel">Historial de Emergencias</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @php
                    $historial = $paciente
                        ? $paciente->emergencias()->where('id', '!=', $emergencia->id)->orderBy('fecha', 'desc')->get()
                        : collect();
                @endphp

                @if($historial->isEmpty())
                    <p class="text-center mb-0">Este paciente no tiene historial de emergencias previas.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Motivo</th>
                                    <th>Dirección</th>
                                    <th>Signos Vitales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historial as $registro)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($registro->hora)->format('h:i A') }}</td>
                                        <td>{{ $registro->motivo }}</td>
                                        <td>{{ $registro->direccion }}</td>
                                        <td>
                                            PA: {{ $registro->pa ?? '—' }}<br>
                                            FC: {{ $registro->fc ?? '—' }}<br>
                                            Temp: {{ $registro->temp ? $registro->temp . ' °C' : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($mostrarFoto)
    {{-- Modal Foto --}}
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 d-flex justify-content-center align-items-center" style="background:rgba(0,0,0,0.7);">
                <div class="modal-body p-0" style="max-width:90vw; max-height:90vh;">
                    <img src="{{ asset('storage/' . $paciente->foto) }}"
                         alt="Foto del paciente"
                         style="max-width:550px; max-height:550px; object-fit:contain; cursor:pointer;"
                         id="imagenGrande">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const img = document.getElementById('imagenGrande');
            if (img) {
                img.addEventListener('click', () => {
                    bootstrap.Modal.getInstance(document.getElementById('fotoModal')).hide();
                });
            }
        });
    </script>
@endif
@endsection
