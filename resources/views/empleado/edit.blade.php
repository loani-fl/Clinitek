@extends('layouts.app')

@section('content')
@php
use Carbon\Carbon;

$fechaMax = Carbon::now()->subYears(18)->format('Y-m-d');
$hoy = Carbon::now();
$minIngreso = $hoy->copy()->subMonth()->format('Y-m-d');
$maxIngreso = $hoy->copy()->addMonth()->format('Y-m-d');

$fechaNacimientoValue = old('fecha_nacimiento', isset($empleado->fecha_nacimiento) ? Carbon::parse($empleado->fecha_nacimiento)->format('Y-m-d') : '');
$fechaIngresoValue = old('fecha_ingreso', isset($empleado->fecha_ingreso) ? Carbon::parse($empleado->fecha_ingreso)->format('Y-m-d') : '');
@endphp

<style>
    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 800px;
        height: 800px;
        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.1;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }
    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        border-color: #91cfff;
        position: relative;
        overflow: hidden;
        margin: 2rem auto;
        padding: 1rem;
        border: 1px solid #91cfff;
        border-radius: 12px;
    }
    label { font-size: 0.9rem; }
    input, select, textarea { font-size: 0.9rem !important; }
</style>

<div class="container" style="max-width: 1200px; padding-bottom: 120px; padding-top: 70px;">

    <!-- Barra fija arriba -->
    <div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050; height: 56px;">
        <div class="d-flex justify-content-between align-items-center px-3" style="height: 56px;">
            <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
        </div>
    </div>

    <!-- Card principal -->
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px;">
        <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 2rem;">Editar empleado</h5>
        </div>

        <div class="card-body px-4 py-3">
        <form action="{{ route('empleados.update', $empleado->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')

                @php
                    $inputClass  = 'form-control form-control-sm';
                    $selectClass = 'form-select form-select-sm';
                @endphp

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres" id="nombres" class="{{ $inputClass }} @error('nombres') is-invalid @enderror"
                               value="{{ old('nombres', $empleado->nombres) }}" required maxlength="50"
                               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
                        @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" name="apellidos" id="apellidos" class="{{ $inputClass }} @error('apellidos') is-invalid @enderror"
                               value="{{ old('apellidos', $empleado->apellidos) }}" required maxlength="50"
                               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
                        @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
    <label for="identidad" class="form-label">Identidad <span class="text-danger">*</span></label>
    <input type="text" name="identidad" id="identidad"
           class="{{ $inputClass }} @error('identidad') is-invalid @enderror"
           value="{{ old('identidad', $empleado->identidad) }}"
           maxlength="13" required pattern="\d{13}"
           title="Debe tener exactamente 13 números, sin letras ni símbolos">
    @error('identidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>



                    <div class="col-md-3">
                        <label for="correo" class="form-label">Correo <span class="text-danger">*</span></label>
                        <input type="email" name="correo" id="correo" class="{{ $inputClass }} @error('correo') is-invalid @enderror"
                               value="{{ old('correo', $empleado->correo) }}" maxlength="30" required>
                        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                        <input type="text" name="telefono" id="telefono" class="{{ $inputClass }} @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono', $empleado->telefono) }}" maxlength="8" required pattern="\d{8}"
                               title="Debe tener exactamente 8 números, sin letras ni símbolos">
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="{{ $inputClass }} @error('fecha_nacimiento') is-invalid @enderror"
                               value="{{ $fechaNacimientoValue }}" max="{{ $fechaMax }}" required>
                        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="fecha_ingreso" class="form-label">Fecha Ingreso <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="{{ $inputClass }} @error('fecha_ingreso') is-invalid @enderror"
                               value="{{ $fechaIngresoValue }}" min="{{ $minIngreso }}" max="{{ $maxIngreso }}" required>
                        @error('fecha_ingreso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="estado" id="estado" class="form-select form-select-sm @error('estado') is-invalid @enderror" required>
                            <option value="">-- Selecciona --</option>
                            @foreach(['Activo', 'Inactivo'] as $estado)
                                <option value="{{ $estado }}" {{ old('estado', $empleado->estado) == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                            @endforeach
                        </select>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                </div>

                <div class="row mb-3">
    <div class="col-md-3">
        <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
        <select name="genero" id="genero" class="{{ $selectClass }} @error('genero') is-invalid @enderror" required>
            <option value="">-- Selecciona --</option>
            @foreach(['Masculino', 'Femenino', 'Otro'] as $g)
                <option value="{{ $g }}" {{ old('genero', $empleado->genero) == $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
        </select>
        @error('genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="area" class="form-label">Área <span class="text-danger">*</span></label>
        <input type="text" name="area" id="area" class="{{ $inputClass }} @error('area') is-invalid @enderror"
               value="{{ old('area', $empleado->area) }}" maxlength="80" required>
        @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="puesto_id" class="form-label">Puesto <span class="text-danger">*</span></label>
        <select name="puesto_id" id="puesto_id" class="{{ $selectClass }} @error('puesto_id') is-invalid @enderror" required>
            <option value="">-- Selecciona --</option>
            @foreach($puestos as $puesto)
                <option value="{{ $puesto->id }}" {{ old('puesto_id', $empleado->puesto_id) == $puesto->id ? 'selected' : '' }}>
                    {{ $puesto->nombre }}
                </option>
            @endforeach
        </select>
        @error('puesto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="salario" class="form-label">Salario (Lps.)</label>
        <input type="text" name="salario" id="salario" class="{{ $inputClass }}" readonly
               value="{{ old('salario', $empleado->salario) ?? '' }}">
        @error('salario') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>


             <div class="row mb-3">
            <div class="col-md-6">
                <label for="foto" class="form-label fw-semibold">Foto:</label>
                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror"
                accept=".jpg,.jpeg,.png,.gif">
                <small class="form-text text-muted">
                Opcional. Formato: JPG, JPEG, PNG o GIF. Máximo 2MB.
                </small>
                @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3 d-flex flex-column justify-content-start">
                <label for="turno_asignado" class="form-label fw-semibold">Turno asignado <span class="text-danger">*</span></label>
                <select name="turno_asignado" id="turno_asignado" class="form-select @error('turno_asignado') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                @foreach(['Mañana','Tarde','Noche'] as $t)
                    <option value="{{ $t }}" {{ old('turno_asignado', $empleado->turno_asignado ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
                </select>
                @error('turno_asignado')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            </div>


                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="direccion" class="form-label">Dirección <span class="text-danger">*</span></label>
                        <textarea name="direccion" id="direccion" rows="3"
                                  class="{{ $inputClass }} @error('direccion') is-invalid @enderror"
                                  maxlength="300" style="resize: vertical;" required>{{ old('direccion', $empleado->direccion) }}</textarea>
                        @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3"
                                  class="{{ $inputClass }} @error('observaciones') is-invalid @enderror"
                                  maxlength="300">{{ old('observaciones', $empleado->observaciones) }}</textarea>
                        @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                    <i class="bi bi-save"></i> Actualizar
                </button>

                <button type="button" id="btnRestablecer" class="btn btn-warning btn-sm px-4 shadow-sm ms-2">
                    <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                </button>


                <a href="{{ route('empleados.index') }}" class="btn btn-success btn-sm px-4 shadow-sm ms-2">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>

            </form>
        </div>
        
    </div>
</div>

<script>
    // Objeto que relaciona id de puesto con salario
    const sueldoPorPuesto = {
        @foreach ($puestos as $puesto)
            "{{ $puesto->id }}": "{{ $puesto->sueldo }}",
        @endforeach
    };

    // Referencias a elementos
    const puestoSelect = document.getElementById('puesto_id');
    const sueldoInput = document.getElementById('salario');

    // Función para actualizar salario según puesto seleccionado
    function actualizarSueldo() {
        const puestoId = puestoSelect.value;
        if (puestoId && sueldoPorPuesto[puestoId]) {
            sueldoInput.value = sueldoPorPuesto[puestoId];
        } else {
            sueldoInput.value = '';
        }
    }

    // Forzar actualización del salario también cuando se presiona "reset"
document.querySelector('form').addEventListener('reset', function () {
    setTimeout(actualizarSueldo, 10); // esperar un momento para que el reset afecte el DOM
});


    // Actualizar al cargar la página (por si ya hay un puesto seleccionado)
    window.addEventListener('DOMContentLoaded', (event) => {
        actualizarSueldo();
    });

    // Actualizar al cambiar el select
    puestoSelect.addEventListener('change', actualizarSueldo);

    document.getElementById('btnRestablecer').addEventListener('click', function() {
    // Reestablecer valores de cada campo con los valores iniciales que quieres
    document.getElementById('nombres').value = @json($empleado->nombres);
    document.getElementById('apellidos').value = @json($empleado->apellidos);
    document.getElementById('identidad').value = @json($empleado->identidad);
    document.getElementById('correo').value = @json($empleado->correo);
    document.getElementById('telefono').value = @json($empleado->telefono);
    document.getElementById('fecha_nacimiento').value = @json($fechaNacimientoValue);
    document.getElementById('fecha_ingreso').value = @json($fechaIngresoValue);
    document.getElementById('estado').value = @json($empleado->estado);
    document.getElementById('genero').value = @json($empleado->genero);
    document.getElementById('area').value = @json($empleado->area);
    document.getElementById('puesto_id').value = @json($empleado->puesto_id);
    document.getElementById('salario').value = @json($empleado->salario);
    document.getElementById('turno_asignado').value = @json($empleado->turno_asignado);
    document.getElementById('direccion').value = @json($empleado->direccion);
    document.getElementById('observaciones').value = @json($empleado->observaciones);

    // Actualiza el salario según el puesto (por si acaso)
    actualizarSueldo();
});

</script>
@endsection