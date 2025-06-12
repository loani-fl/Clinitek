<!DOCTYPE html>
<html>
<head>
    <title>Listado de Empleados y Médicos</title>
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
            <a href="{{ route('empleados.create') }}" class="btn btn-dark me-2">
                <i class="bi bi-person-plus"></i> Registrar empleado
            </a>
            <a href="{{ route('medicos.create') }}" class="btn btn-dark">
                <i class="bi bi-person-plus"></i> Registrar médico
            </a>
        </div>
    </div>

    <div class="table-container">
        <div class="header">
            <h5 class="mb-0">Lista de {{ ucfirst(request('tipo', 'empleados')) }}</h5>
        </div>

        @if(session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('empleados.index') }}" class="row m-3 align-items-end">
            <div class="col-md-3">
                <label for="tipo" class="form-label">Filtrar por tipo</label>
                <select name="tipo" id="tipo" class="form-select" onchange="this.form.submit()">
                    <option value="empleados" {{ request('tipo', 'empleados') == 'empleados' ? 'selected' : '' }}>Empleados</option>
                    <option value="medicos" {{ request('tipo') == 'medicos' ? 'selected' : '' }}>Médicos</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
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
            @forelse($empleados as $registro)
                <tr>
                    <td>{{ $registro->nombres }} {{ $registro->apellidos }}</td>
                    <td>{{ $registro->puesto->nombre ?? 'Sin puesto' }}</td>
                    <td>{{ \Carbon\Carbon::parse($registro->fecha_ingreso)->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="#" class="btn btn-info btn-sm" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-warning btn-sm" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay {{ ucfirst(request('tipo', 'empleados')) }} registrados.</td>
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
