@extends('layouts.app')

@section('content')
<div class="full-width-container">
    <div class="table-container">
        <div class="header-content">
            <h2 class="mb-0">Lista de Médicos</h2>
            {{-- Botón para registrar nuevo médico - ÚNICO --}}
            <a href="{{ route('medicos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Registrar
            </a>

        </div>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tabla completamente responsiva --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Apellidos</th>
                        <th class="text-center">Teléfono</th>
                        <th class="text-center">Correo</th>
                        <th class="text-center">Fecha de Nacimiento</th>
                        <th class="text-center">Fecha de Ingreso</th>
                        <th class="text-center">Género</th>
                        <th class="text-center">Observaciones</th>
                        <th class="text-center">Acciones</th> {{-- Nueva columna --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($medicos as $medico)
                        <tr>
                            <td class="fw-medium">{{ $medico->nombre }}</td>
                            <td>{{ $medico->apellidos }}</td>
                            <td class="text-center">{{ $medico->telefono }}</td>
                            <td>{{ $medico->correo }}</td>
                            <td class="text-center">{{ $medico->fecha_nacimiento }}</td>
                            <td class="text-center">{{ $medico->fecha_ingreso }}</td>
                            <td class="text-center">
                                @if($medico->genero == 'Masculino')
                                    <span class="badge bg-primary">{{ $medico->genero }}</span>
                                @else
                                    <span class="badge bg-info">{{ $medico->genero }}</span>
                                @endif
                            </td>
                            <td>{{ $medico->observaciones ?: 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('medicos.destroy', $medico->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas borrar este médico?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Borrar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-user-md fa-2x mb-2"></i>
                                    <p class="mb-0">No hay médicos registrados.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Información adicional --}}
        @if($medicos->count() > 0)
            <div class="mt-3 text-muted small">
                Total de médicos registrados: <strong>{{ $medicos->count() }}</strong>
            </div>
        @endif
    </div>
</div>
@endsection
