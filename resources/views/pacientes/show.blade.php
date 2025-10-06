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

        /* Estilos para impresión - Adaptados para expediente médico */
        @media print {
            /* Ocultar elementos innecesarios */
            .dropdown,
            .btn-imprimir,
            .text-center.pt-4,
            .table .btn {
                display: none !important;
            }
            
            /* Configuración básica */
            @page {
                margin: 2cm;
                size: A4;
            }
            
            body {
                margin: 0 !important;
                padding: 0 !important;
                font-family: Arial, sans-serif !important;
                font-size: 11pt !important;
                line-height: 1.4 !important;
                color: #000 !important;
                background: white !important;
            }
            
            .container {
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            
            .custom-card {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                background: white !important;
            }
            
            /* Header elegante */
            .card-header, .card-header-flex {
                background: #0d6efd !important;
                color: white !important;
                padding: 20px !important;
                text-align: center !important;
                margin-bottom: 25px !important;
                border-radius: 8px !important;
                -webkit-print-color-adjust: exact;
            }
            
            .card-header h5, .card-header-flex h5 {
                font-size: 22pt !important;
                font-weight: bold !important;
                margin: 0 !important;
                text-transform: uppercase !important;
                letter-spacing: 1px !important;
                color: white !important;
            }
            
            /* Información de la clínica en el header */
            .card-header::before, .card-header-flex::before {
                content: "CLINITEK - EXPEDIENTE MÉDICO";
                display: block;
                font-size: 10pt;
                margin-bottom: 5px;
                opacity: 0.9;
            }
            
            .card-body {
                padding: 0 !important;
            }
            
            /* Títulos de sección mejorados */
            .section-title {
                background: #f0f8ff !important;
                color: #0d6efd !important;
                padding: 12px 15px !important;
                margin: 20px 0 15px 0 !important;
                border-left: 4px solid #0d6efd !important;
                font-size: 14pt !important;
                font-weight: bold !important;
                text-transform: uppercase !important;
                -webkit-print-color-adjust: exact;
                page-break-after: avoid;
            }
            
            /* Datos personales en grid */
            .row.gy-3 {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 12px !important;
                margin-bottom: 20px !important;
            }
            
            .row.gy-3 > div {
                background: #f8f9fa !important;
                padding: 10px !important;
                border-radius: 4px !important;
                border-left: 2px solid #0d6efd !important;
                font-size: 10pt !important;
                -webkit-print-color-adjust: exact;
            }
            
            .row.gy-3 > div strong {
                color: #0d6efd !important;
                font-weight: bold !important;
                font-size: 9pt !important;
                text-transform: uppercase !important;
                display: block !important;
                margin-bottom: 3px !important;
            }
            
            /* Contenedores de información médica */
            .detalle-paciente {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 15px !important;
                margin-bottom: 25px !important;
            }
            
            .detalle-paciente > div {
                background: white !important;
                border: 1px solid #dee2e6 !important;
                border-radius: 6px !important;
                padding: 15px !important;
                border-left: 3px solid #0d6efd !important;
                -webkit-print-color-adjust: exact;
                page-break-inside: avoid;
            }
            
            .detalle-paciente > div strong {
                color: #0d6efd !important;
                font-weight: bold !important;
                font-size: 11pt !important;
                text-transform: uppercase !important;
                display: block !important;
                margin-bottom: 8px !important;
            }
            
            .detalle-paciente p {
                font-size: 10pt !important;
                line-height: 1.3 !important;
                margin: 0 !important;
            }
            
            /* Badges mejorados */
            .badge {
                padding: 4px 8px !important;
                border-radius: 12px !important;
                font-size: 9pt !important;
                font-weight: bold !important;
                -webkit-print-color-adjust: exact;
            }
            
            .bg-primary {
                background: #0d6efd !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
            }
            
            .bg-warning {
                background: #ffc107 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
            }
            
            .bg-info {
                background: #0dcaf0 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
            }
            
            /* Tabla de consultas */
            .table-responsive {
                margin-top: 15px !important;
            }
            
            .table {
                font-size: 9pt !important;
                width: 100% !important;
                border-collapse: collapse !important;
            }
            
            .table th, .table td {
                border: 1px solid #dee2e6 !important;
                padding: 8px !important;
                text-align: center !important;
            }
            
            .table-primary th {
                background: #b6d7ff !important;
                color: #000 !important;
                font-weight: bold !important;
                -webkit-print-color-adjust: exact;
            }
            
            /* Ocultar botones de la tabla */
            .table .btn, .table .bi {
                display: none !important;
            }
            
            /* Mostrar solo texto en las celdas de opciones */
            .table td:last-child::after {
                content: "Ver en sistema";
                font-size: 8pt;
                color: #6c757d;
                font-style: italic;
            }
            
            /* Logo de fondo muy sutil */
            .custom-card::before {
                opacity: 0.02 !important;
            }
            
            /* Primera sección */
            .section-title:first-of-type {
                margin-top: 5px !important;
            }
            
            /* Footer simple */
            .card-body::after {
                content: "Expediente generado por Sistema Clinitek";
                display: block;
                margin-top: 25px;
                padding-top: 10px;
                border-top: 1px solid #dee2e6;
                text-align: center;
                font-size: 8pt;
                color: #6c757d;
                font-style: italic;
            }
        }
        /* ========================================
   ESTILOS DE IMPRESIÓN (copiados de consultas)
   ======================================== */
@media print {
    .fixed-top, .dropdown, .btn-imprimir,
    .d-flex.justify-content-center.gap-3.mb-4,
    .text-center.pt-4, #mensaje-error-orden,
    .alert, .bi, button, a.btn {
        display: none !important;
    }

    .custom-card::before {
        display: none !important;
        content: none !important;
    }

    .print-header { display: block !important; }

    .print-header .clinitek-text {
        color: #6c757d !important;
        font-size: 22pt !important;
        font-weight: 800 !important;
        font-family: 'Impact', 'Arial Black', sans-serif !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        display: inline-block !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    @page { margin: 1.2cm 0.5cm 1.5cm 0.5cm; size: A4; }

    body {
        margin: 0 !important;
        padding: 0 !important;
        font-family: Arial, sans-serif !important;
        font-size: 10pt !important;
        line-height: 1.3 !important;
        color: #000 !important;
        background: white !important;
    }

    .container, .custom-card {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 10px !important;
        box-shadow: none !important;
        border: none !important;
        background: white !important;
        border-radius: 0 !important;
    }

    .card-body { padding: 0 !important; }

    .card-header-flex {
        background: white !important;
        color: #0d6efd !important;
        padding: 10px 0 !important;
        margin-bottom: 12px !important;
        text-align: center !important;
        border: none !important; 
        display: block !important;
    }

    .card-header-flex h5 {
        font-size: 16pt !important;
        font-weight: bold !important;
        color: #000 !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
    }

    .section-title {
        color: #0d6efd !important;
        font-size: 11pt !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
        border-bottom: 2px solid #0d6efd !important;
        padding-bottom: 3px !important;
        margin-top: 12px !important;
        margin-bottom: 8px !important;
    }

    .detalle-grid {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 8px 15px !important;
        background: none !important;
        border: none !important;
        padding: 0 !important;
        margin-bottom: 10px !important;
    }

    .detalle-grid p {
        font-size: 9pt !important;
        margin: 0 !important;
    }

    .detalle-medicos { grid-template-columns: 1fr !important; }

    .detalle-grid p strong {
        color: #0d6efd !important;
        font-weight: bold !important;
        font-size: 8pt !important;
        text-transform: uppercase !important;
    }

    .badge {
        padding: 2px 6px !important;
        font-size: 8pt !important;
        border-radius: 3px !important;
        font-weight: bold !important;
    }

    .bg-success { background: #28a745 !important; color: white !important; }
    .bg-warning { background: #ffc107 !important; color: #000 !important; }
    .bg-danger  { background: #dc3545 !important; color: white !important; }

    .card-body::after {
        content: "Sistema Clinitek";
        display: block !important;
        text-align: center !important;
        font-size: 7pt !important;
        color: #999 !important;
        border-top: 1px solid #ddd !important;
        padding-top: 6px !important;
        margin-top: 12px !important;
    }
}

/* Encabezado Clinitek */
.clinitek-header {
    display: none;
}

@media print {
    .clinitek-header {
        display: flex !important;
        align-items: center;
        justify-content: flex-start;
        gap: 8px;
        margin-bottom: 10px;
        padding-left: 10px;
    }

    .clinitek-logo {
        width: 60px;
        height: 50px;
    }

    .clinitek-text {
        font-family: 'Poppins', 'Arial', sans-serif !important;
        font-size: 21pt !important;
        font-weight: 700 !important;
        color: #8b8fa8 !important;
        letter-spacing: 0.4px !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
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