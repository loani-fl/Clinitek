@extends('layouts.app')

@section('content')
<style>
    html, body { overflow-x: hidden; margin: 0; padding: 0; max-width: 100%; }
    .container { padding-left: 15px; padding-right: 15px; max-width: 100%; overflow-x: hidden; }
    .custom-card { max-width: 1000px; width: 100%; padding-left: 20px; padding-right: 20px; margin-left: auto; margin-right: auto; box-sizing: border-box; }
    input, select, textarea { max-width: 100%; box-sizing: border-box; }
    .error-text { color: red; font-size: 0.85rem; }
</style>

<div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px;">
    <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
        <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Registro de consulta médica</h5>
    </div>

    <form action="{{ route('consultas.store') }}" method="POST" id="formConsulta">
        @csrf
        <div class="row g-3 px-2 mt-3">

            <!-- Información paciente -->
            <h5 class="text-dark fw-bold mt-4 mb-3">Información del paciente</h5>
            <div class="col-md-4">
                <label for="paciente_id">Paciente <span class="text-danger">*</span></label>
                <select name="paciente_id" id="paciente_id" class="form-select form-select-sm">
    <option value="">-- Selecciona --</option>
    @foreach($pacientes as $p)
        <option value="{{ $p->id }}"
            data-identidad="{{ $p->identidad }}"
            data-nacimiento="{{ \Carbon\Carbon::parse($p->fecha_nacimiento)->format('Y-m-d') }}"
            data-telefono="{{ $p->telefono }}"
            data-correo="{{ $p->correo }}"
            data-direccion="{{ $p->direccion }}"
            data-genero="{{ $p->genero }}"
            {{ old('paciente_id') == $p->id ? 'selected' : '' }}>
            {{ $p->nombre }} {{ $p->apellidos }}
        </option>
    @endforeach
</select>

                @error('paciente_id') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3"><label class="form-label">Identidad</label><div class="form-control form-control-sm bg-light" id="identidad"></div></div>
            <div class="col-md-3"><label class="form-label">Fecha de nacimiento</label><div class="form-control form-control-sm bg-light" id="fecha_nacimiento"></div></div>
            <div class="col-md-2"><label class="form-label">Género</label><div class="form-control form-control-sm bg-light" id="genero"></div></div>
            <div class="col-md-3"><label class="form-label">Teléfono</label><div class="form-control form-control-sm bg-light" id="telefono"></div></div>
            <div class="col-md-3"><label class="form-label">Correo electrónico</label><div class="form-control form-control-sm bg-light" id="correo"></div></div>
            <div class="col-md-6"><label>Dirección</label><textarea id="direccion" class="form-control form-control-sm bg-light" rows="2" readonly></textarea></div>

            <!-- Información consulta -->
            <h5 class="text-dark fw-bold mt-4 mb-3">Información de la consulta médica</h5>

            <div class="col-md-2">
                <label for="fecha_consulta">Fecha <span class="text-danger">*</span></label>
                <input type="date" id="fecha_consulta" name="fecha"
                       value="{{ old('fecha', date('Y-m-d')) }}"
                       min="{{ date('Y-m-d') }}"
                       max="{{ date('Y-m-d', strtotime('+6 months')) }}"
                       class="form-control form-control-sm">
                @error('fecha') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="medico">Médico que atiende <span class="text-danger">*</span></label>
                <select name="medico_id" id="medico" class="form-select form-select-sm">
                    <option value="">-- Médico que atiende --</option>
                    @foreach($medicos as $m)
                        <option value="{{ $m->id }}" data-especialidad="{{ $m->especialidad }}" {{ old('medico_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nombre }} {{ $m->apellidos }}
                        </option>
                    @endforeach
                </select>
                @error('medico_id') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3"><label>Especialidad</label><label id="especialidad" class="form-control form-control-sm bg-light"></label></div>

            <div class="col-md-3">
                <label for="hora">Hora <span class="text-danger">*</span></label>
                <select id="hora" name="hora" class="form-select form-select-sm">
                    <option value="">-- Selecciona hora --</option>
                </select>
                @error('hora') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="motivo">Motivo de la consulta <span class="text-danger">*</span></label>
                <textarea name="motivo" maxlength="250" rows="2" class="form-control form-control-sm">{{ old('motivo') }}</textarea>
                @error('motivo') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="sintomas">Síntomas <span class="text-danger">*</span></label>
                <textarea name="sintomas" maxlength="250" rows="2" class="form-control form-control-sm">{{ old('sintomas') }}</textarea>
                @error('sintomas') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-center gap-3 mt-4 mb-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Registrar y Pagar</button>
                <button type="button" id="btnLimpiar" class="btn btn-warning"><i class="bi bi-trash"></i> Limpiar</button>
                <a href="{{ route('consultas.index') }}" class="btn btn-success"><i class="bi bi-arrow-left-circle"></i> Regresar</a>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pacienteSelect = document.getElementById('paciente_id');
    const medicoSelect = document.getElementById('medico');
    const especialidadInput = document.getElementById('especialidad');
    const fechaConsultaInput = document.getElementById('fecha_consulta');
    const horaSelect = document.getElementById('hora');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const form = document.getElementById('formConsulta');

    // Autocompletar datos del paciente
    function autocompletarPaciente() {
        const opt = pacienteSelect.options[pacienteSelect.selectedIndex];
        if (!opt) return;
        document.getElementById('fecha_nacimiento').textContent = opt.getAttribute('data-nacimiento') || '';
        document.getElementById('identidad').textContent = opt.getAttribute('data-identidad') || '';
        document.getElementById('telefono').textContent = opt.getAttribute('data-telefono') || '';
        document.getElementById('correo').textContent = opt.getAttribute('data-correo') || '';
        document.getElementById('direccion').value = opt.getAttribute('data-direccion') || '';
        document.getElementById('genero').textContent = opt.getAttribute('data-genero') || '';
    }

    // Convertir hora 12h a 24h
    function hora12a24(hora12) {
        if(hora12 === '') return '';
        const [hora, minutoPeriodo] = hora12.split(':');
        const [minuto, periodo] = minutoPeriodo.split(' ');
        let h = parseInt(hora);
        if (periodo === 'PM' && h < 12) h += 12;
        if (periodo === 'AM' && h === 12) h = 0;
        return `${h.toString().padStart(2,'0')}:${minuto}:00`;
    }

    // Actualizar la especialidad al cambiar el médico
    function actualizarEspecialidad() {
        const opt = medicoSelect.options[medicoSelect.selectedIndex];
        especialidadInput.textContent = opt ? opt.getAttribute('data-especialidad') : '';
    }

    function cargarHorasDisponibles() {
    const medico = medicoSelect.value;
    const fecha = fechaConsultaInput.value;
    horaSelect.innerHTML = '<option value="">-- Selecciona hora --</option>';
    horaSelect.innerHTML += '<option value="inmediata">Inmediata</option>'; // opción inmediata
    if (!medico || !fecha) return;

    const horas = [];
    let minutos = 8*60;
    const fin = 16*60+30;
    while(minutos <= fin){
        const h = Math.floor(minutos/60);
        const m = minutos%60;
        const periodo = h>=12?'PM':'AM';
        const hora12 = (h%12===0?12:h%12)+':'+String(m).padStart(2,'0')+' '+periodo;
        horas.push(hora12);
        minutos += 30;
    }

    // Crear todas las opciones de hora
    horas.forEach(hora12 => {
        const option = document.createElement('option');
        option.value = hora12;
        option.textContent = hora12;
        horaSelect.appendChild(option);
    });

    // Bloquear horas ocupadas del mismo médico y fecha
    fetch(`/horas-ocupadas?medico_id=${medico}&fecha=${fecha}`)
        .then(res => res.json())
        .then(horasOcupadas => {
            Array.from(horaSelect.options).forEach(option => {
                if(option.value !== 'inmediata' && horasOcupadas.includes(hora12a24(option.value))){
                    option.disabled = true;
                    option.textContent += ' (Ocupada)';
                    if(option.selected) horaSelect.value = '';
                }
            });
        });
}


    // Eventos
    medicoSelect.addEventListener('change', function(){
        actualizarEspecialidad();
        cargarHorasDisponibles();
    });
    fechaConsultaInput.addEventListener('change', cargarHorasDisponibles);
    pacienteSelect.addEventListener('change', autocompletarPaciente);

    btnLimpiar.addEventListener('click', function(e){
        e.preventDefault();
        form.reset();
        document.getElementById('identidad').textContent = '';
        document.getElementById('fecha_nacimiento').textContent = '';
        document.getElementById('telefono').textContent = '';
        document.getElementById('correo').textContent = '';
        document.getElementById('direccion').value = '';
        document.getElementById('genero').textContent = '';
        document.getElementById('especialidad').textContent = '';
        horaSelect.innerHTML = '<option value="">-- Selecciona hora --</option>';
        document.querySelectorAll('.error-text').forEach(el => el.textContent = '');
    });

    // Inicializar
    if(pacienteSelect.value) autocompletarPaciente();
    if(medicoSelect.value) actualizarEspecialidad();
    if(medicoSelect.value && fechaConsultaInput.value) cargarHorasDisponibles();
});

</script>
@endsection
