<!DOCTYPE html>
<html>
<head>
    <title>Listado de empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #e8f4fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            background-color: #007BFF;
        }

        .header .fw-bold {
            font-size: 1.75rem;
            color: #0d6efd;
        }

        .header .btn {
            color: black;
            border-width: 2px;
        }

        .contenedor-principal {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: start;
            padding: 0 3rem;
            margin: 0;
            width: 100vw;
            padding-top: 70px;
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

        .card-header {
            background-color: transparent !important;
            border: none;
        }

        .card-header h5 {
            color: #0d6efd;
            font-weight: bold;
        }

        .table-responsive {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0 1rem 1rem 1rem;
        }

        thead tr {
            background-color: #cce5ff;
            color: #003e7e;
        }

        tbody tr:hover {
            background-color: #e9f2ff;
        }

        table tbody tr {
            height: 50px;
        }

        label {
            font-size: 0.85rem;
        }

        input, select, textarea {
            font-size: 0.85rem !important;
        }

        .btn-white-border {
            background-color: white !important;
            border-width: 2px;
            box-shadow: none !important;
        }

        .estado-activo i {
            color: #00c851 !important;
        }

        .estado-inactivo i {
            color: #ff3547 !important;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100vw;
            height: 50px;
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
            z-index: 999;
            border-top: 1px solid #dee2e6;
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

        #mensajeResultados {
            font-weight: 600;
            color: #000000;
            margin-top: 0.5rem;
            min-height: 1.2em;
            text-align: center;
        }

        .header a:hover {
            text-decoration: underline;
            color: #dceeff;
        }
        
    </style>
</head>
<body>

{{-- Barra de navegación superior fija con menú desplegable --}}
    <div class="header d-flex justify-content-between align-items-center px-3 py-2 fixed-top" style="background-color: #007BFF;">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto;">
            <div class="fw-bold text-white ms-2" style="font-size: 1.5rem;">Clinitek</div>
        </div>

        <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                ☰
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="{{ route('puestos.index') }}">Crear puesto</a></li>
                <li><a class="dropdown-item" href="{{ route('empleado.index') }}">Registrar empleado</a></li>
                <li><a class="dropdown-item" href="{{ route('medicos.index') }}">Registrar médico</a></li>
                <li><a class="dropdown-item" href="{{ route('consultas.index') }}">Registrar consulta</a></li>
                <li><a class="dropdown-item" href="{{ route('pacientes.index') }}">Registrar paciente</a></li>
                <li><a class="dropdown-item" href="{{ route('pacientes.index') }}">Registrar Farmacia</a></li>
            </ul>
        </div>
    </div>

<!-- Contenedor principal -->
<div class="contenedor-principal">
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px;">
        <div class="card-header py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <div class="d-flex justify-content-between align-items-center w-100">
                
                {{-- Botón Inicio a la izquierda --}}
                <div>
                    <a href="{{ route('inicio') }}" class="btn btn-light me-2">
                        <i class="bi bi-house-door"></i> Inicio
                    </a>
                </div>
                
                {{-- Título centrado --}}
                <div class="flex-grow-1 text-center">
                    <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Lista de empleados</h5>
                </div>
                
                {{-- Botón Registrar a la derecha --}}
                <div>
                    <a href="{{ route('empleado.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Registrar empleado
                    </a>
                </div>
                
            </div>
        </div>



        <!-- Formulario filtro sin submit normal -->
        <div class="px-3 py-2 mt-4">
            <form id="formFiltro" onsubmit="return false;">
                <div class="col-12 col-md-9 d-flex gap-2">
                    <input type="text" name="filtro" id="inputFiltro" class="form-control flex-grow-1"
                        placeholder="Buscar por nombre, identidad o puesto"
                        value="{{ request('filtro') }}">

                    <select name="estado" id="selectEstado" class="form-select" style="min-width: 160px;">
                        <option value="">Todos los estados</option>
                        <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Tabla y paginación (contenedor dinámico AJAX) -->
        <div id="tabla-container">
            @include('empleado.partials.tabla', ['empleados' => $empleados])
        </div>

        <!-- Mensaje de resultados -->
        <div id="mensajeResultados" class="text-center fw-semibold text-dark mt-2">
            Se encontraron {{ $empleados->total() }} resultado{{ $empleados->total() != 1 ? 's' : '' }}.
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    © 2025 Clínitek. Todos los derechos reservados.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

    function cargarDatos(page = 1, filtro = '', estado = '') {
        $.ajax({
            url: "{{ route('empleado.index') }}",
            type: 'GET',
            data: { page, filtro, estado },
            success: function(data) {
                $('#tabla-container').html(data.html);
                actualizarMensaje(data.total, data.all, filtro);
                window.history.pushState("", "", `?page=${page}&filtro=${encodeURIComponent(filtro)}&estado=${encodeURIComponent(estado)}`);
            },
            error: function() {
                $('#mensajeResultados').html('Error al cargar los datos.');
            }
        });
    }

    // Evento para input filtro con debounce
    let timeout = null;
    $('#inputFiltro').on('input', function () {
        clearTimeout(timeout);
        let filtro = $(this).val();
        let estado = $('#selectEstado').val();
        timeout = setTimeout(() => {
            cargarDatos(1, filtro, estado);
        }, 500);
    });

    // Evento para select estado
    $('#selectEstado').on('change', function () {
        let filtro = $('#inputFiltro').val();
        let estado = $(this).val();
        cargarDatos(1, filtro, estado);
    });

    // Capturar clicks en paginación (delegado)
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let page = url.split('page=')[1];
        let filtro = $('#inputFiltro').val();
        let estado = $('#selectEstado').val();
        cargarDatos(page, filtro, estado);
    });

    // No hace falta recargar al inicio porque ya se carga con Blade
});
</script>

</body>
</html>
