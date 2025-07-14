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
        padding: 1rem;
        border: 1px solid #91cfff;
        border-radius: 12px;
    }
    /* Cursor pointer para imagen pequeña */
    .clickable-img {
        cursor: pointer;
    }
</style>

<!-- Barra de navegación fija -->
<div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050; height: 56px;">
    <div class="d-flex justify-content-between align-items-center px-3" style="height: 56px;">
        <div class="d-flex align-items-center">
            <!-- Logo -->
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" 
                 style="height: 40px; width: auto; margin-right: 6px;">
            <!-- Texto -->
            <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
        </div>
    </div>
</div>

<!-- Contenedor principal sin scroll innecesario -->
<div class="container mt-5 pt-3" style="max-width: 1000px;">
    
    <!-- Tarjeta con margen superior reducido -->
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
        <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Detalles del empleado</h5>
        </div>

       <!-- FOTO DEL EMPLEADO -->
       <div class="text-center my-4">
           <img src="{{ asset($empleado->foto ? 'storage/' . $empleado->foto : 'images/default-user.png') }}"
                alt="Foto del empleado"
                class="rounded-circle shadow-sm clickable-img"
                style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                data-bs-toggle="modal"
                data-bs-target="#fotoModal">
       </div>

        <!-- Contenido -->
        <div class="card-body px-4 py-3">
            <div class="row gy-3">
                <div class="col-md-3"><strong>Nombres:</strong><br>{{ $empleado->nombres }}</div>
                <div class="col-md-3"><strong>Apellidos:</strong><br>{{ $empleado->apellidos }}</div>
                <div class="col-md-3"><strong>Identidad:</strong><br>{{ $empleado->identidad }}</div>
                <div class="col-md-3"><strong>Correo:</strong><br>{{ $empleado->correo }}</div>

                <div class="col-md-3"><strong>Teléfono:</strong><br>{{ $empleado->telefono }}</div>
                <div class="col-md-3"><strong>Estado Civil:</strong><br>{{ $empleado->estado_civil }}</div>
                <div class="col-md-3">
                    <strong>Género:</strong><br>
                    <span class="badge 
                        {{ $empleado->genero === 'Masculino' ? 'bg-primary' : 
                           ($empleado->genero === 'Femenino' ? 'bg-warning text-dark' : 'bg-info') }}">
                        {{ $empleado->genero }}
                    </span>
                </div>
                <div class="col-md-3"><strong>Fecha de Ingreso:</strong><br>{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</div>

                <div class="col-md-3"><strong>Fecha de Nacimiento:</strong><br>{{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y') }}</div>
                <div class="col-md-3"><strong>Salario:</strong><br>{{ $empleado->salario ? 'Lps. ' . number_format($empleado->salario, 2) : 'No especificado' }}</div>
                <div class="col-md-3"><strong>Área:</strong><br>{{ $empleado->puesto->area ?? 'No especificada' }}</div>
                <div class="col-md-3"><strong>Turno Asignado:</strong><br>{{ $empleado->turno_asignado }}</div>

                <div class="col-md-3"><strong>Puesto:</strong><br>{{ $empleado->puesto->nombre ?? 'No especificado' }}</div>
                <div class="col-md-3">
                    <strong>Estado:</strong><br>
                    @if($empleado->estado == 'Activo' || $empleado->estado == 1 || $empleado->estado === true)
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-danger">Inactivo</span>
                    @endif
                </div>
            </div>

            <!-- Nueva fila para Dirección y Observaciones lado a lado -->
            <div class="row gy-3 mt-3">
                <div class="col-md-6">
                    <strong>Dirección:</strong><br>
                    <span style="white-space: pre-line;">{{ $empleado->direccion }}</span>
                </div>
                <div class="col-md-6">
                    <strong>Observaciones:</strong><br>
                    <span style="white-space: pre-line;">{{ $empleado->observaciones ?: 'Sin observaciones.' }}</span>
                </div>
            </div>
        </div>

        <!-- Botón Regresar centrado -->
        <div class="text-center pb-4">
            <a href="{{ route('empleado.index') }}" 
               class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" 
               style="font-size: 0.85rem;">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </div>
</div>

<!-- Modal para mostrar imagen grande -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0 position-relative d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.7);">
      <div class="modal-body p-0" style="max-width: 90vw; max-height: 90vh;">
        <img src="{{ asset($empleado->foto ? 'storage/' . $empleado->foto : 'images/default-user.png') }}"
             alt="Foto del empleado"
             style="max-width: 450px; max-height: 450px; object-fit: cover; cursor: pointer;"
             id="imagenGrande">
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS (si no está incluido en tu layout) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Cerrar modal al hacer clic en la imagen grande
  document.addEventListener('DOMContentLoaded', function() {
    const imagenGrande = document.getElementById('imagenGrande');
    imagenGrande.addEventListener('click', function() {
      const modal = bootstrap.Modal.getInstance(document.getElementById('fotoModal'));
      modal.hide();
    });
  });
</script>

@endsection