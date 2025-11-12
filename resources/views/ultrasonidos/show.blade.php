@extends('layouts.app')

@section('title', 'Resultados de Ultrasonido')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background-color: #e8f4fc;
    margin: 0;
    padding: 0;
    min-height: 100vh;
}

/* Wrapper principal */
.content-wrapper {
    margin-top: 60px;
    max-width: 1000px;
    background-color: #fff;
    margin-left: auto;
    margin-right: auto;
    border-radius: 1.5rem;
    padding: 1rem 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    margin-bottom: 2rem;
}

/* Logo translúcido */
.content-wrapper::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 2200px;
    height: 2200px;
    background-image: url('{{ asset('images/logo2.jpg') }}');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.1;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 0;
}

h2, h4, h5 {
    color: #003366;
    font-weight: 700;
    margin-bottom: 1rem;
    text-align: center;
    position: relative;
    z-index: 1;
}

h5 {
    font-size: 1.2rem;
}

/* Línea azul decorativa */
.linea-azul {
    height: 3px;
    background-color: #007BFF;
    width: 100%;
    border-radius: 2px;
    margin: 0.5rem 0 1rem 0;
    position: relative;
    z-index: 1;
}

/* Bloque datos del paciente */
.patient-block {
    background-color: #f0f7ff;
    padding: 1rem 1.2rem;
    border-radius: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    position: relative;
    z-index: 1;
}

.patient-data-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem;
}

.patient-data-field {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    min-width: 180px;
    flex: 1;
}

.patient-data-field strong {
    min-width: 100px;
    font-weight: normal;
    color: rgb(3,12,22);
    font-size: 0.95rem;
}

.underline-field-solid {
    border-bottom: 1px solid #333;
    min-height: 1.2rem;
    padding: 0 4px;
    flex: 1;
    font-size: 0.85rem;
}

/* Bloque exámenes */
.examen-block {
    background-color: #f0f7ff;
    padding: 1rem 1rem;
    border-radius: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    position: relative;
    z-index: 1;
}

.examenes-lista {
    display: grid;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

/* Grid responsivo según cantidad */
.examenes-lista.count-1,
.examenes-lista.count-2 {
    grid-template-columns: repeat(2, 1fr);
}

.examenes-lista.count-3,
.examenes-lista.count-4 {
    grid-template-columns: repeat(2, 1fr);
}

.examenes-lista.count-5,
.examenes-lista.count-6,
.examenes-lista.count-7 {
    grid-template-columns: repeat(3, 1fr);
}

.examen-item {
    display: flex;
    align-items: center;
    padding: 0.4rem 0.6rem;
    background-color: #fff;
    border-radius: 0.5rem;
    border: 1px solid #d0e7ff;
    font-size: 0.85rem;
    color: #000;
}

.examen-numero {
    color: #000;
    font-weight: 600;
    font-size: 0.85rem;
    margin-right: 0.4rem;
    flex-shrink: 0;
}

.examen-item span {
    flex: 1;
}

/* Sección imágenes */
.imagenes-section {
    position: relative;
    z-index: 1;
}

/* Card contenedor principal - mismo estilo que patient-block y examen-block */
.ultrasonidos-container {
    background-color: #f0f7ff;
    padding: 1rem 1rem;
    border-radius: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    position: relative;
    z-index: 1;
}

.ultrasonidos-container > h5 {
    text-align: center;
    margin-bottom: 1.5rem;
    font-weight: 700;
}

.examen-card {
    background-color: transparent;
    border-radius: 0;
    padding: 0;
    margin-bottom: 2rem;
    box-shadow: none;
    border: none;
}

.examen-card:last-child {
    margin-bottom: 0;
}

.examen-card h4 {
    color: #1a1a1a;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 1.2rem;
    text-align: left;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid #007BFF;
    letter-spacing: 0.3px;
}

.imagen-block {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: .5rem;
    width: 240px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.2s, box-shadow 0.2s;
}

.imagen-block:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.imagen-block img {
    height: 150px;
    object-fit: cover;
    width: 100%;
    border-radius: 4px;
    margin-bottom: 6px;
}

.imagen-block p {
    font-size: 0.85rem;
    color: #333;
    background-color: #f8f9fa;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
    width: 100%;
    min-height: 50px;
    white-space: pre-wrap;
    word-wrap: break-word;
    overflow-wrap: break-word;
    margin: 0;
}

/* Modal */
#modalDescripcion {
    display: block;
    white-space: pre-wrap;
    word-wrap: break-word;
    overflow-wrap: break-word;
    max-width: 100%;
    line-height: 1.4;
    font-size: 0.95rem;
    color: #333;
    text-align: left;
    margin: 0;
}

/* Alert personalizado */
.alert-info-custom {
    background-color: #e7f3ff;
    border: 1px solid #b3d9ff;
    color: #004085;
    padding: 1rem;
    border-radius: 0.8rem;
    text-align: center;
    font-size: 0.95rem;
}

/* Botones */
button, a.btn {
    font-size: 0.95rem;
    padding: 0.5rem 1rem;
    font-weight: normal;
    border-radius: 0.4rem;
    line-height: 1.2;
}

.btn-success {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
}

@media (max-width: 768px) {
    .patient-data-row {
        flex-direction: column;
    }
    
    .examenes-lista.count-1,
    .examenes-lista.count-2,
    .examenes-lista.count-3,
    .examenes-lista.count-4,
    .examenes-lista.count-5,
    .examenes-lista.count-6,
    .examenes-lista.count-7 {
        grid-template-columns: 1fr !important;
    }
    
    .imagen-block {
        width: 100%;
    }
}
</style>

<div class="content-wrapper">
    {{-- Encabezado --}}
    <div class="row align-items-center mb-3">
        <div class="col-md-3 text-center">
            <img src="{{ asset('images/logo2.jpg') }}" alt="Logo Clinitek" style="height:50px; width:auto;">
            <div style="font-size:0.9rem; font-weight:700; color:#555;">Laboratorio ultrasonidos Honduras</div>
        </div>
        <div class="col-md-9 text-center" style="transform: translateX(30%);">
            <h4 class="mb-0" style="font-size:1.1rem; font-weight:700; color:#333; line-height:1.3;">
                Resultados de ultrasonido
            </h4>
        </div>
    </div>

    <div class="linea-azul"></div>

    {{-- Datos del paciente --}}
    <div class="patient-block">
        <h5>Datos del paciente</h5>
        <div class="patient-data-row">
            <div class="patient-data-field">
                <strong>Nombres:</strong>
                <div class="underline-field-solid">{{ $orden->paciente?->nombre ?? '—' }}</div>
            </div>
            <div class="patient-data-field">
                <strong>Apellidos:</strong>
                <div class="underline-field-solid">{{ $orden->paciente?->apellidos ?? '—' }}</div>
            </div>
        </div>
        <div class="patient-data-row">
            <div class="patient-data-field">
                <strong>Identidad:</strong>
                <div class="underline-field-solid">{{ $orden->paciente?->identidad ?? '—' }}</div>
            </div>
            <div class="patient-data-field">
                <strong>Género:</strong>
                <div class="underline-field-solid">{{ $orden->paciente?->genero ?? '—' }}</div>
            </div>
        </div>
        <div class="patient-data-row">
            <div class="patient-data-field">
                <strong>Fecha de orden:</strong>
                <div class="underline-field-solid">{{ optional($orden->created_at)->format('d/m/Y') }}</div>
            </div>
            <div class="patient-data-field">
                <strong>Médico analista:</strong>
                <div class="underline-field-solid">{{ $orden->medico?->nombre }} {{ $orden->medico?->apellidos }}</div>
            </div>
        </div>
    </div>

    {{-- Exámenes recetados --}}
    <div class="examen-block">
        <h5>Exámenes solicitados</h5>
        @if(isset($examenesKeys) && count($examenesKeys) > 0)
            <div class="examenes-lista count-{{ count($examenesKeys) }}">
                @foreach($examenesKeys as $index => $examenKey)
                    @php
                        $nombreExamen = $mapaNombres[$examenKey] ?? ucfirst($examenKey);
                    @endphp
                    <div class="examen-item">
                        <div class="examen-numero">{{ $index + 1 }}.</div>
                        <span>{{ $nombreExamen }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert-info-custom">
                No se encontraron exámenes solicitados para esta orden.
            </div>
        @endif
    </div>

    {{-- Imágenes de análisis --}}
    <div class="imagenes-section">
        @if($orden->imagenes->isEmpty())
            <div class="alert-info-custom">
                Este paciente aún no tiene análisis de ultrasonido registrados.
            </div>
        @else
            <div class="ultrasonidos-container">
                <h5>Ultrasonidos analizados</h5>

                @foreach($examenesKeys as $examenKey)
                    @php
                        $nombreExamen = $mapaNombres[$examenKey] ?? ucfirst($examenKey);
                        $imagenesDelExamen = $imagenesAgrupadas[$examenKey] ?? collect();
                    @endphp

                    @if($imagenesDelExamen->isNotEmpty())
                        <div class="examen-card">
                            <h4>{{ $nombreExamen }}</h4>

                            <div class="d-flex flex-wrap gap-2">
                                @foreach($imagenesDelExamen as $imagen)
                                    <div class="imagen-block"
                                        data-bs-toggle="modal"
                                        data-bs-target="#imagenModal"
                                        data-ruta="{{ asset('storage/' . $imagen->ruta) }}"
                                        data-descripcion="{{ $imagen->descripcion }}">

                                        <img src="{{ asset('storage/' . $imagen->ruta) }}" class="w-100">

                                        <p>{{ $imagen->descripcion ?? 'Sin descripción.' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- Botón volver --}}
    <div class="text-center pt-3">
        <a href="{{ route('ultrasonidos.index') }}" class="btn btn-success px-4">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="imagenModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detalle de imagen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImagenAmpliada" class="img-fluid" style="max-height: 80vh;">
            </div>
            <div class="modal-footer">
                <strong>Descripción:</strong>
                <p id="modalDescripcion" class="mb-0"></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('imagenModal');
        modal.addEventListener('show.bs.modal', function (event) {
            const item = event.relatedTarget;
            document.getElementById('modalImagenAmpliada').src = item.dataset.ruta;
            document.getElementById('modalDescripcion').textContent = item.dataset.descripcion || 'Sin descripción.';
        });
    });
</script>
@endpush

@endsection