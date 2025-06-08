@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Editar Empleado</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" name="nombres" class="form-control" value="{{ old('nombres', $empleado->nombres) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', $empleado->apellidos) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="identidad" class="form-label">Identidad</label>
                        <input type="text" name="identidad" class="form-control" value="{{ old('identidad', $empleado->identidad) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $empleado->direccion) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control" value="{{ old('correo', $empleado->correo) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $empleado->telefono) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="estado_civil" class="form-label">Estado Civil</label>
                        <input type="text" name="estado_civil" class="form-control" value="{{ old('estado_civil', $empleado->estado_civil) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="genero" class="form-label">Género</label>
                        <select name="genero" class="form-select" required>
                            <option value="Masculino" {{ $empleado->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ $empleado->genero == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ $empleado->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                        <input type="date" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso', $empleado->fecha_ingreso) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="salario" class="form-label">Salario</label>
                        <input type="number" step="0.01" name="salario" class="form-control" value="{{ old('salario', $empleado->salario) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="puesto_id" class="form-label">Puesto</label>
                        <select name="puesto_id" class="form-select" required>
                            @foreach($puestos as $puesto)
                                <option value="{{ $puesto->id }}" {{ $empleado->puesto_id == $puesto->id ? 'selected' : '' }}>
                                    {{ $puesto->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Actualizar
                    </button>
                    <a href="{{ route('empleados.visualizacion') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
