@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5" style="max-width: 800px;">
    <div class="card shadow-sm rounded mx-auto">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0" style="font-size: 1.25rem;">
                <i class="bi bi-person-badge-fill me-2"></i> Detalles del Empleado
            </h4>
            <a href="{{ route('empleado.index') }}" 
   class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2" 
   style="font-size: 0.85rem;">
    <i class="bi bi-arrow-left"></i> Regresar
</a>


        </div>

        <div class="card-body p-3">
            <table class="table table-bordered table-striped align-middle" style="font-size: 0.92rem;">
                <tbody>
                    <tr><th style="width: 30%;">Nombres</th><td>{{ $empleado->nombres }}</td></tr>
                    <tr><th>Apellidos</th><td>{{ $empleado->apellidos }}</td></tr>
                    <tr><th>Identidad</th><td>{{ $empleado->identidad }}</td></tr>
                    <tr><th>Correo</th><td>{{ $empleado->correo }}</td></tr>
                    <tr><th>Teléfono</th><td>{{ $empleado->telefono }}</td></tr>
                    <tr><th>Estado Civil</th><td>{{ $empleado->estado_civil }}</td></tr>
                    <tr>
                        <th>Género</th>
                        <td>
                            <span class="badge 
                                {{ $empleado->genero === 'Masculino' ? 'bg-primary' : 
                                   ($empleado->genero === 'Femenino' ? 'bg-warning text-dark' : 'bg-info') }}">
                                {{ $empleado->genero }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Fecha de Ingreso</th><td>{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td></tr>
                    <tr><th>Fecha de Nacimiento</th><td>{{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y') }}</td></tr>
                    <tr><th>Salario</th><td>{{ $empleado->salario ? 'Lps. ' . number_format($empleado->salario, 2) : 'No especificado' }}</td></tr>
                    <tr><th>Área</th><td>{{ $empleado->puesto->area ?? 'No especificada' }}</td></tr>
                    <tr><th>Turno Asignado</th><td>{{ $empleado->turno_asignado }}</td></tr>
                    <tr><th>Puesto</th><td>{{ $empleado->puesto->nombre ?? 'No especificado' }}</td></tr>
                    <tr>
                        <th>Estado</th>
                        <td>
                            @if($empleado->estado == 'Activo' || $empleado->estado == 1 || $empleado->estado === true)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Dirección</th><td style="white-space: pre-line;">{{ $empleado->direccion }}</td></tr>
                    <tr><th>Observaciones</th><td style="white-space: pre-line;">{{ $empleado->observaciones ?: 'Sin observaciones.' }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<footer class="bg-light text-center py-2 border-top" style="position: fixed; bottom: 0; left: 0; width: 100%; font-size: 0.85rem;">
    © 2025 Clínitek. Todos los derechos reservados.
</footer>
@endsection
