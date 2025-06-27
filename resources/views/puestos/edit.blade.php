@extends('layouts.app')

@section('title', 'Editar Puesto')

@section('content')

<!-- Barra de navegación superior -->
<div class="header d-flex justify-content-between align-items-center px-3 py-2"
     style="background-color: #007BFF; position: sticky; top: 0; z-index: 1030;">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek"
             style="height: 40px; width: auto; margin-right: 6px;">
        <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
        <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>

<!-- Estilo visual personalizado -->
<style>
    .custom-card {
        max-width: 800px;
        background-color: rgba(255, 255, 255, 0.95);
        /*border: 1px solid #91cfff;*/
        border-radius: 0.5rem;
        position: relative;
        overflow: hidden;
        margin: 2rem auto;
        padding: 2rem;
        box-shadow: 0 0 15px rgba(0,123,255,0.25);
        z-index: 1;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 700px;
        height: 700px;
        background-image: url('{{ asset('images/logo2.jpg') }}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.15;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    label {
        font-weight: 600;
        color: #003f6b;
    }
</style>

<!-- Contenedor principal -->
<div class="custom-card">

    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Editar Puesto</h2>
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

