@extends('layouts.app')

@section('content')
    @php use Carbon\Carbon; @endphp

    <div class="container mt-4 mb-5" style="max-width: 800px;">
        <div class="card shadow-sm rounded mx-auto">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0" style="font-size: 1.25rem;">
                    <i class="bi bi-person-badge-fill me-2"></i> Detalles del Paciente
                </h4>
                <a href="{{ route('pacientes.index') }}"
                   class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2"
                   style="font-size: 0.85rem;">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>

            <div class="card-body p-3">
                <table class="table table-bordered table-striped align-middle" style="font-size: 0.92rem;">
                    <tbody>
                    <tr><th style="width: 30%;">Nombre</th><td>{{ $paciente->nombre }}</td></tr>
                    <tr><th>Apellidos</th><td>{{ $paciente->apellidos }}</td></tr>
                    <tr><th>Identidad</th><td>{{ $paciente->identidad }}</td></tr>
                    <tr><th>Correo</th><td>{{ $paciente->correo }}</td></tr>
                    <tr><th>Teléfono</th><td>{{ $paciente->telefono }}</td></tr>
                    <tr><th>Fecha de Nacimiento</th><td>{{ $paciente->fecha_nacimiento ? Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}</td></tr>
                    <tr><th>Dirección</th><td style="white-space: pre-line;">{{ $paciente->direccion }}</td></tr>
                    <tr><th>Tipo de Sangre</th><td>{{ $paciente->tipo_sangre ?: 'No especificado' }}</td></tr>
                    <tr><th>Alergias</th><td>{{ $paciente->alergias ?: 'Ninguna' }}</td></tr>
                    <tr>
                        <th>Género</th>
                        <td>
                            <span class="badge
                                {{ $paciente->genero === 'Masculino' ? 'bg-primary' :
                                   ($paciente->genero === 'Femenino' ? 'bg-warning text-dark' : 'bg-info') }}">
                                {{ $paciente->genero ?? 'No especificado' }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Padecimientos</th><td style="white-space: pre-line;">{{ $paciente->padecimientos ?: 'Ninguno' }}</td></tr>
                    <tr><th>Medicamentos</th><td style="white-space: pre-line;">{{ $paciente->medicamentos ?: 'Ninguno' }}</td></tr>
                    <tr><th>Historial Clínico</th><td style="white-space: pre-line;">{{ $paciente->historial_clinico ?: 'Sin información' }}</td></tr>
                    <tr><th>Historial Quirúrgico</th><td style="white-space: pre-line;">{{ $paciente->historial_quirurgico ?: 'Sin información' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center py-2 border-top" style="position: fixed; bottom: 0; left: 0; width: 100%; font-size: 0.85rem;">
        © 2025 Clínitek. Todos los derechos reservados.
    </footer>
@endsection
