@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-primary rounded-3">
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

                <div class="d-flex justify-content-center">
                    <div class="form-mini" style="max-width: 700px; width: 100%;">

                        {{-- Nombres y Apellidos --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label fw-semibold small">
                                    Nombres <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombres" id="nombres" class="form-control form-control-sm" value="">
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label fw-semibold small">
                                    Apellidos <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="apellidos" id="apellidos" class="form-control form-control-sm" required value="">
                            </div>
                        </div>

                        {{-- Identidad y Teléfono --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="identidad" class="form-label fw-semibold small">
                                    Número de Identidad <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="identidad" id="identidad" class="form-control form-control-sm" required value="">
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label fw-semibold small">
                                    Teléfono <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="telefono" id="telefono" class="form-control form-control-sm" required value="">
                            </div>
                        </div>

                        {{-- Dirección y Correo --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-7">
                                <label for="direccion" class="form-label fw-semibold small">
                                    Dirección <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="direccion" id="direccion" class="form-control form-control-sm" required value="">
                            </div>
                            <div class="col-md-5">
                                <label for="correo" class="form-label fw-semibold small">
                                    Correo electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="correo" id="correo" class="form-control form-control-sm" required value="">
                            </div>
                        </div>

                        {{-- Fecha Ingreso y Fecha Nacimiento --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-6 col-lg-4">
                                <label for="fecha_ingreso" class="form-label fw-semibold small">
                                    Fecha de Ingreso <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control form-control-sm" required value="">
                            </div>
                            <div class="col-md-6 col-lg-4 mt-lg-2 pt-1">
                                <label for="fecha_nacimiento" class="form-label fw-semibold small">
                                    Fecha de Nacimiento <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control form-control-sm" required value="">
                            </div>
                        </div>

                        {{-- Género y Estado Civil --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-6 col-lg-4">
                                <label for="genero" class="form-label fw-semibold small">
                                    Género <span class="text-danger">*</span>
                                </label>
                                <select name="genero" id="genero" class="form-select form-select-sm" required>
                                    <option value="" selected>-- Selecciona --</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-4 mt-lg-2 pt-1">
                                <label for="estado_civil" class="form-label fw-semibold small">
                                    Estado Civil
                                </label>
                                <select name="estado_civil" id="estado_civil" class="form-select form-select-sm">
                                    <option value="" selected>-- Selecciona --</option>
                                    <option value="Soltero">Soltero</option>
                                    <option value="Casado">Casado</option>
                                    <option value="Divorciado">Divorciado</option>
                                    <option value="Viudo">Viudo</option>
                                </select>
                            </div>
                        </div>

                        {{-- Puesto y Sueldo --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-6 col-lg-5">
                                <label for="puesto_id" class="form-label fw-semibold small">
                                    Puesto <span class="text-danger">*</span>
                                </label>
                                <select name="puesto_id" id="puesto_id" class="form-select form-select-sm" required>
                                    <option value="" selected>-- Selecciona un puesto --</option>
                                    @foreach($puestos as $puesto)
                                        <option value="{{ $puesto->id }}" data-sueldo="{{ $puesto->sueldo }}">
                                            {{ $puesto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3 mt-lg-2 pt-1">
                                <label for="sueldo_label" class="form-label fw-semibold small">
                                    Sueldo del Puesto
                                </label>
                                <input type="hidden" name="salario" id="salario" value="">
                                <input type="text" id="sueldo_label" class="form-control form-control-sm" readonly>
                            </div>
                        </div>

                        {{-- Observaciones --}}
                        <div class="row g-3 mt-3">
                            <div class="col-7">
                                <label for="observaciones" class="form-label fw-semibold small">
                                    Observaciones
                                </label>
                                <textarea name="observaciones" id="observaciones" class="form-control form-control-sm" rows="3"></textarea>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="d-flex justify-content-start mt-5 gap-5">
                            <button type="submit" class="btn btn-primary btn-sm shadow-sm px-4">
                                <i class="bi bi-plus-circle"></i> Registrar
                            </button>
                            <a href="{{ route('puestos.index') }}" class="btn btn-primary btn-sm shadow-sm px-4">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>

                    </div> <!-- fin form-mini -->
                </div> <!-- fin d-flex justify-content-center -->
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
                sueldoLabel.value = `$${parseFloat(sueldo).toFixed(2)}`;
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


