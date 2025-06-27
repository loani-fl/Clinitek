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

    .card-header h5 {
        color: #0d6efd;
        font-weight: bold;
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
        min-height: 1.2em; /* para evitar salto al mostrar texto */
    }

    .table td, .table th {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
        line-height: 1.2;
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
        width: 100px;
    }

    table th:nth-child(5), table td:nth-child(5) {
        width: 90px;
    }

    table th:nth-child(6), table td:nth-child(6) {
        width: 90px;
        text-align: center;
        white-space: nowrap;
    }

    .pagination-container nav {
        margin-bottom: 0;
    }

    .pagination-container {
        font-size: 0.9rem;
    }

    /* Botones personalizados */
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
        <h5 class="mb-0 fw-bold text-dark text-center" style="font-size: 2.25rem;">Listado de Pacientes</h5>

        <div class="d-flex gap-2 position-absolute end-0 top-50 translate-middle-y me-3">
            <a href="{{ route('inicio') }}" class="btn btn-sm btn-light">
                <i class="bi bi-house-door"></i> Inicio
            </a>
            <a href="{{ route('pacientes.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo paciente
            </a>
        </div>
    </div>
<div class="p-3">
    {{-- Filtro y botón centrados --}}
    <div class="d-flex justify-content-center align-items-center gap-2 mb-3 flex-wrap">
        <input type="text" id="filtroBusqueda" class="form-control filtro-input" placeholder="Buscar por nombre, apellido o identidad...">
        <button id="btnLimpiar" class="btn btn-outline-primary btn-sm">Limpiar filtro</button>
    </div>

    {{-- Tabla de resultados --}}
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
                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-white-border btn-outline-info btn-sm" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-white-border btn-outline-warning btn-sm" title="Editar">
                                <i class="bi bi-pencil"></i>
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

{{-- Mensaje debajo de los resultados --}}
<div id="mensajeResultados" class="text-center mt-3" style="min-height: 1.2em;"></div>

{{-- Paginación al final --}}
<div class="pagination-container d-flex justify-content-center mt-2">
    {{ $pacientes->links() }}
</div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const noResultadosRow = $('<tr class="no-resultados"><td colspan="6" class="text-center">No hay pacientes que coincidan con la búsqueda.</td></tr>');
    noResultadosRow.hide();
    $('#tablaPacientes').append(noResultadosRow);

    function actualizarMensaje(totalVisible, filtroVacio) {
        if (totalVisible === 0) {
            $('#mensajeResultados').text('No hay pacientes que coincidan con la búsqueda.');
        } else if (filtroVacio) {
            $('#mensajeResultados').text(''); // Sin mensaje si filtro vacío
        } else {
            $('#mensajeResultados').text(`Se encontraron ${totalVisible} resultado${totalVisible > 1 ? 's' : ''}.`);
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

    // No mostrar mensaje al cargar
    $('#mensajeResultados').text('');
});


document.addEventListener('DOMContentLoaded', function () {
    const filtro = document.getElementById('filtroBusqueda');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const tablaContainer = document.getElementById('tablaPacientesContainer');

    // Función para cargar tabla vía AJAX
    function cargarTabla(url = null, search = '') {
        url = url || "{{ route('pacientes.index') }}";

        fetch(url + '?search=' + encodeURIComponent(search), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            tablaContainer.innerHTML = html;
            agregarEventoPaginacion(search); // Reasignar eventos paginación
        })
        .catch(error => console.error('Error al cargar tabla:', error));
    }

    // Evento para filtrar al escribir (puedes cambiar por evento 'input' o botón buscar)
    filtro.addEventListener('keyup', function () {
        const search = this.value.trim();
        cargarTabla(null, search);
    });

    // Botón limpiar filtro
    btnLimpiar.addEventListener('click', function () {
        filtro.value = '';
        cargarTabla();
    });

    // Asignar evento a links de paginación para AJAX
    function agregarEventoPaginacion(search) {
        const links = tablaContainer.querySelectorAll('.pagination a');
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                cargarTabla(url, search);
            });
        });
    }

    // Asignar evento a paginación inicial
    agregarEventoPaginacion('');
});


</script>
@endsection

