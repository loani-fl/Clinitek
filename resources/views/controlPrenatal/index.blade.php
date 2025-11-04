@extends('layouts.app')

@section('title', 'Control Prenatal')

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
        z-index: 1;
        max-width: 1000px;
        width: 100%;
    }
    .card-header {
        border-bottom: 3px solid #007BFF;
        margin-bottom: 1rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header h2 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #003366;
        margin: 0 auto;
    }
    .pagination-container {
        font-size: 0.9rem;
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
            <a href="{{ route('inicio') }}" class="btn btn-light btn-inicio me-3">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <h2>Control Prenatal</h2>

            <a href="{{ route('controles-prenatales.create') }}" class="btn btn-primary ms-3">
                <i class="bi bi-plus-circle"></i> Nuevo Control
            </a>
        </div>

        {{-- Filtros --}}
        <div class="d-flex filter-container mb-3">
            <div>
                <label for="filtroBusqueda" class="filtro-label">Paciente</label>
                <input type="text" id="filtroBusqueda" class="form-control filtro-input"
                       placeholder="Buscar paciente..." autocomplete="off" value="{{ request('search') }}">
            </div>
            <div>
                <label for="fechaDesde" class="filtro-label">Desde</label>
                <input type="date" id="fechaDesde" class="form-control filtro-input" value="{{ request('fecha_desde') }}">
            </div>
            <div>
                <label for="fechaHasta" class="filtro-label">Hasta</label>
                <input type="date" id="fechaHasta" class="form-control filtro-input" value="{{ request('fecha_hasta') }}">
            </div>
            <div style="align-self: flex-end;">
                <button id="btnRecargar" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        {{-- Tabla --}}
        <div id="tabla-container">
            @include('controlPrenatal.tabla', ['controles' => $controles])
        </div>

        <div id="mensajeResultados" class="text-center mt-3" style="min-height: 1.2em;"></div>

        {{-- Paginación --}}
        <div id="paginacion-container" class="pagination-container">
            @include('controlPrenatal.custom-pagination', [
                'currentPage' => $controles->currentPage(),
                'lastPage' => $controles->lastPage(),
                'hasMorePages' => $controles->hasMorePages(),
                'onFirstPage' => $controles->onFirstPage(),
                'from' => $controles->firstItem(),
                'to' => $controles->lastItem(),
                'total' => $controles->total(),
            ])
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

        $.ajax({
           url: "{{ route('ginecologia.index') }}",
            type: 'GET',
            data: {
                page,
                search: query,
                fecha_desde: fechaDesde,
                fecha_hasta: fechaHasta
            },
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

    // Inicial
    cargarDatos(1, $('#filtroBusqueda').val());

    // Eventos
    $('#filtroBusqueda').on('keyup', function () { cargarDatos(1, $(this).val()); });
    $('#fechaDesde, #fechaHasta').on('change', function () { cargarDatos(1, $('#filtroBusqueda').val()); });
    $('#btnRecargar').on('click', function () {
        $('#filtroBusqueda, #fechaDesde, #fechaHasta').val('');
        cargarDatos(1, '');
    });
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = new URLSearchParams($(this).attr('href').split('?')[1]).get('page') || 1;
        cargarDatos(page, $('#filtroBusqueda').val());
    });
});
</script>
@endsection
