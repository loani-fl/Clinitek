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
            max-width: 1100px;
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

        form.row > div.col-md-4 input {
            max-width: 400px;
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
        </div>
    </div>

    <div class="table-container">
        <div class="header">
            <h5 class="mb-0">Lista de Empleados</h5>
        </div>

        @if(session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('empleados.index') }}" class="row m-3">
            <div class="col-md-4">
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por nombre o puesto" />
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('empleados.index') }}" class="btn btn-secondary w-100">Limpiar</a>
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

        <div class="p-3">
            {{ $empleados->links() }}
        </div>
    </div>
</div>
</body>
</html>
