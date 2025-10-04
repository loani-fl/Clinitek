@extends('layouts.app')

@section('title', 'Emergencias')

@section('content')
<style>
    /* Primera fila del encabezado más alta */
    .table thead tr:first-child {
        height: 40px;
    }

    /* Opcional: ajustar padding de las celdas del encabezado */
    .table thead tr:first-child th {
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;
    }

    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }
    .content-wrapper {
        margin-top: 8px;
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
        background-color: transparent !important;
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header h2 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #003366;
        margin: 0 auto;
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
    /* Primera columna: ID o número de fila */
    table th:nth-child(1), table td:nth-child(1) {
        width: 40px;
        text-align: center;
    }

    /* Nombre del paciente: un poco más ancho */
    table th:nth-child(2), table td:nth-child(2) {
        width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Apellidos: más pequeño para que quepa en una línea */
    table th:nth-child(3), table td:nth-child(3) {
        width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Tipo de sangre: columna más pequeña */
    table th:nth-child(4), table td:nth-child(4) {
        width: 30px;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Género: también pequeño */
    table th:nth-child(5), table td:nth-child(5) {
        width: 60px;
        text-align: center;
    }

    /* Hora: ajustable */
    table th:nth-child(6), table td:nth-child(6) {
        width: 80px;
        text-align: center;
    }

    /* Acciones: botón pequeño */
    table th:nth-child(7), table td:nth-child(7) {
        width: 80px;
        text-align: center;
    }

    #mensajeResultados {
        text-align: center;
        margin-top: 0.5rem;
        font-weight: 500;
        color: #555;
        min-height: 2.5em;
    }

    #filtroBusqueda.filtro-input {
        max-width: 450px !important;
    }

    /* Estilos de paginación personalizada */
    .custom-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.3rem;
    }
    
    .custom-pagination .page-item {
        list-style: none;
    }
    
    .custom-pagination .page-link {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        color: #007BFF;
        background-color: #fff;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .custom-pagination .page-link:hover:not(.disabled) {
        background-color: #007BFF;
        color: #fff;
        border-color: #007BFF;
    }
    
    .custom-pagination .page-item.active .page-link {
        background-color: #007BFF;
        color: #fff;
        border-color: #007BFF;
        font-weight: 600;
        cursor: default;
    }
    
    .custom-pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #e9ecef;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.6;
        
        pointer-events: none;
    }
    
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
            <a href="{{ route('inicio') }}" class="btn btn-light btn-inicio me-3">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <h2>Lista de emergencias</h2>

            <a href="{{ route('emergencias.create') }}" class="btn btn-primary ms-3">
                <i class="bi bi-plus-circle"></i> Registrar Emergencia
            </a>
        </div>

        {{-- Filtros --}}
        <div class="d-flex filter-container">
            <div>
                <label class="filtro-label" for="filtroBusqueda">Paciente</label>
                <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar paciente" autocomplete="off">
            </div>

            <div>
                <label class="filtro-label" for="fechaDesde">Desde</label>
                <input type="date" id="fechaDesde" class="form-control filtro-input">
            </div>

            <div>
                <label class="filtro-label" for="fechaHasta">Hasta</label>
                <input type="date" id="fechaHasta" class="form-control filtro-input">
            </div>

            <div>
                <label class="filtro-label" for="filtroDocumentado">Documentación</label>
                <select id="filtroDocumentado" class="form-select filtro-input">
                    <option value="">Todos</option>
                    <option value="1">Documentados</option>
                    <option value="0">Indocumentados</option>
                </select>
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
<div id="paginacion-container" class="mt-3">
    @if(!($isSearch ?? false))
        {{ $emergencias->onEachSide(1)->links('pagination::bootstrap-5') }}
    @endif
</div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    function actualizarMensaje(total, all, query, fechaDesde, fechaHasta, documentado, from, to) {
        let mensajeHTML = '';
        
        // Mensaje de resultados filtrados
        if (!query && !fechaDesde && !fechaHasta && !documentado) {
            mensajeHTML = '';
        } else if (total === 0) {
            mensajeHTML = `No se encontraron resultados.`;
        } else {
            mensajeHTML = `<strong>Se encontraron ${total} resultado${total > 1 ? 's' : ''} de ${all}.</strong><br>`;
        }
        
        // Información de paginación (siempre se muestra si hay resultados)
       // if (total > 0) {
           // mensajeHTML += `<small class="text-muted">Mostrando del <strong>${from}</strong> al <strong>${to}</strong> de <strong>${total}</strong> resultado${total > 1 ? 's' : ''}</small>`;
        //}
        
        $('#mensajeResultados').html(mensajeHTML);
    }

    function cargarDatos(page = 1, query = '') {
        const fechaDesde = $('#fechaDesde').val();
        const fechaHasta = $('#fechaHasta').val();
        const documentado = $('#filtroDocumentado').val();

        $.ajax({
            url: "{{ route('emergencias.index') }}",
            type: 'GET',
            data: { 
                page, 
                search: query, 
                fecha_desde: fechaDesde, 
                fecha_hasta: fechaHasta,
                documentado: documentado
            },
            success: function(data) {
                $('#tabla-container').html(data.html);
                $('#paginacion-container').html(data.pagination);
                
                // Calcular from y to para la paginación
                const total = data.totalFiltrado ?? data.total;
                const perPage = 3; // Debe coincidir con $perPage del controlador
                const from = total > 0 ? ((page - 1) * perPage) + 1 : 0;
                const to = Math.min(page * perPage, total);
                
                actualizarMensaje(total, data.all, query, fechaDesde, fechaHasta, documentado, from, to);
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

    // Filtros de búsqueda, fechas y documentación
    $('#filtroBusqueda').on('keyup', function () { cargarDatos(1, $(this).val()); });
    $('#fechaDesde, #fechaHasta').on('change', function () { cargarDatos(1, $('#filtroBusqueda').val()); });
    $('#filtroDocumentado').on('change', function () { cargarDatos(1, $('#filtroBusqueda').val()); });

    // Botón recargar
    $('#btnRecargar').on('click', function () {
        $('#filtroBusqueda').val('');
        $('#fechaDesde').val('');
        $('#fechaHasta').val('');
        $('#filtroDocumentado').val('');
        cargarDatos(1, '');
    });

    // Paginación dinámica - Actualizado para custom-pagination
    $(document).on('click', '.custom-pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const params = new URLSearchParams(url.split('?')[1]);
        const page = params.get('page') || 1;
        const query = $('#filtroBusqueda').val();

        cargarDatos(page, query);

        // Scroll suave hacia arriba (opcional)
        $('html, body').animate({
            scrollTop: $('.custom-card').offset().top - 20
        }, 300);
    });
});
</script>
@endsection