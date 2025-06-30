<!DOCTYPE html>
<html>
<head>
    <title>Consultas Médicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #e8f4fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            background-color: #007BFF;
        }
        .header .fw-bold {
            font-size: 1.75rem;
            color: #0d6efd;
        }
        .contenedor-principal {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: start;
            padding: 0 3rem;
            margin: 0;
            width: 100vw;
        }
        .custom-card {
            flex-grow: 1;
            background-color: #ffffff;
            border-color: #91cfff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            max-width: 1000px;
            width: 100%;
            padding: 1.5rem;
        }
        .custom-card::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 800px;
            height: 800px;
            background-image: url('/images/logo2.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.15;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }
        .card-header {
            background-color: transparent !important;
            border: none;
        }
        .card-header h5 {
            color: #0d6efd;
            font-weight: bold;
        }
        .table-responsive {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0 1rem 1rem 1rem;
        }
        thead tr {
            background-color: #cce5ff;
            color: #003e7e;
        }
        tbody tr:hover {
            background-color: #e9f2ff;
        }
        table tbody tr {
            height: 50px;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100vw;
            height: 50px;
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
<div class="header">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; margin-right: 6px;">
        <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('inicio') }}" class="text-white fw-semibold">Inicio</a>
        <a href="{{ route('consultas.create') }}" class="text-white fw-semibold">Nueva consulta</a>
    </div>
</div>

<div class="card custom-card mx-auto mt-4 position-relative">
    <div class="card-header py-2 border-bottom border-primary">
        <h5 class="text-center">Consultas Médicas Registradas</h5>
    </div>

    <form method="GET" action="{{ route('consultas.index') }}" class="row px-3 pt-2">
        <div class="col-md-4">
            <label for="fecha">Filtrar por Fecha:</label>
            <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}" class="form-control">
        </div>
        <div class="col-md-5">
            <label for="paciente_id">Filtrar por Paciente:</label>
            <select name="paciente_id" id="paciente_id" class="form-select">
                <option value="">-- Todos los pacientes --</option>
                @foreach ($pacientes as $paciente)
                    <option value="{{ $paciente->id }}" {{ request('paciente_id') == $paciente->id ? 'selected' : '' }}>
                        {{ $paciente->nombre }} {{ $paciente->apellidos }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
                <i class="bi bi-funnel-fill"></i> Filtrar
            </button>
            <a href="{{ route('consultas.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Limpiar
            </a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success mt-3 mx-3">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover mt-3">
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th>Paciente</th>
                    <th>Médico</th>
                    <th>Especialidad</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($consultas as $consulta)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $consulta->paciente->nombre ?? 'Paciente eliminado' }} {{ $consulta->paciente->apellidos ?? '' }}</td>
                        <td>{{ $consulta->medico->nombre ?? 'Médico eliminado' }} {{ $consulta->medico->apellidos ?? '' }}</td>
                        <td>{{ $consulta->especialidad }}</td>
                        <td>{{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @if(is_null($consulta->hora))
                                <span class="badge bg-success">Inmediata</span>
                            @else
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $consulta->hora)->format('h:i A') }}
                            @endif
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($consulta->motivo, 30) }}</td>
                        <td class="text-center">
                            <a href="{{ route('consultas.show', $consulta->id) }}" class="btn btn-sm btn-info" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('consultas.edit', $consulta->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay consultas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $consultas->appends(request()->query())->links() }}
    </div>
</div>

<footer>
    © 2025 Clínitek. Todos los derechos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

