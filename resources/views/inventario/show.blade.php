@extends('layouts.app')

@section('title', 'Detalles del Inventario')

@section('content')
<style>
.detail-card {
    max-width: 1200px;
    width: 95%;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 123, 255, 0.15);
    margin: 0.5rem auto;
    overflow: hidden;
    border: 2px solid #91cfff;
    max-height: 96vh;
    display: flex;
    flex-direction: column;
}

.header-section {
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
    padding: 0.8rem 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}

.header-section::before {
    content: "";
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.logo-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.logo-brand {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.logo-img {
    width: 50px;
    height: 50px;
    object-fit: contain;
    background: white;
    padding: 0.3rem;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.clinic-name {
    font-size: 1.8rem;
    font-weight: 800;
    letter-spacing: 2px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    margin: 0;
    line-height: 1;
}

.document-title {
    font-size: 1rem;
    font-weight: 300;
    margin: 0;
    opacity: 0.95;
    line-height: 1.2;
}

.content-section {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    overflow-y: auto;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.info-item {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    border-left: 4px solid #007BFF;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
}

.info-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.info-value {
    font-size: 1rem;
    color: #212529;
    font-weight: 500;
    line-height: 1.3;
}

.description-section {
    background: white;
    padding: 1.2rem;
    border-radius: 10px;
    border: 2px dashed #91cfff;
    min-height: 120px;
}

.section-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #007BFF;
    text-transform: uppercase;
    margin-bottom: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.description-text {
    color: #495057;
    line-height: 1.6;
    font-size: 0.95rem;
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: pre-wrap;
}

.status-badge {
    display: inline-block;
    padding: 0.35rem 0.8rem;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.75rem;
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

.action-buttons {
    display: flex;
    gap: 0.8rem;
    justify-content: center;
    flex-wrap: wrap;
    padding: 0.8rem 1.2rem;
    border-top: 2px solid #e9ecef;
    background: #f8f9fa;
    flex-shrink: 0;
}

.btn-modern {
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.icon-label {
    font-size: 0.95rem;
}

@media (max-width: 1200px) {
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="detail-card">
    <!-- Header con Logo y Nombre de Clínica -->
    <div class="header-section">
        <div class="logo-container">
            <div class="logo-brand">
               <!--  <img src="{{ asset('images/Barra.png') }}" alt="Logo CLINITEK" class="logo-img">-->
                <div>
                    <h1 class="clinic-name">Detalle de Inventario Médico</h1>
                   <!--  <p class="document-title">Detalle de Inventario Médico</p>-->
                </div>
            </div>
            <div>
                <small style="opacity: 0.9; font-size: 0.9rem;">ID: #{{ str_pad($inventario->id, 5, '0', STR_PAD_LEFT) }}</small>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="content-section">
        
        <!-- Grid de Información (3 columnas, 3 filas = 9 campos) -->
        <div class="info-grid">
            <!-- Fila 1 -->
            <div class="info-item">
                <div class="info-label">
                    <i class="bi bi-upc-scan icon-label"></i> Código
                </div>
                <div class="info-value">{{ $inventario->codigo }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="bi bi-tag icon-label"></i> Categoría
                </div>
                <div class="info-value">{{ $inventario->categoria }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="bi bi-box-seam icon-label"></i> Nombre del Producto
                </div>
                <div class="info-value">{{ $inventario->nombre }}</div>
            </div>

            <!-- Fila 2 -->
            <div class="info-item">
                <div class="info-label">
                    <i class="bi bi-123 icon-label"></i> Cantidad Disponible
                </div>
                <div class="info-value">
                    {{ $inventario->cantidad }} 
                    
                    @if($inventario->cantidad == 0)
                        <span class="status-badge status-vencido ms-2">Agotado</span>
                    @elseif($inventario->cantidad < 10)
                        <span class="status-badge status-bajo ms-2">Stock Bajo</span>
                    @else
                        <span class="status-badge status-disponible ms-2">Disponible</span>
                    @endif
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="bi bi-inbox icon-label"></i> Unidad de Medida
                </div>
                <div class="info-value">{{ $inventario->unidad }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="bi bi-cash-coin icon-label"></i> Precio Unitario
                </div>
                <div class="info-value">L. {{ number_format($inventario->precio_unitario, 2) }}</div>
            </div>

            <!-- Fila 3 -->
            <div class="info-item">
                <div class="info-label">
                    <i class="bi bi-calendar-check icon-label"></i> Fecha de Ingreso
                </div>
                <div class="info-value">{{ \Carbon\Carbon::parse($inventario->fecha_ingreso)->format('d/m/Y') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="bi bi-calendar-x icon-label"></i> Fecha de Vencimiento
                </div>
                <div class="info-value">
                    @if($inventario->fecha_vencimiento)
                        {{ \Carbon\Carbon::parse($inventario->fecha_vencimiento)->format('d/m/Y') }}
                        
                        @php
                            $hoy = \Carbon\Carbon::now();
                            $vencimiento = \Carbon\Carbon::parse($inventario->fecha_vencimiento);
                            $diasRestantes = $hoy->diffInDays($vencimiento, false);
                        @endphp
                        
                        @if($diasRestantes < 0)
                            <span class="status-badge status-vencido ms-2">Vencido</span>
                        @elseif($diasRestantes <= 30)
                            <span class="status-badge status-bajo ms-2">Por vencer</span>
                        @else
                            <span class="status-badge status-disponible ms-2">Vigente</span>
                        @endif
                    @else
                        <span class="text-muted">No aplica</span>
                    @endif
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    <i class="bi bi-calculator icon-label"></i> Valor Total del Stock
                </div>
                <div class="info-value">
                    L. {{ number_format($inventario->cantidad * $inventario->precio_unitario, 2) }}
                </div>
            </div>
        </div>

        <!-- Descripción (ocupa todo el ancho) -->
        <div class="description-section">
            <div class="section-title">
                <i class="bi bi-file-text"></i> Descripción del Producto
            </div>
            <div class="description-text">
                {{ $inventario->descripcion }}
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="action-buttons">
        <a href="{{ route('inventario.edit', $inventario->id) }}" class="btn btn-primary btn-modern">
            <i class="bi bi-pencil-square"></i> Editar
        </a>
        <a href="{{ route('inventario.index') }}" class="btn btn-success btn-modern">
            <i class="bi bi-arrow-left-circle"></i> Regresar
        </a>
    </div>
</div>

@endsection