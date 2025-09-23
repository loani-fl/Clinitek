@php
    // Si es paginación, usamos firstItem(), si es colección completa (filtro), iniciamos en 0
    $startIndex = $emergencias instanceof \Illuminate\Pagination\LengthAwarePaginator
                  ? $emergencias->firstItem() - 1
                  : 0;
@endphp

<table class="table table-striped table-bordered text-center">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($emergencias as $index => $emergencia)
        <tr>
            <td>{{ $startIndex + $loop->iteration }}</td>
            <td>{{ $emergencia->nombres ?? 'Sin documentación' }}</td>
            <td>{{ $emergencia->apellidos ?? '' }}</td>
            <td>{{ \Carbon\Carbon::parse($emergencia->fecha)->format('d/m/Y') }}</td>
            <td>{{ $emergencia->hora }}</td>
            <td>
                <a href="{{ route('emergencias.show', $emergencia->id) }}" class="btn btn-sm btn-outline-info" title="Ver">
                    <i class="bi bi-eye"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">No hay emergencias para mostrar.</td>
        </tr>
        @endforelse
    </tbody>
</table>
