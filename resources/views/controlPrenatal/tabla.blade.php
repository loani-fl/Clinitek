<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Fecha Control</th>
                <th>Próxima Cita</th>
                <th>Semanas de Gestación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($controles as $index => $cita)
                <tr>
                    <td>{{ $controles->firstItem() + $index }}</td>
                    <td>{{ $cita->paciente->nombre ?? 'N/A' }}</td>
                    <td>{{ $cita->paciente->apellidos ?? 'N/A' }}</td>
                    <td>{{ optional($cita->fecha_control)->format('d/m/Y') ?? 'N/A' }}</td>
                    <td>{{ optional($cita->fecha_proxima_cita)->format('d/m/Y') ?? 'N/A' }}</td>
                    <td>{{ $cita->semanas_gestacion ?? 'N/A' }} sem</td>
                    <td class="text-center">
                        <a href="{{ route('controles-prenatales.show', $cita->id) }}" class="btn btn-sm btn-outline-info" title="Ver detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                      
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No hay controles registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
