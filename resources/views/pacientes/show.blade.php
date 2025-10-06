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
        gap: 2rem;
        flex-wrap: wrap;
    }

    .detalle-paciente > div {
        flex: 1 1 250px;
        min-width: 250px;
        word-wrap: break-word;
        white-space: normal;
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        border: 1.5px solid #ced4da;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 1rem;
        border-bottom: 3px solid #0d6efd;
        padding-bottom: 0.25rem;
    }

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

    @media print {
        /* Ocultar botones o elementos innecesarios */
        .btn, .btn-imprimir, nav, .fixed-top, .alert, .acciones,
        .dropdown, a, button, .text-center.pt-4 {
            display: none !important;
        }

        /* Configuración general */
        @page { size: A4; margin: 1.5cm 1.8cm; }
        body {
            font-family: Arial, sans-serif !important;
            font-size: 11pt !important;
            color: #000 !important;
            background: #fff !important;
        }

        .container, .custom-card {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: #fff !important;
        }

        /* Encabezado Clinitek */
        .print-header {
            display: flex !important;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            margin-bottom: 10px;
        }
        .print-header img {
            width: 100px !important;
            height: auto !important;
        }
        .print-header h1 {
            font-family: 'Poppins', sans-serif !important;
            font-size: 22pt !important;
            font-weight: 700 !important;
            color: #8b8fa8 !important;
            margin: 0 !important;
        }

        /* Título del reporte */
        .titulo-reporte {
            text-align: center !important;
            font-size: 14pt !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            margin: 10px 0 15px 0 !important;
            color: #000 !important;
        }

        /* Secciones */
        .section-title {
            color: #004AAD !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            border-bottom: 2px solid #004AAD !important;
            margin-top: 15px !important;
            margin-bottom: 5px !important;
            font-size: 11pt !important;
        }

        /* Datos del paciente */
        .row.gy-3 {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            column-gap: 20px !important;
            row-gap: 4px !important;
        }

        .row.gy-3 strong {
            color: #004AAD !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            font-size: 10pt !important;
        }

        /* Cuadros informativos */
        .info-box {
            border: 1px solid #004AAD !important;
            border-radius: 4px !important;
            padding: 8px 10px !important;
            margin-bottom: 8px !important;
            page-break-inside: avoid !important;
        }

        /* Tablas */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 8px !important;
            font-size: 10pt !important;
        }
        th, td {
            border: 1px solid #004AAD !important;
            padding: 6px 8px !important;
            text-align: left !important;
        }
        th {
            background: #b6d7ff !important;
            color: #000 !important;
            font-weight: bold !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Quitar logo de fondo */
        .custom-card::before {
            opacity: 0.02 !important;
        }

        body {
            margin-bottom: 40px !important;
        }
    }
    @page {
        margin-bottom: 2cm;
    }

  /*  body::after {
        content: "© 2025 Clínitek. Todos los derechos reservados.";
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 9pt;
        color: #555;
        font-family: Arial, sans-serif;
    }*/

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

        <!-- Encabezado Clinitek para impresión -->
        <div class="print-header d-none d-print-flex align-items-center mb-3" style="gap:12px;">
            <img src="/images/Barra.png" alt="Logo Clinitek" style="width:180px;height:auto;">
            <h1 class="m-0" style="font-family:'Poppins',sans-serif;font-size:22pt;font-weight:700;color:#8b8fa8;">
                Clinitek
            </h1>
        </div>

        <div class="card-header-flex">
            <h5>Expediente médico</h5>
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
                                <a href="{{ $consulta->diagnostico ? route('diagnosticos.show', ['diagnostico' => $consulta->diagnostico->id, 'origen' => 'pacientes.show', 'paciente_id' => $paciente->id]) : '#' }}"
                                   class="btn btn-sm btn-outline-primary shadow-sm"
                                   title="Ver diagnóstico"
                                   @if(!$consulta->diagnostico) onclick="alert('No hay diagnóstico disponible'); return false;" @endif>
                                    <i class="bi bi-journal-medical"></i>
                                </a>

                                <a href="{{ route('recetas.show', ['id' => $consulta->id, 'origen' => 'pacientes.show', 'paciente_id' => $paciente->id]) }}"
                                   class="btn btn-sm btn-outline-success"
                                   title="Ver receta médica">
                                    <i class="bi bi-prescription2"></i>
                                </a>

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
