@extends('layouts.app')

@section('title', 'Editar Puesto')

@push('styles')
<style>
    html, body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
        max-width: 100%;
        background-color: #e8f4fc;
    }

    .custom-card {
        position: relative;
        max-width: 1000px;
        width: 96%;
        margin: 60px auto 40px;
        padding: 30px 40px 40px 40px;
        background-color: rgba(255, 255, 255, 0.85); /* Fondo translúcido */
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 1;
        border: 1px solid #91cfff;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 800px;
        height: 800px;
        background-image: url('{{ asset('images/logo2.jpg') }}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.12;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
        border-radius: 1rem;
    }

    label {
        background-color: transparent;
        color: rgba(0, 0, 0, 0.6); /* Texto claro y semitransparente */
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    input, select, textarea {
        position: relative;
        z-index: 1;
        background-color: transparent !important; /* Fondo transparente */
        color: #212529;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        width: 100%;
        box-sizing: border-box;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #007BFF;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100vw;
        z-index: 1050;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header img {
        height: 40px;
        margin-right: 8px;
    }

    .header .fw-bold {
        font-size: 1.5rem;
        color: white;
    }

    .nav-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .nav-buttons a {
        color: white !important;
        font-weight: 600;
        text-decoration: none;
    }

    .nav-buttons a:hover {
        text-decoration: underline;
    }

    .custom-card-header {
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.75rem;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
        color: #000;
        font-weight: 700;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    @media (max-width: 576px) {
        .custom-card {
            padding: 1rem;
            margin: 80px 1rem 2rem 1rem;
        }

        .custom-card-header {
            font-size: 1.2rem;
        }
    }
</style>
@endpush

@section('content')

<!-- Barra de navegación superior -->
<div class="header">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
        <span class="fw-bold">Clinitek</span>
    </div>

    <div class="nav-buttons">
        <a href="{{ route('puestos.create') }}">Crear puesto</a>
        <a href="{{ route('empleado.create') }}">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}">Registrar médico</a>
    </div>
</div>

<!-- Contenedor principal -->
<div class="custom-card">

    <div class="custom-card-header">
        Editar Puesto
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('puestos.update', $puesto->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                <input type="text" name="codigo" id="codigo" class="form-control" maxlength="10"
                       value="{{ old('codigo', $puesto->codigo) }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="nombre" id="nombre" class="form-control" maxlength="50"
                       value="{{ old('nombre', $puesto->nombre) }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
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

            <div class="col-md-4 mb-3">
                <label for="sueldo" class="form-label">Sueldo (Lps.) <span class="text-danger">*</span></label>
                <input type="text" name="sueldo" id="sueldo" class="form-control" maxlength="8"
                       value="{{ old('sueldo', $puesto->sueldo) }}" required
                       pattern="^\d{1,5}(\.\d{1,2})?$"
                       title="Máximo 5 dígitos enteros y 2 decimales (ejemplo: 12345.67)">
            </div>
        </div>

        <div class="row">
            <div class="col-6 mb-3">
                <label for="funcion" class="form-label">Funciones del puesto <span class="text-danger">*</span></label>
                <textarea name="funcion" id="funcion" class="form-control" rows="4" maxlength="300"
                          required>{{ old('funcion', $puesto->funcion) }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-save"></i> Actualizar
            </button>
            <button type="reset" class="btn btn-warning px-4 shadow-sm">
                <i class="bi bi-arrow-counterclockwise"></i> Restablecer
            </button>
            <a href="{{ route('puestos.index') }}" class="btn btn-success px-4 shadow-sm">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </form>
</div>

@endsection
