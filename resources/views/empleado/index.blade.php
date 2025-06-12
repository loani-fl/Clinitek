<!DOCTYPE html>
<html>
<head>
    <title>Listado de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #e8f4fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 60px; /* espacio para el footer fijo */
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
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
            z-index: 999;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="color: #007bff;">Clinitek</h4>
        <div>
            <a href="{{ route('puestos.create') }}" class="btn btn-primary me-2">
                <i class="bi bi-briefcase"></i> Crear puesto
            </a>
            <a href="{{ route('empleados.create') }}" class="btn btn-dark">
                <i class="bi bi-person-plus"></i> Registrar empleado
            </a>

            <a href="{{ route('medicos.create') }}" 
                     class="btn btn-dark"> <i class="bi bi-person-plus"></i> Registrar Médico
                    
                    
                    </a>
        </div>
    </div>

    <div class="table-container">
        <div class="header">
            <h5 class="mb-0">Lista de Empleados</h5>
        </div>

        <!-- Alerta de éxito -->
        @if(session('success'))
            <div id="mensaje-exito" class="alert alert-success m-3 alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <form method="GET" action="{{ route('empleados.index') }}" class="row m-3">
            <div class="col-md-4">
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por nombre o puesto" />
            </div>
            <div class="col-md-3">
                <select id="filtro-estado" name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
        </form>

        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Puesto</th>
                    <th>Fecha de ingreso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->nombres }} {{ $empleado->apellidos }}</td>
                        <td>{{ $empleado->puesto->nombre ?? 'Sin puesto' }}</td>
                        <td>{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-info btn-sm" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay empleados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-3 pb-3">
        {{ $empleados->links() }}
    </div>
</div>

<!-- Footer fijo -->
<footer>
    © 2025 Clínitek. Todos los derechos reservados.
</footer>

<!-- Bootstrap Bundle -->
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
