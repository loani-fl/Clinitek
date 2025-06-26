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
            background-color: #e8f4fc; /* fondo azul clarito */
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
    padding: 0 3rem; /* MÁS margen lateral */
    margin: 0;
    width: 100vw;
}

.custom-card {
    flex-grow: 1;
    background-color: #ffffff;  /* fondo blanco */
    border-color: #91cfff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    max-width: 1000px;
    width: 100%;
    padding: 1.5rem;
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
            background-color: #cce5ff; /* azul más suave */
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
            width: 800px; /* tamaño más pequeño */
            height: 800px; /* tamaño más pequeño */
            background-image: url('/images/logo2.jpg');
            background-size: contain;  /* ajusta sin recortar */
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.15;  /* transparencia baja para que no moleste */
            transform: translate(-50%, -50%);
            pointer-events: none; /* para que no interfiera con clicks */
            z-index: 0;
        }
    </style>
</head>
<body>

<!-- Hover de enlaces -->
<style>
    .header a:hover {
        text-decoration: underline;
        color: #dceeff;
    }
</style>

<!-- Barra de navegación fija -->
<div class="header d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF; position: sticky; top: 0; z-index: 1030;">
    <div class="d-flex align-items-center">
        <!-- Logo con margen reducido para acercar el texto -->
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" 
             style="height: 40px; width: auto; margin-right: 6px;">

        <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
    </div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>
</div>


<!-- Formulario más compacto -->
<div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px;">
    <div class="card-header position-relative py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
        <!-- Botón a la derecha -->
        <a href="{{ route('inicio') }}" class="btn btn-light position-absolute end-0 top-50 translate-middle-y me-2">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        <!-- Título centrado -->
        <h5 class="mb-0 fw-bold text-dark text-center" style="font-size: 2.25rem;">Lista de empleados</h5>
    </div>



    <form action="{{ route('empleado.store') }}" method="POST" novalidate>
        @csrf

    
        <!-- Alerta de éxito -->
        @if(session('success'))
            <div id="mensaje-exito" class="alert alert-success m-3 alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <!-- FILTROS -->
        <div class="row px-3 py-2">
            <div class="col-md-4 mb-2 mb-md-0">
                <input type="text" id="filtro-empleado" class="form-control" placeholder="Buscar por nombre, identidad o puesto" />
            </div>
            <div class="col-md-3">
                <select id="filtro-estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Identidad</th>
                        <th class="text-center">Puesto</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-empleados">
                @forelse($empleados as $index => $empleado)
                    <tr data-estado="{{ $empleado->estado }}">
                        <td>{{ $empleados->firstItem() + $index }}</td>
                        <td class="nombre">{{ $empleado->nombres }} {{ $empleado->apellidos }}</td>
                        <td>{{ $empleado->identidad }}</td>
                        <td class="puesto">{{ $empleado->puesto->nombre ?? 'Sin puesto' }}</td>
                        <td class="text-center">
                            @if($empleado->estado === 'Activo')
                                <span class="estado-activo"><i class="bi bi-circle-fill"></i></span>
                            @else
                                <span class="estado-inactivo"><i class="bi bi-circle-fill"></i></span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('empleado.show', $empleado->id) }}" class="btn btn-white-border btn-outline-info btn-sm" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('empleado.edit', $empleado->id) }}" class="btn btn-white-border btn-outline-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay empleados registrados.</td>
                    </tr>
                @endforelse

                <tr id="sin-resultados" style="display: none;">
                    <td colspan="8" class="text-center text-muted">No hay empleados que coincidan con la búsqueda.</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="px-3 pb-3">
            <div class="d-flex justify-content-center">
                {{ $empleados->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
</div>

<!-- Footer fijo -->
<footer>
    © 2025 Clínitek. Todos los derechos reservados.
</footer>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script de filtros y mensaje -->
<script>
   function aplicarFiltros() {
    const texto = $('#filtro-empleado').val().toLowerCase();
    const estado = $('#filtro-estado').val();
    let visibles = 0;

    $('#tabla-empleados tr').not('#sin-resultados').each(function () {
        const nombre = $(this).find('.nombre').text().toLowerCase();
        const puesto = $(this).find('.puesto').text().toLowerCase();
        const identidad = $(this).find('td').eq(2).text().toLowerCase();
        const estadoActual = $(this).data('estado');

        const coincideTexto = 
            nombre.startsWith(texto) || 
            puesto.startsWith(texto) || 
            identidad.startsWith(texto);

        const coincideEstado = !estado || estado === estadoActual;

        const visible = coincideTexto && coincideEstado;
        $(this).toggle(visible);

        if (visible) {
            visibles++;
            $(this).find('td').eq(0).text(visibles);
        }
    });

    $('#sin-resultados').toggle(visibles === 0);
}



    $(document).ready(function () {
        $('#filtro-empleado, #filtro-estado').on('input change', aplicarFiltros);

        // Ocultar mensaje de éxito tras 3 segundos
        const mensaje = $('#mensaje-exito');
        if (mensaje.length) {
            setTimeout(() => {
                mensaje.alert('close');
            }, 3000);
        }
    });
</script>
</body>
</html>
