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
        .clickable-img {
            cursor: pointer;
        }
    </style>

    <!-- Barra de navegación fija -->
    <!-- Barra de navegación fija -->
    <div class="header d-flex justify-content-between align-items-center px-3 py-2 fixed-top" style="background-color: #007BFF;">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto;">
            <div class="fw-bold text-white ms-2" style="font-size: 1.5rem;">Clinitek</div>
        </div>

        <!-- Menú desplegable funcional -->
        <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle" type="button" id="menuMedico" data-bs-toggle="dropdown" aria-expanded="false">
                ☰
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuMedico">
                <li><a class="dropdown-item" href="{{ route('puestos.create') }}">Crear puesto</a></li>
                <li><a class="dropdown-item" href="{{ route('empleado.create') }}">Registrar empleado</a></li>
                <li><a class="dropdown-item" href="{{ route('medicos.create') }}">Registrar médico</a></li>
                <li><a class="dropdown-item" href="{{ route('consultas.create') }}">Registrar consulta</a></li>
                <li><a class="dropdown-item" href="{{ route('pacientes.create') }}">Registrar paciente</a></li>
            </ul>
        </div>
    </div>


    <!-- Contenedor principal -->
    <div class="container mt-5 pt-3" style="max-width: 1000px;">
        <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
            <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
                <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Detalles del Médico</h5>
            </div>



            <!-- Foto del médico -->
            <div class="text-center my-4">
                @if (!empty($medico->foto))
                    <img src="{{ asset('storage/' . $medico->foto) }}"
                         alt="Foto del médico"
                         class="rounded-circle shadow-sm clickable-img"
                         style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #0d6efd;"
                         data-bs-toggle="modal" data-bs-target="#fotoModal">
                @else
                    <img src="{{ asset('images/default-user.png') }}"
                         alt="Sin foto"
                         class="rounded-circle shadow-sm clickable-img"
                         style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #0d6efd;"
                         data-bs-toggle="modal" data-bs-target="#fotoModal">
                @endif

                <div class="mt-3">
                    <strong>Estado:</strong><br>
                    @if($medico->estado == 'Activo' || $medico->estado == 1 || $medico->estado === true)
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-danger">Inactivo</span>
                    @endif
                </div>
            </div>


            <!-- Información del médico -->
            <div class="card-body px-4 py-3">
                <div class="row gy-3">
                    <div class="col-md-3"><strong>Nombre:</strong><br>{{ $medico->nombre }}</div>
                    <div class="col-md-3"><strong>Apellidos:</strong><br>{{ $medico->apellidos }}</div>
                    <div class="col-md-3"><strong>Número de Identidad:</strong><br>{{ $medico->numero_identidad }}</div>
                    <div class="col-md-3"><strong>Correo:</strong><br>{{ $medico->correo }}</div>

                    <div class="col-md-3"><strong>Teléfono:</strong><br>{{ $medico->telefono }}</div>
                    <div class="col-md-3"><strong>Especialidad:</strong><br>{{ $medico->especialidad }}</div>
                    <div class="col-md-3">
                        <strong>Género:</strong><br>
                        <span class="badge
                        {{ $medico->genero === 'Masculino' ? 'bg-primary' :
                            ($medico->genero === 'Femenino' ? 'bg-warning text-dark' : 'bg-info') }}">
                        {{ $medico->genero }}
                    </span>
                    </div>
                    <div class="col-md-3"><strong>Fecha de Ingreso:</strong><br>{{ \Carbon\Carbon::parse($medico->fecha_ingreso)->format('d/m/Y') }}</div>

                    <div class="col-md-3"><strong>Fecha de Nacimiento:</strong><br>{{ \Carbon\Carbon::parse($medico->fecha_nacimiento)->format('d/m/Y') }}</div>
                    <div class="col-md-3"><strong>Salario:</strong><br>{{ $medico->salario ? 'Lps. ' . number_format($medico->salario, 2) : 'No especificado' }}</div>

                    <div class="col-md-6"><strong>Dirección:</strong><br><span style="white-space: pre-line;">{{ $medico->direccion }}</span></div>
                    <div class="col-md-6"><strong>Observaciones:</strong><br><span style="white-space: pre-line;">{{ $medico->observaciones ?: 'Sin observaciones.' }}</span></div>
                </div>
            </div>

            <!-- Botón Regresar -->
            <div class="text-center pb-4">
                <a href="{{ route('medicos.index') }}"
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
                    <img src="{{ asset($medico->foto ? 'storage/' . $medico->foto : 'images/default-user.png') }}"
                         alt="Foto del médico"
                         style="max-width: 450px; max-height: 450px; object-fit: cover; cursor: pointer;"
                         id="imagenGrande">
                </div>
            </div>
        </div>
    </div>

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

    <!-- Modal para mostrar imagen grande -->
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 position-relative d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.7);">
                <div class="modal-body p-0" style="max-width: 90vw; max-height: 90vh;">
                    <img src="{{ asset($medico->foto ? 'storage/' . $medico->foto : 'images/default-user.png') }}"
                         alt="Foto del médico"
                         style="max-width: 450px; max-height: 450px; object-fit: cover; cursor: pointer;"
                         id="imagenGrande">
                </div>
            </div>
        </div>
    </div>


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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@endsection
