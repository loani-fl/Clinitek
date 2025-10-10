@extends('layouts.app')

@section('content')
    <style>
        /* ================================
           Estilos generales (idénticos al show anterior)
           ================================ */
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
        .detalle-basicos { grid-template-columns: repeat(3, 1fr); }
        .detalle-consulta { grid-template-columns: repeat(5, 1fr); }
        .detalle-medicos { grid-template-columns: repeat(2, 1fr); }
        .detalle-grid p { margin: 0; overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; }
        .detalle-grid p span { display: inline-block; max-width: 100%; word-break: break-word; }
        .badge { font-size: 0.85rem; padding: 0.4em 0.6em; border-radius: 0.35rem; }
        .section-title { font-size: 1.5rem; font-weight: 700; color: #0d6efd; margin-bottom: 1rem; border-bottom: 3px solid #0d6efd; padding-bottom: 0.25rem; }
        .card-header-flex { display: flex; flex-direction: column; align-items: flex-start; gap: 0.5rem; background-color: #fff; border-bottom: 4px solid #0d6efd; padding: 0.75rem 1.5rem; }
        .card-header-flex h5 { font-size: 2.25rem; font-weight: 700; margin: 0; color: #212529; }
        .btn-imprimir { background-color: #ffc107; border-color: #ffc107; color: #000; font-weight: 600; }
        .btn-imprimir:hover { background-color: #ffb700; border-color: #ffb700; color: #000; }
        @media print {
            .fixed-top, .dropdown, .btn-imprimir { display: none !important; }
            .custom-card::before { display: none !important; content: none !important; }
            .print-header { display: block !important; }
            @page { margin: 1.2cm 0.5cm 1.5cm 0.5cm; size: A4; }
            body { margin: 0 !important; padding: 0 !important; font-family: Arial, sans-serif !important; font-size: 10pt !important; line-height: 1.3 !important; color: #000 !important; background: white !important; }
            .container, .custom-card { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 10px !important; box-shadow: none !important; border: none !important; background: white !important; border-radius: 0 !important; }
        }
    </style>

    <div class="container mt-5 pt-3" style="max-width: 1000px;">
        <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
            <div class="card-header-flex">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h5>Detalles de la Sesión Psicológica</h5>

                </div>
            </div>

            <div class="card-body px-4 py-3">
                <!-- Datos Básicos del Paciente -->
                <div class="section-title">Datos Básicos</div>
                <div class="detalle-grid detalle-basicos">
                    <p><strong>Nombre:</strong><br>{{ $sesion->paciente->nombre ?? '' }}</p>
                    <p><strong>Apellidos:</strong><br>{{ $sesion->paciente->apellidos ?? '' }}</p>
                    <p><strong>Edad:</strong><br>
                        {{ $sesion->paciente->fecha_nacimiento ? \Carbon\Carbon::parse($sesion->paciente->fecha_nacimiento)->age . ' años' : '' }}
                    </p>
                    <p><strong>Género:</strong><br>{{ $sesion->paciente->genero ?? '' }}</p>
                    <p><strong>Teléfono:</strong><br>{{ $sesion->paciente->telefono ?? '' }}</p>
                </div>

                <!-- Detalles de la Sesión -->
                <div class="section-title">Detalles de la Sesión</div>
                <div class="detalle-grid detalle-consulta">
                    <p><strong>Médico:</strong><br>{{ $sesion->medico->nombre ?? '' }}</p>
                    <p><strong>Fecha:</strong><br>{{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}</p>
                    <p><strong>Hora Inicio:</strong><br>{{ $sesion->hora_inicio }}</p>
                    <p><strong>Hora Fin:</strong><br>{{ $sesion->hora_fin }}</p>
                    <p><strong>Tipo Examen:</strong><br>{{ $sesion->tipo_examen }}</p>
                </div>

                <!-- Información Médica -->
                <div class="section-title">Información Médica</div>
                <div class="detalle-grid detalle-medicos">
                    <p><strong>Motivo de Consulta:</strong><br><span style="white-space: pre-line;">{{ $sesion->motivo_consulta ?? 'Sin información.' }}</span></p>
                    <p><strong>Resultado:</strong><br><span style="white-space: pre-line;">{{ $sesion->resultado ?? 'Sin información.' }}</span></p>
                    <p><strong>Observaciones:</strong><br><span style="white-space: pre-line;">{{ $sesion->observaciones ?? 'Sin observaciones.' }}</span></p>

                    <!-- Archivo Resultado -->
                    <p><strong>Archivo Resultado:</strong><br>
                        @if(!empty($sesion->archivo_resultado) && file_exists(storage_path('app/public/'.$sesion->archivo_resultado)))
                            <a href="{{ asset('storage/'.$sesion->archivo_resultado) }}" target="_blank" class="btn btn-sm btn-warning shadow-sm">
                                <i class="fas fa-file-alt"></i> Ver Archivo
                            </a>
                        @else
                            <a href="#" class="btn btn-sm btn-secondary disabled" tabindex="-1" aria-disabled="true">
                                No hay archivo disponible
                            </a>
                        @endif
                    </p>


                    <div class="text-center pt-4">
                    <a href="{{ route('sesiones.index') }}" class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
