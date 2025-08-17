<!DOCTYPE html>
<html>
<head>
    <title>Listado de médicos</title>
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
        .contenedor-principal {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: start;
            padding: 0 3rem;
            margin: 0;
            width: 100vw;
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
        .estado-activo i { color: #00c851 !important; }
        .estado-inactivo i { color: #ff3547 !important; }
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
        #mensajeResultados {
            font-weight: 600;
            color: #000000;
            margin-top: 0.5rem;
            min-height: 1.2em;
            text-align: center;
        }
    </style>
</head>
<body>

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
            <li><a class="dropdown-item" href="{{ route('puestos.create') }}">Crear puesto</a></li>
            <li><a class="dropdown-item" href="{{ route('empleado.create') }}">Registrar empleado</a></li>
            <li><a class="dropdown-item" href="{{ route('medicos.create') }}">Registrar médico</a></li>
            <li><a class="dropdown-item" href="{{ route('consultas.create') }}">Registrar consulta</a></li>
            <li><a class="dropdown-item" href="{{ route('pacientes.create') }}">Registrar paciente</a></li>
        </ul>
    </div>
</div>
<div class="contenedor-principal">
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 90px;">
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


        <footer>
    © 2025 Clínitek. Todos los derechos reservados.
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

// Llamar después de cargar la tabla y la paginación
                $(document).ready(function () {
                    actualizarFlechas();

                    // También después de cada carga AJAX
                    $(document).on('click', '.pagination a', function() {
                        setTimeout(actualizarFlechas, 10);
                    });
                });

            });
        </script>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
