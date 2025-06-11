@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        max-width: 97%;
        background-color: #f0faff;
        border-color: #91cfff;
    }
    label {
        font-size: 0.85rem;
    }
    input, select, textarea {
        font-size: 0.85rem !important;
    }
</style>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">Registro de un nuevo empleado</h5>
        </div>

        <div class="card-body">
            {{-- Errores --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <h6 class="small"><i class="bi bi-exclamation-triangle-fill"></i> Corrige los siguientes errores:</h6>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="alert alert-success py-2 small">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('empleados.store') }}" method="POST" novalidate>
                @csrf

                <div class="row g-3">
                    {{-- Nombres, Apellidos, Identidad --}}
                    <div class="col-md-4">
                        <label for="nombres" class="form-label fw-semibold text-muted">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres" id="nombres" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label for="apellidos" class="form-label fw-semibold text-muted">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" name="apellidos" id="apellidos" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label for="identidad" class="form-label fw-semibold text-muted">Identidad <span class="text-danger">*</span></label>
                        <input type="text" name="identidad" id="identidad" class="form-control form-control-sm" required>
                    </div>

                    {{-- Teléfono, Dirección, Correo --}}
                    <div class="col-md-4">
                        <label for="telefono" class="form-label fw-semibold text-muted">Teléfono <span class="text-danger">*</span></label>
                        <input type="text" name="telefono" id="telefono" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label for="direccion" class="form-label fw-semibold text-muted">Dirección <span class="text-danger">*</span></label>
                        <input type="text" name="direccion" id="direccion" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label for="correo" class="form-label fw-semibold text-muted">Correo <span class="text-danger">*</span></label>
                        <input type="email" name="correo" id="correo" class="form-control form-control-sm" required>
                    </div>

                    {{-- Fecha Ingreso, Fecha Nacimiento, Género --}}
                    <div class="col-md-4">
                        <label for="fecha_ingreso" class="form-label fw-semibold text-muted">Fecha de Ingreso <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha_nacimiento" class="form-label fw-semibold text-muted">Fecha de Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label for="genero" class="form-label fw-semibold text-muted">Género <span class="text-danger">*</span></label>
                        <select name="genero" id="genero" class="form-select form-select-sm" required>
                            <option value="">-- Selecciona --</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    {{-- Estado Civil, Puesto, Área --}}
                    <div class="col-md-4">
                        <label for="estado_civil" class="form-label fw-semibold text-muted">Estado Civil <span class="text-danger">*</span></label>
                        <select name="estado_civil" id="estado_civil" class="form-select form-select-sm" required>
                            <option value="">-- Selecciona --</option>
                            <option value="Soltero">Soltero</option>
                            <option value="Casado">Casado</option>
                            <option value="Divorciado">Divorciado</option>
                            <option value="Viudo">Viudo</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="puesto_id" class="form-label fw-semibold text-muted">Puesto <span class="text-danger">*</span></label>
                        <select name="puesto_id" id="puesto_id" class="form-select form-select-sm" required>
                            <option value="">-- Selecciona un puesto --</option>
                            @foreach($puestos as $puesto)
                                <option value="{{ $puesto->id }}" data-sueldo="{{ $puesto->sueldo }}">{{ $puesto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sueldo --}}
                    <div class="col-md-4">
                        <label for="sueldo_label" class="form-label fw-semibold text-muted">Sueldo (Lps.)</label>
                        <input type="hidden" name="salario" id="salario">
                        <input type="text" id="sueldo_label" class="form-control form-control-sm" readonly>
                    </div>
                   <div class="col-md-4">
                    <label for="turno_asignado" class="form-label fw-semibold text-muted">Turno Asignado <span class="text-danger">*</span></label>
                    <select name="turno_asignado" id="turno_asignado" class="form-select form-select-sm" required>
                        <option value="">-- Selecciona un turno --</option>
                        <option value="Mañana">Mañana</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noche">Noche</option>
                    </select>
                </div>

                </div>

                {{-- Observaciones centrado --}}
                <div class="row mt-4">
                    <div class="col-md-8 offset-md-2">
                        <label for="observaciones" class="form-label fw-semibold text-muted">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control form-control-sm" rows="3"></textarea>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-center mt-5 gap-4">
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                        <i class="bi bi-plus-circle"></i> Registrar
                    </button>
                    <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary btn-sm px-4 shadow-sm">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const puestoSelect = document.getElementById('puesto_id');
        const sueldoLabel = document.getElementById('sueldo_label');
        const salarioHidden = document.getElementById('salario');

        function actualizarSueldo() {
            const selectedOption = puestoSelect.options[puestoSelect.selectedIndex];
            const sueldo = selectedOption.getAttribute('data-sueldo');
            if (sueldo) {
                sueldoLabel.value = `Lps. ${parseFloat(sueldo).toFixed(2)}`;
                salarioHidden.value = sueldo;
            } else {
                sueldoLabel.value = '';
                salarioHidden.value = '';
            }
        }

        actualizarSueldo();
        puestoSelect.addEventListener('change', actualizarSueldo);
    });
</script>
@endpush
@endsection
