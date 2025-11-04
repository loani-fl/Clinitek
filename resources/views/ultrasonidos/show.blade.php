@extends('layouts.app')

@section('title', 'Resultados de Ultrasonido')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <style>
        /* Estilos visuales */
        .custom-card::before {
            content: "";
            position: absolute;
            top: 50%; left: 50%;
            width: 800px; height: 800px;
            background-image: url('/images/logo2.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.12;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }
        .custom-card {
            max-width: 1000px;
            background-color: #fff;
            border: 1px solid #91cfff;
            border-radius: 12px;
            margin: 1.5rem auto;
            padding: 1.2rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .info-label { font-weight: 700; font-size: 1rem; color: #003366; display:block; }
        .info-value { font-size: 1.05rem; color: #222; margin-top: 4px; }
        .info-block { padding: 6px 8px; }
        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 0.6rem;
            color: #0b5ed7;
            border-bottom: 2px solid #0b5ed7;
            padding-bottom: 4px;
        }
        .examen-card {
            margin-top: 1.5rem;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            background-color: #f7f9fc;
        }
        .examen-card h4 {
            color: #004080;
            font-weight: 700;
            margin-bottom: 1rem;
            border-bottom: 1px dashed #007BFF;
            padding-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        .imagenes-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: flex-start;
        }
        .imagen-block {
            flex: 0 0 calc(33.33% - 1rem);
            min-width: 280px;
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            overflow: hidden;
            padding: 0.75rem;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .imagen-block:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .imagen-block img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 0.3rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
        }
        .imagen-block .descripcion-text {
            font-size: 0.85rem;
            margin: 0;
            padding: 0.5rem 0;
            color: #555;
            white-space: pre-wrap;
            background-color: #f8f8f8;
            padding: 8px;
            border-radius: 4px;
        }
    </style>

    <div class="container mt-3">
        <div class="card custom-card shadow-sm border rounded-4">

            <div class="card-header py-3" style="background-color:#fff;">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fw-bold text-dark">Resultados de Ultrasonido</h3>

                    <!-- Botón de Reporte -->
                    <button onclick="window.print()"
                            class="btn btn-warning btn-sm d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-printer"></i> Imprimir reporte
                    </button>

                </div>
            </div>

            <!-- Línea azul -->
            <div style="height:4px; background:#0d6efd; width:100%;"></div>

            <div class="card-body px-3 py-3">

            {{-- Datos de la Orden y Paciente --}}
                <div class="row gy-2">
                    <div class="col-12"><div class="section-title">Información de la Orden</div></div>

                    <div class="col-md-4 info-block">
                        <span class="info-label">Paciente:</span>
                        <div class="info-value">
                            {{ $orden->paciente?->nombre ?? 'Sin paciente' }}
                            {{ $orden->paciente?->apellidos ?? '' }}
                        </div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Identidad:</span>
                        <div class="info-value">
                            {{ $orden->paciente?->identidad ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Fecha de Orden:</span>
                        <div class="info-value">
                            {{ optional($orden->created_at)->format('d/m/Y h:i A') ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-12 info-block">
                        <span class="info-label">Médico analista:</span>
                        <div class="info-value">
                            {{ $orden->medico?->nombre ?? 'No asignado' }}
                            {{ $orden->medico?->apellidos ?? '' }}
                        </div>
                    </div>
                </div>

                {{-- Mostrar imágenes --}}
                @if($orden->imagenes->isEmpty())
                    <div class="alert alert-info text-center mt-3">
                        Este paciente aún no tiene análisis de ultrasonido registrados.
                    </div>
                @else
                    @foreach($examenesKeys as $examenKey)
                        @php
                            $nombreExamen = $mapaNombres[$examenKey] ?? ucfirst($examenKey);
                            $imagenesDelExamen = $imagenesAgrupadas[$examenKey] ?? collect();
                        @endphp

                        @if($imagenesDelExamen->isNotEmpty())
                            <div class="examen-card">
                                <h4>{{ $nombreExamen }}</h4>
                                <div class="imagenes-container">
                                    @foreach($imagenesDelExamen as $imagen)
                                        <div class="imagen-block"
                                             data-bs-toggle="modal"
                                             data-bs-target="#imagenModal"
                                             data-ruta="{{ asset('storage/' . $imagen->ruta) }}"
                                             data-descripcion="{{ $imagen->descripcion }}">

                                            <img src="{{ asset('storage/' . $imagen->ruta) }}"
                                                 alt="Imagen de {{ $nombreExamen }}"
                                                 title="Haga clic para ampliar">

                                            <span class="info-label" style="font-size:0.9rem; color:#007BFF;">Descripción:</span>
                                            <p class="descripcion-text">{{ $imagen->descripcion ?? 'Sin descripción.' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                {{-- Botón de regreso --}}
                <div class="text-center pt-4">
                    <a href="{{ route('ultrasonidos.index') }}" class="btn btn-success px-4 shadow-sm">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PARA AMPLIAR IMAGEN --}}
    <div class="modal fade" id="imagenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Detalle de Imagen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="modalImagenAmpliada" src="" class="img-fluid" style="max-height: 80vh; width: auto; object-fit: contain;">
                </div>
                <div class="modal-footer justify-content-start">
                    <strong class="info-label">Descripción:</strong>
                    <p id="modalDescripcion" class="mb-0 text-break" style="font-size:0.95rem;"></p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const imagenModal = document.getElementById('imagenModal');
                const modalImagenAmpliada = document.getElementById('modalImagenAmpliada');
                const modalDescripcion = document.getElementById('modalDescripcion');

                imagenModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const ruta = button.getAttribute('data-ruta');
                    const descripcion = button.getAttribute('data-descripcion');

                    modalImagenAmpliada.src = ruta;
                    modalDescripcion.textContent = descripcion || 'Sin descripción proporcionada.';
                });
            });
        </script>


    @endpush

@endsection
