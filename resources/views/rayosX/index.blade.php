@extends('layouts.app')

@section('title', 'Órdenes de Rayos X')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }
    .content-wrapper {
        margin-top: 50px;
        margin-left: auto;
        margin-right: auto;
        padding: 1rem;
        position: relative;
        max-width: 1000px;
        width: 100%;
    }
    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 800px;
        height: 800px;
        background-image: url('{{ asset("images/logo2.jpg") }}');
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
        padding: 1.5rem;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
        border-radius: 10px;
        z-index: 1;
        max-width: 1000px;
        width: 100%;
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        text-align: center;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header h2 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #003366;
        margin: 0 auto;
        flex-grow: 1;
        text-align: center;
    }
    .btn-inicio {
        font-size: 0.9rem;
    }
    .d-flex.filter-container {
        justify-content: flex-start;
        align-items: flex-end;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .filtro-input {
        font-size: 0.85rem;
        max-width: 200px;
        flex-grow: 1;
    }
    .filtro-label {
        font-size: 0.85rem;
        margin-bottom: 0;
        font-weight: 500;
    }
    .table {
        font-size: 0.85rem;
        width: 100%;
        border-collapse: collapse;
    }
    .table-responsive {
        flex-grow: 1;
        overflow-y: auto;
        max-width: 100%;
    }
    thead tr {
        background-color: #007BFF;
        color: white;
    }
    tbody tr:hover {
        background-color: #e9f2ff;
    }
    .table th, .table td {
        padding: 0.4rem 0.75rem;
        vertical-align: middle;
    }
    table th:nth-child(1), table td:nth-child(1) {
        width: 40px;
        text-align: center;
    }
    .pagination-container {
        font-size: 0.9rem;
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }
    .estado-circle {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        vertical-align: middle;
    }
    .estado-realizado {
        background-color: #28a745;
    }
    .estado-pendiente {
        background-color: yellow;
    }
    #datosPaciente {
        margin-top: 1.5rem;
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        font-size: 0.95rem;
        color: #444;
        box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    }
    .datos-paciente-row {
        display: flex;
        margin-bottom: 0.6rem;
    }
    .datos-paciente-label {
        flex: 0 0 100px;
        font-weight: 700;
        color: #007bff;
    }
    .underline-field {
        border-bottom: 1px dashed #333;
        flex: 1;
        user-select: none;
    }
    .status-legend {
        text-align: center;
        margin-top: 15px;
    }
    .status-legend span {
        display: inline-flex;
        align-items: center;
        margin-right: 20px;
        font-weight: 500;
        font-family: Arial, sans-serif;
        color: #333;
    }
    .status-circle {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        border: 1px solid #ccc;
    }
    .status-pending {
        background-color: yellow;
    }
    .status-done {
        background-color: green;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
            <a href="{{ route('inicio') }}" class="btn btn-light btn-inicio me-3">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <h2>Órdenes de rayos x</h2>

            <a href="{{ route('rayosx.create') }}" class="btn btn-primary ms-3">
                <i class="bi bi-plus-circle"></i> Registrar Orden
            </a>
        </div>

        {{-- Filtros --}}
        <div class="d-flex filter-container">
            <div>
                <label class="filtro-label" for="filtroBusqueda">Paciente</label>
                <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar paciente..." autocomplete="off" value="{{ request('search') }}">
            </div>

            <div>
                <label class="filtro-label" for="fechaDesde">Desde</label>
                <input type="date" id="fechaDesde" class="form-control filtro-input" value="{{ request('fecha_desde') }}">
            </div>

            <div>
                <label class="filtro-label" for="fechaHasta">Hasta</label>
                <input type="date" id="fechaHasta" class="form-control filtro-input" value="{{ request('fecha_hasta') }}">
            </div>

            <div>
                <label class="filtro-label" for="filtroEstado">Estado</label>
                <select id="filtroEstado" class="form-control filtro-input">
                    <option value="">-- Todos --</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Realizado" {{ request('estado') == 'Realizado' ? 'selected' : '' }}>Realizado</option>
                </select>
            </div>

            <div style="align-self: flex-end;">
                <button id="btnRecargar" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <div id="tabla-container" class="table-responsive">
            @include('rayosx.partials.tabla', ['ordenes' => $ordenes, 'isSearch' => $isSearch ?? false])
        </div>

        <div id="mensajeResultados" class="text-center mt-3" style="min-height: 1.2em;"></div>

        <div id="paginacion-container" class="pagination-container">
            @if(!($isSearch ?? false))
                {{ $ordenes->onEachSide(1)->links('pagination::bootstrap-5') }}
            @endif
        </div>

        <div id="datosPaciente" style="display: {{ old('paciente_id') ? 'block' : 'none' }};">
            <div class="datos-paciente-row">
                <div class="datos-paciente-label">Nombres:</div>
                <div id="dp-nombres" class="underline-field">{{ old('nombres') }}</div>
            </div>
            <div class="datos-paciente-row">
                <div class="datos-paciente-label">Apellidos:</div>
                <div id="dp-apellidos" class="underline-field">{{ old('apellidos') }}</div>
            </div>
            <div class="datos-paciente-row">
                <div class="datos-paciente-label">Género:</div>
                <div id="dp-genero" class="underline-field">{{ old('genero') }}</div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    function actualizarMensaje(total, all, query) {
        if (query === '') {
            $('#mensajeResultados').html('');
        } else if (total === 0) {
            $('#mensajeResultados').html(`No se encontraron resultados para "<strong>${query}</strong>".`);
        } else {
            $('#mensajeResultados').html(`<strong>Se encontraron ${total} resultado${total > 1 ? 's' : ''} de ${all}.</strong>`);
        }
    }

    function cargarDatos(page = 1, query = '') {
        const fechaDesde = $('#fechaDesde').val();
        const fechaHasta = $('#fechaHasta').val();
        const estado = $('#filtroEstado').val();

        $.ajax({
            url: "{{ route('rayosx.index') }}",
            type: 'GET',
            data: {
                page,
                search: query,
                fecha_desde: fechaDesde,
                fecha_hasta: fechaHasta,
                estado: estado
            },
            success: function(data) {
                $('#tabla-container').html(data.html);
                $('#paginacion-container').html(data.pagination);
                actualizarMensaje(data.total, data.all, query);
            },
            error: function(xhr) {
                let msg = 'Error al cargar los datos.';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    msg += ' ' + xhr.responseJSON.message;
                }
                $('#mensajeResultados').html(msg);
            }
        });
    }

    // Carga inicial
    cargarDatos(1, $('#filtroBusqueda').val());

    // Eventos de filtro
    $('#filtroBusqueda').on('keyup', function () { cargarDatos(1, $(this).val()); });
    $('#fechaDesde, #fechaHasta, #filtroEstado').on('change', function () { cargarDatos(1, $('#filtroBusqueda').val()); });

    // Botón recargar
    $('#btnRecargar').on('click', function () {
        $('#filtroBusqueda').val('');
        $('#fechaDesde').val('');
        $('#fechaHasta').val('');
        $('#filtroEstado').val('');
        cargarDatos(1, '');
    });

    // Paginación dinámica
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const params = new URLSearchParams(url.split('?')[1]);
        const page = params.get('page') || 1;
        const query = $('#filtroBusqueda').val();
        const fechaDesde = $('#fechaDesde').val();
        const fechaHasta = $('#fechaHasta').val();
        const estado = $('#filtroEstado').val();

        cargarDatos(page, query);

        let newUrl = url.split('?')[0] + '?page=' + page;
        if(query) newUrl += '&search=' + encodeURIComponent(query);
        if(fechaDesde) newUrl += '&fecha_desde=' + encodeURIComponent(fechaDesde);
        if(fechaHasta) newUrl += '&fecha_hasta=' + encodeURIComponent(fechaHasta);
        if(estado) newUrl += '&estado=' + encodeURIComponent(estado);
        window.history.pushState("", "", newUrl);
    });
});
</script>
@endsection
