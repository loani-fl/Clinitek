<table class="table table-bordered table-hover align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Cantidad</th>
            <th>Unidad</th>
            <th>Fecha Ingreso</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($inventarios as $item)
        <tr>
            {{-- Numeración correcta a través de la paginación --}}
            <td>{{ $inventarios->firstItem() + $loop->index }}</td>
            <td>{{ $item->codigo }}</td>
            <td>{{ $item->nombre }}</td>
            <td>{{ $item->categoria }}</td>
            <td>{{ $item->cantidad }}</td>
            <td>{{ $item->unidad ?? '-' }}</td>
            <td>{{ $item->fecha_ingreso ? \Carbon\Carbon::parse($item->fecha_ingreso)->format('d/m/Y') : '—' }}</td>
            <td class="d-flex justify-content-center gap-1">
                <a href="{{ route('inventario.edit', $item->id) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="{{ route('inventario.show', $item->id) }}" class="btn btn-sm btn-info text-white">
                    <i class="bi bi-eye"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center text-muted">No se encontraron registros.</td>
        </tr>
        @endforelse
    </tbody>
</table>
