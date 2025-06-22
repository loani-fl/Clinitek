@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 720px;">
        <div class="card shadow-sm rounded mx-auto">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0" style="font-size: 1.25rem;"><i class="bi bi-person-vcard-fill me-2"></i> Información del Médico</h4>
                <div>
                <a href="{{ route('medicos.index') }}" class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
                </div>
            </div>

            <div class="card-body p-3">
                @if ($medico->foto)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $medico->foto) }}"
                             alt="Foto del médico"
                             class="img-thumbnail rounded-circle shadow"
                             style="width: 120px; height: 120px; object-fit: cover;">

                        <div class="mt-2">
                            @if ($medico->estado)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>
                @endif
             


                <table class="table table-bordered table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                    <tbody>
                    <tr>
                        <th style="width: 30%;">Nombre</th>
                        <td>{{ $medico->nombre }}</td>
                    </tr>
                    <tr>
                        <th>Apellidos</th>
                        <td>{{ $medico->apellidos }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $medico->telefono }}</td>
                    </tr>
                    <tr>
                        <th>Especialidad</th>
                        <td><span class="badge bg-secondary">{{ $medico->especialidad }}</span></td>
                    </tr>
                    <tr>
                        <th>Correo</th>
                        <td>{{ $medico->correo }}</td>
                    </tr>
                    <tr>
                        <th>Identidad</th>
                        <td>{{ $medico->numero_identidad ?: 'No registrado' }}</td>
                    </tr>
                    <tr>
                    <tr>
  <th>Salario</th>
  <td>
    {{ $medico->salario !== null ? 'Lps. ' . number_format($medico->salario, 2, '.', ',') : 'No especificado' }}
  </td>
</tr>


                    <tr>
                        <th>Fecha de Nacimiento</th>
                        <td>{{ \Carbon\Carbon::parse($medico->fecha_nacimiento)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Fecha de Ingreso</th>
                        <td>{{ \Carbon\Carbon::parse($medico->fecha_ingreso)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Género</th>
                        <td>
                           <span class="badge {{ $medico->genero == 'Masculino' ? 'bg-primary' : ($medico->genero == 'Femenino' ? 'bg-warning text-dark' : 'bg-info') }}">

                                {{ $medico->genero }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Observaciones</th>
                        <td>{{ $medico->observaciones ?: 'Sin observaciones.' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
