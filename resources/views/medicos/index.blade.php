@extends('layouts.app')

@section('title', 'Listado de médicos')

@section('content')
<div class="contenedor-principal">
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px;">
        <div class="card-header py-2 d-flex align-items-center justify-content-between"
             style="background-color: #fff; border-bottom: 4px solid #0d6efd;">

            <!-- Botón Inicio a la izquierda -->
            <a href="{{ route('inicio') }}" class="btn btn-light">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <!-- Título centrado -->
            <h5 class="mb-0 fw-bold text-dark flex-grow-1 text-center" style="font-size: 2.25rem;">
                Listado de Médicos
            </h5>

            <!-- Botón Registrar a la derecha -->
            <a href="{{ route('medicos.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Registrar Médico
            </a>
        </div>

        <div class="row px-3 py-2">
            <div class="col-md-4 mb-2 mb-md-0">
                <input type="text" id="filtro-medico" class="form-control" placeholder="Buscar por nombre, identidad o especialidad" />
            </div>
            <div class="col-md-3">
                <select id="filtro-estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        {{-- Contenedor donde se cargará la tabla vía AJAX --}}
        <div id="tabla-container">
            @include('medicos.partials.tabla', ['medicos' => $medicos])
        </div>

        <div id="paginacion-container" class="d-flex justify-content-center mt-2">
            {{ $medicos->onEachSide(1)->links('pagination::bootstrap-4', ['prevText' => 'Anterior', 'nextText' => 'Siguiente']) }}
        </div>

        <!-- Mensaje de resultados -->
        <div id="mensajeResultados" class="text-center mt-2" style="min-height: 1.2em;"></div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .contenedor-principal {
        flex-grow: 1;
        display: flex;
        justify-content: center;
        align-items: start;
        padding: 0 3rem;
        margin: 0;
        width: 100%;
    }

    .custom-card {
        flex-grow: 1;
        background-color: #ffffff;
        border-color: #91cfff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        max-width: 1000px;
        width: 100%;
        padding: 1.5rem;
        position: relative;
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

    .estado-activo i { 
        color: #00c851 !important; 
    }
    
    .estado-inactivo i { 
        color: #ff3547 !important; 
    }

    #mensajeResultados {
        font-weight: 600;
        color: #000000;
        margin-top: 0.5rem;
        min-height: 1.2em;
        text-align: center;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {

    function cargarDatos(page = 1, query = '', estado = '') {
        $.ajax({
            url: "{{ route('medicos.index') }}",
            type: 'GET',
            data: { page, search: query, estado: estado },
            success: function(data) {
                $('#tabla-container').html(data.html);          // Inserta la tabla actualizada
                $('#paginacion-container').html(data.pagination); // Inserta la paginación
                $('#mensajeResultados').text(
                    data.total > 0
                        ? `Mostrando del ${data.from} al ${data.to} de ${data.total} resultados.`
                        : 'No se encontraron resultados.'
                );
                actualizarFlechas();
            },
            error: function() {
                $('#mensajeResultados').text('Error al cargar los datos.');
            }
        });
    }

    // Filtros
    $('#filtro-medico, #filtro-estado').on('input change', function() {
        let query = $('#filtro-medico').val();
        let estado = $('#filtro-estado').val();
        cargarDatos(1, query, estado);
    });

    // Paginación dinámica
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let params = new URLSearchParams(url.split('?')[1]);
        let page = params.get('page') || 1;
        let query = $('#filtro-medico').val();
        let estado = $('#filtro-estado').val();
        cargarDatos(page, query, estado);
        window.history.pushState("", "", url);
    });

    function actualizarFlechas() {
        $('.pagination li:first-child a').text('Anterior');
        $('.pagination li:last-child a').text('Siguiente');
    }

    // Llamar al cargar la página
    actualizarFlechas();
});
</script>
@endpush