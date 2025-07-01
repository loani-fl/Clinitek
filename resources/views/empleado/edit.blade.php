@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
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
        max-width: 900px;
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
            <form action="{{ route('empleado.update', $empleado->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-3">
                    @php
                        $inputClass  = 'form-control form-control-sm';
                        $selectClass = 'form-select form-select-sm';
                    @endphp

                    {{-- Nombres --}}
                    <div class="col-md-4">
                        <label for="nombres">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres" id="nombres"
                               class="{{ $inputClass }} @error('nombres') is-invalid @enderror"
                               value="{{ old('nombres', $empleado->nombres) }}"
                               maxlength="33"
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
                               value="{{ old('apellidos', $empleado->apellidos) }}"
                               maxlength="33"
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
                               value="{{ old('identidad', $empleado->identidad) }}"
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
                               value="{{ old('telefono', $empleado->telefono) }}"
                               maxlength="8"
                               pattern="^[23789]\d{7}$"
                               title="Debe tener 8 dígitos y comenzar con 2,3,7,8 o 9"
                               required>
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Correo --}}
                    <div class="col-md-4">
                        <label for="correo">Correo <span class="text-danger">*</span></label>
                        <input type="email" name="correo" id="correo"
                               class="{{ $inputClass }} @error('correo') is-invalid @enderror"
                               value="{{ old('correo', $empleado->correo) }}"
                               maxlength="30"
                               required>
                        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Fecha nacimiento --}}
                    <div class="col-md-4">
                        <label for="fecha_nacimiento">Fecha de nacimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                               class="{{ $inputClass }} @error('fecha_nacimiento') is-invalid @enderror"
                               value="{{ old('fecha_nacimiento', $empleado->fecha_nacimiento ? Carbon::parse($empleado->fecha_nacimiento)->format('Y-m-d') : '') }}"
                               required>
                        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Fecha ingreso --}}
                    <div class="col-md-4">
                        <label for="fecha_ingreso">Fecha de ingreso <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_ingreso" id="fecha_ingreso"
                               class="{{ $inputClass }} @error('fecha_ingreso') is-invalid @enderror"
                               value="{{ old('fecha_ingreso', $empleado->fecha_ingreso ? Carbon::parse($empleado->fecha_ingreso)->format('Y-m-d') : '') }}"
                               required>
                        @error('fecha_ingreso') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Dirección --}}
                    <div class="col-md-4">
                       <label for="direccion">Dirección <span class="text-danger">*</span></label>
                       <textarea name="direccion" id="direccion" rows="3"
                                 class="{{ $inputClass }} @error('direccion') is-invalid @enderror"
                                 style="resize: vertical;">{{ old('direccion', $empleado->direccion) }}</textarea>
                        @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Género --}}
                    <div class="col-md-4">
                        <label for="genero">Género <span class="text-danger">*</span></label>
                        <select name="genero" id="genero"
                                class="{{ $selectClass }} @error('genero') is-invalid @enderror"
                                required>
                            <option value="">Seleccione</option>
                            @foreach(['Masculino','Femenino','Otro'] as $g)
                                <option value="{{ $g }}" {{ old('genero', $empleado->genero)==$g ? 'selected':'' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Estado civil --}}
                    <div class="col-md-4">
                        <label for="estado_civil">Estado civil</label>
                        <select name="estado_civil" id="estado_civil"
                                class="{{ $selectClass }} @error('estado_civil') is-invalid @enderror">
                            <option value="">Seleccione</option>
                            @foreach(['Soltero','Casado','Divorciado','Viudo'] as $ec)
                                <option value="{{ $ec }}" {{ old('estado_civil', $empleado->estado_civil)==$ec ? 'selected':'' }}>{{ $ec }}</option>
                            @endforeach
                        </select>
                        @error('estado_civil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Puesto --}}
                    <div class="col-md-4">
                        <label for="puesto_id">Puesto <span class="text-danger">*</span></label>
                        <select name="puesto_id" id="puesto_id"
                                class="{{ $selectClass }} @error('puesto_id') is-invalid @enderror"
                                required>
                            <option value="">Seleccione un puesto</option>
                            @foreach($puestos as $p)
                                <option value="{{ $p->id }}" {{ old('puesto_id',$empleado->puesto_id)==$p->id?'selected':'' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                        @error('puesto_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Área --}}
                    <div class="col-md-4">
                        <label for="area">Área <span class="text-danger">*</span></label>
                        <input type="text" name="area" id="area"
                               class="{{ $inputClass }} @error('area') is-invalid @enderror"
                               value="{{ old('area', $empleado->area) }}"
                               maxlength="80"
                               required>
                        @error('area') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Salario --}}
                    <div class="col-md-4">
                        <label for="salario">Salario <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="salario" id="salario"
                               class="{{ $inputClass }} @error('salario') is-invalid @enderror"
                               value="{{ old('salario', $empleado->salario) }}"
                               min="0"
                               required>
                        @error('salario') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Turno --}}
                    <div class="col-md-4">
                        <label for="turno_asignado">Turno asignado <span class="text-danger">*</span></label>
                        <select name="turno_asignado" id="turno_asignado"
                                class="{{ $selectClass }} @error('turno_asignado') is-invalid @enderror"
                                required>
                            <option value="">Seleccione</option>
                            @foreach(['Mañana','Tarde','Noche'] as $t)
                                <option value="{{ $t }}" {{ old('turno_asignado', $empleado->turno_asignado)==$t ? 'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('turno_asignado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-4">
                        <label for="estado">Estado <span class="text-danger">*</span></label>
                        <select name="estado" id="estado"
                                class="{{ $selectClass }} @error('estado') is-invalid @enderror"
                                required>
                            <option value="">Seleccione</option>
                            @foreach(['Activo','Inactivo'] as $est)
                                <option value="{{ $est }}" {{ old('estado',$empleado->estado)==$est ? 'selected':'' }}>{{ $est }}</option>
                            @endforeach
                        </select>
                        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Observaciones --}}
                    <div class="col-12">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3"
                                  class="{{ $inputClass }} @error('observaciones') is-invalid @enderror"
                                  maxlength="350"
                        >{{ old('observaciones',$empleado->observaciones) }}</textarea>
                        @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('empleado.index') }}" class="btn btn-success btn-sm px-4 shadow-sm">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
