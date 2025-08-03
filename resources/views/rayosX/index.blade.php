@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 1100;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }

    .content-wrapper {
        margin-top: 50px;
        margin-left: auto;
        margin-right: auto;
        padding: 1rem;
        position: relative;
        max-width: 1000px;
        width: 100%;
    }

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
        background-color: #fff;
        border-radius: 1.5rem;
        padding: 1.5rem;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
        border-radius: 10px;
        z-index: 1;
        max-width: 1000px;
        width: 100%;
    }

    .card-header {
        background-color: transparent !important;
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        text-align: center;
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #003366;
        margin: 0 auto;
        flex-grow: 1;
        text-align: center;
    }

    .btn-inicio, .btn-registrar {
        font-size: 0.9rem;
    }

    .table {
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

    .table th, .table td {
        padding: 0.4rem 0.75rem;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }

    table th:nth-child(1), table td:nth-child(1) {
        width: 40px;
        text-align: center;
    }

    .pagination-container {
        font-size: 0.9rem;
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }

    .btn-info {
        font-size: 0.85rem;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
            <a href="{{ route('inicio') }}" class="btn btn-light btn-inicio me-3">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <h2>Órdenes de Rayos X</h2>

            <a href="{{ route('rayosx.create') }}" class="btn btn-primary btn-registrar ms-3">
                <i class="bi bi-plus-circle"></i> Registrar orden
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Paciente</th>
                        <th>Diagnóstico</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->id }}</td>
                            <td>{{ $orden->diagnostico->paciente->nombre ?? 'N/A' }} {{ $orden->diagnostico->paciente->apellidos ?? '' }}</td>
                            <td>{{ $orden->diagnostico->descripcion ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('rayosx.show', $orden->id) }}" class="btn btn-sm btn-info">
                                    Ver <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay órdenes registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-container">
                {{ $ordenes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
