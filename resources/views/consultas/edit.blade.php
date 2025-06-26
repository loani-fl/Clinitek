@extends('layouts.app')

@section('content')
<!-- Barra de navegación fija -->
<div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050;">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>
</div>

<!-- Formulario más compacto -->
<div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 90px;">
    <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
        <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Editar consulta médica</h5>
    </div>
    <form action="{{ route('consultas.update', $consulta->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3 px-2 mt-3">
            <h5 class="text-dark fw-bold mt-4 mb-3">Información del paciente</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="paciente_id">Paciente <span class="text-danger">*</span></label>
                    <select name="paciente_id" id="paciente_id" class="form-select form-select-sm" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($pacientes as $p)
                            <option value="{{ $p->id }}"
                                data-nacimiento="{{ $p->fecha_nacimiento }}"
                                data-identidad="{{ $p->identidad }}"
                                data-sexo="{{ $p->sexo }}"
                                data-telefono="{{ $p->telefono }}"
                                data-correo="{{ $p->correo }}"
                                data-direccion="{{ $p->direccion }}"
                                {{ old('paciente_id', $consulta->paciente_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }} {{ $p->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Identidad</label>
                    <input type="text" id="identidad" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-2">
                    <label>Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-2">
                    <label for="sexo">Género <span class="text-danger">*</span></label>
                    <select name="sexo" id="sexo" class="form-select form-select-sm" required>
                        <option value="">-- Selecciona --</option>
                        <option value="Femenino" {{ old('sexo', $consulta->sexo) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="Masculino" {{ old('sexo', $consulta->sexo) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Teléfono</label>
                    <input type="text" id="telefono" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-3">
                    <label>Correo electrónico</label>
                    <input type="email" id="correo" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-6">
                    <label>Dirección</label>
                    <input type="text" id="direccion" class="form-control form-control-sm" readonly>
                </div>
            </div>

            <h5 class="text-dark fw-bold mt-4 mb-3">Información de la consulta médica</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="fecha">Fecha <span class="text-danger">*</span></label>
                    <input type="date" id="fecha" name="fecha" class="form-control form-control-sm" 
                        value="{{ old('fecha', $consulta->fecha) }}" required>
                </div>

                <div class="col-md-2">
                    <label for="hora">Hora <span class="text-danger">*</span></label>
                    <select id="hora" name="hora" class="form-select form-select-sm" required>
                        <option value="">-- Selecciona hora --</option>
                        @php
                            $horas = ['08:00', '09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
                        @endphp
                        @foreach($horas as $hora)
                            <option value="{{ $hora }}" {{ old('hora', $consulta->hora) == $hora ? 'selected' : '' }}>
                                {{ $hora }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="especialidad">Especialidad <span class="text-danger">*</span></label>
                    <input type="text" id="especialidad" name="especialidad" class="form-control form-control-sm"
                        value="{{ old('especialidad', $consulta->especialidad) }}" readonly required>
                </div>

                <div class="col-md-4">
                    <label for="medico_id">Médico que atiende <span class="text-danger">*</span></label>
                    <select name="medico_id" id="medico" class="form-select form-select-sm" required>
                        <option value="">-- Médico que atiende --</option>
                        @foreach($medicos as $m)
                            <option value="{{ $m->id }}"
                                data-especialidad="{{ $m->especialidad }}"
                                {{ old('medico_id', $consulta->medico_id) == $m->id ? 'selected' : '' }}>
                                {{ $m->nombre }} {{ $m->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="motivo">Motivo de la consulta <span class="text-danger">*</span></label>
                    <textarea name="motivo" rows="2" class="form-control form-control-sm" required>{{ old('motivo', $consulta->motivo) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label for="sintomas">Síntomas <span class="text-danger">*</span></label>
                    <textarea name="sintomas" rows="2" class="form-control form-control-sm" required>{{ old('sintomas', $consulta->sintomas) }}</textarea>
                </div>

                <div class="col-md-2">
                    <label for="total_pagar">Total a pagar <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">L.</span>
                        <input type="number" step="0.01" min="0" id="total_pagar" name="total_pagar"
                            class="form-control" value="{{ old('total_pagar', $consulta->total_pagar) }}" readonly required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4 mb-3">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Actualizar
    </button>
    <button type="reset" class="btn btn-secondary">
        <i class="bi bi-arrow-counterclockwise"></i> Restablecer
    </button>
    <a href="{{ route('consultas.index') }}" class="btn btn-outline-danger">
        <i class="bi bi-x-circle"></i> Cancelar
    </a>
</div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pacienteSelect = document.getElementById('paciente_id');
        const identidad = document.getElementById('identidad');
        const nacimiento = document.getElementById('fecha_nacimiento');
        const telefono = document.getElementById('telefono');
        const correo = document.getElementById('correo');
        const direccion = document.getElementById('direccion');
        const sexo = document.getElementById('sexo');

        function llenarDatos() {
            const selected = pacienteSelect.options[pacienteSelect.selectedIndex];
            identidad.value = selected.dataset.identidad || '';
            nacimiento.value = selected.dataset.nacimiento || '';
            telefono.value = selected.dataset.telefono || '';
            correo.value = selected.dataset.correo || '';
            direccion.value = selected.dataset.direccion || '';
            sexo.value = selected.dataset.sexo || '';
        }

        pacienteSelect.addEventListener('change', llenarDatos);

        // Cargar al inicio si ya hay selección
        if (pacienteSelect.value !== "") {
            llenarDatos();
        }

        const medicoSelect = document.getElementById('medico');
        const especialidad = document.getElementById('especialidad');

        medicoSelect.addEventListener('change', function () {
            const selected = medicoSelect.options[medicoSelect.selectedIndex];
            especialidad.value = selected.dataset.especialidad || '';
        });

        if (medicoSelect.value !== "") {
            const selected = medicoSelect.options[medicoSelect.selectedIndex];
            especialidad.value = selected.dataset.especialidad || '';
        }
    });
</script>
@endsection
