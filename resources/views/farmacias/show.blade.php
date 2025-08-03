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
        padding: 1rem;
        border: 1px solid #91cfff;
        border-radius: 12px;
    }
    .clickable-img {
        cursor: pointer;
    }
</style>

<div class="container mt-5 pt-3" style="max-width: 1000px;">
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
        <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Detalles de la farmacia</h5>
        </div>

        @if ($mostrarFoto)
        <div class="text-center my-4">
            <img src="{{ asset('storage/' . $farmacia->foto) }}"
                 alt="Foto de la farmacia"
                 class="rounded-circle shadow-sm clickable-img"
                 style="width: 150px; height: 150px; object-fit: cover;"
                 data-bs-toggle="modal"
                 data-bs-target="#fotoModal">
        </div>
        @endif

        <div class="card-body px-4 py-3">
            <div class="row gy-3">
                <div class="col-md-4"><strong>Nombre:</strong><br>{{ $farmacia->nombre }}</div>
                <div class="col-md-4"><strong>Teléfono:</strong><br>{{ $farmacia->telefono }}</div>
                <div class="col-md-4"><strong>Ubicación:</strong><br>{{ $farmacia->ubicacion }}</div>
                <div class="col-md-4"><strong>Horario:</strong><br>{{ $farmacia->horario }}</div>
                <div class="col-md-4"><strong>Descuento:</strong><br>{{ $farmacia->descuento ?? '—' }}%</div>
                <div class="col-md-4"><strong>Página Web:</strong><br>
                    @if ($farmacia->pagina_web)
                        <a href="{{ $farmacia->pagina_web }}" target="_blank">{{ $farmacia->pagina_web }}</a>
                    @else
                        No disponible
                    @endif
                </div>
                <div class="col-md-4">
                    <strong>Estado:</strong><br>
                    <span class="badge bg-{{ $farmacia->estado === 'Activo' ? 'success' : 'danger' }}">
                        {{ $farmacia->estado }}
                    </span>
                </div>
            </div>

            <div class="row gy-3 mt-3">
                <div class="col-md-12">
                    <strong>Descripción:</strong><br>
                    <span style="white-space: pre-line;">{{ $farmacia->descripcion ?: 'Sin descripción.' }}</span>
                </div>
            </div>
        </div>

        <div class="text-center pb-4">
            <a href="{{ route('farmacias.index') }}" 
               class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" 
               style="font-size: 0.85rem;">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </div>
</div>

@if ($mostrarFoto)
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0 position-relative d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.7);">
      <div class="modal-body p-0" style="max-width: 90vw; max-height: 90vh;">
        <img src="{{ asset('storage/' . $farmacia->foto) }}"
             alt="Foto de la farmacia"
             style="max-width: 450px; max-height: 450px; object-fit: cover; cursor: pointer;"
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
