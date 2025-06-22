@extends('layouts.app')

@section('title', 'Clinitek - Empleados')

@section('content')
<div class="container-fluid px-3 py-3" style="min-height: calc(100vh - 56px - 56px); display: flex; flex-direction: column;">
    <div class="card shadow-sm border w-100 flex-grow-1 d-flex flex-column rounded-3 border-primary bg-light" style="background-color: #f0faff;">
        <!-- Encabezado con botón -->
        <div class="card-header bg-primary text-white py-2 rounded-top d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0 px-2">Listado de empleados</h5>
            <a href="{{ route('empleados.create') }}" class="btn btn-light text-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-person-plus-fill"></i>
                Registrar empleado
            </a>
        </div>

        @if(session('success'))
            <div id="mensaje-exito" class="alert alert-success m-3 alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <!-- Filtros -->
        <div class="row px-3 py-2">
            <div class="col-md-4 mb-2 mb-md-0">
                <input type="text" id="filtro-empleado" class="form-control" placeholder="Buscar por nombre, correo o puesto" />
            </div>
            <div class="col-md-3">
                <select id="filtro-estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive flex-grow-1 overflow-auto px-3 pb-3">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Identidad</th>
                        <th>Correo</th>
                        <th>Puesto</th>
                        <th class="text-center">Estado</th>
                        <th>Fecha de ingreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-empleados">
              @forelse($empleados as $index => $empleado)
    <tr data-estado="{{ $empleado->estado }}">
        <td>{{ $empleados->firstItem() + $index }}</td>
        <td class="nombre">{{ $empleado->nombres }} {{ $empleado->apellidos }}</td>
        <td class="identidad">{{ $empleado->identidad }}</td>
        <td class="correo">{{ $empleado->correo }}</td>
        <td class="puesto">{{ $empleado->puesto->nombre ?? 'Sin puesto' }}</td>
        <td class="text-center">
            @if($empleado->estado === 'Activo')
                <span class="estado-activo"><i class="bi bi-circle-fill"></i></span>
            @else
                <span class="estado-inactivo"><i class="bi bi-circle-fill"></i></span>
            @endif
        </td>
        <td class="fecha-ingreso">{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
        <td>
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('empleados.show', $empleado->id) }}" class="btn btn-white-border btn-outline-info btn-sm" title="Ver">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-white-border btn-outline-warning btn-sm" title="Editar">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
 

        </td>
    </tr>
@empty

                    <tr>
                        <td colspan="8" class="text-center">No hay empleados registrados.</td>
                    </tr>
                @endforelse

                <tr id="sin-resultados" style="display: none;">
                    <td colspan="8" class="text-center text-muted">No hay empleados que coincidan con la búsqueda.</td>
                </tr>
                </tbody>
            </table>

                 <div class="d-flex justify-content-center mt-3">
    {{ $empleados->links() }}
</div>
 

        </div>
        <div id="resultados-container">
    {{-- Aquí se insertan los resultados vía JS --}}
</div>
    


       
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-white-border {
        background-color: white !important;
        border-width: 2px;
        box-shadow: none !important;
    }

    .estado-activo i {
        color: #00c851 !important;
    }

    .estado-inactivo i {
        color: #ff3547 !important;
    }

    tbody tr:hover {
        background-color: #e9f2ff;
    }
</style>
@push('scripts')
<script>
    function aplicarFiltros() {
        const texto = $('#filtro-empleado').val().toLowerCase();
        const estado = $('#filtro-estado').val();
        let visibles = 0;

        $('#tabla-empleados tr').not('#sin-resultados').each(function () {
            const nombre = $(this).find('.nombre').text().toLowerCase();
            const puesto = $(this).find('.puesto').text().toLowerCase();
            const correo = $(this).find('.correo').text().toLowerCase();
            const identidad = $(this).find('.identidad').text().toLowerCase();
            const fecha = $(this).find('.fecha-ingreso').text().toLowerCase();
            const estadoActual = $(this).data('estado');

            const coincideTexto =
                nombre.includes(texto) ||
                puesto.includes(texto) ||
                correo.includes(texto) ||
                identidad.includes(texto) ||
                fecha.includes(texto);

            const coincideEstado = !estado || estado === estadoActual;

            const visible = coincideTexto && coincideEstado;
            $(this).toggle(visible);

            if (visible) {
                $(this).find('td:first').text(++visibles);
            }
        });

        $('#sin-resultados').toggle(visibles === 0);

        const $contenedor = $('#resultados-container');
        $contenedor.empty();

        if ($('#filtro-empleado').val() || $('#filtro-estado').val()) {
            if (visibles === 0) {
                $contenedor.html(`<div class="alert alert-warning mt-2">No hay registros que coincidan con tu búsqueda.</div>`);
            } else {
                $contenedor.html(`<div class="alert alert-info mt-2">${visibles} registro(s) encontrado(s).</div>`);
            }
        }
    }

    $(document).ready(function () {
        $('#filtro-empleado').on('input', aplicarFiltros);
        $('#filtro-estado').on('change', aplicarFiltros);
    });

</script>
@endpush
