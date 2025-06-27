@extends('layouts.app')

@section('title', 'Listado de Puestos')

@section('content')
<style>
    body {
        overflow-x: hidden;
        background-color: #e8f4fc;
    }

    .custom-card {
        background-color: #ffffff;
        border-color: #91cfff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        max-width: 90%;
        width: 100%;
        padding: 1rem;
        margin-top: 60px;
        min-height: calc(100vh - 80px);
    }

    .card-header h5 {
        color: #0d6efd;
        font-weight: bold;
    }

    thead tr {
        background-color: #cce5ff;
        color: #003e7e;
    }

    tbody tr:hover {
        background-color: #e9f2ff;
    }

    .table td, .table th {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
        line-height: 1.2;
    }

    table th:nth-child(1), table td:nth-child(1) {
        width: 30px;
        text-align: center;
    }

    table th:nth-child(2), table td:nth-child(2) {
        width: 120px;
    }

    table th:nth-child(3), table td:nth-child(3) {
        width: 120px;
    }

    table th:nth-child(4), table td:nth-child(4) {
        width: 100px;
    }

    table th:nth-child(5), table td:nth-child(5) {
        width: 90px;
        white-space: nowrap;
    }

    /* Botones personalizados */
    .btn-white-border {
        border: 1px solid white !important;
    }

    .btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
    }

    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
    }

    .btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }

    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: black;
    }
</style>

<div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050;">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>
</div>

<div class="card custom-card shadow-sm border rounded-4 mx-auto w-100">
    <div class="card-header position-relative py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
        <h5 class="mb-0 fw-bold text-dark text-center" style="font-size: 2.25rem;">Listado de Puestos</h5>
    </div>

    <div class="p-3">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 p-3 mb-4 small">
                {{ session('success') }}
            </div>
        @endif

        @if($puestos->isEmpty())
            <div class="alert alert-info shadow-sm" role="alert">
                No hay puestos registrados aún.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($puestos as $index => $puesto)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $puesto->codigo }}</td>
                                <td>{{ $puesto->nombre }}</td>
                                <td>{{ $puesto->area }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('puestos.show', $puesto->id) }}" class="btn btn-white-border btn-outline-info btn-sm" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('puestos.edit', $puesto) }}" class="btn btn-white-border btn-outline-warning btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
