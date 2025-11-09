@extends('layouts.app')

@section('title', 'Resultados de Ultrasonido')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<style>
    .custom-card {
        max-width: 850px; /* ✅ antes 1000px */
        background-color: #fff;
        border: 1px solid #91cfff;
        border-radius: .8rem;
        padding: 1.2rem; /* ✅ menos padding */
        margin: .8rem auto;
        position: relative;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%; left: 50%;
        width: 600px; height: 600px; /* ✅ antes 800px */
        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.12;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    .titulo-principal {
        font-size: 1.5rem; /* ✅ antes 1.8 */
        font-weight: 700;
        color: #003366;
        text-align: center;
        margin-bottom: 4px;
        z-index: 1;
        position: relative;
    }

    .linea-azul {
        height: 3px; /* ✅ antes 4 */
        width: 100%;
        background: #0d6efd;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .section-title {
        font-size: 1rem; /* ✅ antes 1.2 */
        font-weight: 700;
        margin-bottom: 4px;
        color: #0b5ed7;
        border-bottom: 2px solid #0b5ed7;
        padding-bottom: 3px;
        position: relative;
        z-index: 1;
    }

    .examen-card {
        margin-top: 1rem;
        padding-bottom: 0.7rem;
        position: relative;
        z-index: 1;
    }

    .examen-card h4 {
        color: #004080;
        font-weight: 700;
        border-bottom: 1px dashed #0d6efd;
        padding-bottom: 0.3rem;
        font-size: 1rem; /* ✅ antes 1.1 */
        margin-bottom: 0.6rem;
    }

    .imagen-block {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: .5rem; /* ✅ antes .7 */
        width: 240px; /* ✅ antes 290 */
        cursor: pointer;
    }

    .imagen-block img {
        height: 150px; /* ✅ antes 180px */
        object-fit: cover;
    }
</style>

<div class="container mt-3">
    <div class="custom-card shadow-sm">

        <h3 class="titulo-principal">Resultados de Ultrasonido</h3>
        <div class="linea-azul"></div>

        {{-- INFORMACIÓN --}}
        <div class="row gy-1 mb-2">
            <div class="col-12">
                <div class="section-title">Información de la Orden</div>
            </div>

            <div class="col-md-4">
                <strong>Paciente:</strong><br>
                {{ $orden->paciente?->nombre }} {{ $orden->paciente?->apellidos }}
            </div>

            <div class="col-md-4">
                <strong>Identidad:</strong><br>
                {{ $orden->paciente?->identidad ?? '—' }}
            </div>

            <div class="col-md-4">
                <strong>Fecha de Orden:</strong><br>
                {{ optional($orden->created_at)->format('d/m/Y h:i A') }}
            </div>

            <div class="col-md-12 mt-1">
                <strong>Médico analista:</strong><br>
                {{ $orden->medico?->nombre }} {{ $orden->medico?->apellidos }}
            </div>
        </div>

        {{-- IMÁGENES --}}
        @if($orden->imagenes->isEmpty())
            <div class="alert alert-info text-center mt-3">
                Este paciente aún no tiene análisis de ultrasonido registrados.
            </div>
        @else
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

                                    <small class="text-primary fw-bold">Descripción:</small>
                                    <p class="mb-1">{{ $imagen->descripcion ?? 'Sin descripción.' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

        <div class="text-center pt-3">
            <a href="{{ route('ultrasonidos.index') }}" class="btn btn-success px-4">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="imagenModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5>Detalle de Imagen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImagenAmpliada" class="img-fluid" style="max-height: 80vh;">
            </div>
            <div class="modal-footer">
                <strong>Descripción:</strong>
                <p id="modalDescripcion" class="mb-0 text-break"></p>
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
