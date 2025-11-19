@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<style>
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

.table th, .table td {
    padding: 0.4rem 0.75rem;
    vertical-align: middle;
    border: 1px solid #dee2e6;
    text-align: center;
}

.table thead {
    background-color: #007BFF;
    color: white;
}

.table tbody tr:hover {
    background-color: #e9f2ff;
}

#mensajeResultados {
    text-align: center;
    margin-top: 0.5rem;
    font-weight: 500;
    color: #555;
    min-height: 2.5em;
}

.filter-container input,
.filter-container select {
    margin-right: 0.5rem;
}

.btn-ver, .btn-editar, .btn-eliminar {
    width: 36px;
    height: 36px;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1rem;
    border-radius: 6px;
}

.btn-ver {
    border: 2px solid #85bfff;
    color: #0856b3;
    background-color: white;
}
.btn-ver:hover {
    background-color: #e0f0ff;
    color: #0856b3;
}

.btn-editar {
    border: 2px solid #ffcc00;
    color: #996600;
    background-color: white;
}
.btn-editar:hover {
    background-color: #fff8cc;
    color: #996600;
}

.btn-eliminar {
    border: 2px solid #ff4d4d;
    color: #a30000;
    background-color: white;
}
.btn-eliminar:hover {
    background-color: #ffe5e5;
    color: #a30000;
}
</style>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
            <a href="{{ route('inicio') }}" class="btn btn-light btn-inicio me-3">
                <i class="bi bi-house-door"></i> Inicio
            </a>
            <h2>Usuarios</h2>
            <a href="{{ route('usuarios.create') }}" class="btn btn-primary ms-3"> Nuevo usuario </a>


        </div>

        {{-- Filtros --}}
        <div class="d-flex filter-container mb-3" style="gap:0.5rem; flex-wrap: nowrap; align-items: center;">
            <input type="text" id="filtroBusqueda" class="form-control" placeholder="Buscar por nombre o email"
                value="{{ request('search') }}" style="width: 260px;">

        </div>

        {{-- Tabla --}}
        <div id="tabla-container" class="table-responsive">
            @include('Usuarios.partials.tabla', ['usuarios' => $usuarios])
        </div>

        {{-- Mensaje de resultados --}}
        <div id="mensajeResultados">
            @if($usuarios->total() > 0)
                Mostrando del {{ $usuarios->firstItem() }} al {{ $usuarios->lastItem() }} de {{ $usuarios->total() }} resultados
            @endif
        </div>

        {{-- Paginación --}}
        <div id="paginacion-container" class="mt-3">
            @include('Usuarios.partials.custom-pagination', ['usuarios' => $usuarios])
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    function actualizarTabla(page = 1) {
        const query = $('#filtroBusqueda').val();
        const rol = $('#filtroRol').val();

        $.ajax({
            url: "{{ route('usuarios.index') }}",
            type: 'GET',
            data: { page: page, search: query, rol: rol },
            success: function(data) {
                $('#tabla-container').html(data.html);
                $('#paginacion-container').html(data.pagination);
                $('#mensajeResultados').html(data.total > 0
                    ? `Mostrando del ${data.from} al ${data.to} de ${data.total} resultados`
                    : 'No se encontraron resultados.');
            },
            error: function(xhr){
                $('#mensajeResultados').html('Error al cargar los datos.');
            }
        });
    }

    // Filtro de texto
    $('#filtroBusqueda').on('input', function() {
        actualizarTabla(1);
    });

    // Filtro por rol
    $('#filtroRol').on('change', function() {
        actualizarTabla(1);
    });

    // Paginación dinámica
    $(document).on('click', '.custom-pagination a', function(e){
        e.preventDefault();
        const page = $(this).data('page');
        actualizarTabla(page);
        $('html, body').animate({ scrollTop: $('.custom-card').offset().top - 20 }, 300);
    });
});
</script>
@endsection
