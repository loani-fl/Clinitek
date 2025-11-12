@php
    $perPage = $ordenes->perPage();
    $currentPage = $ordenes->currentPage();
    $startIndex = ($currentPage - 1) * $perPage + 1;
@endphp

<table class="table table-bordered">
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
    @forelse($ordenes as $index => $orden)
        <tr>
            <td>{{ $startIndex + $index }}</td>
            <td>{{ $orden->paciente->nombre }} {{ $orden->paciente->apellidos }}</td>
            <td>{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</td>
            <td>
                @if(strtolower($orden->estado) == 'realizado')
                    <span class="estado-circle estado-realizado"></span> Realizado
                @else
                    <span class="estado-circle estado-pendiente"></span> Pendiente
                @endif
            </td>

            <td>
                <a href="{{ route('ultrasonidos.analisis', $orden->id) }}"
                   class="btn btn-sm btn-outline-success"
                   data-bs-toggle="tooltip"
                   data-bs-placement="top"
                   title="crear análisis ">
                    <i class="bi bi-clipboard-data"></i>
                </a>

                <a href="{{ route('ultrasonidos.show', $orden->id) }}"
                   class="btn btn-sm btn-outline-info"
                   data-bs-toggle="tooltip"
                   data-bs-placement="top"
                   title="Ver resultados del análisis">
                    <i class="bi bi-eye"></i>
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">No hay órdenes registradas.</td>
        </tr>
    @endforelse
    </tbody>
</table>

