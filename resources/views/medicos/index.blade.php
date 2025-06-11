@extends('layouts.app')

@section('title')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded-4 border-0">
            <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Listado de Médicos</h4>
            </div>

            <div class="card-body">
                {{-- Mensaje de éxito --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                {{-- Buscador --}}
                <form action="{{ route('medicos.index') }}" method="GET" class="mb-3 row g-2 align-items-center">
                    <div class="col-auto">
                        <input
                            type="text"
                            name="buscar"
                            class="form-control"
                            placeholder="Buscar por nombre o especialidad"
                            value="{{ request('buscar') }}"
                            style="min-width: 250px;"
                        >
                    </div>
                    <div class="col-auto">
                        <select name="estado" class="form-select" style="min-width: 130px;">
                            <option value="">-- Todos --</option>
                            <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-primary">Buscar</button>
                    </div>
                </form>


                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-primary text-primary text-uppercase small">
                        <tr>
                            <th>#</th>
                            <th>Estado</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Especialidad</th>
                            <th>Género</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if($medicos->isEmpty())
                            <tr>
                                <td colspan="9">
                                    <div class="alert alert-info shadow-sm text-center m-0" role="alert">
                                        <i class="bi bi-info-circle me-2"></i> No hay médicos registrados aún.
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($medicos as $medico)
                                <tr>
                                    <td>{{ ($medicos->currentPage() - 1) * $medicos->perPage() + $loop->iteration }}</td>
                                    <td class="text-center">
                                        @if($medico->estado)
                                            <i class="bi bi-circle-fill text-success" title="Activo"></i>
                                        @else
                                            <i class="bi bi-circle-fill text-danger" title="Inactivo"></i>
                                        @endif
                                    </td>
                                    <td class="fw-medium">{{ $medico->nombre }}</td>
                                    <td>{{ $medico->apellidos }}</td>
                                    <td class="text-center">{{ $medico->telefono }}</td>
                                    <td>{{ $medico->correo }}</td>
                                    <td class="text-center">{{ $medico->especialidad }}</td>
                                    <td class="text-center">
                                        @if($medico->genero === 'Masculino')
                                            <span class="badge bg-primary">{{ $medico->genero }}</span>
                                        @else
                                            <span class="badge bg-info">{{ $medico->genero }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-sm btn-outline-info me-2" title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-sm btn-outline-warning me-2" title="Editar Médico">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $medicos->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
