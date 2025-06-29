@extends('layouts.app')

@section('content')
<style>
    html, body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
        max-width: 100%;
        background-color: #e8f4fc;
    }

    .custom-card {
        position: relative;
        max-width: 1400px;
        width: 96%;
        margin: 80px auto 40px;
        padding: 30px;
        background-color: rgba(255, 255, 255, 0.85); /* Fondo translúcido */
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 1;
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
        opacity: 0.12;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
        border-radius: 1rem;
    }

    input, select, textarea {
        max-width: 100%;
        box-sizing: border-box;
        background-color: transparent !important;
        color: #212529;
    }

    textarea[readonly], input[readonly] {
        background-color: transparent !important;
    }

    label {
        background-color: transparent;
        color: rgba(0, 0, 0, 0.6); /* Muy claro */
        font-weight: 500;
    }

    .invalid-feedback {
        display: block;
        font-size: 0.8rem;
    }

    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 1050;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }

    .header img {
        height: 40px;
        margin-right: 8px;
    }
</style>

<!-- ✅ Barra fija -->
<div class="header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek">
        <span class="fw-bold text-white fs-4">Clinitek</span>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>

<!-- ✅ Tarjeta -->
<div class="card custom-card shadow-sm border rounded-4 w-100">
    <div class="card-header text-center py-2 bg-white border-bottom border-4 border-primary">
        <h5 class="mb-0 fw-bold text-dark fs-2">Editar consulta médica</h5>
    </div>

    <form action="{{ route('consultas.update', $consulta->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="px-3 pt-3 pb-4" style="position: relative; z-index: 1;">
            <!-- ✅ TÍTULO NUEVO -->
            <h5 class="text-dark fw-bold mb-3">Información del paciente</h5>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="paciente_id" class="form-label">Paciente <span class="text-danger">*</span></label>
                    <select name="paciente_id" id="paciente_id" 
                        class="form-select form-select-sm @error('paciente_id') is-invalid @enderror" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($pacientes as $p)
                            <option value="{{ $p->id }}"
                                data-nacimiento="{{ \Carbon\Carbon::parse($p->fecha_nacimiento)->format('Y-m-d') }}"
                                data-identidad="{{ $p->identidad }}"
                                data-genero="{{ $p->genero }}"
                                data-telefono="{{ $p->telefono }}"
                                data-correo="{{ $p->correo }}"
                                data-direccion="{{ $p->direccion }}"
                                {{ old('paciente_id', $consulta->paciente_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }} {{ $p->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    @error('paciente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Identidad</label>
                    <input type="text" id="identidad" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-2">
                    <label for="genero" class="form-label">Género</label>
                    <select id="genero" name="genero" class="form-select form-select-sm" readonly disabled>
                        <option value="">--</option>
                        <option value="Femenino" {{ old('genero', $consulta->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="Masculino" {{ old('genero', $consulta->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" id="telefono" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" id="correo" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dirección</label>
                    <textarea id="direccion" class="form-control form-control-sm" readonly rows="3"></textarea>
                </div>
            </div>

            <!-- Información de la consulta -->
            <h5 class="text-dark fw-bold mt-4 mb-3">Información de la consulta médica</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="fecha_consulta" class="form-label">Fecha <span class="text-danger">*</span></label>
                    <input type="date" id="fecha_consulta" name="fecha" 
                        class="form-control form-control-sm @error('fecha') is-invalid @enderror"
                        value="{{ old('fecha', $consulta->fecha) }}" required>
                    @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <label for="hora" class="form-label">Hora <span class="text-danger">*</span></label>
                    <select id="hora" name="hora" 
                        class="form-select form-select-sm @error('hora') is-invalid @enderror" required>
                        <option value="">-- Selecciona hora --</option>
                        <option value="inmediata" {{ old('hora', $consulta->hora) == 'inmediata' ? 'selected' : '' }}>Inmediata</option>
                    </select>
                    @error('hora') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <input type="text" id="especialidad" name="especialidad" 
                        class="form-control form-control-sm" readonly
                        value="{{ old('especialidad', $consulta->especialidad) }}">
                </div>

                <div class="col-md-4">
                    <label for="medico" class="form-label">Médico <span class="text-danger">*</span></label>
                    <select name="medico_id" id="medico" 
                        class="form-select form-select-sm @error('medico_id') is-invalid @enderror" required>
                        <option value="">-- Médico que atiende --</option>
                        @foreach($medicos as $m)
                            <option value="{{ $m->id }}"
                                data-nombre="{{ $m->nombre }}"
                                data-especialidad="{{ $m->especialidad }}"
                                {{ old('medico_id', $consulta->medico_id) == $m->id ? 'selected' : '' }}>
                                {{ $m->nombre }} {{ $m->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    @error('medico_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Motivo y síntomas -->
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label for="motivo" class="form-label">Motivo <span class="text-danger">*</span></label>
                    <textarea name="motivo" rows="3" 
                        class="form-control form-control-sm @error('motivo') is-invalid @enderror" required>{{ old('motivo', $consulta->motivo) }}</textarea>
                    @error('motivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="sintomas" class="form-label">Síntomas <span class="text-danger">*</span></label>
                    <textarea name="sintomas" rows="3" 
                        class="form-control form-control-sm @error('sintomas') is-invalid @enderror" required>{{ old('sintomas', $consulta->sintomas) }}</textarea>
                    @error('sintomas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Total a pagar -->
            <div class="row g-3 mt-3" id="contenedor_total_pagar" style="display: {{ old('hora', $consulta->hora) == 'inmediata' ? 'block' : 'none' }};">
                <div class="col-md-2">
                    <label for="total_pagar" class="form-label">Total a pagar <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">L.</span>
                        <input type="number" step="0.01" min="0" id="total_pagar" name="total_pagar"
                            class="form-control @error('total_pagar') is-invalid @enderror"
                            value="{{ old('total_pagar', $consulta->total_pagar) }}"
                            {{ old('hora', $consulta->hora) == 'inmediata' ? '' : 'readonly' }} required>
                    </div>
                    @error('total_pagar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Actualizar
                </button>
                <a href="{{ route('consultas.index') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </div>
    </form>
</div>





<script>
document.addEventListener('DOMContentLoaded', function () {
    const pacienteSelect = document.getElementById('paciente_id');
    const generoSelect = document.getElementById('genero');
    const medicoSelect = document.getElementById('medico');
    const especialidadInput = document.getElementById('especialidad');
    const fechaConsultaInput = document.getElementById('fecha_consulta');
    const horaSelect = document.getElementById('hora');
    const totalPagarInput = document.getElementById('total_pagar');
    const contenedorTotalPagar = document.getElementById('contenedor_total_pagar');

    const preciosPorEspecialidad = {
        "Cardiología": 900.00,
        "Pediatría": 500.00,
        "Dermatología": 900.00,
        "Medicina General": 800.00,
        "Psiquiatría": 500.00,
        "Neurología": 1000.00,
        "Radiología": 700.00
    };

    function autocompletarPaciente() {
        const opt = pacienteSelect.options[pacienteSelect.selectedIndex];
        if (!opt) return;
        document.getElementById('fecha_nacimiento').value = opt.getAttribute('data-nacimiento') || '';
        document.getElementById('identidad').value = opt.getAttribute('data-identidad') || '';
        generoSelect.value = opt.getAttribute('data-genero') || '';
        document.getElementById('telefono').value = opt.getAttribute('data-telefono') || '';
        document.getElementById('correo').value = opt.getAttribute('data-correo') || '';
        document.getElementById('direccion').value = opt.getAttribute('data-direccion') || '';
    }
    pacienteSelect.addEventListener('change', autocompletarPaciente);
    if (pacienteSelect.value) autocompletarPaciente();

    function actualizarVisibilidadTotalPagar() {
        const horaSeleccionada = horaSelect.value;

        if (horaSeleccionada === 'inmediata') {
            contenedorTotalPagar.style.display = 'block';

            const selectedMedico = medicoSelect.options[medicoSelect.selectedIndex];
            const especialidad = selectedMedico ? selectedMedico.getAttribute('data-especialidad') : '';

            if (especialidad && preciosPorEspecialidad.hasOwnProperty(especialidad)) {
                totalPagarInput.value = preciosPorEspecialidad[especialidad].toFixed(2);
            } else {
                totalPagarInput.value = '';
            }
        } else {
            contenedorTotalPagar.style.display = 'none';
            totalPagarInput.value = '';
        }
    }

    medicoSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const especialidad = selected.getAttribute('data-especialidad') || '';
        especialidadInput.value = especialidad;

        if (especialidad && preciosPorEspecialidad.hasOwnProperty(especialidad)) {
            if (horaSelect.value === 'inmediata') {
                totalPagarInput.value = preciosPorEspecialidad[especialidad].toFixed(2);
            }
        } else {
            totalPagarInput.value = '';
        }

        cargarHorasDisponibles();
    });

    fechaConsultaInput.addEventListener('change', cargarHorasDisponibles);

    horaSelect.addEventListener('change', actualizarVisibilidadTotalPagar);

    function hora12a24(hora12) {
        if (hora12 === 'inmediata') return null;
        const [hora, minutoPeriodo] = hora12.split(':');
        const [minuto, periodo] = minutoPeriodo.split(' ');
        let h = parseInt(hora);
        if (periodo === 'PM' && h < 12) h += 12;
        if (periodo === 'AM' && h === 12) h = 0;
        return `${h.toString().padStart(2, '0')}:${minuto}:00`;
    }

    function cargarHorasDisponibles() {
        const medico = medicoSelect.value;
        const fecha = fechaConsultaInput.value;
        if (!medico || !fecha) {
            horaSelect.innerHTML = '<option value="">-- Selecciona hora --</option><option value="inmediata">Inmediata</option>';
            return;
        }

        fetch(`/consultas/horas-disponibles?medico_id=${medico}&fecha=${fecha}`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Selecciona hora --</option><option value="inmediata">Inmediata</option>';
                data.forEach(hora => {
                    const selected = oldHora === hora ? 'selected' : '';
                    options += `<option value="${hora}" ${selected}>${hora}</option>`;
                });
                horaSelect.innerHTML = options;
                actualizarVisibilidadTotalPagar();
            })
            .catch(err => {
                console.error(err);
                horaSelect.innerHTML = '<option value="">-- Selecciona hora --</option><option value="inmediata">Inmediata</option>';
            });
    }

    const oldHora = @json(old('hora', $consulta->hora));

    cargarHorasDisponibles();
});
</script>
@endsection


