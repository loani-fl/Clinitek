<table class="table table-bordered table-hover align-middle">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Cantidad</th>
            <th>Unidad</th>
            <th>Fecha Ingreso</th>
            <th>Vencimiento</th>
            <th>Precio (L.)</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($inventarios as $item)
        <tr>
            {{-- Numeración correcta a través de la paginación --}}
            <td>{{ $inventarios->firstItem() + $loop->index }}</td>
            <td>{{ $item->nombre }}</td>
            <td>{{ $item->categoria }}</td>
            <td>{{ $item->cantidad }}</td>
            <td>{{ $item->unidad ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->fecha_ingreso)->format('d/m/Y') }}</td>
            <td>{{ $item->fecha_vencimiento ? \Carbon\Carbon::parse($item->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
            <td>{{ $item->precio_unitario ? number_format($item->precio_unitario,2) : '—' }}</td>
            <td>
                <a href="{{ route('inventario.show', $item->id) }}" class="btn btn-info btn-sm text-white"><i class="bi bi-eye"></i></a>
                <a href="{{ route('inventario.edit', $item->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center text-muted">No se encontraron registros.</td>
        </tr>
        @endforelse
    </tbody>
</table>
