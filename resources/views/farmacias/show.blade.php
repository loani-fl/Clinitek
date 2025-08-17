@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    $mostrarFoto = $farmacia->foto && Storage::exists('public/' . $farmacia->foto);
@endphp

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
        padding: 1.5rem;
        border: 1px solid #91cfff;
        border-radius: 12px;
        z-index: 1;
    }

    .clickable-img {
        cursor: pointer;
    }

    .farmacia-img {
        width: 180px;
        height: auto;
        max-height: 180px;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 0 6px rgba(0,0,0,0.1);
    }

    .info-label {
        font-weight: bold;
        font-size: 0.95rem;
    }

    .info-block {
        margin-bottom: 1rem;
        word-wrap: break-word;
        word-break: break-word;
    }

    .ubicacion-descripcion {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .ubicacion-descripcion > div {
        flex: 1 1 48%;
    }

    @media (max-width: 768px) {
        .ubicacion-descripcion > div {
            flex: 1 1 100%;
        }
    }
</style>

<div class="container mt-5 pt-3" style="max-width: 1000px;">
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
        <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Detalles de la farmacia</h5>
        </div>

        <div class="card-body px-4 py-3">
            <div class="row">
                @if ($mostrarFoto)
                <div class="col-md-4 text-center mb-3 mb-md-0 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('storage/' . $farmacia->foto) }}"
                         alt="Foto de la farmacia"
                         class="farmacia-img clickable-img"
                         data-bs-toggle="modal"
                         data-bs-target="#fotoModal">
                </div>
                @endif

                <div class="col-md-8">
                    <div class="row gy-3">
                        <div class="col-md-6 info-block">
                            <span class="info-label">Nombre:</span><br>{{ $farmacia->nombre }}
                        </div>
                        <div class="col-md-6 info-block">
                            <span class="info-label">Teléfono:</span><br>{{ $farmacia->telefono }}
                        </div>

                        <div class="col-md-6 info-block">
                            <span class="info-label">Horario:</span><br>{{ $farmacia->horario }}
                        </div>
                        <div class="col-md-6 info-block">
                            <span class="info-label">Descuento:</span><br>{{ $farmacia->descuento ?? '—' }}%
                        </div>

                        <div class="col-md-6 info-block">
                            <span class="info-label">Página Web:</span><br>
                            @if ($farmacia->pagina_web)
                                <a href="{{ $farmacia->pagina_web }}" target="_blank">{{ $farmacia->pagina_web }}</a>
                            @else
                                No disponible
                            @endif
                        </div>
                    </div>

                    <!-- Ubicación y Descripción en la misma fila -->
                    <div class="ubicacion-descripcion mt-3">
                        <div class="info-block">
                            <span class="info-label">Ubicación:</span><br>
                            @php
                                $ubicacion = [];
                                if (!empty($farmacia->departamento)) $ubicacion[] = $farmacia->departamento;
                                if (!empty($farmacia->ciudad)) $ubicacion[] = $farmacia->ciudad;
                                if (!empty($farmacia->direccion)) $ubicacion[] = $farmacia->direccion;
                            @endphp

                            @if(count($ubicacion) > 0)
                                {{ implode(', ', $ubicacion) }}
                            @else
                                <span>No hay ubicación disponible</span>
                            @endif
                        </div>

                        <div class="info-block">
                            <span class="info-label">Descripción:</span><br>
                            <span style="white-space: pre-line;">{{ $farmacia->descripcion ?: 'Sin descripción.' }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="text-center pb-4 mt-3">
            <a href="{{ route('farmacias.index') }}" 
               class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" 
               style="font-size: 0.85rem;">
                ← Regresar
            </a>
        </div>
    </div>
</div>

@if ($mostrarFoto)
<!-- Modal de imagen -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0 position-relative d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.7);">
      <div class="modal-body p-0" style="max-width: 90vw; max-height: 90vh;">
        <img src="{{ asset('storage/' . $farmacia->foto) }}"
             alt="Foto de la farmacia"
             style="max-width: 450px; max-height: 450px; object-fit: contain; cursor: pointer;"
             id="imagenGrande">
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const imagenGrande = document.getElementById('imagenGrande');
    imagenGrande.addEventListener('click', function() {
      const modal = bootstrap.Modal.getInstance(document.getElementById('fotoModal'));
      modal.hide();
    });
  });
</script>
@endif
@endsection
