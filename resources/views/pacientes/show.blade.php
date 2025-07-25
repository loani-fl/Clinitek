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

        .detalle-paciente {
            display: flex;
            gap: 2rem; /* espacio entre columnas */
            flex-wrap: wrap; /* que se acomode en varias filas si no cabe */
        }

        .detalle-paciente > div {
            flex: 1 1 250px; /* ancho flexible mínimo 250px */
            min-width: 250px;
            word-wrap: break-word; /* rompe palabras largas */
            white-space: normal; /* permite el salto de línea */
            padding: 0.75rem;
            background-color: #f8f9fa; /* un fondo claro para separar */
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            border: 1.5px solid #ced4da; /* borde gris claro estilo Bootstrap */
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 1rem;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 0.25rem;
        }
    </style>

    {{-- Menú desplegable estilo Bootstrap --}}
    <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            ☰
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="{{ route('puestos.create') }}">Crear puesto</a></li>
            <li><a class="dropdown-item" href="{{ route('empleado.create') }}">Registrar empleado</a></li>
            <li><a class="dropdown-item" href="{{ route('medicos.create') }}">Registrar médico</a></li>
        </ul>
    </div>
</div>
    <!-- Contenedor principal -->
    <div class="container mt-5 pt-3" style="max-width: 1000px;">
        <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
        <div class="card-header d-flex justify-content-between align-items-center py-2 px-3" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
    <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Expediente medico</h5>


            <a href="{{ route('diagnosticos.show', $paciente->id ?? 1) }}"
               class="btn btn-primary"
               style="font-size: 1rem;">
                Ver Diagnósticos
            </a>
</div>

            <div class="card-body px-4 py-3">

                <!-- Título Datos Personales -->
                <div class="section-title">Datos Personales</div>

                <div class="row gy-3 mb-4">
                    <div class="col-md-3"><strong>Nombre:</strong><br>{{ $paciente->nombre }}</div>
                    <div class="col-md-3"><strong>Apellidos:</strong><br>{{ $paciente->apellidos }}</div>
                    <div class="col-md-3"><strong>Identidad:</strong><br>{{ $paciente->identidad }}</div>
                    <div class="col-md-3"><strong>Fecha de Nacimiento:</strong><br>{{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') : 'No especificado' }}</div>

                    <div class="col-md-3"><strong>Teléfono:</strong><br>{{ $paciente->telefono ?? 'No especificado' }}</div>
                    <div class="col-md-3">
                        <strong>Género:</strong><br>
                        <span class="badge
                        {{ $paciente->genero === 'Masculino' ? 'bg-primary' :
                           ($paciente->genero === 'Femenino' ? 'bg-warning text-dark' : 'bg-info') }}">
                        {{ $paciente->genero ?? 'No especificado' }}
                    </span>
                    </div>
                    <div class="col-md-3"><strong>Correo:</strong><br>{{ $paciente->correo ?? 'No especificado' }}</div>
                    <div class="col-md-3"><strong>Tipo de Sangre:</strong><br>{{ $paciente->tipo_sangre ?? 'No especificado' }}</div>

                    <div class="col-md-3"><strong>Dirección:</strong><br><span style="white-space: pre-line;">{{ $paciente->direccion ?? 'No especificado' }}</span></div>
                </div>

                <!-- Título Datos Médicos -->
                <div class="section-title">Datos Médicos</div>

                <div class="detalle-paciente">
                    <div>
                        <strong>Padecimientos:</strong><br>
                        <p class="text-break" style="white-space: pre-line;">{!! nl2br(e($paciente->padecimientos ?? 'No especificado')) !!}</p>
                    </div>
                    <div>
                        <strong>Medicamentos:</strong><br>
                        <p class="text-break" style="white-space: pre-line;">{!! nl2br(e($paciente->medicamentos ?? 'No especificado')) !!}</p>
                    </div>
                    <div>
                        <strong>Historial Clínico:</strong><br>
                        <p class="text-break" style="white-space: pre-line;">{!! nl2br(e($paciente->historial_clinico ?? 'No especificado')) !!}</p>
                    </div>
                    <div>
                        <strong>Alergias:</strong><br>
                        <p class="text-break" style="white-space: pre-line;">{!! nl2br(e($paciente->alergias ?? 'No especificado')) !!}</p>
                    </div>
                    <div>
                        <strong>Historial Quirúrgico:</strong><br>
                        <p class="text-break" style="white-space: pre-line;">{!! nl2br(e($paciente->historial_quirurgico ?? 'No especificado')) !!}</p>
                    </div>
                </div>





            </div>

                <div class="text-center pt-4">
                    <a href="{{ route('pacientes.index') }}"
                       class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2"
                       style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
