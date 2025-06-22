@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<style>
    body { background-color: #e8f4fc; }
    .custom-card { max-width: 97%; background-color: #f0faff; border-color: #91cfff; }
    label { font-size: 0.85rem; }
    input, select, textarea { font-size: 0.85rem !important; }
    .input-corto { width: 100% !important; }
</style>

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 140px);">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <h4 class="mb-0">Editar empleado</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    @php
                        $inputClass  = 'form-control form-control-sm input-corto';
                        $selectClass = 'form-select form-select-sm input-corto';
                    @endphp
<div class="row g-4 mt-3 px-4">
    {{-- Fila 1 --}}
    <div class="col-md-2">
        {{-- Nombres --}}
        <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
        <input type="text" name="nombres" id="nombres"
               class="{{ $inputClass }} @error('nombres') is-invalid @enderror"
               value="{{ old('nombres', $empleado->nombres) }}">
        @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Apellidos --}}
        <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
        <input type="text" name="apellidos" id="apellidos"
               class="{{ $inputClass }} @error('apellidos') is-invalid @enderror"
               value="{{ old('apellidos', $empleado->apellidos) }}">
        @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Identidad --}}
        <label for="identidad" class="form-label">Identidad <span class="text-danger">*</span></label>
        <input type="text" name="identidad" id="identidad"
               class="{{ $inputClass }} @error('identidad') is-invalid @enderror"
               value="{{ old('identidad', $empleado->identidad) }}">
        @error('identidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Teléfono --}}
        <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
        <input type="text" name="telefono" id="telefono"
               class="{{ $inputClass }} @error('telefono') is-invalid @enderror"
               value="{{ old('telefono', $empleado->telefono) }}">
        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Correo --}}
        <label for="correo" class="form-label">Correo <span class="text-danger">*</span></label>
        <input type="email" name="correo" id="correo"
               class="{{ $inputClass }} @error('correo') is-invalid @enderror"
               value="{{ old('correo', $empleado->correo) }}">
        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Fecha Ingreso --}}
        <label for="fecha_ingreso" class="form-label">Fecha de ingreso <span class="text-danger">*</span></label>
        <input type="date" name="fecha_ingreso" id="fecha_ingreso"
               class="{{ $inputClass }} @error('fecha_ingreso') is-invalid @enderror"
               value="{{ old('fecha_ingreso', $empleado->fecha_ingreso ? Carbon::parse($empleado->fecha_ingreso)->format('Y-m-d') : '') }}">
        @error('fecha_ingreso') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row g-4 mt-3 px-4">
    {{-- Fila 2 --}}
    <div class="col-md-2">
        {{-- Fecha Nacimiento --}}
        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento <span class="text-danger">*</span></label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
               class="{{ $inputClass }} @error('fecha_nacimiento') is-invalid @enderror"
               value="{{ old('fecha_nacimiento', $empleado->fecha_nacimiento ? Carbon::parse($empleado->fecha_nacimiento)->format('Y-m-d') : '') }}">
        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Género --}}
        <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
        <select name="genero" id="genero"
                class="{{ $selectClass }} @error('genero') is-invalid @enderror">
            <option value="">Seleccione</option>
            @foreach(['Masculino','Femenino','Otro'] as $g)
                <option value="{{ $g }}" {{ old('genero', $empleado->genero)==$g ? 'selected':'' }}>{{ $g }}</option>
            @endforeach
        </select>
        @error('genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Estado Civil --}}
        <label for="estado_civil" class="form-label">Estado civil</label>
        <select name="estado_civil" id="estado_civil"
                class="{{ $selectClass }} @error('estado_civil') is-invalid @enderror">
            <option value="">Seleccione</option>
            @foreach(['Soltero','Casado','Divorciado','Viudo'] as $ec)
                <option value="{{ $ec }}" {{ old('estado_civil', $empleado->estado_civil)==$ec ? 'selected':'' }}>{{ $ec }}</option>
            @endforeach
        </select>
        @error('estado_civil') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Área --}}
        <label for="area" class="form-label">Área <span class="text-danger">*</span></label>
        <select name="area" id="area"
                class="{{ $selectClass }} @error('area') is-invalid @enderror">
            <option value="">Seleccione un área</option>
            @php
                $areas = [
                    'Administración' => 15000,
                    'Recepción' => 12000,
                    'Laboratorio' => 18000,
                    'Farmacia' => 16000,
                    'Enfermería' => 17000,
                    'Mantenimiento' => 11000
                ];
            @endphp
            @foreach($areas as $nombre => $sueldo)
                <option value="{{ $nombre }}"
                        data-sueldo="{{ $sueldo }}"
                        {{ old('area', $empleado->area) == $nombre ? 'selected' : '' }}>
                    {{ $nombre }}
                </option>
            @endforeach
        </select>
        @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Puesto --}}
        <label for="puesto_id" class="form-label">Puesto <span class="text-danger">*</span></label>
        <select name="puesto_id" id="puesto_id"
                class="{{ $selectClass }} @error('puesto_id') is-invalid @enderror">
            <option value="">Seleccione un puesto</option>
            @foreach($puestos as $p)
                <option value="{{ $p->id }}" {{ old('puesto_id',$empleado->puesto_id)==$p->id?'selected':'' }}>{{ $p->nombre }}</option>
            @endforeach
        </select>
        @error('puesto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-2">
        {{-- Salario --}}
        <label for="salario" class="form-label">Salario <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="salario" id="salario"
               class="{{ $inputClass }} @error('salario') is-invalid @enderror"
               value="{{ old('salario', $empleado->salario) }}">
        @error('salario') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<<div class="row g-4 mt-3 px-4">
    {{-- Turno --}}
    <div class="col-md-2">
        <label for="turno_asignado" class="form-label">Turno asignado <span class="text-danger">*</span></label>
        <select name="turno_asignado" id="turno_asignado"
                class="{{ $selectClass }} @error('turno_asignado') is-invalid @enderror">
            <option value="">Seleccione</option>
            @foreach(['Mañana','Tarde','Noche'] as $t)
                <option value="{{ $t }}" {{ old('turno_asignado', $empleado->turno_asignado)==$t ? 'selected':'' }}>{{ $t }}</option>
            @endforeach
        </select>
        @error('turno_asignado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Estado --}}
    <div class="col-md-2">
        <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
        <select name="estado" id="estado"
                class="{{ $selectClass }} @error('estado') is-invalid @enderror">
            <option value="">Seleccione</option>
            @foreach(['Activo','Inactivo'] as $est)
                <option value="{{ $est }}" {{ old('estado',$empleado->estado)==$est ? 'selected':'' }}>{{ $est }}</option>
            @endforeach
        </select>
        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Dirección --}}
    <div class="col-md-4">
        <label for="direccion" class="form-label">Dirección <span class="text-danger">*</span></label>
        <textarea name="direccion" id="direccion" rows="3"
                  class="form-control form-control-sm @error('direccion') is-invalid @enderror">{{ old('direccion', $empleado->direccion) }}</textarea>
        @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Observaciones --}}
    <div class="col-md-4">
        <label for="observaciones" class="form-label">Observaciones</label>
        <textarea name="observaciones" id="observaciones" rows="3"
                  class="form-control form-control-sm @error('observaciones') is-invalid @enderror">{{ old('observaciones',$empleado->observaciones) }}</textarea>
        @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

   <div class="mt-4 d-flex justify-content-center gap-5">
    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-pencil-fill" style="font-size: 1.2rem;"></i> Actualizar
    </button>

    <button type="reset" id="btnRestablecer" class="btn btn-warning btn-sm px-4 shadow-sm d-flex align-items-center gap-2" title="Restablecer formulario a valores originales">
        <i class="bi bi-arrow-clockwise" style="font-size: 1.1rem;"></i> Restablecer
    </button>

    <a href="{{ route('empleados.index') }}" class="btn btn-success d-flex align-items-center gap-2">
        <i class="bi bi-x-circle"></i> Cancelar
    </a>
</div>



    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const areaSelect = document.getElementById('area');
        const salarioInput = document.getElementById('salario');

        function autoFillSalario() {
            const selectedOption = areaSelect.options[areaSelect.selectedIndex];
            const sueldo = selectedOption.getAttribute('data-sueldo');
            if (sueldo) {
                salarioInput.value = sueldo;
                salarioInput.readOnly = true;
            } else {
                salarioInput.value = '';
                salarioInput.readOnly = false;
            }
        }

        areaSelect.addEventListener('change', autoFillSalario);
        autoFillSalario(); // al cargar
    });
</script>
@endsection