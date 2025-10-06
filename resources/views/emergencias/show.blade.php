@extends('layouts.app')

@section('title', 'Detalles del Paciente en Emergencias')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Carbon\Carbon;

    $paciente = $emergencia->paciente;
    $mostrarFoto = false;

    if(!$paciente && $emergencia->foto && Storage::exists('public/' . $emergencia->foto)) {
        $mostrarFoto = true;
    }

    if(!$emergencia->edad && $paciente?->fecha_nacimiento) {
        $edad = Carbon::parse($paciente->fecha_nacimiento)->age;
    } else {
        $edad = $emergencia->edad;
    }

    $historial = $paciente
        ? $paciente->emergencias()->where('id', '!=', $emergencia->id)->orderBy('fecha', 'desc')->get()
        : collect();
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
    .card-header h5 { font-size: 2rem; }
    .paciente-img { width: 140px; height: 140px; object-fit: cover; border-radius: 10px; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.08); display: block; margin: 0 auto 1rem auto; }
    .info-label { font-weight: 700; font-size: 1rem; color: #003366; display:block; }
    .info-value { font-size: 1.05rem; color: #222; margin-top: 4px; }
    .info-block { padding: 6px 8px; }
    .section-title { font-size: 1.2rem; font-weight: 700; margin-top: 1rem; margin-bottom: 0.6rem; color: #0b5ed7; border-bottom: 2px solid #0b5ed7; padding-bottom: 4px; }
    .vital-label { font-weight: 600; color: #0d6efd; display:block; text-align:center; font-size:1rem; }
    .vital-value { font-size: 1.1rem; text-align:center; font-weight: 500; margin-top: 3px; }
    @media (max-width: 768px) {
        .paciente-img { width: 110px; height: 110px; }
        .card-header h5 { font-size: 1.6rem; }
        .info-value { font-size: 1rem; }
        .vital-value { font-size: 1rem; }
    }
</style>

<div class="container mt-3">
    <div class="card custom-card shadow-sm border rounded-4">
        <div class="card-header text-center py-2" style="background-color:#fff; border-bottom:4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark">Detalles del Paciente en Emergencias</h5>
        </div>

        <div class="card-body px-3 py-3">
            {{-- Foto del paciente --}}
            @if($mostrarFoto)
                <img src="{{ asset('storage/' . $emergencia->foto) }}"
                     alt="Foto del paciente"
                     class="paciente-img"
                     data-bs-toggle="modal"
                     data-bs-target="#fotoModal">
            @endif

            {{-- Datos del paciente --}}
            <div class="row gy-2">
                <div class="col-md-3 info-block">
                    <span class="info-label">Nombres:</span>
                    <div class="info-value">{{ $paciente->nombre ?? $emergencia->nombre ?? '—' }}</div>
                </div>
                <div class="col-md-3 info-block">
                    <span class="info-label">Apellidos:</span>
                    <div class="info-value">{{ $paciente->apellido ?? $emergencia->apellido ?? '—' }}</div>
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
                    <div class="info-value">{{ $paciente->genero ?? $emergencia->genero ?? '—' }}</div>
                </div>
                <div class="col-md-3 info-block">
                    <span class="info-label">Teléfono:</span>
                    <div class="info-value">{{ $paciente->telefono ?? $emergencia->telefono ?? '—' }}</div>
                </div>
                <div class="col-md-3 info-block">
                    <span class="info-label">Tipo de Sangre:</span>
                    <div class="info-value">{{ $paciente->tipo_sangre ?? $emergencia->tipo_sangre ?? '—' }}</div>
                </div>
                <div class="col-md-6 info-block">
                    <span class="info-label">Dirección:</span>
                    <div class="info-value">
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $paciente->direccion ?? $emergencia->direccion ?? '—' }}</textarea>
                    </div>
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
                    <div class="info-value">
                        <textarea class="form-control" rows="3" readonly style="resize:none; background-color:#f8f9fa;">{{ $emergencia->motivo ?? '—' }}</textarea>
                    </div>
                </div>

                {{-- Signos Vitales --}}
                <div class="col-12 mt-3">
                    <div class="section-title">Signos Vitales</div>
                </div>
                <div class="col-md-4 info-block text-center">
                    <span class="vital-label">Presión Arterial</span>
                    <span class="vital-value">{{ $emergencia->pa ?? '—' }}</span>
                </div>
                <div class="col-md-4 info-block text-center">
                    <span class="vital-label">Frecuencia Cardíaca</span>
                    <span class="vital-value">{{ $emergencia->fc ?? '—' }}</span>
                </div>
                <div class="col-md-4 info-block text-center">
                    <span class="vital-label">Temperatura</span>
                    <span class="vital-value">{{ $emergencia->temp ? $emergencia->temp . ' °C' : '—' }}</span>
                </div>
            </div>

            {{-- Botones --}}
            <div class="text-center pt-3">
                <a href="{{ route('emergencias.index') }}" class="btn btn-success btn-sm px-4 shadow-sm">← Regresar</a>
                <button type="button" class="btn btn-primary btn-sm px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#historialModal">Ver Historial</button>
                <a href="{{ route('hospitalizaciones.create', ['emergencia_id' => $emergencia->id]) }}" class="btn btn-warning btn-sm px-4 shadow-sm">Transferir a Hospitalización</a>
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
                @if($historial->isEmpty())
                    <p class="text-center mb-0">Este paciente no tiene historial de emergencias previas.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Signos Vitales</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historial as $registro)
                                    <tr>
                                        <td>{{ $registro->paciente->nombre }}</td>
                                        <td>{{ $registro->paciente->apellido }}</td>
                                        <td>{{ \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($registro->hora)->format('h:i A') }}</td>
                                        <td>
                                            PA: {{ $registro->pa ?? '—' }}<br>
                                            FC: {{ $registro->fc ?? '—' }}<br>
                                            Temp: {{ $registro->temp ? $registro->temp . ' °C' : '—' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('emergencias.show', $registro->id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
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

{{-- Modal Foto --}}
@if($mostrarFoto)
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 d-flex justify-content-center align-items-center" style="background:rgba(0,0,0,0.7);">
                <div class="modal-body p-0" style="max-width:90vw; max-height:90vh;">
                    <img src="{{ asset('storage/' . $emergencia->foto) }}"
                         alt="Foto del paciente"
                         style="max-width:550px; max-height:550px; object-fit:contain; cursor:pointer;"
                         id="imagenGrande">
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Scripts --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Modal foto
        const img = document.getElementById('imagenGrande');
        if (img) {
            img.addEventListener('click', () => {
                bootstrap.Modal.getInstance(document.getElementById('fotoModal')).hide();
            });
        }

        // Mostrar/ocultar historial
        const btn = document.querySelector('button[data-bs-target="#historialModal"]');
        const modal = document.getElementById('historialModal');
        modal.addEventListener('shown.bs.modal', () => {
            const tabla = modal.querySelector('table');
            if (!tabla) return;
        });
    });
</script>
@endsection
