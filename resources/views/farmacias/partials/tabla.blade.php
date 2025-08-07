<table class="table table-striped table-bordered table-hover text-center align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Ubicación</th>
            <th>Descuento</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($farmacias as $index => $farmacia)
            <tr>
                <td>{{ $farmacias->firstItem() + $index }}</td>
                <td>{{ $farmacia->nombre }}</td>
                <td>{{ $farmacia->telefono }}</td>
                <td>{{ $farmacia->ubicacion }}</td>
                <td>{{ $farmacia->descuento ?? '—' }}%</td>
                <td>
                    <a href="{{ route('farmacias.show', $farmacia) }}" class="btn btn-ver btn-sm" title="Ver">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('farmacias.edit', $farmacia) }}" class="btn btn-editar btn-sm" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('farmacias.destroy', $farmacia) }}" method="POST" class="form-eliminar d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No hay farmacias registradas.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {!! $farmacias->links() !!}
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.form-eliminar');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Evita el envío inmediato

                Swal.fire({
                    title: '¿Está seguro?',
                    text: 'Esta farmacia se eliminará permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Envía el formulario si el usuario confirma
                    }
                });
            });
        });
    });
</script>
