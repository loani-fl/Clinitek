@extends('layouts.app')

@section('content')
    <style>
        html, body {
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            max-width: 100%;
        }

        .custom-card {
            max-width: 1000px;
            width: 100%;
            padding-left: 20px;
            padding-right: 20px;
            margin-left: auto;
            margin-right: auto;
            box-sizing: border-box;
            position: relative;
        }

        input, select, textarea {
            max-width: 100%;
            box-sizing: border-box;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.75rem;
            color: #dc3545;
        }

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
            opacity: 0.15;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }
    </style>

    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px;">
        <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Registro de Sesión Psicológica</h5>
        </div>

        <form action="{{ route('sesiones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 px-2 mt-3">

                <!-- Información del Paciente -->
                <h5 class="text-dark fw-bold mt-4 mb-3">Información del Paciente</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="paciente_id">Paciente <span class="text-danger">*</span></label>
                        <select name="paciente_id" id="paciente_id" class="form-select form-select-sm @error('paciente_id') is-invalid @enderror" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($pacientes as $p)
                                <option value="{{ $p->id }}"
                                        data-nacimiento="{{ $p->fecha_nacimiento }}"
                                        data-genero="{{ $p->genero }}"
                                        data-telefono="{{ $p->telefono }}"
                                >
                                    {{ $p->nombre }} {{ $p->apellidos }}
                                </option>
                            @endforeach
                        </select>
                        @error('paciente_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Edad</label>
                        <input type="text" id="edad" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Género</label>
                        <input type="text" id="genero" class="form-control form-control-sm bg-light" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Teléfono</label>
                        <input type="text" id="telefono" class="form-control form-control-sm bg-light" readonly>
                    </div>
                </div>

                <!-- Médico -->
                <h5 class="text-dark fw-bold mt-4 mb-3">Médico que Atiende</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="medico_id">Médico <span class="text-danger">*</span></label>
                        <select name="medico_id" id="medico_id" class="form-select form-select-sm @error('medico_id') is-invalid @enderror" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($medicos as $m)
                                <option value="{{ $m->id }}">{{ $m->nombre }} {{ $m->apellidos }}</option>
                            @endforeach
                        </select>
                        @error('medico_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Detalles de la Sesión Psicológica -->
                <h5 class="text-dark fw-bold mt-4 mb-3">Detalles de la Sesión</h5>
                <div class="row g-3">
                    <div class="col-md-2">
                        <label for="fecha">Fecha <span class="text-danger">*</span></label>
                        <input type="date" name="fecha" id="fecha" class="form-control form-control-sm @error('fecha') is-invalid @enderror" required value="{{ old('fecha', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                        @error('fecha')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="hora_inicio">Hora Inicio <span class="text-danger">*</span></label>
                        <input type="time" name="hora_inicio" id="hora_inicio" class="form-control form-control-sm @error('hora_inicio') is-invalid @enderror" required value="{{ old('hora_inicio') }}">
                        @error('hora_inicio')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="hora_fin">Hora Fin <span class="text-danger">*</span></label>
                        <input type="time" name="hora_fin" id="hora_fin" class="form-control form-control-sm @error('hora_fin') is-invalid @enderror" required value="{{ old('hora_fin') }}">
                        @error('hora_fin')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <label for="motivo_consulta">Motivo de la consulta <span class="text-danger">*</span></label>
                        <textarea name="motivo_consulta" id="motivo_consulta" rows="2" class="form-control form-control-sm @error('motivo_consulta') is-invalid @enderror" required>{{ old('motivo_consulta') }}</textarea>
                        @error('motivo_consulta')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tipo_examen">Tipo de examen psicométrico <span class="text-danger">*</span></label>
                        <input type="text" name="tipo_examen" id="tipo_examen" class="form-control form-control-sm @error('tipo_examen') is-invalid @enderror" required value="{{ old('tipo_examen') }}">
                        @error('tipo_examen')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="resultado">Resultado <span class="text-danger">*</span></label>
                        <textarea name="resultado" id="resultado" rows="3" class="form-control form-control-sm @error('resultado') is-invalid @enderror" required>{{ old('resultado') }}</textarea>
                        @error('resultado')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="observaciones">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3" class="form-control form-control-sm">{{ old('observaciones') }}</textarea>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="archivo_resultado">Archivo Resultado</label>
                        <input type="file" name="archivo_resultado" id="archivo_resultado" class="form-control form-control-sm">
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-center gap-3 mt-4 mb-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Registrar sesión</button>
                    <button type="reset" class="btn btn-warning"><i class="bi bi-trash"></i> Limpiar</button>
                    <a href="{{ route('sesiones.index') }}" class="btn btn-success"><i class="bi bi-arrow-left"></i> Regresar</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pacienteSelect = document.getElementById('paciente_id');
            const edadInput = document.getElementById('edad');
            const generoInput = document.getElementById('genero');
            const telefonoInput = document.getElementById('telefono');

            pacienteSelect.addEventListener('change', function() {
                const selected = pacienteSelect.selectedOptions[0];
                if(selected.value) {
                    const nacimiento = new Date(selected.dataset.nacimiento);
                    const hoy = new Date();
                    let edad = hoy.getFullYear() - nacimiento.getFullYear();
                    if(hoy.getMonth() < nacimiento.getMonth() || (hoy.getMonth() === nacimiento.getMonth() && hoy.getDate() < nacimiento.getDate())) {
                        edad--;
                    }
                    edadInput.value = edad;
                    generoInput.value = selected.dataset.genero;
                    telefonoInput.value = selected.dataset.telefono;
                } else {
                    edadInput.value = '';
                    generoInput.value = '';
                    telefonoInput.value = '';
                }
            });
        });
    </script>
@endsection
