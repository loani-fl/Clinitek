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
        margin-top: 60px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        padding: 1rem;
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

    .custom-card {
        background-color: #fff;
        border-radius: 1.5rem;
        padding: 1.5rem;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
        z-index: 1;
    }

   .card-header {
    background-color: transparent !important;
    border-bottom: 3px solid #007BFF;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    text-align: center;
    position: relative;
}


    .card-header h3 {
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
        font-size: 0.9rem;
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

<div class="header d-flex justify-content-between align-items-center px-3 py-2">
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

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">
        <div class="card-header">
          <h5 class="mb-0 text-dark text-center" style="font-size: 2.25rem; font-weight: bold;">Lista de puestos</h5>

            <a href="{{ route('inicio') }}" class="btn btn-light btn-inicio">
                <i class="bi bi-house-door"></i> Inicio
            </a>
        </div>

        <div class="d-flex filter-container">
            <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar por código o nombre...">
        </div>

        @if($puestos->isEmpty())
            <div class="alert alert-info shadow-sm" role="alert">
                No hay puestos registrados aún.
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Departamento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaPuestos">
                    @foreach ($puestos as $index => $puesto)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $puesto->codigo }}</td>
                            <td>{{ $puesto->nombre }}</td>
                            <td>{{ $puesto->area }}</td>
                            <td class="text-center">
                                <a href="{{ route('puestos.show', $puesto->id) }}" class="btn btn-sm btn-outline-info me-2" title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('puestos.edit', $puesto) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div id="mensajeResultados" class="text-center mt-3" style="min-height: 1.2em;"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const filas = $('#tablaPuestos tr');
    const mensaje = $('#mensajeResultados');

    function actualizarMensaje(totalVisible, total) {
        if ($('#filtroBusqueda').val().trim() === '') {
            mensaje.text('');
        } else if (totalVisible === 0) {
            mensaje.html('No hay puestos que coincidan con la búsqueda.');
        } else {
            mensaje.html(`<strong>Se encontraron ${totalVisible} resultado${totalVisible > 1 ? 's' : ''} de ${total}.</strong>`);
        }
    }

    $('#filtroBusqueda').on('keyup', function () {
        let valor = $(this).val().toLowerCase();
        let totalVisible = 0;
        const total = filas.length;

        filas.each(function () {
            let texto = $(this).text().toLowerCase();
            const visible = texto.indexOf(valor) > -1;
            $(this).toggle(visible);
            if (visible) totalVisible++;
        });

        // Reenumerar
        let i = 1;
        $('#tablaPuestos tr:visible').each(function () {
            $(this).find('td:first').text(i++);
        });

        actualizarMensaje(totalVisible, total);
    });
});
</script>
@endsection

