@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 1100;
        padding: 0.5rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .content-wrapper {
        margin-top: 70px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
        padding: 2rem;
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
        padding: 2rem;
        box-shadow: 0 12px 30px 5px rgba(0, 0, 0, 0.18);
        width: 100%;
        max-width: 1200px;
        position: relative;
        z-index: 1;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #007BFF;
        margin-bottom: 1rem;
        background-color: transparent !important;
        padding-bottom: 0.5rem;
    }

    .card-header h2 {
        margin: 0 auto;
        color: #000;
        font-weight: bold;
        font-size: 2.25rem;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
        justify-content: flex-start;
        align-items: center;
    }

    .filter-row input, .filter-row select {
        font-size: 0.85rem;
        flex: 1 1 200px;
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
        padding: 0.5rem 0.75rem;
        vertical-align: middle;
    }

    .pagination-container {
        font-size: 0.9rem;
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }

    #mensajeResultados {
        font-weight: 600;
        color: #000;
        margin-top: 0.5rem;
        min-height: 1.2em;
        text-align: center;
    }
</style>

<div class="header">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto;">
        <div class="fw-bold text-white ms-2" style="font-size: 1.5rem;">Clinitek</div>
    </div>

    <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            ☰
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="{{ route('pacientes.create') }}">Registrar paciente</a></li>
            <li><a class="dropdown-item" href="{{ route('consultas.index') }}">Registrar consulta</a></li>
        </ul>
    </div>
</div>

<div class="content-wrapper">
    <div class="card custom-card shadow-sm">

        <div class="card-header">
            <a href="{{ route('inicio') }}" class="btn btn-light">
                <i class="bi bi-house-door"></i> Inicio
            </a>
            <h2>Lista de pacientes</h2>
            <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Registrar paciente
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="filter-row">
    <input type="text" id="inputFiltro" class="form-control" placeholder="Buscar por nombre, apellido o identidad" style="max-width: 300px;" value="{{ request('filtro') }}">
</div>


        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre(s)</th>
                        <th>Apellidos</th>
                        <th>Identidad</th>
                        <th>Género</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaPacientes">
                    @forelse($pacientes as $index => $paciente)
                        <tr>
                            <td>{{ $pacientes->firstItem() + $index }}</td>
                            <td>{{ $paciente->nombre }}</td>
                            <td>{{ $paciente->apellidos }}</td>
                            <td>{{ $paciente->identidad }}</td>
                            <td>{{ $paciente->genero ?? 'No especificado' }}</td>
                            <td>
                                <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay pacientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="mensajeResultados">
            Se encontraron {{ $pacientes->total() }} resultado{{ $pacientes->total() != 1 ? 's' : '' }}.
        </div>

        @if($pacientes->hasPages())
            <div class="pagination-container">
                {{ $pacientes->links('pagination::bootstrap-4') }}
            </div>
        @endif

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    function actualizarMensaje(total) {
        $('#mensajeResultados').html(total === 0 ? 'No se encontraron resultados.' : `Se encontraron ${total} resultado${total > 1 ? 's' : ''}.`);
    }

    function filtrarTabla() {
        let filtro = $('#inputFiltro').val().toLowerCase();
        let totalVisible = 0;
        $('#tablaPacientes tr').each(function () {
            let texto = $(this).text().toLowerCase();
            if (texto.indexOf(filtro) > -1) {
                $(this).show();
                totalVisible++;
            } else {
                $(this).hide();
            }
        });
        actualizarMensaje(totalVisible);

        let indice = 1;
        $('#tablaPacientes tr:visible').each(function () {
            $(this).find('td:first').text(indice++);
        });
    }

    $('#inputFiltro').on('keyup', filtrarTabla);
});
</script>
@endsection
