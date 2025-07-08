@php
    $inputClass  = 'form-control form-control-sm';
    $selectClass = 'form-select form-select-sm';
@endphp

<div class="row g-3">
    {{-- Nombres --}}
    <div class="col-md-4">
        <label for="nombres">Nombres <span class="text-danger">*</span></label>
        <input type="text" name="nombres" id="nombres"
            class="{{ $inputClass }} @error('nombres') is-invalid @enderror"
            value="{{ old('nombres', $empleado->nombres ?? '') }}"
            maxlength="50"
            pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
            title="Solo letras y espacios"
            required>
        @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Apellidos --}}
    <div class="col-md-4">
        <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
        <input type="text" name="apellidos" id="apellidos"
            class="{{ $inputClass }} @error('apellidos') is-invalid @enderror"
            value="{{ old('apellidos', $empleado->apellidos ?? '') }}"
            maxlength="50"
            pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
            title="Solo letras y espacios"
            required>
        @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Identidad --}}
    <div class="col-md-4">
        <label for="identidad">Identidad <span class="text-danger">*</span></label>
        <input type="text" name="identidad" id="identidad"
            class="{{ $inputClass }} @error('identidad') is-invalid @enderror"
            value="{{ old('identidad', $empleado->identidad ?? '') }}"
            maxlength="13"
            pattern="^\d{13}$"
            title="Debe contener exactamente 13 dígitos"
            required>
        @error('identidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Teléfono --}}
    <div class="col-md-4">
        <label for="telefono">Teléfono <span class="text-danger">*</span></label>
        <input type="text" name="telefono" id="telefono"
            class="{{ $inputClass }} @error('telefono') is-invalid @enderror"
            value="{{ old('telefono', $empleado->telefono ?? '') }}"
            maxlength="8"
            pattern="^\d{8}$"
            title="Debe tener 8 dígitos"
            required>
        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Correo --}}
    <div class="col-md-4">
        <label for="correo">Correo <span class="text-danger">*</span></label>
        <input type="email" name="correo" id="correo"
            class="{{ $inputClass }} @error('correo') is-invalid @enderror"
            value="{{ old('correo', $empleado->correo ?? '') }}"
            maxlength="30"
            required>
        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Fecha nacimiento --}}
    <div class="col-md-4">
        <label for="fecha_nacimiento">Fecha de nacimiento <span class="text-danger">*</span></label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
            class="{{ $inputClass }} @error('fecha_nacimiento') is-invalid @enderror"
            value="{{ old('fecha_nacimiento', isset($empleado->fecha_nacimiento) ? \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('Y-m-d') : '') }}"
            required>
        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Fecha ingreso --}}
    <div class="col-md-4">
        <label for="fecha_ingreso">Fecha de ingreso <span class="text-danger">*</span></label>
        <input type="date" name="fecha_ingreso" id="fecha_ingreso"
            class="{{ $inputClass }} @error('fecha_ingreso') is-invalid @enderror"
            value="{{ old('fecha_ingreso', isset($empleado->fecha_ingreso) ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('Y-m-d') : '') }}"
            required>
        @error('fecha_ingreso') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Estado civil --}}
    <div class="col-md-4">
        <label for="estado_civil">Estado civil</label>
        <select name="estado_civil" id="estado_civil" class="{{ $selectClass }} @error('estado_civil') is-invalid @enderror">
            <option value="">Seleccione</option>
            @foreach(['Soltero','Casado','Divorciado','Viudo'] as $ec)
                <option value="{{ $ec }}" {{ old('estado_civil', $empleado->estado_civil ?? '') == $ec ? 'selected' : '' }}>{{ $ec }}</option>
            @endforeach
        </select>
        @error('estado_civil') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Género --}}
    <div class="col-md-4">
        <label for="genero">Género <span class="text-danger">*</span></label>
        <select name="genero" id="genero" class="{{ $selectClass }} @error('genero') is-invalid @enderror" required>
            <option value="">Seleccione</option>
            @foreach(['Masculino','Femenino','Otro'] as $g)
                <option value="{{ $g }}" {{ old('genero', $empleado->genero ?? '') == $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
        </select>
        @error('genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Área --}}
    <div class="col-md-4">
        <label for="area">Área <span class="text-danger">*</span></label>
        <input type="text" name="area" id="area"
            class="{{ $inputClass }} @error('area') is-invalid @enderror"
            value="{{ old('area', $empleado->area ?? '') }}"
            maxlength="80"
            required>
        @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Puesto --}}
    <div class="col-md-4">
        <label for="puesto_id">Puesto <span class="text-danger">*</span></label>
        <select name="puesto_id" id="puesto_id"
            class="{{ $selectClass }} @error('puesto_id') is-invalid @enderror"
            required>
            <option value="">Seleccione un puesto</option>
            @foreach($puestos as $p)
                <option value="{{ $p->id }}" {{ old('puesto_id', $empleado->puesto_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
            @endforeach
        </select>
        @error('puesto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Salario --}}
    <div class="col-md-4">
        <label for="salario">Salario (Lps.) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="salario" id="salario"
            class="{{ $inputClass }} @error('salario') is-invalid @enderror"
            value="{{ old('salario', $empleado->salario ?? '') }}"
            min="0"
            required readonly>
        @error('salario') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Turno asignado --}}
    <div class="col-md-4">
        <label for="turno_asignado">Turno asignado <span class="text-danger">*</span></label>
        <select name="turno_asignado" id="turno_asignado" class="{{ $selectClass }} @error('turno_asignado') is-invalid @enderror" required>
            <option value="">Seleccione</option>
            @foreach(['Mañana','Tarde','Noche'] as $t)
                <option value="{{ $t }}" {{ old('turno_asignado', $empleado->turno_asignado ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        @error('turno_asignado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Estado --}}
    <div class="col-md-4">
        <label for="estado">Estado <span class="text-danger">*</span></label>
        <select name="estado" id="estado" class="{{ $selectClass }} @error('estado') is-invalid @enderror" required>
            <option value="">Seleccione</option>
            @foreach(['Activo','Inactivo'] as $est)
                <option value="{{ $est }}" {{ old('estado', $empleado->estado ?? '') == $est ? 'selected' : '' }}>{{ $est }}</option>
            @endforeach
        </select>
        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Observaciones --}}
    <div class="col-12">
        <label for="observaciones">Observaciones</label>
        <textarea name="observaciones" id="observaciones" rows="3"
            class="{{ $inputClass }} @error('observaciones') is-invalid @enderror"
            maxlength="350">{{ old('observaciones', $empleado->observaciones ?? '') }}</textarea>
        @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>