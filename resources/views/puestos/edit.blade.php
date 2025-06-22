@extends('layouts.app')

@section('title', 'Editar Puesto')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        background-color: #f0faff;
        border-color: #91cfff;
    }
    label {
        font-weight: 600;
        color: #003f6b;
    }
    .form-control, select.form-select {
        font-size: 0.9rem;
    }
    .btn-primary, .btn-secondary {
        transition: 0.2s;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }
    .btn-secondary:hover {
        background-color: #cce5ff;
        color: #004085;
    }
</style>

<div class="container-fluid mt-4">
    <div class="card custom-card shadow rounded-4 border-0 w-100" style="max-width: 600px; margin: auto;">
        <div class="card-header bg-primary text-white rounded-top-4 px-4 py-3">
            <h4 class="mb-0">Editar Puesto</h4>
        </div>
        <div class="card-body px-4 py-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('puestos.update', $puesto->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                    <input type="text" name="codigo" id="codigo" class="form-control" maxlength="10" 
                        value="{{ old('codigo', $puesto->codigo) }}" required>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" id="nombre" class="form-control" maxlength="50" 
                        value="{{ old('nombre', $puesto->nombre) }}" required>
                </div>

                <div class="mb-3">
                    <label for="area" class="form-label">Departamento <span class="text-danger">*</span></label>
                    <select name="area" id="area" class="form-select" required>
                        <option value="" disabled {{ old('area', $puesto->area) ? '' : 'selected' }}>Seleccione un departamento</option>
                        @foreach(['Administración', 'Recepción', 'Laboratorio', 'Farmacia', 'Enfermería', 'Mantenimiento'] as $area)
                            <option value="{{ $area }}" {{ old('area', $puesto->area) == $area ? 'selected' : '' }}>
                                {{ $area }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="sueldo" class="form-label">Sueldo (Lps.) <span class="text-danger">*</span></label>
                    <input type="text" name="sueldo" id="sueldo" class="form-control" maxlength="8" 
                        value="{{ old('sueldo', $puesto->sueldo) }}" required
                        pattern="^\d{1,5}(\.\d{1,2})?$"
                        title="Máximo 5 dígitos enteros y 2 decimales (ejemplo: 12345.67)">
                </div>

                <div class="mb-3">
                    <label for="funcion" class="form-label">Funciones del puesto <span class="text-danger">*</span></label>
                    <textarea name="funcion" id="funcion" class="form-control" rows="4" maxlength="300" required>{{ old('funcion', $puesto->funcion) }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                <a href="{{ route('puestos.index') }}" class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2"
                    style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                        <i class="bi bi-plus-circle"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
