<div class="table-responsive">
    <table class="table table-bordered table-striped mb-0">
        <thead>
        <tr>
            <th>#</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Identidad</th>
            <th class="text-center">Especialidad</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
        </thead>
        <tbody id="tabla-medicos">
        @forelse($medicos as $index => $medico)
            <tr>
                <td>{{ $medicos->firstItem() + $index }}</td>
                <td>{{ $medico->nombre }} {{ $medico->apellidos }}</td>
                <td>{{ $medico->numero_identidad }}</td>
                <td>{{ $medico->especialidad }}</td>
                <td class="text-center">
                    @if($medico->estado)
                        <span class="estado-activo"><i class="bi bi-circle-fill"></i></span>
                    @else
                        <span class="estado-inactivo"><i class="bi bi-circle-fill"></i></span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No hay médicos registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
