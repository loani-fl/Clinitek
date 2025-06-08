@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Editar Empleado</h2>

    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $empleado->nombre }}" required>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" name="correo" id="correo" class="form-control" value="{{ $empleado->correo }}" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="{{ $empleado->telefono }}" required>
        </div>

        <button type="submit" class="btn btn-warning">Actualizar</button>
        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
