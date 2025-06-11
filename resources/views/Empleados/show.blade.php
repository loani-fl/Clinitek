@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-user"></i> Detalles del Empleado</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><strong>Nombre:</strong></label>
                    <div class="form-control">{{ $empleado->nombre_completo }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><strong>Identidad:</strong></label>
                    <div class="form-control">{{ $empleado->identidad }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><strong>Dirección:</strong></label>
                    <div class="form-control">{{ $empleado->direccion }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><strong>Correo:</strong></label>
                    <div class="form-control">{{ $empleado->correo }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><strong>Teléfono:</strong></label>
                    <div class="form-control">{{ $empleado->telefono }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><strong>Estado Civil:</strong></label>
                    <div class="form-control">{{ $empleado->estado_civil }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><strong>Género:</strong></label>
                    <div class="form-control">{{ $empleado->genero }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><strong>Fecha de Ingreso:</strong></label>
                    <div class="form-control">{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label"><strong>Salario:</strong></label>
                    <div class="form-control">L. {{ number_format($empleado->salario, 2) }}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><strong>Puesto:</strong></label>
                    <div class="form-control">{{ $empleado->puesto->nombre ?? 'Sin asignar' }}</div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('empleados.visualizacion') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
