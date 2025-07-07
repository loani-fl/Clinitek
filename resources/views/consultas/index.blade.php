@extends('layouts.app')

@section('content')

<!-- Barra azul completa ancho -->
<div class="d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF; position: sticky; top: 0; width: 100%; z-index: 1030;">
    <div class="d-flex align-items-center">
        <!-- Logo sin fondo -->
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto;">
        <span class="ms-2 text-white small fw-light">Clinitek</span>
    </div>

    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('inicio') }}" class="btn btn-light btn-sm">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        <a href="{{ route('consultas.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Nueva consulta
        </a>
    </div>
</div>

<!-- Contenedor principal con card blanca -->
<div class="content-wrapper" style="background-color: #f0f4f7; min-height: 100vh; padding: 2rem;">
    <div class="card custom-card shadow-sm">

        {{-- Encabezado estilizado --}}
        <div class="card-header text-center py-3" style="background-color: transparent; border-bottom: 3px solid #007BFF;">
            <h2 class="mb-0 fw-bold text-black" style="font-size: 2.25rem;">Consultas médicas registradas</h2>
        </div>

        <div class="card-body">
            {{-- Filtros automáticos --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="buscarPaciente">Buscar paciente:</label>
                    <input type="text" id="buscarPaciente" class="form-control" placeholder="Buscar paciente...">
                </div>

                <div class="col-md-4">
                    <label for="medico">Filtrar por médico:</label>
                    <select name="medico_id" id="medico" class="form-control">
                        <option value="">Todos los médicos</option>
                        @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}" {{ request('medico_id') == $medico->id ? 'selected' : '' }}>
                                {{ $medico->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="desde">Desde:</label>
                    <input type="date" id="desde" name="desde" class="form-control" value="{{ request('desde') }}">
                </div>

                <div class="col-md-2">
                    <label for="hasta">Hasta:</label>
                    <input type="date" id="hasta" name="hasta" class="form-control" value="{{ request('hasta') }}">
                </div>
            </div>

            {{-- Tabla de consultas --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaConsultas">
                        @forelse ($consultas as $consulta)
                            <tr data-paciente-id="{{ $consulta->paciente->id }}" data-medico-id="{{ $consulta->medico->id }}">
                                <td>{{ $consulta->id }}</td>
                                <td class="nombre-paciente">{{ $consulta->paciente->nombre }}</td>
                                <td>{{ $consulta->medico->nombre }}</td>
                                <td>{{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($consulta->hora)->format('h:i A') }}</td>
                                <td>
                                    <a href="{{ route('consultas.show', $consulta->id) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('consultas.edit', $consulta->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr id="sinResultados">
                                <td colspan="6">No se encontraron resultados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $consultas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Estilo personalizado para fondo con logo --}}
<style>
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
        max-width: 1200px;
        background-color: #fff;
        border-color: #91cfff;
        position: relative;
        overflow: hidden;
        margin: 2rem auto;
    }
</style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputPaciente = document.getElementById('buscarPaciente');
    const selectMedico = document.getElementById('medico');
    const tbody = document.getElementById('tablaConsultas');
    const filaSinResultadosId = 'sinResultados';

    function filtrar() {
        const textoPaciente = inputPaciente.value.toLowerCase().trim();
        const medicoSeleccionado = selectMedico.value;

        let filasVisibles = 0;

        for (const fila of tbody.querySelectorAll('tr')) {
            // Ignorar fila "No hay resultados"
            if (fila.id === filaSinResultadosId) continue;

            const nombrePaciente = fila.querySelector('.nombre-paciente').textContent.toLowerCase();
            const medicoId = fila.getAttribute('data-medico-id');

            // Filtrado paciente: el texto debe estar en el nombre del paciente
            const coincidePaciente = nombrePaciente.includes(textoPaciente);

            // Filtrado médico: si hay médico seleccionado, debe coincidir el paciente que tenga ese médico
            const coincideMedico = medicoSeleccionado === '' || medicoId === medicoSeleccionado;

            if (coincidePaciente && coincideMedico) {
                fila.style.display = '';
                filasVisibles++;
            } else {
                fila.style.display = 'none';
            }
        }

        // Mostrar mensaje "No se encontraron resultados" si no hay filas visibles
        let filaSinResultados = document.getElementById(filaSinResultadosId);
        if (!filaSinResultados) {
            filaSinResultados = document.createElement('tr');
            filaSinResultados.id = filaSinResultadosId;
            filaSinResultados.innerHTML = '<td colspan="6" class="text-center">No se encontraron resultados.</td>';
            tbody.appendChild(filaSinResultados);
        }

        filaSinResultados.style.display = filasVisibles === 0 ? '' : 'none';
    }

    // Eventos para filtrar en vivo
    inputPaciente.addEventListener('input', filtrar);
    selectMedico.addEventListener('change', filtrar);

    // Ejecutar filtro al cargar para respetar filtro médico por defecto si hay
    filtrar();
});
</script>
@endsection