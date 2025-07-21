@extends('layouts.app')

@section('content')
    <div class="content-wrapper d-flex justify-content-center p-2">
        <div class="card shadow-sm" style="max-width: 1000px; width: 100%;"> {{-- ← Aumentado el ancho --}}
            <div class="card-header d-flex align-items-center gap-2 py-2 px-3">
                <img src="{{ asset('images/logo2.jpg') }}" alt="Logo" style="height: 40px;">
                <div>
                    <h6 class="mb-0">Laboratorio Clínico Honduras</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">Orden de Exámenes</small>
                </div>
            </div>

            <div class="card-body p-2">
                {{-- Datos del paciente --}}
                <h6 class="border-bottom pb-1 mb-2" style="font-size: 0.9rem;">Datos del Paciente</h6>
                <div class="row gx-1 gy-1 mb-2" style="font-size: 0.85rem;">
                    <div class="col-12 col-md-4"><strong>Nombre:</strong> {{ $paciente->nombre }} {{ $paciente->apellidos }}</div>
                    <div class="col-6 col-md-4"><strong>Identidad:</strong> {{ $paciente->identidad }}</div>
                    <div class="col-6 col-md-4"><strong>Edad:</strong> {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años</div>
                </div>
                <div class="row gx-1 gy-1 mb-3" style="font-size: 0.85rem;">
                    <div class="col-6 col-md-4"><strong>Fecha Consulta:</strong> {{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</div>
                    <div class="col-6 col-md-8"><strong>Médico:</strong> {{ $consulta->medico->nombre ?? '' }} {{ $consulta->medico->apellidos ?? '' }}</div>
                </div>

                {{-- Exámenes seleccionados --}}
                <h6 class="border-bottom pb-1 mb-2" style="font-size: 0.9rem;">Exámenes Solicitados</h6>
                @php
                    $secciones = [
                        'HEMATOLOGÍA', 'HORMONAS', 'ORINA Y FLUIDOS', 'BIOQUÍMICOS',
                        'MARCADORES TUMORALES', 'PERFIL DE ANEMIA', 'PERFIL DIABETES',
                        'INMUNOLOGÍA Y AUTOINMUNIDAD', 'INFECCIOSOS'
                    ];
                @endphp

                @foreach ($secciones as $seccion)
                    @php
                        $examenesFiltrados = collect($examenesSeleccionados)
                            ->filter(fn($e) => strtoupper($e['seccion']) === strtoupper($seccion));
                    @endphp

                    @if ($examenesFiltrados->isNotEmpty())
                        <div class="mb-1">
                            <h6 class="text-primary fw-semibold mb-1" style="font-size: 0.85rem;">{{ $seccion }}</h6>
                            <ul class="list-unstyled small mb-0" style="font-size: 0.8rem; padding-left: 15px;">
                                @foreach ($examenesFiltrados as $examen)
                                    <li>• {{ ucwords(str_replace('_', ' ', $examen['nombre'])) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach

                {{-- Lista de órdenes anteriores --}}
                <hr>
                <h6 class="border-bottom pb-1 mb-2" style="font-size: 0.9rem;">Órdenes anteriores del paciente</h6>

                @if ($ordenesPaciente->isEmpty())
                    <p class="text-muted" style="font-size: 0.85rem;">No hay órdenes de exámenes anteriores.</p>
                @else
                    <ul class="list-group" style="max-height: 200px; overflow-y: auto; font-size: 0.85rem;">
                        @foreach ($ordenesPaciente as $orden)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    Consulta: {{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }} - {{ $orden->especialidad }}
                                </div>
                                @if ($orden->diagnostico)
                                    <a href="{{ route('examenes.show', $orden->diagnostico->id) }}" class="btn btn-sm btn-primary">
                                        Ver
                                    </a>
                                @else
                                    <span class="text-muted small">Sin diagnóstico</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="text-center mt-3">
                    <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-secondary px-3 py-1">Volver</a>
                </div>
            </div>
        </div>
    </div>
@endsection
