@extends('layouts.app')

@section('content')
<style>
    /* ================================
       Estilos generales
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

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 1rem;
        border-bottom: 3px solid #0d6efd;
        padding-bottom: 0.25rem;
    }

    .card-header-flex {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
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

    /* ================================
       Ajustes solo para impresión
       ================================ */
    @media print {
        .fixed-top, .dropdown, .btn-imprimir, .btn-success { display: none !important; }
        .custom-card::before { display: none !important; content: none !important; }

        @page { margin: 0.8cm 0.4cm 0.8cm 0.4cm; size: A4; }

        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: Arial, sans-serif !important;
            font-size: 9.5pt !important;
            line-height: 1.15 !important;
            color: #000 !important;
            background: white !important;
        }

        .container, .custom-card {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 8px !important;
            box-shadow: none !important;
            border: none !important;
            background: white !important;
            border-radius: 0 !important;
        }

        .detalle-grid {
            gap: 0.6rem !important;
            padding: 0.4rem 0.6rem !important;
            border-width: 1px !important;
        }

        .section-title {
            font-size: 1.2rem !important;
            margin: 0.4rem 0 0.3rem 0 !important;
            padding-bottom: 0.1rem !important;
            border-bottom-width: 2px !important;
        }

        .card-header-flex {
            padding: 0.4rem 0.6rem !important;
            border-bottom-width: 2px !important;
        }

        .card-header-flex h5 {
            font-size: 1.6rem !important;
            margin-bottom: 0.3rem !important;
        }

        .detalle-grid p {
            font-size: 9pt !important;
            line-height: 1.1 !important;
        }

        /* Mostrar solo texto en lugar de botón Ver archivo */
        .ver-archivo-btn {
            display: block !important;
            background: none !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
            color: #000 !important;
            font-weight: normal !important;
        }

        .ver-archivo-btn::before {
            content: "Por favor verificar el archivo original";
            font-weight: normal !important;
            color: #000 !important;
            font-size: 9pt !important;
        }

        .ver-archivo-btn i { display: none !important; }
        .ver-archivo-btn .texto-boton { display: none !important; }
    }
</style>

<div class="container mt-5 pt-3" style="max-width: 1000px;">
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
        <div class="card-header-flex">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h5>Detalles de resultados de examenes </h5>

                <!-- Botón de Reporte -->
                <button onclick="window.print()"
                        class="btn btn-imprimir btn-sm d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-printer"></i>Imprimir reporte
                </button>
            </div>
        </div>

        <div class="card-body px-4 py-3">
            <!-- Datos Básicos del Paciente -->
            <div class="section-title">Datos básicos</div>
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
            <div class="section-title">Detalles de la sesión</div>
            <div class="detalle-grid detalle-consulta">
                <p><strong>Médico:</strong><br>{{ $sesion->medico->nombre ?? '' }}</p>
                <p><strong>Fecha:</strong><br>{{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}</p>
                <p><strong>Hora inicio:</strong><br>{{ $sesion->hora_inicio }}</p>
                <p><strong>Hora final:</strong><br>{{ $sesion->hora_fin }}</p>
                <p><strong>Tipo examen:</strong><br>{{ $sesion->tipo_examen }}</p>
            </div>

            <!-- Información Médica -->
            <div class="section-title">Información médica</div>
            <div class="detalle-grid detalle-medicos">
                <p><strong>Motivo de consulta:</strong><br>
                    <span style="white-space: pre-line;">{{ $sesion->motivo_consulta ?? 'Sin información.' }}</span></p>
                <p><strong>Resultado:</strong><br>
                    <span style="white-space: pre-line;">{{ $sesion->resultado ?? 'Sin información.' }}</span></p>
                <p><strong>Observaciones:</strong><br>
                    <span style="white-space: pre-line;">{{ $sesion->observaciones ?? 'Sin observaciones.' }}</span></p>

                <!-- Archivo Resultado -->
                <p><strong>Archivo resultado:</strong><br>
                    @if(!empty($sesion->archivo_resultado))
                        @php
                            $fileUrl = asset('storage/'.$sesion->archivo_resultado);
                            $extension = strtolower(pathinfo($sesion->archivo_resultado, PATHINFO_EXTENSION));
                        @endphp

                        <button type="button"
                                class="btn btn-sm btn-warning shadow-sm ver-archivo-btn"
                                data-url="{{ $fileUrl }}"
                                data-extension="{{ $extension }}">
                            <span class="texto-boton"><i class="bi bi-file-earmark-text"></i> Ver archivo</span>
                        </button>
                    @else
                        <a href="#" class="btn btn-sm btn-secondary disabled" tabindex="-1" aria-disabled="true">
                            No hay archivo disponible
                        </a>
                    @endif
                </p>

                <!-- Modal para mostrar imagen -->
                <div class="modal fade" id="imagenModal" tabindex="-1" aria-labelledby="imagenModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow-lg rounded-3">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="imagenModalLabel">Archivo de Resultado</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="imagenResultado" src="" alt="Archivo Imagen" class="img-fluid rounded shadow">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Botón Regresar -->
            <div class="d-flex justify-content-center pt-4">
                <a href="{{ route('sesiones.index') }}"
                   class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2"
                   style="font-size: 0.85rem;">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const botones = document.querySelectorAll('.ver-archivo-btn');
        const modal = new bootstrap.Modal(document.getElementById('imagenModal'));
        const imagen = document.getElementById('imagenResultado');

        botones.forEach(btn => {
            btn.addEventListener('click', () => {
                const url = btn.dataset.url;
                const ext = btn.dataset.extension;

                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    imagen.src = url;
                    modal.show();
                } else if (ext === 'pdf') {
                    window.open(url, '_blank');
                } else {
                    alert('Tipo de archivo no soportado para vista previa.');
                }
            });
        });
    });
</script>
@endpush

@endsection
