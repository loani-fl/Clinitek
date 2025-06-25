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

    <div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 56px);">
        <div class="card custom-card shadow-sm border rounded-4 w-100">
            <div class="card-header bg-primary text-white py-2">
                <h4 class="mb-0"><i class="bi bi-person-badge-fill me-2"></i> Editar Paciente</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        @php
                            $inputClass  = 'form-control form-control-sm input-corto';
                            $textareaClass = 'form-control form-control-sm';
                        @endphp

                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre"
                                   class="{{ $inputClass }} @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $paciente->nombre) }}">
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" name="apellidos" id="apellidos"
                                   class="{{ $inputClass }} @error('apellidos') is-invalid @enderror"
                                   value="{{ old('apellidos', $paciente->apellidos) }}">
                            @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="identidad" class="form-label">Identidad</label>
                            <input type="text" name="identidad" id="identidad"
                                   class="{{ $inputClass }} @error('identidad') is-invalid @enderror"
                                   value="{{ old('identidad', $paciente->identidad) }}">
                            @error('identidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                   class="{{ $inputClass }} @error('fecha_nacimiento') is-invalid @enderror"
                                   value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento ? Carbon::parse($paciente->fecha_nacimiento)->format('Y-m-d') : '') }}">
                            @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" name="telefono" id="telefono" maxlength="8"
                                   class="{{ $inputClass }} @error('telefono') is-invalid @enderror"
                                   value="{{ old('telefono', $paciente->telefono) }}">
                            @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>



                        <div class="col-md-4">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" name="correo" id="correo"
                                   class="{{ $inputClass }} @error('correo') is-invalid @enderror"
                                   value="{{ old('correo', $paciente->correo) }}"
                                   maxlength="50">
                            @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="direccion"
                                   class="{{ $inputClass }} @error('direccion') is-invalid @enderror"
                                   value="{{ old('direccion', $paciente->direccion) }}">
                            @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="genero" class="form-label">Género</label>
                            <select name="genero" id="genero"
                                    class="{{ $inputClass }} @error('genero') is-invalid @enderror">
                                <option value="">Seleccione</option>
                                @foreach(['Masculino', 'Femenino', 'Otro'] as $g)
                                    <option value="{{ $g }}" {{ old('genero', $paciente->genero) == $g ? 'selected' : '' }}>
                                        {{ $g }}
                                    </option>
                                @endforeach
                            </select>
                            @error('genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>


                        <div class="col-md-4">
                            <label for="tipo_sangre" class="form-label">Tipo de sangre</label>
                            <input type="text" name="tipo_sangre" id="tipo_sangre"
                                   class="{{ $inputClass }} @error('tipo_sangre') is-invalid @enderror"
                                   value="{{ old('tipo_sangre', $paciente->tipo_sangre) }}">
                            @error('tipo_sangre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>


                        <div class="col-md-4">
                            <label for="alergias" class="form-label">Alergias</label>
                            <input type="text" name="alergias" id="alergias"
                                   class="{{ $inputClass }} @error('alergias') is-invalid @enderror"
                                   value="{{ old('alergias', $paciente->alergias) }}">
                            @error('alergias') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="padecimientos" class="form-label">Padecimientos</label>
                            <textarea name="padecimientos" id="padecimientos" rows="2"
                                      class="{{ $textareaClass }} @error('padecimientos') is-invalid @enderror">{{ old('padecimientos', $paciente->padecimientos) }}</textarea>
                            @error('padecimientos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="medicamentos" class="form-label">Medicamentos</label>
                            <textarea name="medicamentos" id="medicamentos" rows="2"
                                      class="{{ $textareaClass }} @error('medicamentos') is-invalid @enderror">{{ old('medicamentos', $paciente->medicamentos) }}</textarea>
                            @error('medicamentos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="historial_clinico" class="form-label">Historial clínico</label>
                            <textarea name="historial_clinico" id="historial_clinico" rows="3"
                                      class="{{ $textareaClass }} @error('historial_clinico') is-invalid @enderror">{{ old('historial_clinico', $paciente->historial_clinico) }}</textarea>
                            @error('historial_clinico') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="historial_quirurgico" class="form-label">Historial quirúrgico</label>
                            <textarea name="historial_quirurgico" id="historial_quirurgico" rows="2"
                                      class="{{ $textareaClass }} @error('historial_quirurgico') is-invalid @enderror">{{ old('historial_quirurgico', $paciente->historial_quirurgico) }}</textarea>
                            @error('historial_quirurgico') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                            <i class="bi bi-save2-fill"></i> Actualizar
                        </button>

                        <button type="reset" class="btn btn-warning btn-sm px-4 shadow-sm text-white">
                            <i class="bi bi-arrow-counterclockwise"></i> Restablecer
                        </button>

                        <a href="{{ route('pacientes.index') }}" class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2"
                           style="font-size: 0.85rem;">
                            <i class="bi bi-arrow-left"></i> Regresar
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const telefonoInput = document.getElementById('telefono');

            telefonoInput.addEventListener('keydown', function(event) {
                const tecla = event.key;
                // Permitir teclas especiales
                if (
                    tecla === "Backspace" || tecla === "Delete" || tecla === "ArrowLeft" ||
                    tecla === "ArrowRight" || tecla === "Tab" || tecla === "Enter"
                ) return;

                // Solo números permitidos
                if (!/[0-9]/.test(tecla)) {
                    event.preventDefault();
                }
            });
        });
    </script>


@endsection
