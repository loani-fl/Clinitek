<!DOCTYPE html> 
<html>
<head>
    <title>Listado de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
            font-size: 16px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 95%;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        thead tr {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        tbody tr:hover {
            background-color: #e9f2ff;
        }
        table tbody tr {
            height: 50px;
        }
        .btn-white-bg {
            background-color: white !important;
            box-shadow: none !important;
            cursor: pointer;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-width: 1.5px !important;
        }
        th, td {
            vertical-align: middle;
            white-space: nowrap;
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container mt-4">

    @if(session('autenticado') && isset($usuarioActual))
        <div class="d-flex justify-content-end align-items-center gap-2 mb-3">
            <span class="fw-bold">Bienvenido, {{ $usuarioActual }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-danger">Cerrar sesión</button>
            </form>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary fw-bold">Clinitek</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('puestos.create') }}" class="btn btn-outline-primary btn-white-bg">
                <i class="bi bi-briefcase"></i> Crear puesto
            </a>
            <a href="{{ route('empleados.create') }}" class="btn btn-outline-dark btn-white-bg">
                <i class="bi bi-person-plus"></i> Registrar empleado
            </a>
        </div>
    </div>

    <div class="table-container">
        <div class="header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="mb-0">Lista de Empleados</h5>
            <form method="GET" action="{{ route('empleados.visualizacion') }}" class="row row-cols-2 row-cols-md-auto g-2 align-items-center">
                <div class="col">
                    <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Buscar..." value="{{ request('buscar') }}">
                </div>
                <div class="col">
                    <select name="ordenar_por" class="form-select form-select-sm w-auto">
                        <option value="nombres" {{ request('ordenar_por') == 'nombres' ? 'selected' : '' }}>Nombre</option>
                        <option value="puesto_id" {{ request('ordenar_por') == 'puesto_id' ? 'selected' : '' }}>Puesto</option>
                    </select>
                </div>
                <div class="col">
                    <select name="direccion" class="form-select form-select-sm w-auto">
                        <option value="asc" {{ request('direccion') == 'asc' ? 'selected' : '' }}>Asc</option>
                        <option value="desc" {{ request('direccion') == 'desc' ? 'selected' : '' }}>Desc</option>
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
                </div>
                <div class="col">
                    <a href="{{ route('empleados.visualizacion') }}" class="btn btn-sm btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Identidad</th>
                        <th>Dirección</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Estado Civil</th>
                        <th>Género</th>
                        <th>Fecha Ingreso</th>
                        <th>Salario</th>
                        <th>Puesto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->nombres }} {{ $empleado->apellidos }}</td>
                        <td>{{ $empleado->identidad }}</td>
                        <td>{{ $empleado->direccion }}</td>
                        <td>{{ $empleado->correo }}</td>
                        <td>{{ $empleado->telefono }}</td>
                        <td>{{ $empleado->estado_civil }}</td>
                        <td>{{ $empleado->genero }}</td>
                        <td>{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
                        <td>L. {{ number_format($empleado->salario, 2) }}</td>
                        <td>{{ $empleado->puesto->nombre ?? 'Sin puesto' }}</td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center flex-wrap">
                                <a href="{{ route('empleados.show', $empleado->id) }}" class="btn btn-outline-info btn-white-bg btn-sm" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-outline-warning btn-white-bg btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar este empleado?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-white-bg btn-sm" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">No hay empleados registrados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $empleados->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
