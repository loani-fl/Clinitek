@extends('layouts.app')

@section('title', 'Listado de Empleados')

@section('content')
<style>
    html, body {
        height: 100%;
        background-color: #e8f4fc;
    }

    .custom-card {
        background-color: #f0faff;
        border: 1px solid #91cfff;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .table thead th {
        background-color: #d0e9ff;
        color: #003f6b;
    }

    .table td, .table th {
        vertical-align: middle;
    }

    .btn-outline-info,
    .btn-outline-warning,
    .btn-outline-danger {
        transition: 0.2s;
    }

    .btn-outline-info:hover {
        background-color: #d0f0ff;
        color: #0d6efd;
    }

    .btn-outline-warning:hover {
        background-color: #fff4d0;
        color: #ffc107;
    }

    .btn-outline-danger:hover {
        background-color: #ffe0e0;
        color: #dc3545;
    }
</style>
       
<!-- Barra de navegación (sin cambios) -->
<div class="header d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF;">
    <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
        <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>

<div class="container-fluid mt-4">
    <div class="card custom-card shadow rounded-4 border-0 w-100">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center px-4 py-3">
            <h4 class="mb-0">Lista de Puestos</h4>
        
        </div>

        <div class="card-body px-4 py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if($puestos->isEmpty())
                <div class="alert alert-info shadow-sm" role="alert">
                    <i class="bi bi-info-circle me-2"></i> No hay puestos registrados aún.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 rounded-3 overflow-hidden">
                        <thead class="text-uppercase small">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Código</th> {{-- Nueva columna para Código --}}
                                <th>Nombre</th>
                                <th>Departamento</th> {{-- Nueva columna para Departamento (Área) --}}
                                <th style="width: 220px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($puestos as $puesto)
                                <tr>
                                    <td class="fw-semibold text-secondary">{{ $puesto->id }}</td>
                                    <td>{{ $puesto->codigo }}</td> {{-- Mostrar el Código --}}
                                    <td class="fw-medium">{{ $puesto->nombre }}</td>
                                    <td>{{ $puesto->area }}</td> {{-- Mostrar el Área (Departamento) --}}
                                    <td>
                                        <a href="{{ route('puestos.show', $puesto->id) }}" class="btn btn-sm btn-outline-info me-2" title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('puestos.edit', $puesto) }}" class="btn btn-sm btn-outline-warning me-2" title="Editar Puesto">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>