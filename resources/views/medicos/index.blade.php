@extends('layouts.app')

@section('title', 'Listado de Médicos')

@section('content')

<div class="header d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF;">
    <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
        <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>

<div class="container mt-3" style="max-width: 1000px;"> {{-- ancho más pequeño --}}

    <div class="card shadow rounded-4 border-0">

        {{-- Título sin fondo azul, texto centrado, negro y en negrita --}}
        <div class="card-header rounded-top-4 d-flex justify-content-center align-items-center" style="background-color: transparent; border-bottom: 3px solid #007BFF;">
    <h4 class="mb-0 fw-bold text-black text-center">Listado de Médicos</h4>
</div>


        <div class="card-body">
            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

           {{-- Buscador con ancho más pequeño --}}
           <form action="{{ route('medicos.index') }}" method="GET" class="mb-3 row g-2 align-items-center" style="max-width: 600px;">
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

                    <tbody id="tabla-medicos">
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
                                        <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-sm btn-outline-warning" title="Editar Médico">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $medicos->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#buscador').on('keyup', function () {
        let valor = $(this).val();

        $.ajax({
            url: '/medicos/buscar/ajax',
            type: 'GET',
            data: { buscar: valor },
            success: function (data) {
                $('#tabla-medicos').html(data);
            },
            error: function () {
                alert('Ocurrió un error al buscar médicos.');
            }
        });
    });
</script>

@endsection
