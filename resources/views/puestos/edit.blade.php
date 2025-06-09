@extends('layouts.app')

@section('title', '')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        max-width: 95%;
        background-color: #f0faff;
        border-color: #91cfff;
    }
    label {
        font-size: 0.78rem;
        margin-bottom: 0.2rem;
        color: #5a5a5a;
    }
    input, select, textarea {
        font-size: 0.85rem !important;
    }
    .btn-sm {
        font-size: 0.85rem;
        padding: 0.45rem 1rem;
        border-radius: 6px;
    }
    .alert-danger {
        font-size: 0.8rem;
    }
</style>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2 rounded-top-4">
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

                {{-- Primera fila: Código - Nombre --}}
                <div class="row g-4">
                    <div class="col-md-3">
                        <label for="codigo" class="form-label">Código del Puesto <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" id="codigo" class="form-control form-control-sm @error('codigo') is-invalid @enderror" value="{{ old('codigo', $puesto->codigo) }}" placeholder="Ej. A001" required>
                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del Puesto <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control form-control-sm @error('nombre') is-invalid @enderror" value="{{ old('nombre', $puesto->nombre) }}" placeholder="Ej. Auxiliar Administrativo" required>
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Segunda fila: Área - Sueldo --}}
                <div class="row g-4 mt-1">
                    <div class="col-md-4">
                        <label for="area" class="form-label">Departamento o Área <span class="text-danger">*</span></label>
                        <select name="area" id="area" class="form-select form-select-sm @error('area') is-invalid @enderror" required onchange="autoFillSueldo()">
                            <option value="">-- Selecciona un área --</option>
                            @foreach(['Administración', 'Recepción', 'Laboratorio', 'Farmacia', 'Enfermería', 'Mantenimiento'] as $area)
                                <option value="{{ $area }}" {{ old('area', $puesto->area) == $area ? 'selected' : '' }}>{{ $area }}</option>
                            @endforeach
                        </select>
                        @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="sueldo" class="form-label">Sueldo (Lps.) <span class="text-danger">*</span></label>
                        <input type="number" name="sueldo" id="sueldo" step="0.01" class="form-control form-control-sm @error('sueldo') is-invalid @enderror" value="{{ old('sueldo', $puesto->sueldo) }}" placeholder="Ej. 15000.00" required>
                        @error('sueldo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Tercera fila: Funciones --}}
                <div class="row g-4 mt-1">
                    <div class="col-md-9">
                        <label for="funcion" class="form-label">Funciones <span class="text-danger">*</span></label>
                        <textarea name="funcion" id="funcion" class="form-control form-control-sm @error('funcion') is-invalid @enderror" rows="3" placeholder="Describe las tareas principales" required>{{ old('funcion', $puesto->funcion ?? '') }}</textarea>
                        @error('funcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Botones centrados --}}
                <div class="d-flex justify-content-center mt-4 gap-4">
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('puestos.index') }}" class="btn btn-outline-secondary btn-sm px-4 shadow-sm">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function autoFillSueldo() {
        const selectArea   = document.getElementById('area');
        const sueldoInput  = document.getElementById('sueldo');
        const sueldosBase  = {
            "Administración": 20000,
            "Recepción": 15000,
            "Laboratorio": 18000,
            "Farmacia": 17000,
            "Enfermería": 19000,
            "Mantenimiento": 14000
        };
        const selectedValue = selectArea.value;
        const sueldoBase = sueldosBase[selectedValue] || '';
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










