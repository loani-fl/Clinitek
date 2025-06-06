@extends('layouts.app')

@section('content')
    <div class="full-width-container">
        <div class="table-container">
            <div class="header-content d-flex justify-content-between align-items-center mb-3">
                <h2>Detalle del Médico</h2>
                <div>
                    <a href="{{ route('medicos.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Volver a la lista
                    </a>
                    <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                </div>
            </div>

            <table class="table table-bordered table-hover align-middle">
                <tbody>
                <tr>
                    <th class="text-center" style="width: 25%;">Nombre</th>
                    <td>{{ $medico->nombre }}</td>
                </tr>
                <tr>
                    <th class="text-center">Apellidos</th>
                    <td>{{ $medico->apellidos }}</td>
                </tr>
                <tr>
                    <th class="text-center">Teléfono</th>
                    <td class="text-center">{{ $medico->telefono }}</td>
                </tr>
                <tr>
                    <th class="text-center">Correo</th>
                    <td>{{ $medico->correo }}</td>
                </tr>
                <tr>
                    <th class="text-center">Fecha de Nacimiento</th>
                    <td class="text-center">{{ $medico->fecha_nacimiento }}</td>
                </tr>
                <tr>
                    <th class="text-center">Fecha de Ingreso</th>
                    <td class="text-center">{{ $medico->fecha_ingreso }}</td>
                </tr>
                <tr>
                    <th class="text-center">Género</th>
                    <td class="text-center">
                        @if($medico->genero == 'Masculino')
                            <span class="badge bg-primary">{{ $medico->genero }}</span>
                        @else
                            <span class="badge bg-info">{{ $medico->genero }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-center">Observaciones</th>
                    <td>{{ $medico->observaciones ?: 'N/A' }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

