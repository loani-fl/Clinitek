@extends('layouts.app')

@push('styles')
<style>
    html, body {
        background-color: #e8f4fc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .contenedor-principal {
        display: flex;
        justify-content: center;
        align-items: start;
        padding-top: 80px;
    }

    .custom-card {
        background-color: #ffffff;
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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

    .card-header {
        border-bottom: 4px solid #0d6efd;
        background-color: transparent;
    }

    .card-header h5 {
        font-size: 2rem;
        color: #0d6efd;
        font-weight: bold;
    }

    thead {
        background-color: #cce5ff;
        color: #003e7e;
    }

    tbody tr:hover {
        background-color: #e9f2ff;
    }

    #mensajeResultados {
        font-weight: 600;
        color: #000000;
        margin-top: 0.5rem;
        text-align: center;
    }

    table tr td:first-child {
        text-align: center;
    }

    /* Botones de acción personalizados */
    .btn-ver {
        border: 2px solid #85bfff; /* Azul claro */
        color: #0856b3;
        background-color: white;
    }
    .btn-ver:hover {
        background-color: #e0f0ff;
        color: #0856b3;
    }

    .btn-editar {
        border: 2px solid #ffcc00; /* Amarillo */
        color: #996600;
        background-color: white;
    }
    .btn-editar:hover {
        background-color: #fff8cc;
        color: #996600;
    }

</style>
@endpush

@section('content')
<div class="contenedor-principal">
    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center" style="flex: 1;">
                <a href="{{ route('inicio') }}" class="btn btn-light me-2">
                    <i class="bi bi-house-door"></i> Inicio
                </a>
            </div>

            <h5 class="mb-0 flex-grow-1 text-center" style="color: black;">
                Listado de farmacias asociadas
            </h5>

            <div style="flex: 1; display: flex; justify-content: flex-end;">
                <a href="{{ route('farmacias.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1" style="white-space: nowrap;">
                    <i class="bi bi-plus-circle"></i> Registrar
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3 mx-3" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <div class="px-3 py-2 mt-3">
            <form id="formFiltro" onsubmit="return false;">
                <div class="d-flex gap-2">
                    <input type="text" name="filtro" id="inputFiltro" class="form-control" 
                        placeholder="Buscar por nombre o ubicación" value="{{ request('filtro') }}">
                    <select name="estado" id="selectEstado" class="form-select" style="min-width: 160px;">
                        <option value="">Todos los estados</option>
                        <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activa</option>
                        <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>
            </form>
        </div>

        <div id="tabla-container">
            @include('farmacias.partials.tabla', ['farmacias' => $farmacias, 'conNumeracion' => true])
        </div>

        <div id="mensajeResultados">
            Se encontraron {{ $farmacias->total() }} resultado{{ $farmacias->total() != 1 ? 's' : '' }}.
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function () {
    function actualizarMensaje(total, all, query) {
        if (query === '') {
            $('#mensajeResultados').html('');
        } else {
            $('#mensajeResultados').html(`<strong>Se encontraron ${total} resultado${total > 1 ? 's' : ''} de ${all}.</strong>`);
        }
    }

    function cargarDatos(page = 1, filtro = '', estado = '') {
        $.ajax({
            url: "{{ route('farmacias.index') }}",
            type: 'GET',
            data: { page, filtro, estado },
            success: function(data) {
                $('#tabla-container').html(data.html);
                actualizarMensaje(data.total, data.all, filtro);
                window.history.pushState("", "", `?page=${page}&filtro=${encodeURIComponent(filtro)}&estado=${encodeURIComponent(estado)}`);
            },
            error: function(xhr) {
                let msg = 'No se encontraron resultados.';
                if(xhr.responseJSON && xhr.responseJSON.message){
                    msg += ' ' + xhr.responseJSON.message;
                }
                $('#mensajeResultados').html(msg);
            }

        });
    }

    let timeout = null;
    $('#inputFiltro').on('input', function () {
        clearTimeout(timeout);
        let filtro = $(this).val();
        let estado = $('#selectEstado').val();
        timeout = setTimeout(() => {
            cargarDatos(1, filtro, estado);
        }, 500);
    });

    $('#selectEstado').on('change', function () {
        let filtro = $('#inputFiltro').val();
        let estado = $(this).val();
        cargarDatos(1, filtro, estado);
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let page = url.split('page=')[1];
        let filtro = $('#inputFiltro').val();
        let estado = $('#selectEstado').val();
        cargarDatos(page, filtro, estado);
    });
});
</script>
@endpush
@endsection
