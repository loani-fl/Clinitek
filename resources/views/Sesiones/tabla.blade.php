{{-- resources/views/sesiones/tabla.blade.php --}}
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Paciente</th>
                <th>Edad</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Tipo de examen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sesiones as $index => $sesion)
            <tr>
                <td>{{ $sesiones->firstItem() + $index }}</td>
                <td>{{ $sesion->paciente->nombre }} {{ $sesion->paciente->apellidos }}</td>
                <td>{{ \Carbon\Carbon::parse($sesion->paciente->fecha_nacimiento)->age }}</td>
                <td>{{ $sesion->hora_inicio }}</td>
                <td>{{ $sesion->hora_fin }}</td>
                <td>{{ $sesion->tipo_examen }}</td>
                <td class="text-center">
                    <a href="{{ route('sesiones.show', $sesion->id) }}" 
                       class="btn btn-sm btn-outline-info" 
                       title="Ver detalles de la sesión">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No se encontraron sesiones.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
