<!DOCTYPE html>
<html>
<head>
    <title>Listado de empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #e8f4fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .custom-card {
            max-width: 97%;
            background-color: #f0faff;
            border-color: #91cfff;
        }
        label {
            font-size: 0.85rem;
        }
        input, select, textarea {
            font-size: 0.85rem !important;
        }
        thead tr {
            background-color: #007bff;
            color: white;
        }
        tbody tr:hover {
            background-color: #e9f2ff;
        }
        table tbody tr {
            height: 50px;
        }
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
    </style>
</head>
<body>

<!-- Encabezado superior -->
<div class="bg-primary text-white px-4 py-2">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <div class="fw-bold fs-5 mb-0">Clinitek</div>
        <a href="{{ route('puestos.create') }}" class="btn btn-light text-primary">
            <i class="bi bi-briefcase"></i> Crear puesto
        </a>
        <a href="{{ route('empleados.create') }}" class="btn btn-dark">
            <i class="bi bi-person-plus"></i> Registrar empleado
        </a>
    </div>
</div>

<!-- Contenedor principal -->
<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <div class="px-2">
                <h5 class="mb-0">Lista de empleados</h5>
            </div>
        </div>

        @if(session('success'))
            <div id="mensaje-exito" class="alert alert-success m-3 alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <!-- FILTROS -->
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
        <div class="table-responsive px-3 pb-3">
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
                        <td>{{ $empleado->identidad }}</td>
                        <td class="correo">{{ $empleado->correo }}</td>
                        <td class="puesto">{{ $empleado->puesto->nombre ?? 'Sin puesto' }}</td>
                        <td class="text-center">
                            @if($empleado->estado === 'Activo')
                                <span class="estado-activo"><i class="bi bi-circle-fill"></i></span>
                            @else
                                <span class="estado-inactivo"><i class="bi bi-circle-fill"></i></span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
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
        </div>

        <div class="px-3 pb-3">
            {{ $empleados->links() }}
        </div>
    </div>
</div>

<!-- Bootstrap Bundle (necesario para alert-dismissible) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script de filtros y mensaje -->
<script>
    function aplicarFiltros() {
        const texto = $('#filtro-empleado').val().toLowerCase();
        const estado = $('#filtro-estado').val();
        let visibles = 0;

        $('#tabla-empleados tr').not('#sin-resultados').each(function () {
            const nombre = $(this).find('.nombre').text().toLowerCase();
            const puesto = $(this).find('.puesto').text().toLowerCase();
            const correo = $(this).find('.correo').text().toLowerCase();
            const estadoActual = $(this).data('estado');

            const coincideTexto = nombre.includes(texto) || puesto.includes(texto) || correo.includes(texto);
            const coincideEstado = !estado || estado === estadoActual;

            const visible = coincideTexto && coincideEstado;
            $(this).toggle(visible);
            if (visible) visibles++;
        });

        $('#sin-resultados').toggle(visibles === 0);
    }

    $(document).ready(function () {
        $('#filtro-empleado, #filtro-estado').on('input change', aplicarFiltros);

        // Ocultar mensaje de éxito tras 3 segundos
        const mensaje = $('#mensaje-exito');
        if (mensaje.length) {
            setTimeout(() => {
                mensaje.alert('close');
            }, 3000);
        }
    });
</script>
</body>
</html>
