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
        padding-top: 40px; /* Espacio para navbar fija */
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
    min-height: 600px; /* NUEVO */
    padding-left: 20px;
    padding-right: 20px;
    padding-bottom: 2rem; /* NUEVO */
    margin-left: auto;
    margin-right: auto;
    position: relative;
    background-color: #fff;
    border-radius: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 0.075);
    overflow: hidden;
}

    input, select, textarea {
        max-width: 100%;
        box-sizing: border-box;
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
        <!-- Aquí va la info paciente -->
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

    @php
    $estado = strtolower($consulta->estado);

    $siguienteEstado = '';
    $claseBoton = '';
    $iconoBoton = '';
    $textoBoton = '';

    if ($estado === 'pendiente') {
        $siguienteEstado = 'cancelada';
        $claseBoton = 'btn-danger';
        $iconoBoton = 'bi-x-circle';
        $textoBoton = 'Cancelar';
    } elseif ($estado === 'cancelada') {
        $siguienteEstado = 'pendiente';
        $claseBoton = 'btn-warning';
        $iconoBoton = 'bi-clock-history';
        $textoBoton = 'Volver a Pendiente';
    }
    @endphp

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h5 class="text-dark fw-bold mb-0">Información de la consulta médica</h5>

       @if ($siguienteEstado)
    <form action="{{ route('consultas.cambiarEstado', $consulta->id) }}" method="POST" class="d-inline">
        @csrf
        @method('PATCH')
        <input type="hidden" name="estado" value="{{ $siguienteEstado }}">
        <button type="submit" class="btn {{ $claseBoton }} btn-sm">
            <i class="bi {{ $iconoBoton }}"></i> {{ $textoBoton }}
        </button>
    </form>
    @endif

    </div>

    <form action="{{ route('consultas.update', $consulta->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <input type="hidden" name="paciente_id" value="{{ $consulta->paciente_id }}">
        <input type="hidden" name="especialidad" value="{{ old('especialidad', $consulta->especialidad) }}">

        <div class="row g-3">
            <div class="col-md-2">
                <label for="fecha" class="@error('fecha') is-invalid @enderror">Fecha <span class="text-danger">*</span></label>
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
                <label for="medico" class="@error('medico_id') is-invalid @enderror">Médico que atiende <span class="text-danger">*</span></label>
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
                <label for="hora" class="@error('hora') is-invalid @enderror">Hora <span class="text-danger">*</span></label>
                <select id="hora" name="hora" class="form-select form-select-sm @error('hora') is-invalid @enderror" required>
                    {{-- Opciones las carga JS --}}
                </select>
                @error('hora')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mt-4">
                <label for="motivo" class="@error('motivo') is-invalid @enderror">Motivo de la consulta <span class="text-danger">*</span></label>
                <textarea name="motivo" maxlength="250" rows="2" required
                    class="form-control form-control-sm @error('motivo') is-invalid @enderror">{{ old('motivo', $consulta->motivo) }}</textarea>
                @error('motivo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mt-4">
                <label for="sintomas" class="@error('sintomas') is-invalid @enderror">Síntomas <span class="text-danger">*</span></label>
                <textarea name="sintomas" maxlength="250" rows="2" required
                    class="form-control form-control-sm @error('sintomas') is-invalid @enderror">{{ old('sintomas', $consulta->sintomas) }}</textarea>
                @error('sintomas')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-5 flex-wrap">
            <button type="submit" class="btn btn-primary d-flex align-items-center" title="Guardar los cambios">
                <i class="bi bi-pencil-square me-2"></i> Actualizar
            </button>
            <button type="button" id="restablecerBtn" class="btn btn-warning d-flex align-items-center" title="Restablecer los campos">
                <i class="bi bi-arrow-counterclockwise me-2"></i> Restablecer
            </button>
            <a href="{{ route('consultas.index') }}" class="btn btn-success d-flex align-items-center" title="Volver al listado">
                <i class="bi bi-arrow-left me-2"></i> Regresar
            </a>
        </div>
    </form>
</div>

@php
use Carbon\Carbon;

$horaFormateada = null;

if (!empty($consulta->hora)) {
    try {
        $horaFormateada = Carbon::createFromFormat('H:i:s', $consulta->hora)->format('g:i A');
    } catch (\Exception $e) {
        $horaFormateada = null; // O un texto como 'Hora inválida'
    }
} else {
    $horaFormateada = null; // Por ejemplo, para consultas "inmediatas"
}
@endphp


<script>
// Función para convertir hora 12h a 24h (ej: 2:30 PM -> 14:30:00)
function hora12a24(hora12) {
    if (hora12 === 'inmediata') return null;
    const [hora, minutoPeriodo] = hora12.split(':');
    const [minuto, periodo] = minutoPeriodo.split(' ');
    let h = parseInt(hora);
    if (periodo === 'PM' && h < 12) h += 12;
    if (periodo === 'AM' && h === 12) h = 0;
    return `${h.toString().padStart(2, '0')}:${minuto}:00`;
}

// Carga especialidad según médico seleccionado
function actualizarEspecialidad() {
    const selectMedico = document.getElementById('medico');
    const especialidadLabel = document.getElementById('especialidad');
    const medicoSeleccionado = selectMedico.options[selectMedico.selectedIndex];
    const especialidad = medicoSeleccionado ? medicoSeleccionado.getAttribute('data-especialidad') : '';
    especialidadLabel.textContent = especialidad || '';
}

// Carga las horas disponibles y marca las ocupadas
function cargarHorasDisponiblesEditar(horaActual) {
    const medico = document.getElementById('medico').value;
    const fecha = document.getElementById('fecha_consulta').value;
    const horaSelect = document.getElementById('hora');

    horaSelect.innerHTML = '';

    // Opción inicial deshabilitada
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Seleccione una hora';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    horaSelect.appendChild(defaultOption);

    const inmediataOption = document.createElement('option');
    inmediataOption.value = 'inmediata';
    inmediataOption.textContent = 'Inmediata';
    horaSelect.appendChild(inmediataOption);

    if (!medico || !fecha) return;

    const horas = [];
    let minutos = 8 * 60;
    const fin = (16 * 60) + 30;

    while (minutos <= fin) {
        const h = Math.floor(minutos / 60);
        const m = minutos % 60;
        const periodo = h >= 12 ? 'PM' : 'AM';
        const hora12 = (h % 12 === 0 ? 12 : h % 12);
        const minutoStr = m.toString().padStart(2, '0');
        horas.push(`${hora12}:${minutoStr} ${periodo}`);
        minutos += 30;
    }

    fetch(`/horas-ocupadas?medico_id=${encodeURIComponent(medico)}&fecha=${encodeURIComponent(fecha)}`)
        .then(res => res.json())
        .then(horasOcupadas => {
            horas.forEach(hora12 => {
                const hora24 = hora12a24(hora12);
                const option = document.createElement('option');
                option.value = hora12;
                option.textContent = hora12;

                if (horasOcupadas.includes(hora24) && hora12 !== horaActual) {
                    option.disabled = true;
                    option.textContent += ' (Ocupada)';
                }

                horaSelect.appendChild(option);
            });

            if (horaActual) {
                const optMatch = Array.from(horaSelect.options).find(opt => opt.value === horaActual);
                if (optMatch) {
                    optMatch.disabled = false;
                    optMatch.textContent = horaActual; 
                    optMatch.selected = true;
                }
            }
        })
        .catch(err => {
            console.error('Error cargando horas:', err);
            horas.forEach(hora12 => {
                const option = document.createElement('option');
                option.value = hora12;
                option.textContent = hora12;
                horaSelect.appendChild(option);
            });
        });
}

document.addEventListener('DOMContentLoaded', function() {
    actualizarEspecialidad();

 const horaConsulta = "{{ old('hora', $horaFormateada ?? '') }}";
// 👈 aquí insertamos la hora formateada
    cargarHorasDisponiblesEditar(horaConsulta);

    document.getElementById('medico').addEventListener('change', function() {
        actualizarEspecialidad();
        cargarHorasDisponiblesEditar(null);
    });

    document.getElementById('fecha_consulta').addEventListener('change', function() {
        cargarHorasDisponiblesEditar(null);
    });

    document.getElementById('restablecerBtn').addEventListener('click', function() {
        location.reload();
    });
});

document.querySelector('form').addEventListener('submit', function(e) {
    const motivo = this.motivo.value.trim();
    const sintomas = this.sintomas.value.trim();

    const regex = /^[a-zA-Z0-9\s.,áéíóúÁÉÍÓÚñÑ-]+$/;

    if (!regex.test(motivo)) {
        e.preventDefault();
        alert('El motivo solo puede contener letras, números, espacios, comas, puntos y guiones.');
        this.motivo.focus();
        return false;
    }

    if (!regex.test(sintomas)) {
        e.preventDefault();
        alert('Los síntomas solo pueden contener letras, números, espacios, comas, puntos y guiones.');
        this.sintomas.focus();
        return false;
    }
});
</script>


@endsection






