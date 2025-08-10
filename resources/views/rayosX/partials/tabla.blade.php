<table class="table table-bordered table-striped align-middle mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Paciente</th>
            <th>Fecha</th>
            <th>Total a Pagar</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($ordenes as $index => $orden)
        <tr>
            <td>{{ ($ordenes instanceof \Illuminate\Pagination\LengthAwarePaginator) ? $ordenes->firstItem() + $index : $index + 1 }}</td>
            <td>
                @if($orden->paciente_tipo === 'clinica' && $orden->paciente_id)
                    {{ optional($orden->pacienteClinica)->nombre ?? 'Sin nombre' }}
                    {{ optional($orden->pacienteClinica)->apellidos ?? '' }}
                @elseif($orden->paciente_tipo === 'rayosx' && $orden->paciente_id)
                    {{ optional($orden->pacienteRayosX)->nombre ?? 'Sin nombre' }}
                    {{ optional($orden->pacienteRayosX)->apellidos ?? '' }}
                @elseif($orden->diagnostico && $orden->diagnostico->paciente)
                    {{ $orden->diagnostico->paciente->nombre ?? 'Sin nombre' }}
                    {{ $orden->diagnostico->paciente->apellidos ?? '' }}
                @else
                    {{ $orden->nombres ?? 'Sin nombre' }}
                    {{ $orden->apellidos ?? '' }}
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</td>
            <td>${{ number_format($orden->total_precio, 2) }}</td>
            <td class="text-center">
                @php
                    $estado = strtolower($orden->estado);
                    $claseCirculo = $estado === 'realizado' ? 'estado-realizado' : 'estado-pendiente';
                @endphp
                <span class="estado-circulo {{ $claseCirculo }}" title="{{ ucfirst($estado) }}"></span>
            </td>
            <td>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('rayosx.show', $orden->id) }}" class="btn btn-sm btn-outline-info" title="Ver orden">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('rayosx.analisis', $orden->id) }}" class="btn btn-sm btn-outline-success" title="Analizar orden">
                        <i class="bi bi-clipboard-data"></i>
                    </a>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center fst-italic text-muted">No hay órdenes registradas.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<style>
    .estado-circulo {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .estado-realizado {
        background-color: #28a745;
    }
    .estado-pendiente {
        background-color: #ffc107;
    }
</style>
