<table class="table table-bordered table-striped mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Departamento</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($puestos as $index => $puesto)
            <tr>
                <td class="text-center">{{ $puestos->firstItem() + $index }}</td>
                <td>{{ $puesto->codigo }}</td>
                <td>{{ $puesto->nombre }}</td>
                <td>{{ $puesto->area }}</td>
                <td class="text-center">
                    <a href="{{ route('puestos.show', $puesto->id) }}" class="btn btn-sm btn-outline-info me-2" title="Ver detalles">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('puestos.edit', $puesto) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No hay puestos registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>

