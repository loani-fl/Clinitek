@extends('layouts.app')

@section('content')
    <style>
        body {
            overflow-x: hidden;
        }

        .custom-card {
            background-color: #ffffff;
            border-color: #91cfff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            max-width: 90%;
            width: 100%;
            padding: 1rem;
            margin-top: 60px;
            min-height: calc(100vh - 80px);
        }

        thead tr {
            background-color: #cce5ff;
            color: #003e7e;
        }

        tbody tr:hover {
            background-color: #e9f2ff;
        }

        .filtro-input {
            font-size: 0.85rem;
            max-width: 300px;
        }

        #mensajeResultados {
            font-weight: 600;
            color: #0d6efd;
            margin-top: 0.5rem;
            min-height: 1.2em;
        }

        .table td, .table th {
            padding: 0.3rem 0.5rem;
            font-size: 0.85rem;
            line-height: 1.2;
            vertical-align: middle;
        }

        table th:nth-child(1), table td:nth-child(1) {
            width: 30px;
            text-align: center;
        }

        table th:nth-child(2), table td:nth-child(2) {
            width: 120px;
        }

        table th:nth-child(3), table td:nth-child(3) {
            width: 120px;
        }

        table th:nth-child(4), table td:nth-child(4) {
            width: 150px;
        }

        table th:nth-child(5), table td:nth-child(5) {
            width: 90px;
            text-align: center;
        }

        table th:nth-child(6), table td:nth-child(6) {
            width: 110px;
            text-align: center;
        }

        .pagination-container {
            font-size: 0.9rem;
        }

        .btn-white-border {
            border: 1px solid white !important;
        }

        .btn-outline-info {
            color: #0dcaf0;
            border-color: #0dcaf0;
        }

        .btn-outline-info:hover {
            background-color: #0dcaf0;
            color: white;
        }

        .btn-outline-warning {
            color: #ffc107;
            border-color: #ffc107;
        }

        .btn-outline-warning:hover {
            background-color: #ffc107;
            color: black;
        }
    </style>

    <div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050;">
        <div class="d-flex justify-content-between align-items-center px-3 py-2">
            <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
                <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
                <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
            </div>
        </div>
    </div>

    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100">
        <div class="card-header position-relative py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark text-center" style="font-size: 2.25rem;">Listado de Médicos</h5>
            <div class="d-flex gap-2 position-absolute end-0 top-50 translate-middle-y me-3">
                <a href="{{ route('inicio') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-house-door"></i> Inicio
                </a>
                <a href="{{ route('medicos.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle"></i> Nuevo médico
                </a>
            </div>
        </div>

        <div class="p-3">
            <div class="d-flex justify-content-center align-items-center gap-2 mb-3 flex-wrap">
                <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar por nombre, apellido o especialidad...">
                <button id="btnLimpiar" class="btn btn-outline-primary btn-sm">Limpiar filtro</button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody id="tablaMedicos">
                    @forelse ($medicos as $index => $medico)
                        <tr>
                            <td>{{ $medicos->firstItem() + $index }}</td>
                            <td>{{ $medico->nombre }}</td>
                            <td>{{ $medico->apellidos }}</td>
                            <td>{{ $medico->especialidad }}</td>
                            <td class="text-center">
                                @php
                                    $estado = strtolower(trim($medico->estado ?? ''));
                                @endphp

                                @if ($estado === 'activo' || $estado === '1' || $estado === 'true')
                                    <span class="d-inline-flex align-items-center gap-1">
            <span class="rounded-circle" style="width: 10px; height: 10px; background-color: #28a745;"></span>
            <span class="text-success"></span>
        </span>
                                @else
                                    <span class="d-inline-flex align-items-center gap-1">
            <span class="rounded-circle" style="width: 10px; height: 10px; background-color: #dc3545;"></span>
            <span class="text-danger"></span>
        </span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-white-border btn-outline-info btn-sm" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-white-border btn-outline-warning btn-sm" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay médicos registrados.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div id="mensajeResultados" class="text-center mt-3"></div>

            <div class="pagination-container d-flex justify-content-center mt-2">
                {{ $medicos->links() }}
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const noResultadosRow = $('<tr class="no-resultados"><td colspan="6" class="text-center">No hay médicos que coincidan con la búsqueda.</td></tr>');
            noResultadosRow.hide();
            $('#tablaMedicos').append(noResultadosRow);

            function actualizarMensaje(totalVisible, filtroVacio) {
                if (totalVisible === 0) {
                    $('#mensajeResultados').text('No hay médicos que coincidan con la búsqueda.');
                } else if (filtroVacio) {
                    $('#mensajeResultados').text('');
                } else {
                    $('#mensajeResultados').text(`Se encontraron ${totalVisible} resultado${totalVisible > 1 ? 's' : ''}.`);
                }
            }

            function filtrarTabla() {
                let valor = $('#filtroBusqueda').val().toLowerCase();
                let totalVisible = 0;

                $('#tablaMedicos tr').not('.no-resultados').each(function () {
                    let textoFila = $(this).text().toLowerCase();
                    if (textoFila.indexOf(valor) > -1) {
                        $(this).show();
                        totalVisible++;
                    } else {
                        $(this).hide();
                    }
                });

                if (totalVisible === 0) {
                    noResultadosRow.show();
                } else {
                    noResultadosRow.hide();
                }

                actualizarMensaje(totalVisible, valor === '');

                let indice = 1;
                $('#tablaMedicos tr:visible').not('.no-resultados').each(function () {
                    $(this).find('td:first').text(indice++);
                });
            }

            $('#filtroBusqueda').on('keyup', filtrarTabla);

            $('#btnLimpiar').on('click', function () {
                $('#filtroBusqueda').val('');
                filtrarTabla();
                $('#mensajeResultados').text('');
                $('#filtroBusqueda').focus();
            });

            $('#mensajeResultados').text('');
        });
    </script>
@endsection
