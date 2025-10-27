@extends('layouts.app')

@section('title', 'Detalles del Inventario')

@section('content')
    <style>
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
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.8rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.75rem;
            margin-left: 8px;
        }
        .status-disponible {
            background: #d4edda;
            color: #155724;
        }
        .status-bajo {
            background: #fff3cd;
            color: #856404;
        }
        .status-vencido {
            background: #f8d7da;
            color: #721c24;
        }
        @media (max-width: 768px) {
            .info-value { font-size: 1rem; }
        }
    </style>

    <div class="container mt-3">
        <div class="card custom-card shadow-sm border rounded-4">
            <div class="card-header text-center py-3" style="background-color:#fff; border-bottom:4px solid #0d6efd;">
                <h3 class="mb-0 fw-bold text-dark">Detalles del Inventario</h3>
            </div>

            <div class="card-body px-3 py-3">
                {{-- Información Básica del Producto --}}
                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Código:</span>
                        <div class="info-value">{{ $inventario->codigo }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Categoría:</span>
                        <div class="info-value">{{ $inventario->categoria }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Nombre del Producto:</span>
                        <div class="info-value">{{ $inventario->nombre }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Unidad de Medida:</span>
                        <div class="info-value">{{ $inventario->unidad }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Precio Unitario:</span>
                        <div class="info-value">L. {{ number_format($inventario->precio_unitario, 2) }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Valor Total del Stock:</span>
                        <div class="info-value">L. {{ number_format($inventario->cantidad * $inventario->precio_unitario, 2) }}</div>
                    </div>

                    {{-- Stock y Estado --}}
                    <div class="col-12"><div class="section-title">Stock y Estado</div></div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Cantidad Disponible:</span>
                        <div class="info-value">
                            {{ $inventario->cantidad }}
                            @if($inventario->cantidad == 0)
                                <span class="status-badge status-vencido">Agotado</span>
                            @elseif($inventario->cantidad < 10)
                                <span class="status-badge status-bajo">Stock Bajo</span>
                            @else
                                <span class="status-badge status-disponible">Disponible</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Fecha de Ingreso:</span>
                        <div class="info-value">{{ \Carbon\Carbon::parse($inventario->fecha_ingreso)->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Fecha de Vencimiento:</span>
                        <div class="info-value">
                            @if($inventario->fecha_vencimiento)
                                {{ \Carbon\Carbon::parse($inventario->fecha_vencimiento)->format('d/m/Y') }}
                                @php
                                    $hoy = \Carbon\Carbon::now();
                                    $vencimiento = \Carbon\Carbon::parse($inventario->fecha_vencimiento);
                                    $diasRestantes = $hoy->diffInDays($vencimiento, false);
                                @endphp
                                @if($diasRestantes < 0)
                                    <span class="status-badge status-vencido">Vencido</span>
                                @elseif($diasRestantes <= 30)
                                    <span class="status-badge status-bajo">Por vencer</span>
                                @else
                                    <span class="status-badge status-disponible">Vigente</span>
                                @endif
                            @else
                                <span class="text-muted">No aplica</span>
                            @endif
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="col-12 mt-3"><div class="section-title">Descripción del Producto</div></div>
                    <div class="col-12 info-block">
                        <textarea class="form-control" rows="4" readonly style="resize:none; background-color:#f8f9fa;">{{ $inventario->descripcion }}</textarea>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="text-center pt-3">
                    <a href="{{ route('inventario.index') }}" class="btn btn-success btn-sm px-4 shadow-sm">← Regresar</a>
                    <a href="{{ route('inventario.edit', $inventario->id) }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                        Editar Producto
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection