@extends('layouts.app')

@section('title', 'Emergencias')

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
        max-width: 350px;
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
        border: 1px solid #dee2e6;
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
    #mensajeResultados {
        text-align: center;
        margin-top: 0.5rem;
        font-weight: 500;
        color: #555;
        min-height: 1.2em;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
            <a href="{{ route('inicio') }}" class="btn btn-light btn-inicio me-3">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <h2>Emergencias</h2>

            <a href="{{ route('emergencias.create') }}" class="btn btn-primary ms-3">
                <i class="bi bi-plus-circle"></i> Registrar Emergencia
            </a>
        </div>

        {{-- Filtros --}}
        <div class="d-flex filter-container">
            <div>
                <label class="filtro-label" for="filtroBusqueda">Paciente</label>
                <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar paciente..." autocomplete="off">
            </div>

            <div>
                <label class="filtro-label" for="fechaDesde">Desde</label>
                <input type="date" id="fechaDesde" class="form-control filtro-input">
            </div>

            <div>
                <label class="filtro-label" for="fechaHasta">Hasta</label>
                <input type="date" id="fechaHasta" class="form-control filtro-input">
            </div>

            <div style="align-self: flex-end;">
                <button id="btnRecargar" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        {{-- Contenedor de tabla --}}
        <div id="tabla-container" class="table-responsive">
            @include('emergencias.tabla', ['emergencias' => $emergencias, 'isSearch' => $isSearch])
        </div>

        <div id="mensajeResultados"></div>

        {{-- Paginación --}}
        <div id="paginacion-container" class="pagination-container mt-3">
            {{ $emergencias->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    function actualizarMensaje(total, all, query) {
        if (!query) $('#mensajeResultados').html('');
        else if (total === 0) $('#mensajeResultados').html(`No se encontraron resultados para "<strong>${query}</strong>".`);
        else $('#mensajeResultados').html(`<strong>Se encontraron ${total} resultado${total > 1 ? 's' : ''} de ${all}.</strong>`);
    }

    function cargarDatos(page = 1, query = '') {
        const fechaDesde = $('#fechaDesde').val();
        const fechaHasta = $('#fechaHasta').val();

        $.ajax({
            url: "{{ route('emergencias.index') }}",
            type: 'GET',
            data: { page, search: query, fecha_desde: fechaDesde, fecha_hasta: fechaHasta },
            success: function(data) {
                $('#tabla-container').html(data.html);
                $('#paginacion-container').html(data.pagination);
                actualizarMensaje(data.total, data.all, query);
            },
            error: function(xhr) {
                let msg = 'Error al cargar los datos.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg += ' ' + xhr.responseJSON.message;
                $('#mensajeResultados').html(msg);
            }
        });
    }

    // Carga inicial
    cargarDatos(1, $('#filtroBusqueda').val());

    // Eventos de filtro
    $('#filtroBusqueda').on('keyup', function () { cargarDatos(1, $(this).val()); });
    $('#fechaDesde, #fechaHasta').on('change', function () { cargarDatos(1, $('#filtroBusqueda').val()); });

    // Botón recargar
    $('#btnRecargar').on('click', function () {
        $('#filtroBusqueda').val('');
        $('#fechaDesde').val('');
        $('#fechaHasta').val('');
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
        cargarDatos(page, query);

        let newUrl = url.split('?')[0] + '?page=' + page;
        if(query) newUrl += '&search=' + encodeURIComponent(query);
        if(fechaDesde) newUrl += '&fecha_desde=' + encodeURIComponent(fechaDesde);
        if(fechaHasta) newUrl += '&fecha_hasta=' + encodeURIComponent(fechaHasta);
        window.history.pushState("", "", newUrl);
    });
});
</script>
@endsection
