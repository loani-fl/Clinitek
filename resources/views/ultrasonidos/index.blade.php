@extends('layouts.app')

@section('title', 'Órdenes de Ultrasonido')

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
        tbody tr:hover {
            background-color: #e9f2ff;
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
    </style>

    <div class="content-wrapper">
        <div class="card custom-card shadow-sm">
            <div class="card-header">
                <a href="{{ route('inicio') }}" class="btn btn-light btn-inicio me-3">
                    <i class="bi bi-house-door"></i> Inicio
                </a>

                <h2>Órdenes de ultrasonido</h2>

                <a href="{{ route('ultrasonidos.create') }}" class="btn btn-primary ms-3">
                    <i class="bi bi-plus-circle"></i> Registrar Orden
                </a>
            </div>

            {{-- Filtros --}}
            <div class="d-flex filter-container">
                <div>
                    <label class="filtro-label" for="filtroBusqueda">Paciente</label>
                    <input type="text" id="filtroBusqueda" class="form-control filtro-input" autocomplete="off">
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
                    <label class="filtro-label" for="filtroEstado">Estado</label>
                    <select id="filtroEstado" class="form-control filtro-input">
                        <option value="">-- Todos --</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Realizado">Realizado</option>
                    </select>
                </div>

                <div style="align-self: flex-end;">
                    <button id="btnRecargar" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
            </div>

            <div id="tabla-container">
                @include('ultrasonidos.partials')
            </div>

            <div id="mensajeResultados" class="text-center mt-3" style="min-height: 1.2em;"></div>

            <div id="paginacion-container" class="pagination-container">
                {{ $ordenes->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                    url: "{{ route('ultrasonidos.index') }}",
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
                    }
                });
            }

            $('#filtroBusqueda').on('keyup', function () { cargarDatos(1, $(this).val()); });
            $('#fechaDesde, #fechaHasta, #filtroEstado').on('change', function () { cargarDatos(1, $('#filtroBusqueda').val()); });

            $('#btnRecargar').on('click', function () {
                $('#filtroBusqueda').val('');
                $('#fechaDesde').val('');
                $('#fechaHasta').val('');
                $('#filtroEstado').val('');
                cargarDatos(1, '');
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const params = new URLSearchParams(url.split('?')[1]);
                const page = params.get('page') || 1;

                cargarDatos(page, $('#filtroBusqueda').val());
            });
        });
    </script>
@endsection

