@extends('layouts.app')

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
        height: 60px;
        display: flex;
        align-items: center;
    }
    .content-wrapper {
        margin-top: 40px;
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
        position: relative;
        justify-content: center;
        align-items: center;
        background-color: transparent !important;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 3px solid #007BFF;
        display: flex;
    }

    .card-header h2 {
        color: #000 !important;
        margin: 0 auto;
    }
    .alert-success-custom {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 0.75rem 1.25rem;
        border-radius: 0.25rem;
        position: relative;
        z-index: 1200;
        margin-top: 1rem;
    }
    .alert-danger-custom {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 0.75rem 1.25rem;
        border-radius: 0.25rem;
        position: relative;
        z-index: 1200;
        margin-top: 1rem;
    }
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .filter-row > div {
        flex: 1 1 150px;
        min-width: 120px;
    }

    /* Estilos de tabla idénticos a consultas */
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

    .pagination-container {
        display: flex;
        justify-content: center;
    }
</style>

<div class="content-wrapper">
    <div class="card custom-card">
        <div class="card-header justify-content-center align-items-center">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <a href="{{ route('inicio') }}" class="btn btn-light">
                        <i class="bi bi-house-door"></i> Inicio
                    </a>
                </div>

                <div class="text-center flex-grow-1">
                    <h2 class="fw-bold mb-0">Lista de roles</h2>
                </div>

                <div>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Crear rol
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div id="mensaje-exito" class="alert alert-success alert-dismissible fade show alert-success-custom">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div id="mensaje-error" class="alert alert-danger alert-dismissible fade show alert-danger-custom">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card-body">
            <div class="d-flex justify-content-start align-items-center gap-2 mb-3">
                <label class="mb-0">Buscar:</label>
                <input type="text" id="busqueda" class="form-control form-control-sm" placeholder="Nombre del rol..." style="max-width: 300px;">
            </div>

            <div class="overflow-x-auto mt-3">
                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre del rol</th>
                            <th>Permisos</th>
                            <th>Usuarios asignados</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-roles">
                        <!-- El contenido se cargará dinámicamente -->
                    </tbody>
                </table>
            </div>

            <div id="resultado-busqueda" class="mt-2 text-center fw-bold text-info" style="display: none;"></div>

            <div class="mt-4 pagination-container" id="pagination-container">
                <!-- La paginación se cargará dinámicamente -->
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const busquedaInput = document.getElementById('busqueda');
    const tablaRoles = document.getElementById('tabla-roles');
    const paginationContainer = document.getElementById('pagination-container');
    const resultadoBusqueda = document.getElementById('resultado-busqueda');
    let timeout = null;

    // Cargar datos iniciales
    cargarRoles();

    // Búsqueda en tiempo real
    busquedaInput.addEventListener('keyup', function () {
        clearTimeout(timeout);
        
        timeout = setTimeout(function () {
            cargarRoles(1, busquedaInput.value.trim());
        }, 300); // Espera 300ms
    });

    function cargarRoles(page = 1, busqueda = '') {
        const url = `{{ route('roles.index') }}?page=${page}&busqueda=${encodeURIComponent(busqueda)}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Actualizar tabla
            tablaRoles.innerHTML = data.html;
            
            // Actualizar paginación
            paginationContainer.innerHTML = data.pagination;
            
            // Mostrar resultado de búsqueda
            if (busqueda) {
                resultadoBusqueda.style.display = 'block';
                resultadoBusqueda.textContent = `${data.total} resultado${data.total !== 1 ? 's' : ''} encontrado${data.total !== 1 ? 's' : ''}`;
            } else {
                resultadoBusqueda.style.display = 'none';
            }

            // Agregar eventos a los enlaces de paginación
            agregarEventosPaginacion(busqueda);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function agregarEventosPaginacion(busqueda) {
        const links = paginationContainer.querySelectorAll('a.page-link');
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                cargarRoles(page, busqueda);
            });
        });
    }
});
</script>
@endsection