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

<<<<<<< HEAD
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

=======
@section('content')
<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }
    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 1100;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        height: 60px;
        display: flex;
        align-items: center;
    }
    .content-wrapper {
        margin-top: 40px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
        padding: 2rem;
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
    .custom-card {
        background-color: #fff;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 12px 30px 5px rgba(0, 0, 0, 0.18);
        width: 100%;
        max-width: 1200px;
        position: relative;
        z-index: 1;
    }
    .card-header {
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .alert-success-custom {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 0.75rem 1.25rem;
        border-radius: 0.25rem;
        position: relative;
        z-index: 1200;
        margin-top: 1rem;
    }
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .filter-row > div {
        flex: 1 1 150px;
        min-width: 120px;
    }
    .estado-circulo {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        margin: 0 auto;
        vertical-align: middle;
    }
    .estado-realizado {
        background-color: #28a745;
    }
    .estado-pendiente {
        background-color: #ffc107;
    }
    .estado-cancelado {
        background-color: #dc3545;
    }
    .estado-leyenda {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 1.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        color: #444;
    }
</style>

<!-- Barra de navegación fija
<div class="header d-flex justify-content-between align-items-center px-3 py-2">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
        <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
        <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>
-->

<div class="content-wrapper">
    <div class="card custom-card">

        <div class="card-header">
            <h2 class="fw-bold text-black mb-0">Consultas médicas registradas</h2>
            <a href="{{ route('inicio') }}" class="btn btn-light">
                <i class="bi bi-house-door"></i> Inicio
            </a>
        </div>

        @if(session('success'))
            <div id="mensaje-exito" class="alert alert-success alert-dismissible fade show alert-success-custom">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif




        <div class="card-body">

            <div class="filter-row">
                <div>
                    <label>Filtrar por Médico:</label>
                    <select id="medico" class="form-select form-select-sm">
                        <option value="">-- Todos los Médicos --</option>
                        @foreach($medicos as $medico)
                            <option value="{{ strtolower($medico->nombre . ' ' . $medico->apellidos) }}">
                                {{ $medico->nombre }} {{ $medico->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Buscar Paciente:</label>
                    <input type="text" id="busqueda" class="form-control form-control-sm" placeholder="Nombre del paciente...">
                </div>
                <div>
                    <label>Desde:</label>
                    <input type="date" id="fechaInicio" class="form-control form-control-sm">
                </div>
                <div>
                    <label>Hasta:</label>
                    <input type="date" id="fechaFin" class="form-control form-control-sm">
                </div>
                <div>
                    <label>Filtrar por Estado:</label>
                    <select id="estadoFiltro" class="form-select form-select-sm">
                        <option value="">-- Todos los Estados --</option>
                        <option value="realizado">Realizado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="cancelada">Cancelada</option> <!-- Aquí corrigió a "cancelada" -->
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto mt-3">
                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-consultas">
                        @foreach($consultas as $index => $consulta)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $consulta->paciente->nombre ?? '' }} {{ $consulta->paciente->apellidos ?? '' }}</td>
                                <td>{{ $consulta->medico->nombre ?? '' }} {{ $consulta->medico->apellidos ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $consulta->hora ? \Carbon\Carbon::parse($consulta->hora)->format('g:i A') : 'Inmediata' }}</td>
                                <td>
                                    @php
                                        $estado = strtolower($consulta->estado);
                                        $claseCirculo = match($estado) {
                                            'realizada' => 'estado-realizado',
                                            'pendiente' => 'estado-pendiente',
                                            'cancelada' => 'estado-cancelado',
                                            default => ''
                                        };
                                    @endphp
                                    <span class="estado-circulo {{ $claseCirculo }}" title="{{ $estado }}"></span>
                                </td>
                                <td>
                                    <a href="{{ route('consultas.show', $consulta->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('consultas.edit', $consulta->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>
                        @endforeach

                                                <tr id="sin-resultados" style="display: none;">
                            <td colspan="7" class="text-center fst-italic text-muted">No hay resultados que mostrar</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div id="contador-resultados" class="mt-2 text-center fw-bold"></div>

            <div class="estado-leyenda">
                <div><span class="estado-circulo estado-realizado"></span> Realizado</div>
                <div><span class="estado-circulo estado-pendiente"></span> Pendiente</div>
                <div><span class="estado-circulo estado-cancelado"></span> Cancelada</div> <!-- Texto corregido -->
            </div>

            <div class="mt-4 pagination-container">
                {{ $consultas->links() }}
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const medicoSelect = document.getElementById('medico');
    const busquedaInput = document.getElementById('busqueda');
    const fechaInicioInput = document.getElementById('fechaInicio');
    const fechaFinInput = document.getElementById('fechaFin');
    const estadoFiltroSelect = document.getElementById('estadoFiltro');
    const tabla = document.getElementById('tabla-consultas');
    const contadorResultados = document.getElementById('contador-resultados');
    const filasOriginales = Array.from(tabla.querySelectorAll('tr:not(#sin-resultados)'));
    const filaSinResultados = document.getElementById('sin-resultados');

    const hoy = new Date();
    const dosMesesAtras = new Date();
    dosMesesAtras.setMonth(hoy.getMonth() - 2);

    const formatoFecha = fecha => fecha.toISOString().split('T')[0];

    const minFecha = formatoFecha(dosMesesAtras);
    const maxFecha = formatoFecha(hoy);

    fechaInicioInput.min = minFecha;
    fechaInicioInput.max = maxFecha;
    fechaFinInput.min = minFecha;
    fechaFinInput.max = maxFecha;

    fechaInicioInput.value = minFecha;
    fechaFinInput.value = maxFecha;

    function actualizarContador(cantidad) {
        const textoBusquedaVacio = busquedaInput.value.trim() === "";
        const medicoSeleccionadoVacio = medicoSelect.value.trim() === "";
        const fechaInicioDefault = fechaInicioInput.value === minFecha;
        const fechaFinDefault = fechaFinInput.value === maxFecha;
        const estadoSeleccionadoVacio = estadoFiltroSelect.value.trim() === "";

        if (cantidad > 0 && (!textoBusquedaVacio || !medicoSeleccionadoVacio || !fechaInicioDefault || !fechaFinDefault || !estadoSeleccionadoVacio)) {
            contadorResultados.style.display = 'block';
            contadorResultados.textContent = `${cantidad} resultado${cantidad !== 1 ? 's' : ''} encontrado${cantidad !== 1 ? 's' : ''}`;
        } else {
            contadorResultados.style.display = 'none';
            contadorResultados.textContent = '';
        }
    }

    function filtrarTabla() {
        const medicoSeleccionado = medicoSelect.value.toLowerCase();
        const textoBusqueda = busquedaInput.value.toLowerCase().trim();
        const fechaInicio = fechaInicioInput.value;
        const fechaFin = fechaFinInput.value;
        const estadoSeleccionado = estadoFiltroSelect.value.toLowerCase();

        tabla.innerHTML = '';
        let cantidadVisible = 0;

        filasOriginales.forEach(fila => {
            const celdas = fila.querySelectorAll('td');
            const nombrePaciente = celdas[1]?.innerText.toLowerCase().trim();
            const nombreMedico = celdas[2]?.innerText.toLowerCase().trim();
            const fechaTexto = celdas[3]?.innerText.trim();

            const estadoCelda = celdas[5]?.querySelector('span.estado-circulo')?.getAttribute('title').toLowerCase() || '';

            const partesFecha = fechaTexto.split('/');
            const fechaConsulta = partesFecha.length === 3 ? `${partesFecha[2]}-${partesFecha[1].padStart(2, '0')}-${partesFecha[0].padStart(2, '0')}` : '';

            const coincideMedico = medicoSeleccionado === '' || nombreMedico.includes(medicoSeleccionado);
            const coincidePaciente = textoBusqueda === '' || nombrePaciente.startsWith(textoBusqueda);

            let coincideFecha = true;
            if (fechaInicio && fechaConsulta < fechaInicio) coincideFecha = false;
            if (fechaFin && fechaConsulta > fechaFin) coincideFecha = false;

            const coincideEstado = estadoSeleccionado === '' || estadoCelda === estadoSeleccionado;

            if (coincideMedico && coincidePaciente && coincideFecha && coincideEstado) {
                cantidadVisible++;
                celdas[0].textContent = cantidadVisible;
                tabla.appendChild(fila);
            }
        });

        // Mostrar fila "No hay resultados" si no hay nada visible
        if (cantidadVisible === 0) {
            filaSinResultados.style.display = '';
            tabla.appendChild(filaSinResultados);
        } else {
            filaSinResultados.style.display = 'none';
        }

        actualizarContador(cantidadVisible);
    }

    medicoSelect.addEventListener('change', filtrarTabla);
    busquedaInput.addEventListener('keyup', filtrarTabla);
    fechaInicioInput.addEventListener('change', filtrarTabla);
    fechaFinInput.addEventListener('change', filtrarTabla);
    estadoFiltroSelect.addEventListener('change', filtrarTabla);

    filtrarTabla();
});

</script>

@endsection
>>>>>>> 518e4da0b585a1b447a81f857362ece5f7e7517b
