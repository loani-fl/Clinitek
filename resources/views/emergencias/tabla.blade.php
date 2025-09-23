@php
    $perPage = $emergencias->perPage();
    $currentPage = $emergencias->currentPage();
    $startIndex = ($currentPage - 1) * $perPage;
@endphp

<div class="table-responsive mx-auto" style="max-width: 900px;">
    <table class="table table-bordered table-sm text-center">
        <thead class="table-light">
            <tr>
                <th style="width: 5%; font-size: 0.85rem;">#</th>
                <th style="width: 20%; font-size: 0.85rem;">Nombres</th>
                <th style="width: 20%; font-size: 0.85rem;">Apellidos</th>
                <th style="width: 25%; font-size: 0.85rem;">Fecha y hora</th>
                <th style="width: 15%; font-size: 0.85rem;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($emergencias as $index => $emergencia)
                <tr style="font-size: 0.85rem;">
                    <td>{{ $startIndex + $index + 1 }}</td>
                    <td>{{ $emergencia->nombres ?? 'Sin documentación' }}</td>
                    <td>{{ $emergencia->apellidos ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($emergencia->fecha_hora)->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('emergencias.show', $emergencia->id) }}" 
                           class="btn btn-white-border btn-outline-info btn-sm" 
                           title="Ver">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay emergencias registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
