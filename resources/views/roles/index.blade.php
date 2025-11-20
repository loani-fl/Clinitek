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
                        @forelse($roles as $index => $role)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ $role->permissions->count() }} permisos
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $role->users->count() }} usuarios
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este rol?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center fst-italic text-muted">No hay roles registrados</td>
                            </tr>
                        @endforelse

                        <tr id="sin-resultados" style="display: none;">
                            <td colspan="5" class="text-center fst-italic text-muted">No hay resultados que mostrar</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="contador-resultados" class="mt-2 text-center fw-bold"></div>

            @if(isset($roles) && method_exists($roles, 'links'))
            <div class="mt-4 pagination-container">
                {{ $roles->links() }}
            </div>
            @endif

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const busquedaInput = document.getElementById('busqueda');
    const tabla = document.getElementById('tabla-roles');
    const contadorResultados = document.getElementById('contador-resultados');
    const filasOriginales = Array.from(tabla.querySelectorAll('tr:not(#sin-resultados)'));
    const filaSinResultados = document.getElementById('sin-resultados');

    function actualizarContador(cantidad) {
        const textoBusquedaVacio = busquedaInput.value.trim() === "";

        if (cantidad > 0 && !textoBusquedaVacio) {
            contadorResultados.style.display = 'block';
            contadorResultados.textContent = `${cantidad} resultado${cantidad !== 1 ? 's' : ''} encontrado${cantidad !== 1 ? 's' : ''}`;
        } else {
            contadorResultados.style.display = 'none';
            contadorResultados.textContent = '';
        }
    }

    function filtrarTabla() {
        const textoBusqueda = busquedaInput.value.toLowerCase().trim();

        tabla.innerHTML = '';
        let cantidadVisible = 0;

        filasOriginales.forEach(fila => {
            const celdas = fila.querySelectorAll('td');
            if (celdas.length === 0) return;

            const nombreRol = celdas[1]?.innerText.toLowerCase().trim() || '';

            const coincide = textoBusqueda === '' || nombreRol.includes(textoBusqueda);

            if (coincide) {
                cantidadVisible++;
                celdas[0].textContent = cantidadVisible;
                tabla.appendChild(fila);
            }
        });

        if (cantidadVisible === 0) {
            filaSinResultados.style.display = '';
            tabla.appendChild(filaSinResultados);
        } else {
            filaSinResultados.style.display = 'none';
        }

        actualizarContador(cantidadVisible);
    }

    busquedaInput.addEventListener('keyup', filtrarTabla);

    filtrarTabla();
});
</script>
@endsection