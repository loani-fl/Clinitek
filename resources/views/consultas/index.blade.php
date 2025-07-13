@extends('layouts.app')

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
