@extends('layouts.app')

@section('title', '')

@section('content')
<div class="container-fluid">

    <div class="card shadow rounded-4 border-0">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Lista de Puestos</h4>
            <a href="{{ route('puestos.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Puesto
            </a>
        </div>

        <div class="card-body">
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
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-primary text-primary text-uppercase small">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Nombre</th>
                                <th style="width: 220px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($puestos as $puesto)
                                <tr>
                                    <td class="fw-semibold text-secondary">{{ $puesto->id }}</td>
                                    <td class="fw-medium">{{ $puesto->nombre }}</td>
                                    <td>
                                        <a href="{{ route('puestos.show', $puesto->id) }}" class="btn btn-sm btn-outline-info me-2" title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('puestos.edit', $puesto) }}" class="btn btn-sm btn-outline-warning me-2" title="Editar Puesto">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('puestos.destroy', $puesto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que quieres eliminar este puesto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Eliminar Puesto">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $puestos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection













