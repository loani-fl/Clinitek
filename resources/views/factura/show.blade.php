@extends('layouts.app')

@section('title', 'Factura #' . $factura->numero_factura)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-0">
                    <!-- Header de la Factura -->
                    <div class="bg-primary text-white p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h2 class="mb-0 fw-bold d-flex align-items-center">
                                    <img src="{{ asset('images/barra.png') }}" alt="Clinitek Logo" class="me-2" style="height: 32px;">
                                    CLINITEK
                                </h2>
                                <p class="mb-0 opacity-75">Sistema de Facturación</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h3 class="mb-1">FACTURA</h3>
                                <p class="mb-0"># {{ $factura->numero_factura }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información de la Factura -->
                    <div class="p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user me-2"></i>Información del Paciente
                                    </h5>
                                    <p class="mb-2">
                                        <strong>Nombre:</strong> 
                                        <span class="ms-2">
                                            @if($factura->paciente)
                                                {{ $factura->paciente->nombre }} {{ $factura->paciente->apellidos }}
                                            @else
                                                {{ $factura->paciente_nombre }}
                                            @endif
                                        </span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Identidad:</strong> 
                                        <span class="ms-2">{{ $factura->paciente_identidad }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-calendar me-2"></i>Información de Facturación
                                    </h5>
                                    <p class="mb-2">
                                        <strong>Fecha:</strong> 
                                        <span class="ms-2">{{ date('d/m/Y', strtotime($factura->fecha)) }}</span>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Hora:</strong> 
                                        <span class="ms-2">{{ \Carbon\Carbon::parse($factura->hora)->format('h:i A') }}</span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Método de Pago:</strong>
                                        <span class="badge bg-success ms-2">
                                            {{ ucfirst(str_replace('_', ' ', $factura->metodo_pago)) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($factura->tipo === 'consulta')
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="bg-info bg-opacity-10 p-3 rounded">
                                    <h5 class="text-info mb-3">
                                        <i class="fas fa-user-md me-2"></i>Información Médica
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-0">
                                                <strong>Médico:</strong> 
                                                <span class="ms-2">
                                                    @if($factura->medico)
                                                        {{ $factura->medico->nombre }} {{ $factura->medico->apellidos }}
                                                    @else
                                                        {{ $factura->medico_nombre }}
                                                    @endif
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-0">
                                                <strong>Especialidad:</strong> 
                                                <span class="ms-2 text-capitalize">{{ str_replace('_', ' ', $factura->especialidad) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Detalle de Servicios -->
                        <div class="mb-4">
                            <h5 class="text-dark mb-3 border-bottom pb-2">
                                <i class="fas fa-list me-2"></i>Detalle de Servicios
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col" class="fw-semibold">#</th>
                                            <th scope="col" class="fw-semibold">Descripción</th>
                                            <th scope="col" class="text-end fw-semibold">Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($factura->descripcion && is_array($factura->descripcion))
                                            @foreach($factura->descripcion as $index => $item)
                                            <tr>
                                                <td class="text-muted">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($factura->tipo === 'consulta')
                                                            <i class="fas fa-stethoscope text-primary me-2"></i>
                                                        @else
                                                            <i class="fas fa-x-ray text-info me-2"></i>
                                                        @endif
                                                        {{ $item['servicio'] ?? 'Servicio no disponible' }}
                                                    </div>
                                                </td>
                                                <td class="text-end fw-semibold">
                                                    L. {{ number_format($item['precio'] ?? 0, 2) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">
                                                    No hay servicios registrados o hay un error en los datos.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="bg-dark text-white p-2 rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold">TOTAL A PAGAR:</h5>
                                        <h4 class="mb-0 fw-bold">L. {{ number_format($factura->total, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tipo de Servicio Badge -->
                        <div class="mt-4 text-center">
                            @if($factura->tipo === 'consulta')
                                <span class="badge bg-primary px-3 py-2 fs-6">
                                    <i class="fas fa-stethoscope me-2"></i>Servicio de evaluación clínica
                                </span>
                            @else
                                <span class="badge bg-info px-3 py-2 fs-6">
                                    <i class="fas fa-x-ray me-2"></i>Orden de Rayos X
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer con Botones -->
                    <div class="bg-light p-4 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Factura generada el {{ date('d/m/Y h:i A', strtotime($factura->created_at)) }}
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="btn-group" role="group">
                                    <button onclick="window.print()" class="btn btn-success me-2">
                                        <i class="fas fa-print me-1"></i>Imprimir
                                    </button>
                                    @if($factura->tipo === 'rayos_x')
                                        <a href="{{ route('rayosx.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Volver
                                        </a>
                                    @else
                                        <a href="{{ route('consultas.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Volver
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionales para impresión -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .card, .card * {
        visibility: visible;
    }
    .card {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
    }
    .btn-group {
        display: none !important;
    }
    .bg-primary {
        background-color: #0d6efd !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
    }
    .bg-light {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
    }
    .bg-dark {
        background-color: #212529 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
    }
    .table-primary th {
        background-color: #b6d7ff !important;
        -webkit-print-color-adjust: exact;
    }
    .badge {
        -webkit-print-color-adjust: exact;
    }
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.table th {
    border-top: none;
}

.badge {
    font-size: 0.875em;
}

.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.text-primary {
    color: #667eea !important;
}

.text-info {
    color: #17a2b8 !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.03);
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
}
</style>
@endsection