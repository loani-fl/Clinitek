@forelse($medicos as $medico)
    <tr>
        <td>{{ $medico->id }}</td>
        <td class="text-center">
            @if($medico->estado)
                <i class="bi bi-circle-fill text-success" title="Activo"></i>
            @else
                <i class="bi bi-circle-fill text-danger" title="Inactivo"></i>
            @endif
        </td>
        <td>{{ $medico->nombre }}</td>
        <td>{{ $medico->apellidos }}</td>
        <td>{{ $medico->telefono }}</td>
        <td>{{ $medico->correo }}</td>
        <td>{{ $medico->especialidad }}</td>
        <td>{{ $medico->genero }}</td>
        <td>
            <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-sm btn-outline-info">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-pencil-square"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center">
            <div class="alert alert-info">No se encontraron resultados.</div>
        </td>
    </tr>
@endforelse
