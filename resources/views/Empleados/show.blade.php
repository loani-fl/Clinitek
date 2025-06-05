@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Detalles del Empleado</h2>

    <div class="mb-3">
        <strong>Nombre:</strong> {{ $empleado->nombre }}
    </div>

    <div class="mb-3">
        <strong>Correo:</strong> {{ $empleado->correo }}
    </div>

    <div class="mb-3">
        <strong>Teléfono:</strong> {{ $empleado->telefono }}
    </div>

    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
