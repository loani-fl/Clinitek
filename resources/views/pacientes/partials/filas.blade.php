@forelse($pacientes as $paciente)
    <tr>
        <td>{{ $paciente->id }}</td>
        <td>{{ $paciente->nombre }} {{ $paciente->apellidos }}</td>
        <td>{{ $paciente->identidad }}</td>
    </tr>
@empty
    <tr>
        <td colspan="3">No se encontraron pacientes.</td>
    </tr>
@endforelse
