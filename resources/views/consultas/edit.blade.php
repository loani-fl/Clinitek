@extends('layouts.app')

@section('content')


<style>
    /* Estilo para el mensaje de éxito */
    .alert-success-custom {
        background-color: #d1e7dd;
        border: 1px solid #badbcc;
        color: #0f5132;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0, 128, 0, 0.2);
        border-radius: 0.5rem;
        padding: 1rem 1.5rem;
        position: relative;
        z-index: 1200;
        margin-bottom: 1rem;
    }
    body {
        padding-top: 40px; /* Espacio suficiente para la navbar fija (ajusta según altura real) */
        margin: 0;
        overflow-x: hidden;
        max-width: 100%;
    }

    .container {
        padding-left: 15px;
        padding-right: 15px;
        max-width: 100%;
        overflow-x: hidden;
    }

    .custom-card {
        max-width: 1000px;
        width: 100%;
        padding-left: 20px;
        padding-right: 20px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        background-color: #fff;
        border-radius: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 0.075);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
        max-width: 100%;
    }

    input, select, textarea {
        max-width: 100%;
        box-sizing: border-box;
    }

    .valid-feedback {
        display: block;
        font-size: 0.75rem;
        color: #198754;
    }

    .form-control.is-valid {
        border-color: #198754;
        padding-right: 2.3rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23198754' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' class='bi bi-check-lg' viewBox='0 0 16 16'%3e%3cpath d='M13 4.5 6 11.5 3 8.5'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.6rem center;
        background-size: 0.9rem 0.9rem;
    }

    label.is-invalid {
        color: #dc3545;
        font-weight: 600;
    }

    form {
        padding-left: 10px;
        padding-right: 10px;
    }

    footer {
        position: static;
        width: 100%;
        background-color: #f8f9fa;
        padding: 10px 0;
        text-align: center;
        font-size: 0.9rem;
        color: #6c757d;
        border-top: 1px solid #dee2e6;
        margin-top: 40px;
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

    .header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 56px;
        background-color: #007BFF;
        z-index: 1030;
        display: flex;
        align-items: center;
        padding: 0 1rem;
        box-sizing: border-box;
    }

    .no-select {
    user-select: none !important;
    -webkit-user-select: none !important;
    -moz-user-select: none !important;
    -ms-user-select: none !important;
    pointer-events: none !important;
    cursor: default;
}
</style>

<!-- Barra de navegación fija -->
<div class="header d-flex justify-content-between align-items-center px-3 py-2" 
     style="background-color: #007BFF; position: fixed; top: 0; left: 0; right: 0; z-index: 1030; height: 56px;">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" 
             style="height: 40px; width: auto; margin-right: 6px;">
        <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
    </div>
    <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
        <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
    </div>
</div>



<!-- Formulario más compacto -->
<div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px; z-index:1;">
    <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
        <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Editar consulta médica</h5>
    </div>


<h5 class="text-dark fw-bold mt-4 mb-3">Información del paciente</h5>

<div class="row g-3">
    <div class="col-md-4">
        <label>Paciente</label>
        <div class="form-control form-control-sm bg-light no-select" aria-hidden="true">
            <span style="pointer-events: none;">{{ $consulta->paciente->nombre }} {{ $consulta->paciente->apellidos }}</span>
        </div>
    </div>




    <div class="col-md-3">
        <label>Identidad</label>
        <div class="form-control form-control-sm bg-light no-select" aria-hidden="true">
            <span style="pointer-events: none;">{{ $consulta->paciente->identidad }}</span>
        </div>
    </div>

    <div class="col-md-3">
        <label>Fecha de nacimiento</label>
        <div class="form-control form-control-sm bg-light no-select" aria-hidden="true">
            <span style="pointer-events: none;">{{ \Carbon\Carbon::parse($consulta->paciente->fecha_nacimiento)->format('d/m/Y') }}</span>
        </div>
    </div>

    <div class="col-md-2">
        <label>Género</label>
        <div class="form-control form-control-sm bg-light no-select" aria-hidden="true">
            <span style="pointer-events: none;">{{ $consulta->paciente->genero }}</span>
        </div>
    </div>

    <div class="col-md-3">
        <label>Teléfono</label>
        <div class="form-control form-control-sm bg-light no-select" aria-hidden="true">
            <span style="pointer-events: none;">{{ $consulta->paciente->telefono }}</span>
        </div>
    </div>

    <div class="col-md-3">
        <label>Correo electrónico</label>
        <div class="form-control form-control-sm bg-light no-select" aria-hidden="true">
            <span style="pointer-events: none;">{{ $consulta->paciente->correo }}</span>
        </div>
    </div>

    <div class="col-md-6">
        <label>Dirección</label>
        <div class="form-control form-control-sm bg-light no-select" style="min-height: 48px;" aria-hidden="true">
            <span style="pointer-events: none;">{{ $consulta->paciente->direccion }}</span>
        </div>
    </div>
</div>
<h5 class="text-dark fw-bold mt-4 mb-3">Información de la consulta médica</h5>

<form action="{{ route('consultas.update', $consulta->id) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="hidden" name="paciente_id" value="{{ $consulta->paciente_id }}">
    <input type="hidden" name="especialidad" value="{{ old('especialidad', $consulta->especialidad) }}">

    <div class="row g-3">
        <div class="col-md-2">
            <label for="fecha">Fecha <span class="text-danger">*</span></label>
            <input type="date" id="fecha_consulta" name="fecha" 
                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                max="{{ \Carbon\Carbon::now()->addMonth()->format('Y-m-d') }}"
                value="{{ old('fecha', $consulta->fecha) }}" 
                class="form-control form-control-sm @error('fecha') is-invalid @enderror" required>
            @error('fecha')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="medico">Médico que atiende <span class="text-danger">*</span></label>
            <select name="medico_id" id="medico" class="form-select form-select-sm @error('medico_id') is-invalid @enderror" required>
                <option value="">-- Médico que atiende --</option>
                @foreach($medicos as $m)
                    <option 
                        value="{{ $m->id }}" 
                        data-especialidad="{{ $m->especialidad }}"
                        {{ (old('medico_id', $consulta->medico_id) == $m->id) ? 'selected' : '' }}>
                        {{ $m->nombre }} {{ $m->apellidos }}
                    </option>
                @endforeach
            </select>
            @error('medico_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-3">
            <label for="especialidad">Especialidad</label>
            <label id="especialidad" class="form-control form-control-sm bg-light"></label>
        </div>

        <div class="col-md-3">
            <label for="hora">Hora <span class="text-danger">*</span></label>
            <select id="hora" name="hora" class="form-select form-select-sm @error('hora') is-invalid @enderror" required>
                {{-- La opción inicial la pondrá JS con la hora ocupada --}}
            </select>
            @error('hora')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mt-3">
            <label for="motivo">Motivo de la consulta <span class="text-danger">*</span></label>
            <textarea name="motivo" maxlength="250" rows="2" class="form-control form-control-sm @error('motivo') is-invalid @enderror" required>{{ old('motivo', $consulta->motivo) }}</textarea>
            @error('motivo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mt-3">
            <label for="sintomas">Síntomas <span class="text-danger">*</span></label>
            <textarea name="sintomas" maxlength="250" rows="2" class="form-control form-control-sm @error('sintomas') is-invalid @enderror" required>{{ old('sintomas', $consulta->sintomas) }}</textarea>
            @error('sintomas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

<div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">

<form action="{{ route('consultas.update', $consulta->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Aquí van tus campos del formulario -->

    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
        <!-- Botón Actualizar -->
        <button type="submit" class="btn btn-primary d-flex align-items-center" data-bs-toggle="tooltip" title="Guardar los cambios">
            <i class="bi bi-pencil-square me-2"></i> Actualizar
        </button>

        <!-- Botón Restablecer -->
        <button type="button" id="restablecerBtn" class="btn btn-warning d-flex align-items-center" data-bs-toggle="tooltip" title="Restablecer los campos del formulario">
            <i class="bi bi-arrow-counterclockwise me-2"></i> Restablecer
        </button>
    </div>
</form> {{-- 👈 Aquí cierra el formulario de actualización --}}


<div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
    <!-- Botón Cancelar Consulta -->
    <form action="{{ route('consultas.cancelar', $consulta->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-danger d-flex align-items-center" data-bs-toggle="tooltip" title="Cancelar la consulta y marcarla como cancelada">
            <i class="bi bi-x-circle me-2"></i> Cancelar Consulta
        </button>
    </form>

    <!-- Botón Regresar -->
    <a href="{{ route('consultas.index') }}" class="btn btn-success d-flex align-items-center" data-bs-toggle="tooltip" title="Volver al listado de consultas">
        <i class="bi bi-arrow-left me-2"></i> Regresar
    </a>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const medicoSelect = document.getElementById('medico');
    const especialidadLabel = document.getElementById('especialidad');
    const fechaConsultaInput = document.getElementById('fecha_consulta');
    const horaSelect = document.getElementById('hora');
    const motivoInput = document.querySelector('textarea[name="motivo"]');
    const sintomasInput = document.querySelector('textarea[name="sintomas"]');
    const restablecerBtn = document.getElementById('restablecerBtn');

    const consultaHoraOriginal = "{{ $consulta->hora }}"; // Ejemplo: "12:30 PM"
    const consultaMedicoOriginal = "{{ $consulta->medico_id }}";
    const consultaFechaOriginal = "{{ $consulta->fecha }}";
    const consultaMotivoOriginal = `{{ $consulta->motivo }}`;
    const consultaSintomasOriginal = `{{ $consulta->sintomas }}`;

    function hora12a24(hora12) {
        const [horaMinuto, periodo] = hora12.split(' ');
        let [hora, minuto] = horaMinuto.split(':').map(Number);
        if (periodo === 'PM' && hora !== 12) hora += 12;
        if (periodo === 'AM' && hora === 12) hora = 0;
        return `${hora.toString().padStart(2, '0')}:${minuto.toString().padStart(2, '0')}`;
    }

    function cargarHorasDisponibles() {
        const medico = medicoSelect.value;
        const fecha = fechaConsultaInput.value;

        horaSelect.innerHTML = '';
        horaSelect.appendChild(new Option('-- Selecciona hora --', ''));
        horaSelect.appendChild(new Option('Inmediata', 'inmediata'));

        if (!medico || !fecha) return;

        const horas = [];
        let minutos = 8 * 60;
        const fin = (16 * 60) + 30;
        while (minutos <= fin) {
            const h = Math.floor(minutos / 60);
            const m = minutos % 60;
            const periodo = h >= 12 ? 'PM' : 'AM';
            const hora12 = (h % 12 === 0 ? 12 : h % 12) + ':' + m.toString().padStart(2, '0') + ' ' + periodo;
            horas.push(hora12);
            minutos += 30;
        }

        fetch(`/horas-ocupadas?medico_id=${encodeURIComponent(medico)}&fecha=${encodeURIComponent(fecha)}`)
            .then(res => res.json())
            .then(horasOcupadas => {
                const horasOcupadasSinSegundos = horasOcupadas.map(h => h.slice(0, 5));
                const horaOriginal24 = consultaHoraOriginal ? hora12a24(consultaHoraOriginal) : null;

                horas.forEach(hora12 => {
                    const hora24 = hora12a24(hora12);
                    const option = document.createElement('option');
                    option.value = hora24;

                    let textoVisible = hora12;
                    let estaOcupada = horasOcupadasSinSegundos.includes(hora24);

                    if (horaOriginal24 === hora24) {
                        option.selected = true;
                        if (estaOcupada) textoVisible += ' ocupada';
                    } else if (estaOcupada) {
                        textoVisible += ' ocupada';
                        option.disabled = true;
                    }

                    option.textContent = textoVisible;
                    horaSelect.appendChild(option);
                });
            })
            .catch(() => {
                horas.forEach(hora12 => {
                    const option = new Option(hora12, hora12a24(hora12));
                    horaSelect.appendChild(option);
                });
            });
    }

    function actualizarEspecialidad() {
        const selected = medicoSelect.options[medicoSelect.selectedIndex];
        const especialidad = selected ? selected.getAttribute('data-especialidad') : '';
        especialidadLabel.textContent = especialidad || '';
    }

    medicoSelect.addEventListener('change', () => {
        actualizarEspecialidad();
        cargarHorasDisponibles();
    });

    fechaConsultaInput.addEventListener('change', cargarHorasDisponibles);

    restablecerBtn.addEventListener('click', () => {
        // Restaurar valores originales
        medicoSelect.value = consultaMedicoOriginal;
        fechaConsultaInput.value = consultaFechaOriginal;
        motivoInput.value = consultaMotivoOriginal;
        sintomasInput.value = consultaSintomasOriginal;

        actualizarEspecialidad();
        cargarHorasDisponibles();
    });

    actualizarEspecialidad();
    cargarHorasDisponibles();
});

document.querySelectorAll('.no-select').forEach(el => {
    el.addEventListener('contextmenu', e => e.preventDefault());
    el.addEventListener('keydown', e => e.preventDefault());
    el.addEventListener('copy', e => e.preventDefault());
    el.addEventListener('cut', e => e.preventDefault());
});
document.addEventListener('DOMContentLoaded', function () {
    const mensaje = document.getElementById('mensaje-exito');
    if (mensaje) {
        setTimeout(() => {
            // Si usas Bootstrap 5:
            const bsAlert = bootstrap.Alert.getOrCreateInstance(mensaje);
            bsAlert.close();

            // Si quieres solo ocultar sin animación, usa esta línea en vez de las dos anteriores:
            // mensaje.style.display = 'none';
        }, 6000);
    }
});


@endsection


