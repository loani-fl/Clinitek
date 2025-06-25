@extends('layouts.app')

@section('content')
<!-- Barra de navegación superior -->
<div class="header d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF; position: sticky; top: 0; z-index: 1030;">
    <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
        <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>

<div class="container-fluid py-4" style="background-color: #e8f4fc; min-height: 100vh;">
    <div class="mx-auto rounded border shadow" style="max-width: 1100px; background-color: #fff;">

        <!-- Franja azul título pegada arriba -->
        <div style="padding: 15px 30px; border-top-left-radius: 10px; border-top-right-radius: 10px; background-color: transparent; border-bottom: 3px solid #007BFF;">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="mb-0 fw-bold text-black text-center flex-grow-1">Listado de Pacientes</h2>
        <a href="{{ route('inicio') }}" class="btn btn-success ms-3">
            <i class="bi bi-house-door"></i> Inicio
        </a>
    </div>
</div>




        <!-- Tabla con sombra y sin margen arriba para pegarse a la franja -->
        <table class="table table-bordered table-striped align-middle mb-0" style="box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);">
            <thead class="table-primary">
                <tr>
                    <th>Nombre(s)</th>
                    <th>Apellidos</th>
                    <th>Identidad</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pacientes as $paciente)
                    <tr>
                        <td>{{ $paciente->nombre }}</td>
                        <td>{{ $paciente->apellidos }}</td>
                        <td>{{ $paciente->identidad }}</td>
                        <td>{{ $paciente->telefono }}</td>
                        <td>
                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-info" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay pacientes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center py-3">
            {{ $pacientes->links() }}
        </div>
    </div>
</div>
@endsection
