@extends('layouts.app')

@section('title', '')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-pencil-fill me-2"></i> Editar Puesto</h5>
        </div>

        <div class="card-body">
            {{-- Alerta de error --}}
            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('puestos.update', $puesto) }}" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        {{-- Código del puesto y Nombre --}}
                        <div class="row g-4 mt-4">
                            <div class="mb-3 col-md-3">
                                <label for="codigo" class="form-label small fw-semibold">Código del Puesto <span class="text-danger">*</span></label>
                                <input type="text" name="codigo" id="codigo" class="form-control form-control-sm @error('codigo') is-invalid @enderror" value="{{ old('codigo', $puesto->codigo) }}" placeholder="Ej. A001" required>
                                @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3 col-md-8">
                                <label for="nombre" class="form-label small fw-semibold">Nombre del Puesto <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-control form-control-sm @error('nombre') is-invalid @enderror" value="{{ old('nombre', $puesto->nombre) }}" placeholder="Ej. Auxiliar Administrativo" required>
                                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Área y Sueldo --}}
                        <div class="row">
                            <div class="mb-3 col-md-5">
                                <label for="area" class="form-label small fw-semibold">Departamento o Área <span class="text-danger">*</span></label>
                                <select name="area" id="area" class="form-select form-select-sm @error('area') is-invalid @enderror" required onchange="autoFillSueldo()">
                                    <option value="">-- Selecciona un área --</option>
                                    <option value="Administración" {{ old('area', $puesto->area) == 'Administración' ? 'selected' : '' }}>Administración</option>
                                    <option value="Recepción" {{ old('area', $puesto->area) == 'Recepción' ? 'selected' : '' }}>Recepción</option>
                                    <option value="Laboratorio" {{ old('area', $puesto->area) == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                                    <option value="Farmacia" {{ old('area', $puesto->area) == 'Farmacia' ? 'selected' : '' }}>Farmacia</option>
                                    <option value="Enfermería" {{ old('area', $puesto->area) == 'Enfermería' ? 'selected' : '' }}>Enfermería</option>
                                    <option value="Mantenimiento" {{ old('area', $puesto->area) == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                </select>
                                @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="sueldo" class="form-label small fw-semibold">Sueldo (Lps.) <span class="text-danger">*</span></label>
                                <input type="number" name="sueldo" id="sueldo" step="0.01" class="form-control form-control-sm @error('sueldo') is-invalid @enderror" value="{{ old('sueldo', $puesto->sueldo) }}" placeholder="Ej. 15000.00" required>
                                @error('sueldo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Funciones --}}
                        <div class="row g-3 mt-3">
                            <div class="col-7">
                                <label for="funcion" class="form-label fw-semibold small">Funciones <span class="text-danger">*</span></label>
                                <textarea name="funcion" id="funcion" class="form-control form-control-sm @error('funcion') is-invalid @enderror" rows="3" placeholder="Describe las tareas principales" required>{{ old('funcion', $puesto->funcion ?? '') }}</textarea>
                                @error('funcion') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

{{-- Botones alineados a la izquierda con espacio entre ellos --}}
<div class="row mt-4">
    <div class="col text-start">
        <button type="submit" class="btn btn-primary btn-sm px-4 me-2">
            <i class="bi bi-save"></i> Guardar Cambios
        </button>
        <a href="{{ route('puestos.index') }}" class="btn btn-outline-secondary btn-sm px-4">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</div>

</div>


                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .form-control-sm,
    .form-select-sm {
        border-radius: 6px;
        font-size: 0.9rem;
        padding: 0.4rem 0.6rem;
    }
    .label-funcion {
        font-size: 0.75rem;
    }
    .btn-sm {
        font-size: 0.875rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
    }
    .alert-danger {
        font-size: 0.85rem;
    }
</style>

<script>
    function autoFillSueldo() {
        const selectArea   = document.getElementById('area');
        const sueldoInput  = document.getElementById('sueldo');
        const selected     = selectArea.options[selectArea.selectedIndex];
        const sueldosBase  = {
            "Administración": 20000,
            "Recepción": 15000,
            "Laboratorio": 18000,
            "Farmacia": 17000,
            "Enfermería": 19000,
            "Mantenimiento": 14000
        };
        const sueldoBase = sueldosBase[selected.value] || '';
        if (sueldoBase && (sueldoInput.value === '' || sueldoInput.value === sueldoInput.dataset.prevValue)) {
            sueldoInput.value = sueldoBase;
            sueldoInput.dataset.prevValue = sueldoBase;
        }
    }

    window.onload = () => {
        const sueldoInput = document.getElementById('sueldo');
        sueldoInput.dataset.prevValue = sueldoInput.value || '';
    };
</script>
@endsection







