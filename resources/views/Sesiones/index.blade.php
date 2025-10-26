
@extends('layouts.app')

@section('title', 'Sesiones Psicológicas')

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
        max-width: 1200px;
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
        max-width: 1200px;
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
        padding: 0.4rem 0.5rem;
        vertical-align: middle;
        border: 1px solid #dee2e6;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Anchos de columnas */
    table th:nth-child(1), table td:nth-child(1) { width: 40px; text-align: center; }
    table th:nth-child(2), table td:nth-child(2) { width: 150px; }
    table th:nth-child(3), table td:nth-child(3) { width: 50px; text-align: center; }
    table th:nth-child(4), table td:nth-child(4) { width: 70px; text-align: center; }
    table th:nth-child(5), table td:nth-child(5) { width: 90px; }
    table th:nth-child(6), table td:nth-child(6) { width: 130px; }
    table th:nth-child(7), table td:nth-child(7) { width: 90px; text-align: center; }
    table th:nth-child(8), table td:nth-child(8) { width: 80px; text-align: center; }
    table th:nth-child(9), table td:nth-child(9) { width: 80px; text-align: center; }
    table th:nth-child(10), table td:nth-child(10) { width: 120px; }
    table th:nth-child(11), table td:nth-child(11) { width: 120px; text-align: center; }

    #mensajeResultados {
        text-align: center;
        margin-top: 0.5rem;
        font-weight: 500;
        color: #555;
        min-height: 2.5em;
    }

    /* Estilos de paginación personalizada */
    .custom-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0;
        padding: 0;
        list-style: none;
    }
    
    .custom-pagination .page-item {
        list-style: none;
        margin: 0;
    }
    
    .custom-pagination .page-link {
        display: inline-block;
        padding: 0.5rem 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 0;
        margin-left: -1px;
        color: #007BFF;
        background-color: #fff;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    
    .custom-pagination .page-item:first-child .page-link {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
        margin-left: 0;
    }
    
    .custom-pagination .page-item:last-child .page-link {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
    
    .custom-pagination .page-link:hover:not(.disabled) {
        background-color: #007BFF;
        color: #fff;
        border-color: #007BFF;
        z-index: 2;
    }
    
    .custom-pagination .page-item.active .page-link {
        background-color: #007BFF;
        color: #fff;
        border-color: #007BFF;
        font-weight: 600;
        cursor: default;
        z-index: 3;
    }
    
    .custom-pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #e9ecef;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.6;
        pointer-events: none;
    }
    
    .pagination-info {
        text-align: center;
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.75rem;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
            <a href="{{ route('inicio') }}" class="btn btn-light btn-inicio me-3">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <h2>Sesiones psicológicas</h2>

            <a href="{{ route('sesiones.create') }}" class="btn btn-primary ms-3">
                <i class="bi bi-plus-circle"></i> Nueva sesión
            </a>
        </div>

        {{-- Filtros --}}
        <div class="d-flex filter-container">
            <div>
                <label class="filtro-label" for="filtroBusqueda">Paciente</label>
                <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar paciente" autocomplete="off">
            </div>

            <div>
                <label class="filtro-label" for="filtroMedico">Médico</label>
                <input type="text" id="filtroMedico" class="form-control filtro-input" placeholder="Buscar médico" autocomplete="off">
            </div>

            <div>
                <label class="filtro-label" for="fechaDesde">Desde</label>
                <input type="date" id="fechaDesde" class="form-control filtro-input">
            </div>

            <div>
                <label class="filtro-label" for="fechaHasta">Hasta</label>
                <input type="date" id="fechaHasta" class="form-control filtro-input">
            </div>

           @php
    $examenes = [
        "Test de Inteligencia (WAIS)","Test de Raven","Test de Bender",
        "Test de Machover (Figura Humana)","Test de la Casa-Árbol-Persona (HTP)",
        "Test de Luscher (Colores)","Test de 16 Factores de Personalidad (16PF)",
        "Inventario Multifásico de Personalidad de Minnesota (MMPI)","Inventario de Ansiedad de Beck (BAI)",
        "Inventario de Depresión de Beck (BDI)","Escala de Autoestima de Rosenberg",
        "Test de Apercepción Temática (TAT)","Test de la Figura Compleja de Rey",
        "Test de Aptitudes Diferenciales (DAT)","Test de Dominós D48","Test Cleaver","Test DISC"
    ];
@endphp

<div>
    <label class="filtro-label" for="filtroTipoExamen">Tipo de examen</label>
    <select id="filtroTipoExamen" class="form-select filtro-input">
        <option value="">Todos</option>
        @foreach ($examenes as $examen)
            <option value="{{ $examen }}">{{ $examen }}</option>
        @endforeach
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
            @include('sesiones.tabla', ['sesiones' => $sesiones, 'isSearch' => $isSearch])
        </div>

        <div id="mensajeResultados"></div>

        {{-- Paginación --}}
        <div id="paginacion-container" class="mt-3">
            @if(!($isSearch ?? false))
                {{ $sesiones->onEachSide(1)->links('pagination::bootstrap-5') }}
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    function actualizarMensaje(total, all, query, medico, fechaDesde, fechaHasta, tipoExamen) {
        let mensajeHTML = '';
        
        if (!query && !medico && !fechaDesde && !fechaHasta && !tipoExamen) {
            mensajeHTML = '';
        } else if (total === 0) {
            mensajeHTML = `No se encontraron resultados.`;
        } else {
            mensajeHTML = `<strong>Se encontraron ${total} resultado${total > 1 ? 's' : ''} de ${all}.</strong><br>`;
        }
        
        $('#mensajeResultados').html(mensajeHTML);
    }

    function cargarDatos(page = 1) {
        const query = $('#filtroBusqueda').val();
        const medico = $('#filtroMedico').val();
        const fechaDesde = $('#fechaDesde').val();
        const fechaHasta = $('#fechaHasta').val();
        const tipoExamen = $('#filtroTipoExamen').val();

        $.ajax({
            url: "{{ route('sesiones.index') }}",
            type: 'GET',
            data: { 
                page, 
                search: query,
                medico: medico,
                fecha_desde: fechaDesde, 
                fecha_hasta: fechaHasta,
                tipo_examen: tipoExamen
            },
            success: function(data) {
                $('#tabla-container').html(data.html);
                $('#paginacion-container').html(data.pagination);
                
                const total = data.totalFiltrado ?? data.total;
                actualizarMensaje(total, data.all, query, medico, fechaDesde, fechaHasta, tipoExamen);
            },
            error: function(xhr) {
                let msg = 'Error al cargar los datos.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg += ' ' + xhr.responseJSON.message;
                $('#mensajeResultados').html(msg);
            }
        });
    }

    // Eventos de filtrado
    let typingTimer;
    const doneTypingInterval = 500;

    $('#filtroBusqueda, #filtroMedico').on('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => cargarDatos(), doneTypingInterval);
    });

    $('#fechaDesde, #fechaHasta, #filtroTipoExamen').on('change', function() {
        cargarDatos();
    });

    $('#btnRecargar').on('click', function() {
        $('#filtroBusqueda').val('');
        $('#filtroMedico').val('');
        $('#fechaDesde').val('');
        $('#fechaHasta').val('');
        $('#filtroTipoExamen').val('');
        cargarDatos();
    });

    // Paginación con AJAX
    $(document).on('click', '.custom-pagination .page-link', function(e) {
        e.preventDefault();
        if (!$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
            const url = $(this).attr('href');
            const page = new URL(url, window.location.origin).searchParams.get('page');
            cargarDatos(page);
        }
    });
});
</script>
@endsection