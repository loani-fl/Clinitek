<div class="table-responsive">
@if($controles->count() > 0)
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Paciente</th>
               
                <th>Fecha Control</th>
                <th>Próxima Cita</th>
                <th>Semanas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($controles as $index => $control)
                <tr>
                    <td>{{ $controles->firstItem() + $index }}</td>
                    <td>{{ $control->paciente->nombre ?? 'N/A' }} {{ $control->paciente->apellidos ?? '' }}</td>
                
                    <td>{{ optional($control->fecha_control)->format('d/m/Y') ?? 'N/A' }}</td>
                    <td>{{ optional($control->fecha_proxima_cita)->format('d/m/Y') ?? 'N/A' }}</td>
                    <td>
                        @php
                            $semanas = $control->semanas_gestacion ?? 0;
                            if ($semanas <= 13) $clase = 'bg-info';
                            elseif ($semanas <= 27) $clase = 'bg-warning';
                            else $clase = 'bg-danger';
                        @endphp
                        <span class="badge {{ $clase }}">{{ $semanas }} sem</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('controles-prenatales.show', $control->id) }}" class="btn btn-sm btn-outline-info" title="Ver detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-center py-4">
        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
        <p class="mt-2">No hay controles prenatales registrados</p>
        <a href="{{ route('controles-prenatales.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Registrar Primer Control
        </a>
    </div>
@endif
</div>
