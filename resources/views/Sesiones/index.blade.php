@extends('layouts.app')

@section('content')
    <style>
        html, body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .custom-card {
            max-width: 1200px;
            width: 100%;
            margin: 20px auto;
            padding: 20px;
            position: relative;
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

        table {
            width: 100%;
            table-layout: fixed;
        }

        th, td {
            text-align: left;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>

    <div class="card custom-card shadow-sm border rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark">Sesiones Psicológicas</h5>
            <a href="{{ route('sesiones.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Nueva sesión</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Género</th>
                        <th>Teléfono</th>
                        <th>Médico</th>
                        <th>Fecha</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Tipo de examen</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sesiones as $sesion)
                        <tr>
                            <td>{{ $sesiones->firstItem() + $loop->index }}</td>
                            <td>{{ $sesion->paciente->nombre }} {{ $sesion->paciente->apellidos }}</td>
                            <td>{{ \Carbon\Carbon::parse($sesion->paciente->fecha_nacimiento)->age }}</td>
                            <td>{{ $sesion->paciente->genero }}</td>
                            <td>{{ $sesion->paciente->telefono }}</td>
                            <td>{{ $sesion->medico->nombre }} {{ $sesion->medico->apellidos }}</td>
                            <td>{{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $sesion->hora_inicio }}</td>
                            <td>{{ $sesion->hora_fin }}</td>
                            <td>{{ $sesion->tipo_examen }}</td>
                            <td>
                                <a href="{{ route('sesiones.show', $sesion->id) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('sesiones.edit', $sesion->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('sesiones.destroy', $sesion->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Desea eliminar esta sesión?')"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">No se encontraron sesiones.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-end mt-3">
                {{ $sesiones->links() }}
            </div>
        </div>
    </div>
@endsection
