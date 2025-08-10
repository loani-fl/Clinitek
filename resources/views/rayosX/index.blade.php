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

    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 1100;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
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
    }

    .card-header h2 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #003366;
        margin: 0;
    }

    .btn-inicio {
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        font-size: 0.9rem;
    }

    .d-flex.filter-container {
        justify-content: flex-start;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .filtro-input {
        font-size: 0.85rem;
        max-width: 300px;
        flex-grow: 1;
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
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <a href="{{ route('inicio') }}" class="btn btn-light me-3">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <h2 class="fw-bold mb-0 flex-grow-1 text-center">Órdenes de Rayos X</h2>

            <a href="{{ route('rayosx.create') }}" class="btn btn-primary ms-3">
                <i class="bi bi-plus-circle"></i> Registrar Orden
            </a>
        </div>

        <div class="d-flex filter-container">
            <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar paciente...">
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
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    function actualizarMensaje(total, all, query) {
        if (query === '') {
            $('#mensajeResultados').html('');
        } else if (total === 0) {
            $('#mensajeResultados').html(`No se encontraron resultados para "<strong>${query}</strong>" de un total de ${all}.`);
        } else {
            $('#mensajeResultados').html(`<strong>Se encontraron ${total} resultado${total > 1 ? 's' : ''} de ${all}.</strong>`);
        }
    }

    function cargarDatos(page = 1, query = '') {
        $.ajax({
            url: "{{ route('rayosx.index') }}",
            type: 'GET',
            data: { page, search: query },
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

    // Carga inicial sin filtro
    cargarDatos();

    // Filtrar al escribir con debounce para no saturar peticiones
    let typingTimer;
    $('#filtroBusqueda').on('keyup', function () {
        clearTimeout(typingTimer);
        let query = $(this).val();
        typingTimer = setTimeout(() => cargarDatos(1, query), 300);
    });

    // Paginación dinámica (delegación)
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let params = new URLSearchParams(url.split('?')[1]);
        let page = params.get('page') || 1;
        let query = $('#filtroBusqueda').val();
        cargarDatos(page, query);

        // Actualizar URL sin recargar página
        let newUrl = url.split('?')[0] + '?page=' + page;
        if (query) newUrl += '&search=' + encodeURIComponent(query);
        window.history.pushState("", "", newUrl);
    });
});
</script>
@endsection
