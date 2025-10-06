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

        /* Estilo del botón imprimir */
        .btn-imprimir {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000;
            font-weight: 600;
        }
        
        .btn-imprimir:hover {
            background-color: #ffb700;
            border-color: #ffb700;
            color: #000;
        }

        /* Header con botón */
        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            border-bottom: 4px solid #0d6efd;
            padding: 0.75rem 1.5rem;
        }

        .card-header-flex h5 {
            font-size: 2.25rem;
            font-weight: 700;
            margin: 0;
            color: #212529;
        }

        /* Estilos para impresión - Ultra compacto estilo documento formal */
        @media print {
            /* Ocultar elementos innecesarios */
            .dropdown,
            .btn-imprimir,
            .text-center.pt-4,
            .table .btn,
            .bi {
                display: none !important;
            }
            
            /* Configuración de página */
            @page {
                margin: 1.2cm;
                size: A4;
            }
            
            * {
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }
            
            body {
                font-family: Arial, sans-serif !important;
                font-size: 9pt !important;
                line-height: 1.1 !important;
                color: #000 !important;
            }
            
            .container, .custom-card {
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                border: none !important;
                background: white !important;
            }
            
            /* Header compacto */
            .card-header, .card-header-flex {
                background: #0d6efd !important;
                color: white !important;
                padding: 6px 10px !important;
                margin-bottom: 6px !important;
                text-align: center !important;
                -webkit-print-color-adjust: exact;
            }
            
            .card-header h5, .card-header-flex h5 {
                font-size: 12pt !important;
                font-weight: bold !important;
                text-transform: uppercase !important;
                color: white !important;
                letter-spacing: 0.5px !important;
            }
            
            .card-header::before, .card-header-flex::before {
                content: "CLINITEK - SISTEMA MÉDICO | ";
                font-size: 7pt !important;
            }
            
            /* Secciones solo con línea azul */
            .section-title {
                color: #0d6efd !important;
                font-size: 9pt !important;
                font-weight: bold !important;
                text-transform: uppercase !important;
                border-top: 1.5px solid #0d6efd !important;
                padding-top: 3px !important;
                padding-bottom: 2px !important;
                margin-top: 6px !important;
                margin-bottom: 3px !important;
                background: none !important;
                border-left: none !important;
                -webkit-print-color-adjust: exact;
            }
            
            /* Datos personales en tabla compacta 4 columnas */
            .row.gy-3 {
                display: block !important;
                width: 100% !important;
                margin-bottom: 6px !important;
            }
            
            .row.gy-3::after {
                content: "";
                display: table;
                clear: both;
            }
            
            .row.gy-3 > div {
                display: inline-block !important;
                width: 48% !important;
                float: left !important;
                padding: 2px 4px !important;
                font-size: 8pt !important;
                border-bottom: 1px solid #ddd !important;
                background: none !important;
                vertical-align: top !important;
            }
            
            .row.gy-3 > div:nth-child(odd) {
                margin-right: 2% !important;
            }
            
            .row.gy-3 > div strong {
                color: #0d6efd !important;
                font-weight: bold !important;
                font-size: 7pt !important;
                text-transform: uppercase !important;
            }
            
            .row.gy-3 > div strong::after {
                content: ": ";
            }
            
            .row.gy-3 > div br {
                display: none !important;
            }
            
            /* Datos médicos compactos */
            .detalle-paciente {
                display: block !important;
                margin-bottom: 6px !important;
            }
            
            .detalle-paciente > div {
                padding: 2px 0 !important;
                border-bottom: 1px solid #e0e0e0 !important;
                background: none !important;
            }
            
            .detalle-paciente > div:last-child {
                border-bottom: none !important;
            }
            
            .detalle-paciente > div strong {
                color: #0d6efd !important;
                font-weight: bold !important;
                font-size: 8pt !important;
                text-transform: uppercase !important;
            }
            
            .detalle-paciente > div strong::after {
                content: ": ";
            }
            
            .detalle-paciente > div br:first-of-type {
                display: none !important;
            }
            
            .detalle-paciente p {
                display: inline !important;
                font-size: 8pt !important;
                line-height: 1.15 !important;
            }
            
            /* Badges */
            .badge {
                padding: 1px 4px !important;
                font-size: 7pt !important;
                border-radius: 2px !important;
                -webkit-print-color-adjust: exact;
            }
            
            .bg-primary {
                background: #0d6efd !important;
                color: white !important;
            }
            
            .bg-warning {
                background: #ffc107 !important;
                color: #000 !important;
            }
            
            .bg-info {
                background: #0dcaf0 !important;
                color: #000 !important;
            }
            
            /* Tabla ultra compacta */
            .table-responsive {
                margin-top: 3px !important;
            }
            
            .table {
                width: 100% !important;
                font-size: 7pt !important;
                border-collapse: collapse !important;
            }
            
            .table th, .table td {
                border: 1px solid #ddd !important;
                padding: 2px 3px !important;
                text-align: center !important;
            }
            
            .table-primary th {
                background: #cce5ff !important;
                color: #000 !important;
                font-weight: bold !important;
                -webkit-print-color-adjust: exact;
            }
            
            .table td:last-child::after {
                content: "Sistema";
                font-size: 6pt !important;
            }
            
            /* Eliminar logo */
            .custom-card::before {
                display: none !important;
            }
            
            /* Footer mínimo */
            .card-body::after {
                content: "Clinitek";
                display: block !important;
                text-align: center !important;
                font-size: 6pt !important;
                color: #999 !important;
                border-top: 1px solid #e0e0e0 !important;
                padding-top: 3px !important;
                margin-top: 6px !important;
            }
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
            <!-- Header con título y botón -->
            <div class="card-header-flex">
                <h5>Expediente médico</h5>
                <!-- Botón Imprimir Reporte -->
                <button onclick="window.print()" class="btn btn-imprimir btn-sm d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="fas fa-print"></i> Imprimir Reporte
                </button>
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

            <!-- Consultas Registradas -->
            <div class="section-title mt-5">Consultas Registradas</div>

            @if($paciente->consultas->isEmpty())
                <p class="text-muted">No hay consultas registradas para este paciente.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-primary">
                        <tr>
                            <th>Fecha</th>
                            <th>Médico Asignado</th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($paciente->consultas as $consulta)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $consulta->medico->nombre ?? 'Sin asignar' }}</td>
                                <td>
                                    <!-- Ver Diagnóstico -->
                                    <a href="{{ $consulta->diagnostico ? route('diagnosticos.show', ['diagnostico' => $consulta->diagnostico->id, 'origen' => 'pacientes.show', 'paciente_id' => $paciente->id]) : '#' }}"
                                       class="btn btn-sm btn-outline-primary shadow-sm"
                                       title="Ver diagnóstico"
                                       @if(!$consulta->diagnostico) onclick="alert('No hay diagnóstico disponible'); return false;" @endif>
                                        <i class="bi bi-journal-medical"></i>
                                    </a>

                                    <!-- Ver Receta -->
                                    <a href="{{ route('recetas.show', ['id' => $consulta->id, 'origen' => 'pacientes.show', 'paciente_id' => $paciente->id]) }}"
                                       class="btn btn-sm btn-outline-success"
                                       title="Ver receta médica">
                                        <i class="bi bi-prescription2"></i>
                                    </a>

                                    <!-- Ver Exámenes -->
                                    <a href="{{ $consulta->diagnostico ? route('examenes.show', ['diagnostico' => $consulta->diagnostico->id, 'origen' => 'pacientes.show', 'paciente_id' => $paciente->id]) : '#' }}"
                                       class="btn btn-sm btn-outline-danger"
                                       title="Ver exámenes"
                                       @if(!$consulta->diagnostico) onclick="alert('No hay orden de examen disponible'); return false;" @endif>
                                        <i class="bi bi-file-earmark-medical"></i>
                                    </a>

                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="text-center pt-4">
                <a href="{{ route('pacientes.index') }}"
                   class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2"
                   style="font-size: 0.85rem;">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>

        </div>
        </div>

@endsection