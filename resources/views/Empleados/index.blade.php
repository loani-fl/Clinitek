<!DOCTYPE html>
<html>
<head>
    <title>Listado de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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

        /* Botones "Crear puesto" y "Registrar empleado" con fondo blanco fijo y sin efecto */
        .btn-white-bg {
            background-color: white !important;
            color: black !important;
            border-color: black !important;
            box-shadow: none !important;
            transition: none !important;
            cursor: pointer;
            padding: 0.5rem 1.2rem;
            font-size: 1rem;
        }
        .btn-white-bg:hover,
        .btn-white-bg:focus,
        .btn-white-bg:active {
            background-color: white !important;
            color: black !important;
            border-color: black !important;
            box-shadow: none !important;
            outline: none !important;
        }

        /* Botones restantes mantendrán su estilo normal Bootstrap pero con padding cómodo */
        .btn {
            padding: 0.5rem 1.2rem;
            font-size: 1rem;
        }

        /* Buscador ancho fijo para escritorio */
        form.row > div.col-md-4 input {
            max-width: 400px;
        }
    </style>
</head>
<body>
<div class="container mt-4">

    {{-- Botones superiores --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="color: #007bff;">Clinitek</h4>
        <div>
            <a href="#" class="btn btn-outline-primary me-2 btn-white-bg">
                <i class="bi bi-briefcase"></i> Crear puesto
            </a>
            <a href="{{ route('empleados.create') }}" class="btn btn-outline-dark btn-white-bg">
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

        {{-- Buscador --}}
        <form method="GET" action="{{ route('empleados.index') }}" class="row m-3">
            <div class="col-md-4">
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por nombre o puesto">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('empleados.index') }}" class="btn btn-secondary w-100">Limpiar</a>
            </div>
        </form>

        {{-- Tabla --}}
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
                    <td>{{ $empleado->nombre }}</td>
                    <td>{{ $empleado->puesto }}</td>
                    <td>{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('empleados.show', $empleado->id) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este empleado?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay empleados registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        <div class="p-3">
            {{ $empleados->links() }}
        </div>
    </div>
</div>
</body>
</html>
