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
          border-radius: 10px;
        z-index: 1;
         max-width: 1000px;  /* Igual que en el segundo */
    width: 100%;        /* Que ocupe todo el ancho dentro del contenedor */
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
        font-size: 0.5rem;
        width: 100%;  /* Tabla siempre al 100% del contenedor */
    border-collapse: collapse; /* Para bordes uniformes */
    }
    .table-responsive {
    flex-grow: 1;
    overflow-y: auto;
   
    max-width: 100%; /* Para que no exceda la card */
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

{{-- Barra superior fija --}}
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

<div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px;">
  <div class="card-header position-relative py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
    <!-- Botón a la derecha -->
    <a href="{{ route('inicio') }}" class="btn btn-light position-absolute end-0 top-50 translate-middle-y me-2">
      <i class="bi bi-house-door"></i> Inicio
    </a>
    <!-- Título centrado -->
    <h5 class="mb-0 fw-bold text-dark text-center" style="font-size: 2.25rem;">Listado de Pacientes</h5>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
      <strong>{{ session('success') }}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  @endif

  <div class="p-3">
    {{-- Filtro y botón centrados --}}
    <div class="d-flex justify-content-center align-items-center gap-2 mb-3 flex-wrap">
      <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar por nombre, apellido o identidad...">
      <!--<button id="btnLimpiar" class="btn btn-outline-primary btn-sm">Limpiar filtro</button>-->
    </div>

        <div class="d-flex filter-container">
            <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar por nombre, apellido o identidad...">
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
            @forelse ($pacientes as $index => $paciente)
                <tr>
                    <td>{{ $pacientes->firstItem() + $index }}</td>
                    <td>{{ $paciente->nombre }}</td>
                    <td>{{ $paciente->apellidos }}</td>
                    <td>{{ $paciente->identidad }}</td>
                    <td>
                        <span class="badge
                          {{ $paciente->genero === 'Masculino' ? 'bg-primary' :
                             ($paciente->genero === 'Femenino' ? 'bg-warning text-dark' : 'bg-info') }}">
                          {{ $paciente->genero ?? 'No especificado' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-outline-info me-2" title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-sm btn-outline-warning" title="Editar paciente">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
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

<div id="mensajeResultados" class="text-center mt-3" style="min-height: 1.2em;"></div>

@if ($pacientes->hasPages())
    <div class="pagination-container">
        {{ $pacientes->links('pagination::bootstrap-4') }}
    </div>
@endif
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const totalPacientes = {{ $pacientes->total() }};
</script>

<script>
$(document).ready(function () {
    const noResultadosRow = $('<tr class="no-resultados"><td colspan="6" class="text-center">No hay pacientes que coincidan con la búsqueda.</td></tr>');
    noResultadosRow.hide();
    $('#tablaPacientes').append(noResultadosRow);
function actualizarMensaje(totalVisible, filtroVacio) {
    if (filtroVacio) {
        $('#mensajeResultados').html('');
    } else if (totalVisible === 0) {
        $('#mensajeResultados').html('No hay pacientes que coincidan con la búsqueda.');
    } else {
        const total = $('#tablaPacientes tr').not('.no-resultados').length;
        $('#mensajeResultados').html(`<strong>Se encontraron ${totalVisible} resultado${totalVisible > 1 ? 's' : ''} de ${total}.</strong>`);
    }
}


    function filtrarTabla() {
        let valor = $('#filtroBusqueda').val().toLowerCase();
        let totalVisible = 0;

        $('#tablaPacientes tr').not('.no-resultados').each(function () {
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

        // Reenumerar visibles
        let indice = 1;
        $('#tablaPacientes tr:visible').not('.no-resultados').each(function () {
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
