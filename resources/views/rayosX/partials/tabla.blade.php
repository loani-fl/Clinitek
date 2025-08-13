<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Paciente</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($ordenes as $orden)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $orden->paciente->nombre ?? 'N/A' }} {{ $orden->paciente->apellidos ?? '' }}</td>
            <td>{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</td>
            <td>
                <span class="estado-circle {{ $orden->estado === 'Realizado' ? 'estado-realizado' : 'estado-pendiente' }}"></span>
                {{ $orden->estado }}
            </td>
            <td>
                <a href="{{ route('rayosx.show', $orden->id) }}" class="btn btn-sm btn-primary">Ver</a>
                {{-- Otras acciones --}}

                  <a href="{{ route('rayosx.analisis', $orden->id) }}" class="btn btn-sm btn-outline-success" title="Analizar orden">
                        <i class="bi bi-clipboard-data"></i>
                    </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center text-muted">No hay órdenes para mostrar.</td>
        </tr>
        @endforelse
    </tbody>
</table>
