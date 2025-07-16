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

</div>
<div class="contenedor-principal">
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px;">
        <div class="card-header position-relative py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <a href="{{ route('inicio') }}" class="btn btn-light position-absolute end-0 top-50 translate-middle-y me-2">
                <i class="bi bi-house-door"></i> Inicio
            </a>
            <h5 class="mb-0 fw-bold text-dark text-center" style="font-size: 2.25rem;">Listado de Médicos</h5>
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
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Identidad</th>
                    <th class="text-center">Especialidad</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody id="tabla-medicos">
                @forelse($medicos as $index => $medico)
                    <tr data-estado="{{ $medico->estado }}">
                        <td>{{ $medicos->firstItem() + $index }}</td>
                        <td class="nombre">{{ $medico->nombre }} {{ $medico->apellidos }}</td>
                        <td>{{ $medico->numero_identidad }}</td>
                        <td class="especialidad">{{ $medico->especialidad }}</td>
                        <td class="text-center">
                            @if($medico->estado)
                                <span class="estado-activo"><i class="bi bi-circle-fill"></i></span>
                            @else
                                <span class="estado-inactivo"><i class="bi bi-circle-fill"></i></span>
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
                <tr id="sin-resultados" style="display: none;">
                    <td colspan="6" class="text-center text-muted">No hay médicos que coincidan con la búsqueda.</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="mensajeResultados" class="text-center mt-3" style="min-height: 1.2em;"></div>
        <div class="px-3 pb-3">
            <div class="d-flex justify-content-center" id="paginacion-container">
                {{ $medicos->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
<footer>
    © 2025 Clínitek. Todos los derechos reservados.
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function actualizarMensajeResultados(totalVisibles, filtroVacio) {
            if (totalVisibles === 0 && !filtroVacio) {
                $('#mensajeResultados').text('No hay médicos que coincidan con la búsqueda.');
            } else if (filtroVacio) {
                $('#mensajeResultados').text('');
            } else {
                $('#mensajeResultados').text(`Se encontraron ${totalVisibles} resultado${totalVisibles > 1 ? 's' : ''}.`);
            }
        }


        function cargarDatos(page = 1, filtro = '', estado = '') {
            $.ajax({
                url: "{{ route('medicos.index') }}",
                type: 'GET',
                data: {
                    page: page,
                    search: filtro,
                    estado: estado
                },
                success: function(data) {
                    $('#tabla-container').html(data.html);
                    $('#paginacion-container').html(data.pagination);
                    actualizarMensajeResultados(data.total, filtro === '');
                },
                error: function(xhr) {
                    $('#mensajeResultados').text('Error al cargar los datos.');
                }
            });
        }

        // Carga inicial
        cargarDatos();

        // Filtrar al escribir o cambiar estado
        $('#filtro-medico, #filtro-estado').on('input change', function () {
            let filtro = $('#filtro-medico').val();
            let estado = $('#filtro-estado').val();
            cargarDatos(1, filtro, estado);
        });

        // Paginación con delegación (links dinámicos)
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let params = new URLSearchParams(url.split('?')[1]);
            let page = params.get('page') || 1;
            let filtro = $('#filtro-medico').val();
            let estado = $('#filtro-estado').val();
            cargarDatos(page, filtro, estado);

            // Actualiza la URL en la barra sin recargar
            let newUrl = url.split('?')[0] + '?page=' + page;
            if(filtro) newUrl += '&search=' + encodeURIComponent(filtro);
            if(estado) newUrl += '&estado=' + estado;
            window.history.pushState("", "", newUrl);
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
