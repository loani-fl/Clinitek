{{-- resources/views/sesiones/tabla.blade.php --}}
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Tipo de examen</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sesiones as $index => $sesion)
            <tr>
                <td>{{ $sesiones->firstItem() + $index }}</td>
                <td>{{ $sesion->paciente->nombre }} {{ $sesion->paciente->apellidos }}</td>
                <td>{{ $sesion->medico->nombre }} {{ $sesion->medico->apellidos }}</td>
                <td>{{ $sesion->tipo_examen }}</td>
                <td>{{ \Carbon\Carbon::parse($sesion->fecha)->format('d/m/Y') }}</td>
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
                <td colspan="6" class="text-center text-muted">No se encontraron sesiones.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
