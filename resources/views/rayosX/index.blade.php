@extends('layouts.app')

@section('title', 'Órdenes de Rayos X')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .content-wrapper {
        margin-top: 50px;
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
        padding: 1rem;
        position: relative;
        width: 100%;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 800px;
        height: 800px;
        background-image: url('{{ asset("images/logo2.jpg") }}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.15;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    .custom-card {
        background-color: #fff;
        border-radius: 1.5rem;
        padding: 1.5rem;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
        z-index: 1;
        max-width: 1000px;
        width: 100%;
    }

    .card-header {
        background-color: transparent !important;
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h5 {
        font-size: 2.25rem;
        font-weight: bold;
        color: #003366;
        margin: 0;
        flex-grow: 1;
        text-align: center;
    }

    .btn-start, .btn-end {
        white-space: nowrap;
    }

    .btn-start {
        margin-right: 1rem;
    }

    .btn-end {
        margin-left: 1rem;
    }

    .filter-container {
        justify-content: flex-start;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        display: flex;
    }

    .filtro-input {
        font-size: 0.85rem;
        max-width: 300px;
        flex-grow: 1;
    }

    table {
        font-size: 0.9rem;
        width: 100%;
        border-collapse: collapse;
    }

    thead tr {
        background-color: #007BFF;
        color: white;
    }

    tbody tr:hover {
        background-color: #e9f2ff;
    }

    th, td {
        padding: 0.4rem 0.75rem;
        vertical-align: middle;
    }

    /* Columna # con ancho fijo pequeño */
    th:nth-child(1), td:nth-child(1) {
        width: 40px;
        text-align: center;
    }

    /* Columna Paciente más ancha */
    th:nth-child(2), td:nth-child(2) {
        min-width: 220px;
    }

    /* Columna Fecha */
    th:nth-child(3), td:nth-child(3) {
        width: 110px;
    }

    /* Columna Total a pagar */
    th:nth-child(4), td:nth-child(4) {
        width: 130px;
    }

    /* Columna Estado */
    th:nth-child(5), td:nth-child(5) {
        width: 80px;
        text-align: center;
    }

    /* Columna Acciones más ancha para botones alineados */
    th:nth-child(6), td:nth-child(6) {
        width: 130px;
    }

    /* Botones en columna Acciones */
    td:nth-child(6) .btn {
        min-width: 70px; /* tamaño mínimo uniforme */
    }

    .pagination-container {
        font-size: 0.9rem;
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }

    /* Círculos de estado */
    .estado-circulo {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .estado-realizado {
        background-color: #28a745;
    }
    .estado-pendiente {
        background-color: #ffc107;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">

        <div class="card-header">
            <a href="{{ route('inicio') }}" class="btn btn-light btn-start">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <h5>Órdenes de Rayos X</h5>

            <a href="{{ route('rayosx.create') }}" class="btn btn-primary btn-end">
                <i class="bi bi-plus-circle"></i> Registrar Orden
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <div class="d-flex filter-container">
            <input type="text" id="busqueda" class="form-control filtro-input" placeholder="Buscar paciente...">
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Paciente</th>
                        <th style="width: 110px;">Fecha</th>
                        <th style="width: 130px;">Total a Pagar</th>
                        <th style="width: 80px;" class="text-center">Estado</th>
                        <th style="width: 130px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-rayosx">
                    @foreach($ordenes as $index => $orden)
                    <tr>
                        <td>{{ $ordenes->firstItem() + $index }}</td>
                        <td>
                            @if($orden->paciente_tipo === 'clinica' && $orden->paciente_id)
                                {{ optional($orden->pacienteClinica)->nombre ?? 'Sin nombre' }}
                                {{ optional($orden->pacienteClinica)->apellidos ?? '' }}
                            @elseif($orden->paciente_tipo === 'rayosx' && $orden->paciente_id)
                                {{ optional($orden->pacienteRayosX)->nombre ?? 'Sin nombre' }}
                                {{ optional($orden->pacienteRayosX)->apellidos ?? '' }}
                            @elseif($orden->diagnostico && $orden->diagnostico->paciente)
                                {{ $orden->diagnostico->paciente->nombre ?? 'Sin nombre' }}
                                {{ $orden->diagnostico->paciente->apellidos ?? '' }}
                            @else
                                {{ $orden->nombres ?? 'Sin nombre' }}
                                {{ $orden->apellidos ?? '' }}
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</td>
                        <td>${{ number_format($orden->total_precio, 2) }}</td>
                        <td class="text-center align-middle">
                            @php
                                $estado = strtolower($orden->estado);
                                $claseCirculo = $estado === 'realizado' ? 'estado-realizado' : 'estado-pendiente';
                            @endphp
                            <span class="estado-circulo {{ $claseCirculo }}" title="{{ ucfirst($estado) }}"></span>
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('rayosx.show', $orden->id) }}" class="btn btn-sm btn-outline-primary" title="Ver orden">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('rayosx.analisis', $orden->id) }}" class="btn btn-sm btn-outline-success ms-1" title="Analizar orden">
                                <i class="bi bi-clipboard-data"></i> Analizar
                            </a>
                        </td>
                    </tr>
                    @endforeach

                    @if($ordenes->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center fst-italic text-muted">No hay resultados que mostrar</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-4">
            {{ $ordenes->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $('#busqueda').on('keyup', function() {
        let texto = $(this).val().toLowerCase().trim();
        $('#tabla-rayosx tr').each(function() {
            let paciente = $(this).find('td:eq(1)').text().toLowerCase();
            if(paciente.indexOf(texto) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>
@endsection
