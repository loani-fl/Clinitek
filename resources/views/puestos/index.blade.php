@extends('layouts.app')

@section('title', 'Listado de Puestos')

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
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        padding: 1rem;
        position: relative;
           max-width: 1000px;  /* Igual que el max-width del segundo */
            width: 100%;        /* Para que la card ocupe todo el ancho disponible */
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
        padding: 1.5rem;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
        z-index: 1;
         max-width: 1000px;  /* Igual que en el segundo */
    width: 100%;        /* Que ocupe todo el ancho dentro del contenedor */
    }

.card-header {
    background-color: transparent !important;
    border-bottom: 3px solid #007BFF;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    /* Elimina text-align y position absolute */
    /* text-align: center; */
    /* position: relative; */
    display: flex;
    align-items: center;
    justify-content: space-between;
}



    .card-header h3 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #003366;
        margin: 0;
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
        font-size: 0.9rem;
            width: 100%;  /* Tabla siempre al 100% del contenedor */
    border-collapse: collapse; /* Para bordes uniformes */
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

<!--<div class="header d-flex justify-content-between align-items-center px-3 py-2">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto;">
        <div class="fw-bold text-white ms-2" style="font-size: 1.5rem;">Clinitek</div>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="nav-link text-white">Crear puesto</a>
        <a href="{{ route('empleado.create') }}" class="nav-link text-white">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}" class="nav-link text-white">Registrar médico</a>
    </div>
</div>
-->
<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
            <!-- Botón Inicio a la izquierda -->
            <a href="{{ route('inicio') }}" class="btn btn-light me-3">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <!-- Título centrado y que ocupe espacio central -->
            <h5 class="mb-0 text-dark flex-grow-1 text-center" style="font-size: 2.25rem; font-weight: bold;">
                Lista de puestos
            </h5>

            <!-- Botón Registrar puesto a la derecha -->
            <a href="{{ route('puestos.create') }}" class="btn btn-primary ms-3">
                <i class="bi bi-plus-circle"></i> Registrar puesto
            </a>
        </div>

        <div class="d-flex filter-container">
            <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar por código o nombre">
        </div>

        <div id="tabla-container" class="table-responsive">
            @include('puestos.partials.tabla', ['puestos' => $puestos])
        </div>

        <div id="mensajeResultados" class="text-center mt-3" style="min-height: 1.2em;"></div>

       <div id="paginacion-container" class="pagination-container">
    @if(!$isSearch)
        {{ $puestos->onEachSide(1)->links('pagination::bootstrap-5') }}
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
            url: "{{ route('puestos.index') }}",
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

    // Filtrar al escribir
    $('#filtroBusqueda').on('keyup', function () {
        let query = $(this).val();
        cargarDatos(1, query);
    });

    // Paginación con delegación, para capturar clicks en links creados dinámicamente
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let params = new URLSearchParams(url.split('?')[1]);
        let page = params.get('page') || 1;
        let query = $('#filtroBusqueda').val();
        cargarDatos(page, query);

        // Actualizar URL sin recargar
        let newUrl = url.split('?')[0] + '?page=' + page;
        if (query) newUrl += '&search=' + encodeURIComponent(query);
        window.history.pushState("", "", newUrl);
    });
});
</script>
@endsection


