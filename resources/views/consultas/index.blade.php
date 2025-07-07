@extends('layouts.app')

@section('title', 'Consultas Médicas')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary fw-bold mx-auto">Consultas Médicas Registradas</h4>

        <div class="d-flex gap-2 position-absolute end-0 me-3">
            <a href="{{ route('inicio') }}" class="btn btn-sm btn-light">
                <i class="bi bi-house-door"></i> Inicio
            </a>
            <a href="{{ route('consultas.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva consulta
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Especialidad</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Motivo</th>
                    <th>Estado</th> <!-- NUEVA COLUMNA -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($consultas as $consulta)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            @if($consulta->paciente)
                                {{ $consulta->paciente->nombre }} {{ $consulta->paciente->apellidos }}
                            @else
                                <span class="text-danger fst-italic">Paciente eliminado</span>
                            @endif
                        </td>
                        <td>
                            @if($consulta->medico)
                                {{ $consulta->medico->nombre }} {{ $consulta->medico->apellidos }}
                            @else
                                <span class="text-danger fst-italic">Médico eliminado</span>
                            @endif
                        </td>
                        <td>{{ $consulta->especialidad }}</td>
                        <td>{{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @if(is_null($consulta->hora))
                                <span class="badge bg-success">Inmediata</span>
                            @else
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $consulta->hora)->format('h:i A') }}
                            @endif
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($consulta->motivo, 30) }}</td>
                        <td>
                            @if($consulta->estado === 'cancelada')
                                <span class="badge bg-danger">Cancelada</span>
                            @else
                                <span class="badge bg-primary">Pendiente</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('consultas.show', $consulta->id) }}" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('consultas.edit', $consulta->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('consultas.destroy', $consulta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta consulta?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No hay consultas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $consultas->links() }}
    </div>
</div>
@endsection

