@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-primary rounded-3">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>Registro de un nuevo puesto</h5>
        </div>

        <div class="card-body">
            {{-- Errores --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <h6 class="small"><i class="bi bi-exclamation-triangle-fill"></i> Corrige los siguientes errores:</h6>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('puestos.store') }}" novalidate>
                @csrf

                <div class="d-flex justify-content-center">
                    <div class="form-mini" style="max-width: 700px; width: 100%;">

                        <div class="row gx-18 mt-5">
                            <div class="col-md-3">
                                <label for="codigo" class="form-label fw-semibold small text-center w-100">
                                    Código <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="codigo" id="codigo" class="form-control form-control-sm" value="{{ old('codigo') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nombre" class="form-label fw-semibold small text-center w-100">
                                    Nombre del Puesto <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="nombre" class="form-control form-control-sm" value="{{ old('nombre') }}" required>
                            </div>
                        </div>

                        {{-- Área y Sueldo --}}
                        <div class="row g-4 mt-4">
                            <div class="col-md-5">
                                <label for="area" class="form-label fw-semibold small text-center w-100">
                                    Área / Departamento <span class="text-danger">*</span>
                                </label>
                                <select name="area" id="area" class="form-select form-select-sm" required onchange="autoFillSueldo()">
                                    <option value="">-- Selecciona un área --</option>
                                    <option value="Administración" {{ old('area') == 'Administración' ? 'selected' : '' }} data-sueldo="15000">Administración</option>
                                    <option value="Recepción" {{ old('area') == 'Recepción' ? 'selected' : '' }} data-sueldo="12000">Recepción</option>
                                    <option value="Laboratorio" {{ old('area') == 'Laboratorio' ? 'selected' : '' }} data-sueldo="18000">Laboratorio</option>
                                    <option value="Farmacia" {{ old('area') == 'Farmacia' ? 'selected' : '' }} data-sueldo="16000">Farmacia</option>
                                    <option value="Enfermería" {{ old('area') == 'Enfermería' ? 'selected' : '' }} data-sueldo="17000">Enfermería</option>
                                    <option value="Mantenimiento" {{ old('area') == 'Mantenimiento' ? 'selected' : '' }} data-sueldo="11000">Mantenimiento</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="sueldo" class="form-label fw-semibold small text-center w-100">
                                    Sueldo <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="sueldo" id="sueldo" step="0.01" class="form-control form-control-sm" value="{{ old('sueldo') }}" required>
                            </div>
                        </div>

                        {{-- Función del Puesto --}}
                        <div class="row g-4 mt-4">
                            <div class="col-8">
                                <label for="funcion" class="form-label fw-semibold small text-center w-100">
                                    Función del Puesto <span class="text-danger">*</span>
                                </label>
                                <textarea name="funcion" id="funcion" rows="3" class="form-control form-control-sm" required>{{ old('funcion') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-start mt-5 gap-5">
                            <button type="submit" class="btn btn-primary btn-sm shadow-sm px-4">
                                <i class="bi bi-plus-circle"></i> Registrar
                            </button>
                            <a href="{{ route('puestos.index') }}" class="btn btn-primary btn-sm shadow-sm px-4">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function autoFillSueldo() {
        const select = document.getElementById('area');
        const sueldoInput = document.getElementById('sueldo');
        const option = select.options[select.selectedIndex];
        const sueldo = option.getAttribute('data-sueldo');

        if (sueldo && (sueldoInput.value === '' || sueldoInput.value === sueldoInput.dataset.prevValue)) {
            sueldoInput.value = sueldo;
            sueldoInput.dataset.prevValue = sueldo;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const sueldoInput = document.getElementById('sueldo');
        sueldoInput.dataset.prevValue = sueldoInput.value || '';
    });
</script>
@endpush
@endsection









