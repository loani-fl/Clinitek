<table class="table table-bordered table-striped align-middle mb-0">
<thead>
<tr>
<th>#</th>
<th>Nombres</th>
<th>Apellidos</th>
<th>Identidad</th>
<th>Género</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
@forelse ($pacientes as $paciente)
<tr>
<td>
@if ($pacientes instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $pacientes->firstItem() + $loop->index }}
@else
  <td>{{ $loop->iteration }}</td>

@endif
</td>
<td>{{ $paciente->nombre }}</td>
<td>{{ $paciente->apellidos }}</td>
<td>{{ $paciente->identidad }}</td>
<td>
<span class="badge {{ $paciente->genero === 'Masculino' ? 'bg-primary' : ($paciente->genero === 'Femenino' ? 'bg-warning text-dark' : 'bg-info') }}">
{{ $paciente->genero ?? 'No especificado' }}
</span>
</td>
<td>
<div class="d-flex gap-2 justify-content-center">
<a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-outline-info me-2" title="Ver detalles">
<i class="bi bi-eye"></i>
</a>
<a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-sm btn-outline-warning" title="Editar paciente">
<i class="bi bi-pencil-square"></i>
</a>
</div>
</td>
</tr>
@empty
<tr>
<td colspan="6" class="text-center">No hay pacientes registrados.</td>
</tr>
@endforelse
</tbody>
</table>





