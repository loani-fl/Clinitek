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

        /* Contenedor para cada sección con grid de columnas */
        .detalle-grid {
            display: grid;
            gap: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            border: 1.5px solid #ced4da;
            padding: 1rem 1.5rem;
            word-wrap: break-word;
            white-space: normal;
        }

        /* Datos Básicos: 3 columnas */
        .detalle-basicos {
            grid-template-columns: repeat(3, 1fr);
        }

        /* Detalles de la Consulta: 5 columnas */
        .detalle-consulta {
            grid-template-columns: repeat(5, 1fr);
        }

        /* Datos Médicos: 2 columnas */
        .detalle-medicos {
            grid-template-columns: repeat(2, 1fr);
        }

        .detalle-grid p {
            margin: 0;
            overflow-wrap: break-word;
            word-wrap: break-word;
            hyphens: auto;
        }

        .detalle-grid p span {
            display: inline-block;
            max-width: 100%;
            word-break: break-word;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.4em 0.6em;
            border-radius: 0.35rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 1rem;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 0.25rem;
        }

        /* Nuevo: header flex para título y botón */
        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            border-bottom: 4px solid #0d6efd;
            padding: 0.75rem 1.5rem;
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
        }

        .card-header-flex h5 {
            font-size: 2.25rem;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }
    </style>

    <!-- Barra fija superior -->
    <div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050; height: 56px;">
        <div class="d-flex justify-content-between align-items-center px-3" style="height: 56px;">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
                <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
            </div>
        </div>
    </div>


    <!-- Contenedor principal -->
    <div class="container mt-5 pt-3" style="max-width: 1000px;">
        <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
            <!-- Header con título y botón -->
            <!-- Header con título y botón -->
            <div class="card-header-flex">
                <h5>Detalles de la Consulta</h5>

                <div class="d-flex align-items-center gap-2">

                    <!-- Botón Crear diagnóstico -->
                    <a href="{{ route('diagnosticos.create', [$consulta->paciente->id, $consulta->id]) }}"
                       class="btn btn-primary btn-sm px-3 shadow-sm d-inline-flex align-items-center gap-1"
                       title="Crear diagnóstico">
                        <i class="bi bi-journal-medical"></i> Crear diagnóstico
                    </a>

                    <!-- Menú desplegable Opciones -->
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownOpciones" data-bs-toggle="dropdown" aria-expanded="false">
                            Opciones
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownOpciones">
                            <li>
                                <a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">
                                    Editar Diagnostico
                                </a>
                            </li>

                            <li>
                            <a href="{{ route('recetas.create', $consulta->id) }}">Crear receta médica</a>

                            </li>
                            <li>
                                <a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">
                                    Crear Orden de Examen
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>


            <div class="card-body px-4 py-3">

                <!-- Datos Básicos (Paciente) -->
                <div class="section-title">Datos Básicos</div>
                <div class="detalle-grid detalle-basicos">
                    <p><strong>Nombre:</strong><br>{{ $consulta->paciente->nombre ?? '' }}</p>
                    <p><strong>Apellidos:</strong><br>{{ $consulta->paciente->apellidos ?? '' }}</p>
                    <p><strong>Identidad:</strong><br>{{ $consulta->paciente->identidad ?? 'No disponible' }}</p>

                    <p><strong>Fecha de Nacimiento:</strong><br>{{ $consulta->paciente->fecha_nacimiento ? \Carbon\Carbon::parse($consulta->paciente->fecha_nacimiento)->format('d/m/Y') : 'No especificado' }}</p>
                    <p><strong>Teléfono:</strong><br>{{ $consulta->paciente->telefono ?? 'No especificado' }}</p>
                    <p><strong>Correo:</strong><br>{{ $consulta->paciente->correo ?? 'No especificado' }}</p>

                    <p>
                        <strong>Género:</strong><br>
                        <span class="badge
                        {{ ($consulta->paciente->genero ?? '') === 'Masculino' ? 'bg-primary' :
                            (($consulta->paciente->genero ?? '') === 'Femenino' ? 'bg-warning text-dark' : 'bg-info') }}">
                        {{ $consulta->paciente->genero ?? 'No especificado' }}
                    </span>
                    </p>
                    <p><strong>Tipo de Sangre:</strong><br>{{ $consulta->paciente->tipo_sangre ?? 'No especificado' }}</p>
                    <p><strong>Dirección:</strong><br><span style="white-space: pre-line;">{{ $consulta->paciente->direccion ?? 'No especificado' }}</span></p>
                </div>

                <!-- Detalles de la Consulta -->
                <div class="section-title">Detalles de la Consulta</div>
                <div class="detalle-grid detalle-consulta">
                    <p><strong>Médico:</strong><br>{{ $consulta->medico->nombre ?? '' }} {{ $consulta->medico->apellidos ?? '' }}</p>
                    <p><strong>Especialidad:</strong><br>{{ $consulta->medico->especialidad ?? 'No especificada' }}</p>
                    <p><strong>Fecha Consulta:</strong><br>{{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</p>
                    <p><strong>Hora:</strong><br>{{ $consulta->hora ? \Carbon\Carbon::parse($consulta->hora)->format('g:i A') : 'Inmediata' }}</p>
                    <p>
                        <strong>Estado:</strong><br>
                        @php
                            $estado = strtolower($consulta->estado);
                            $claseEstado = $estado === 'realizado' ? 'bg-success' :
                                           ($estado === 'pendiente' ? 'bg-warning text-dark' :
                                           ($estado === 'cancelado' ? 'bg-danger' : 'bg-secondary'));
                        @endphp
                        <span class="badge {{ $claseEstado }}">{{ ucfirst($estado) }}</span>
                    </p>
                </div>

                <!-- Datos Médicos -->
                <div class="section-title">Datos Médicos</div>
                <div class="detalle-grid detalle-medicos">
                    <p><strong>Padecimientos:</strong><br><span style="white-space: pre-line;">{{ $consulta->paciente->padecimientos ?? 'Sin información.' }}</span></p>
                    <p><strong>Medicamentos:</strong><br><span style="white-space: pre-line;">{{ $consulta->paciente->medicamentos ?? 'Sin información.' }}</span></p>
                    <p><strong>Historial Clínico:</strong><br><span style="white-space: pre-line;">{{ $consulta->paciente->historial_clinico ?? 'Sin información.' }}</span></p>
                    <p><strong>Alergias:</strong><br><span style="white-space: pre-line;">{{ $consulta->paciente->alergias ?? 'Sin información.' }}</span></p>
                    <p><strong>Observaciones:</strong><br><span style="white-space: pre-line;">{{ $consulta->observaciones ?? 'Sin observaciones.' }}</span></p>
                </div>

                <div class="text-center pt-4">
                    <a href="{{ route('consultas.index') }}" class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
