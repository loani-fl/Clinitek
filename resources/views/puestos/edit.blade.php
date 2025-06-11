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
    .alert-danger, .alert-warning, .alert-success {
        font-size: 0.8rem;
    }
</style>

<div class="d-flex justify-content-center mt-4 mb-5">

    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2 rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-pencil-fill me-2"></i> Editar Puesto</h5>
        </div>

        <div class="card-body">
            {{-- Mensajes de sesión --}}
            @if (session('info'))
                <div class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>{{ session('info') }}</div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

        

            <form method="POST" action="{{ route('puestos.update', $puesto) }}" class="needs-validation" novalidate id="editPuestoForm">
                @csrf
                @method('PUT')

                {{-- Primera fila: Código - Nombre --}}
                <div class="row g-4">
                    <div class="col-md-3">
                        <label for="codigo" class="form-label">Código del Puesto <span class="text-danger">*</span></label>
                        <input type="text" name="codigo" id="codigo"
                            class="form-control form-control-sm @error('codigo') is-invalid @enderror"
                            value="{{ old('codigo', $puesto->codigo) }}"
                            required maxlength="10"
                            pattern="[A-Za-z0-9\-]{1,10}"
                            title="Máximo 10 caracteres. Solo letras, números y guiones.">
                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

          <div class="col-md-4">
    <label for="nombre" class="form-label">Nombre del Puesto <span class="text-danger">*</span></label>
    <input type="text" name="nombre" id="nombre" 
    class="form-control form-control-sm @error('nombre') is-invalid @enderror"
    required 
    maxlength="50"
    pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+"
    title="Solo letras (mayúsculas o minúsculas) y espacios. No se permiten números ni caracteres especiales."
    value="{{ old('nombre', $puesto->nombre) }}">

    @error('nombre') 
        <div class="invalid-feedback">{{ $message }}</div> 
    @enderror
</div>

                </div>

                {{-- Segunda fila: Área - Sueldo --}}
                <div class="row g-4 mt-1">
                    <div class="col-md-4">
                        <label for="area" class="form-label">Departamento o Área <span class="text-danger">*</span></label>
                        <select name="area" id="area"
                            class="form-select form-select-sm @error('area') is-invalid @enderror"
                            required onchange="autoFillSueldo()">
                            <option value="">-- Selecciona un área --</option>
                            @foreach(['Administración', 'Recepción', 'Laboratorio', 'Farmacia', 'Enfermería', 'Mantenimiento'] as $area)
                                <option value="{{ $area }}"
                                    {{ old('area', $puesto->area) == $area ? 'selected' : '' }}
                                    data-sueldo="{{ ['Administración'=>20000,'Recepción'=>15000,'Laboratorio'=>18000,'Farmacia'=>17000,'Enfermería'=>19000,'Mantenimiento'=>14000][$area] }}">
                                    {{ $area }}
                                </option>
                            @endforeach
                        </select>
                        @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="sueldo" class="form-label">Sueldo (Lps.) <span class="text-danger">*</span></label>
                        <input type="text" name="sueldo" id="sueldo"
                            class="form-control form-control-sm @error('sueldo') is-invalid @enderror"
                            required pattern="^\d{1,5}(\.\d{1,2})?$"
                            title="Solo números. Hasta 5 dígitos y 2 decimales."
                            inputmode="decimal"
                            value="{{ old('sueldo', $puesto->sueldo) }}">
                        @error('sueldo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Tercera fila: Funciones --}}
                <div class="row g-4 mt-1">
                    <div class="col-md-5">
                        <label for="funcion" class="form-label">Funciones <span class="text-danger">*</span></label>
                        <textarea name="funcion" id="funcion"
                            class="form-control form-control-sm @error('funcion') is-invalid @enderror"
                            rows="3" required maxlength="50"
                            title="Máximo 50 caracteres.">{{ old('funcion', $puesto->funcion ?? '') }}</textarea>
                        @error('funcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
{{-- Botones --}}
<div class="d-flex justify-content-center mt-4 gap-4">
    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
        <i class="bi bi-save"></i> Guardar Cambios
    </button>

    <button type="button" id="btnRestablecer" class="btn btn-primary btn-sm px-4 shadow-sm" title="Restablecer formulario a valores originales">
        <i class="bi bi-arrow-counterclockwise"></i> Restablecer
    </button>

   

    
</div>

            </form>
        </div>
    </div>
</div>

<script>
    function autoFillSueldo() {
        const selectArea = document.getElementById('area');
        const sueldoInput = document.getElementById('sueldo');
        const sueldosBase = {
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
        // Guardar valores iniciales
        const form = document.getElementById('editPuestoForm');
        form._initialValues = {
            codigo: form.codigo.value,
            nombre: form.nombre.value,
            area: form.area.value,
            sueldo: form.sueldo.value,
            funcion: form.funcion.value,
        };

        document.getElementById('btnRestablecer').addEventListener('click', () => {
            // Restaurar valores iniciales
            form.codigo.value = form._initialValues.codigo;
            form.nombre.value = form._initialValues.nombre;
            form.area.value = form._initialValues.area;
            form.sueldo.value = form._initialValues.sueldo;
            form.funcion.value = form._initialValues.funcion;

            // Actualizar sueldo base si corresponde
            autoFillSueldo();

            // Remover clases de error si hay
            ['codigo', 'nombre', 'area', 'sueldo', 'funcion'].forEach(id => {
                const el = document.getElementById(id);
                el.classList.remove('is-invalid');
            });
        });

        // Para la función de autoFillSueldo, guardar el valor previo
        const sueldoInput = document.getElementById('sueldo');
        sueldoInput.dataset.prevValue = sueldoInput.value || '';
    };
</script>
@endsection













