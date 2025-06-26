@extends('layouts.app')

@section('title', 'Listado de Pacientes')

@section('content')

    <div class="header d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF;">
        <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
            <a href="{{ route('pacientes.create') }}" class="text-decoration-none text-white fw-semibold">Registrar paciente</a>

        </div>


    </div>

    <div class="container mt-3" style="max-width: 1000px;">

        <div class="card shadow rounded-4 border-0">


            <div
                class="card-header rounded-top-4 d-flex justify-content-center align-items-center" style="background-color: transparent; border-bottom: 3px solid #007BFF;">
                <h4 class="mb-0 fw-bold text-black text-center">Listado de Pacientes</h4>


            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                {{-- Buscador básico (opcional) --}}
                <form action="{{ route('pacientes.index') }}" method="GET" class="mb-3 row g-2 align-items-center" style="max-width: 600px;">
                    <div class="col-auto">
                        <input
                            type="text"
                            name="buscar"
                            class="form-control"
                            placeholder="Buscar por nombre o identidad"
                            value="{{ request('buscar') }}"
                            style="min-width: 250px;"
                        >
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
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Identidad</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Género</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody id="tabla-pacientes">
                        @forelse ($pacientes as $paciente)
                            <tr>
                                <td>{{ ($pacientes->currentPage() - 1) * $pacientes->perPage() + $loop->iteration }}</td>
                                <td class="fw-medium">{{ $paciente->nombre }}</td>
                                <td>{{ $paciente->apellidos }}</td>
                                <td>{{ $paciente->identidad }}</td>
                                <td class="text-center">{{ $paciente->telefono }}</td>
                                <td>{{ $paciente->correo ?? 'No especificado' }}</td>
                                <td class="text-center">
                                    @if($paciente->genero === 'Masculino')
                                        <span class="badge bg-primary">{{ $paciente->genero }}</span>
                                    @elseif($paciente->genero === 'Femenino')
                                        <span class="badge bg-warning text-dark">{{ $paciente->genero }}</span>
                                    @else
                                        <span class="badge bg-info">{{ $paciente->genero }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-outline-info me-2" title="Ver Detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-sm btn-outline-warning" title="Editar Paciente">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="alert alert-info shadow-sm text-center m-0" role="alert">
                                        <i class="bi bi-info-circle me-2"></i> No hay pacientes registrados aún.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $pacientes->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
