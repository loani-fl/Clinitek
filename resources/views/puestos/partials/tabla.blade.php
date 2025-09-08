<table class="table table-bordered table-striped align-middle mb-0">
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
                {{-- Si es búsqueda, enumeramos desde 1 con loop, si no, usamos startIndex para la paginación --}}
                <td class="text-center">
                    {{ $isSearch ? $loop->iteration : ($startIndex ?? 0) + $loop->iteration }}
                </td>

                <td>{{ $puesto->codigo }}</td>
                <td>{{ $puesto->nombre }}</td>
                <td>{{ $puesto->area }}</td>
                <td class="text-center">
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('puestos.show', $puesto->id) }}" class="btn btn-sm btn-outline-info me-2" title="Ver detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('puestos.edit', $puesto->id) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No hay puestos registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>
