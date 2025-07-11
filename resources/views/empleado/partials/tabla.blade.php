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
                <td colspan="6" class="text-center">No hay empleados registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $empleados->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>
