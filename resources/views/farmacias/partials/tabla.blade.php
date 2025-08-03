@push('styles')
<style>
    /* Botón Ver con borde azul claro */
    .btn-ver {
        border: 2px solid #85bfff !important;
        color: #0856b3 !important;
        background-color: white !important;
    }
    .btn-ver:hover {
        background-color: #e0f0ff !important;
        color: #0856b3 !important;
    }

    /* Botón Editar con borde amarillo */
    .btn-editar {
        border: 2px solid #ffcc00 !important;
        color: #996600 !important;
        background-color: white !important;
    }
    .btn-editar:hover {
        background-color: #fff8cc !important;
        color: #996600 !important;
    }
</style>
@endpush

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th> <!-- Nueva columna para la numeración -->
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Ubicación</th>
            <th>Descuento</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($farmacias as $index => $farmacia)
            <tr>
                <td>{{ $farmacias->firstItem() + $index }}</td> <!-- Numeración correcta con paginación -->
                <td>{{ $farmacia->nombre }}</td>
                <td>{{ $farmacia->telefono }}</td>
                <td>{{ $farmacia->ubicacion }}</td>
                <td>{{ $farmacia->descuento ?? '—' }}%</td>
                <td>
                    <span class="badge bg-{{ $farmacia->estado === 'Activo' ? 'success' : 'danger' }}">
                        {{ $farmacia->estado }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('farmacias.show', $farmacia) }}" class="btn btn-ver btn-sm" title="Ver">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('farmacias.edit', $farmacia) }}" class="btn btn-editar btn-sm" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('farmacias.destroy', $farmacia) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Está seguro de eliminar esta farmacia?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">No hay farmacias registradas.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {!! $farmacias->links() !!}
</div>
