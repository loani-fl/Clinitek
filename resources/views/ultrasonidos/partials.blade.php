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
                {{-- Si el análisis ya está realizado, deshabilitar el botón --}}
                @if(strtolower($orden->estado) == 'realizado')
                    <button class="btn btn-sm btn-secondary"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="El análisis ya fue realizado"
                            disabled>
                        <i class="bi bi-clipboard-data"></i>
                    </button>
                @else
                    <a href="{{ route('ultrasonidos.analisis', $orden->id) }}"
                       class="btn btn-sm btn-outline-success"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="Crear análisis">
                        <i class="bi bi-clipboard-data"></i>
                    </a>
                @endif

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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
