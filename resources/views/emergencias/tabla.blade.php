<div class="table-responsive">
    <table class="table table-striped table-bordered">
       <thead>
            <tr>
                <th>#</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Tipo de sangre</th>
                <th>Género</th>
                <th>Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($emergencias as $index => $emergencia)
            <tr>
                <td>{{ $emergencias->firstItem() + $index }}</td>
                <td>{{ $emergencia->documentado && $emergencia->paciente ? $emergencia->paciente->nombre : 'Sin documento' }}</td>
                <td>{{ $emergencia->documentado && $emergencia->paciente ? $emergencia->paciente->apellidos : 'Sin documento' }}</td>
                <td>{{ $emergencia->documentado && $emergencia->paciente ? $emergencia->paciente->tipo_sangre : '-' }}</td>
                <td>{{ $emergencia->documentado && $emergencia->paciente ? $emergencia->paciente->genero : '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($emergencia->hora)->format('H:i') }}</td>
                <td class="text-center">
                    <a href="{{ route('emergencias.show', $emergencia->id) }}" 
                       class="btn btn-sm btn-outline-info" 
                       title="Ver detalles de la emergencia">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No se encontraron emergencias.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>