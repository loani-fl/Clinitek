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

    <div class="d-flex justify-content-center mb-4">
        <div style="width: 100%; max-width: 400px;">
            <input type="text" id="buscador" class="form-control form-control-sm shadow-sm text-center" placeholder="Buscar por nombre o identidad...">
        </div>
    </div>

    <div class="mx-auto rounded border shadow" style="max-width: 1100px; background-color: #fff;">

        <!-- Franja azul título pegada arriba -->
        <div style="background-color: #007BFF; padding: 15px 30px; border-top-left-radius: 10px; border-top-right-radius: 10px;">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0 text-white">Listado de Pacientes</h2>
                <a href="{{ route('inicio') }}" class="btn btn-light">
                    <i class="bi bi-house-door"></i> Inicio
                </a>
            </div>
        </div>

        <!-- Tabla con sombra y sin margen arriba para pegarse a la franja -->
        <table id="tablaPacientes" class="table table-bordered table-striped align-middle mb-0" style="box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);">
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
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-white-border btn-outline-info btn-sm" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-white-border btn-outline-warning btn-sm" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay pacientes registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <!-- Mostrar total resultados -->
        <div id="resultadosInfo" class="text-center mt-3" style="font-weight: 600;">
            {{ $pacientes->count() }} resultados de {{ $pacientes->count() }}
        </div>


        <div class="d-flex justify-content-center py-3">
            {{ $pacientes->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buscador = document.getElementById('buscador');
            const filas = document.querySelectorAll('#tablaPacientes tbody tr');
            const resultadosInfo = document.getElementById('resultadosInfo');
            const totalResultados = filas.length;

            resultadosInfo.textContent = `${totalResultados} resultados de ${totalResultados}`;

            buscador.addEventListener('input', function () {
                const texto = this.value.toLowerCase().trim();
                let visibleCount = 0;

                filas.forEach(fila => {
                    const nombre = fila.cells[0].textContent.toLowerCase();
                    const identidad = fila.cells[2].textContent.toLowerCase();

                    const coincide = nombre.includes(texto) || identidad.includes(texto);
                    if (coincide) {
                        fila.style.display = '';
                        visibleCount++;
                    } else {
                        fila.style.display = 'none';
                    }
                });

                resultadosInfo.textContent = `${visibleCount} resultados de ${totalResultados}`;
            });
        });

    </script>


@endsection
