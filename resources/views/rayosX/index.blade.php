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
        margin-top: 70px;
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
        position: relative;
        background-color: transparent !important;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 3px solid #007BFF;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header h2 {
        color: #000 !important;
        margin: 0 auto;
        font-weight: 700;
        font-size: 2rem; /* título más grande */
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
    }
    .btn-group-left, .btn-group-right {
        display: flex;
        gap: 0.5rem;
        z-index: 2;
    }
    .filter-row {
        margin-bottom: 1rem;
    }
    .estado-circulo {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 5px;
    }
    .estado-realizado {
        background-color: #28a745;
    }
    .estado-pendiente {
        background-color: #ffc107;
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

<div class="content-wrapper">
    <div class="card custom-card">

        <div class="card-header">
            <div class="btn-group-left">
                <a href="{{ route('inicio') }}" class="btn btn-light">
                    <i class="bi bi-house-door"></i> Inicio
                </a>
            </div>

            <h2>Órdenes de Rayos X</h2>

            <div class="btn-group-right">
                <a href="{{ route('rayosx.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Registrar Orden
                </a>
            </div>
        </div>

        @if(session('success'))
            <div id="mensaje-exito" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body">

            {{-- Barra de búsqueda centrada y más corta --}}
            <div class="filter-row d-flex justify-content-center">
                <div style="max-width: 300px; width: 100%;">
                    <input type="text" id="busqueda" class="form-control form-control-sm" placeholder="Buscar paciente...">
                </div>
            </div>

            <div class="overflow-x-auto mt-3">
                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th>Diagnóstico</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-rayosx">
                        @foreach($ordenes as $index => $orden)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $orden->diagnostico->paciente->nombre ?? '' }} {{ $orden->diagnostico->paciente->apellidos ?? '' }}</td>
                            <td>{{ $orden->diagnostico->titulo ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @php
                                    $estado = strtolower($orden->estado);
                                    $claseCirculo = $estado === 'realizado' ? 'estado-realizado' : 'estado-pendiente';
                                @endphp
                                <span class="estado-circulo {{ $claseCirculo }}" title="{{ ucfirst($estado) }}"></span>
                            </td>
                            <td>
                                <a href="{{ route('rayosx.show', $orden->id) }}" class="btn btn-sm btn-outline-primary" title="Ver orden">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach

                        <tr id="sin-resultados" style="display: none;">
                            <td colspan="6" class="text-center fst-italic text-muted">No hay resultados que mostrar</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="contador-resultados" class="mt-2 text-center fw-bold"></div>

            {{-- Leyenda de estados --}}
            <div class="estado-leyenda">
                <div><span class="estado-circulo estado-realizado"></span> Realizado</div>
                <div><span class="estado-circulo estado-pendiente"></span> Pendiente</div>
            </div>

            <div class="mt-4 pagination-container">
                {{ $ordenes->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const busquedaInput = document.getElementById('busqueda');
    const tabla = document.getElementById('tabla-rayosx');
    const contadorResultados = document.getElementById('contador-resultados');
    const filasOriginales = Array.from(tabla.querySelectorAll('tr:not(#sin-resultados)'));
    const filaSinResultados = document.getElementById('sin-resultados');

    function actualizarContador(cantidad) {
        if (cantidad > 0) {
            contadorResultados.style.display = 'block';
            contadorResultados.textContent = `${cantidad} resultado${cantidad !== 1 ? 's' : ''} encontrado${cantidad !== 1 ? 's' : ''}`;
        } else {
            contadorResultados.style.display = 'none';
            contadorResultados.textContent = '';
        }
    }

    function filtrarTabla() {
        const textoBusqueda = busquedaInput.value.toLowerCase().trim();

        tabla.innerHTML = '';
        let cantidadVisible = 0;

        filasOriginales.forEach(fila => {
            const celdas = fila.querySelectorAll('td');
            const nombrePaciente = celdas[1]?.innerText.toLowerCase().trim();

            const coincidePaciente = textoBusqueda === '' || nombrePaciente.startsWith(textoBusqueda);

            if (coincidePaciente) {
                cantidadVisible++;
                celdas[0].textContent = cantidadVisible;
                tabla.appendChild(fila);
            }
        });

        if (cantidadVisible === 0) {
            filaSinResultados.style.display = '';
            tabla.appendChild(filaSinResultados);
        } else {
            filaSinResultados.style.display = 'none';
        }

        actualizarContador(cantidadVisible);
    }

    busquedaInput.addEventListener('keyup', filtrarTabla);

    filtrarTabla();
});
</script>
@endsection
