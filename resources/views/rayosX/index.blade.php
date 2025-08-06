@extends('layouts.app')

@section('content')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        border: none;
    }
    table th, table td {
        vertical-align: middle !important;
    }
</style>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Órdenes de Rayos X</h5>
            <a href="{{ route('rayosx.create') }}" class="btn btn-light btn-sm">+ Nueva Orden</a>
        </div>
        <div class="card-body p-0">
            @if ($ordenes->count() > 0)
                <table class="table mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Identidad</th>
                            <th>Fecha</th>
                            <th>Diagnóstico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordenes as $orden)
                            <tr>
                                <td>{{ $orden->id }}</td>
                                <td>
                                    @if ($orden->nombres || $orden->apellidos)
                                        {{ $orden->nombres }} {{ $orden->apellidos }}
                                    @elseif ($orden->pacienteClinica)
                                        {{ $orden->pacienteClinica->nombre }} {{ $orden->pacienteClinica->apellidos }}
                                    @elseif ($orden->pacienteRayosX)
                                        {{ $orden->pacienteRayosX->nombre }} {{ $orden->pacienteRayosX->apellidos }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if ($orden->identidad)
                                        {{ $orden->identidad }}
                                    @elseif ($orden->pacienteClinica)
                                        {{ $orden->pacienteClinica->identidad }}
                                    @elseif ($orden->pacienteRayosX)
                                        {{ $orden->pacienteRayosX->identidad }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $orden->diagnostico->descripcion ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('rayosx.show', $orden->id) }}" class="btn btn-sm btn-primary">Ver</a>
                                    <a href="{{ route('rayosx.edit', $orden->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('rayosx.destroy', $orden->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta orden?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-3">
                    {{ $ordenes->links() }}
                </div>
            @else
                <div class="p-3 text-center text-muted">
                    No hay órdenes de Rayos X registradas.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
