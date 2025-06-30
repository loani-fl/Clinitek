@extends('layouts.app')

@section('content')
<style>
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

    <form action="{{ route('consultas.update', $consulta->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3 px-2 mt-3">

            <!-- INFORMACIÓN DEL PACIENTE -->
            <h5 class="text-dark fw-bold mt-4 mb-3">Información del paciente</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="paciente_id">Paciente <span class="text-danger">*</span></label>
                    <select name="paciente_id" id="paciente_id" class="form-select form-select-sm @error('paciente_id') is-invalid @enderror" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($pacientes as $p)
                            <option value="{{ $p->id }}"
                                data-nacimiento="{{ \Carbon\Carbon::parse($p->fecha_nacimiento)->format('Y-m-d') }}"
                                data-identidad="{{ $p->identidad }}"
                                data-genero="{{ $p->genero }}"
                                data-telefono="{{ $p->telefono }}"
                                data-correo="{{ $p->correo }}"
                                data-direccion="{{ $p->direccion }}"
                                {{ (old('paciente_id', $consulta->paciente_id) == $p->id) ? 'selected' : '' }}>
                                {{ $p->nombre }} {{ $p->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    @error('paciente_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label>Identidad</label>
                    <input type="text" id="identidad" class="form-control form-control-sm" readonly value="{{ old('identidad', $consulta->paciente->identidad ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label>Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" class="form-control form-control-sm" readonly value="{{ old('fecha_nacimiento', $consulta->paciente->fecha_nacimiento ?? '') }}">
                </div>

                <div class="col-md-2">
                    <label for="genero">Género <span class="text-danger">*</span></label>
                    <input type="text" id="genero" name="genero" class="form-control form-control-sm @error('genero') is-invalid @enderror" value="{{ old('genero', $consulta->paciente->genero ?? '') }}" readonly required>
                    @error('genero')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label>Teléfono</label>
                    <input type="text" id="telefono" class="form-control form-control-sm" readonly value="{{ old('telefono', $consulta->paciente->telefono ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label>Correo electrónico</label>
                    <input type="email" id="correo" class="form-control form-control-sm" readonly value="{{ old('correo', $consulta->paciente->correo ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label>Dirección</label>
                    <input type="text" id="direccion" class="form-control form-control-sm" readonly value="{{ old('direccion', $consulta->paciente->direccion ?? '') }}">
                </div>
            </div>

            <!-- INFORMACIÓN DE LA CONSULTA MÉDICA -->
            <h5 class="text-dark fw-bold mt-4 mb-3">Información de la consulta médica</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="fecha_consulta">Fecha <span class="text-danger">*</span></label>
                    <input type="date" id="fecha_consulta" name="fecha" class="form-control form-control-sm @error('fecha') is-invalid @enderror"
                        value="{{ old('fecha', $consulta->fecha) }}" min="{{ now()->format('Y-m-d') }}" required>
                    @error('fecha')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="hora">Hora <span class="text-danger">*</span></label>
                    <select id="hora" name="hora" class="form-select form-select-sm @error('hora') is-invalid @enderror" required>
                        <option value="">-- Selecciona hora --</option>
                        {{-- Opciones generadas dinámicamente con JS --}}
                    </select>
                    @error('hora')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="especialidad">Especialidad <span class="text-danger">*</span></label>
                    <input type="text" id="especialidad" name="especialidad" class="form-control form-control-sm @error('especialidad') is-invalid @enderror" readonly value="{{ old('especialidad', $consulta->medico->especialidad ?? '') }}" required>
                    @error('especialidad')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="medico">Médico que atiende <span class="text-danger">*</span></label>
                    <select name="medico_id" id="medico" class="form-select form-select-sm @error('medico_id') is-invalid @enderror" required>
                        <option value="">-- Médico que atiende --</option>
                        @foreach($medicos as $m)
                            <option value="{{ $m->id }}" data-nombre="{{ $m->nombre }}" data-especialidad="{{ $m->especialidad }}"
                                {{ (old('medico_id', $consulta->medico_id) == $m->id) ? 'selected' : '' }}>
                                {{ $m->nombre }} {{ $m->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    @error('medico_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <label for="motivo">Motivo de la consulta <span class="text-danger">*</span></label>
                <textarea name="motivo" rows="2" class="form-control form-control-sm @error('motivo') is-invalid @enderror" required>{{ old('motivo', $consulta->motivo) }}</textarea>
                @error('motivo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="sintomas">Síntomas <span class="text-danger">*</span></label>
                <textarea name="sintomas" rows="2" class="form-control form-control-sm @error('sintomas') is-invalid @enderror" required>{{ old('sintomas', $consulta->sintomas) }}</textarea>
                @error('sintomas')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div id="contenedor_total_pagar" style="display:none;">
                <div class="col-md-2">
                    <label for="total_pagar">Total a pagar <span id="total_asterisco" class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">L.</span>
                        <input type="number" step="0.01" min="0" id="total_pagar" name="total_pagar" class="form-control @error('total_pagar') is-invalid @enderror" value="{{ old('total_pagar', $consulta->total_pagar) }}" readonly required>
                    </div>
                    @error('total_pagar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Botones centrados -->
<div class="d-flex justify-content-center gap-3 mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-pencil-square"></i> Actualizar
    </button>

    <button type="reset" class="btn btn-warning px-4 shadow-sm">
        <i class="bi bi-arrow-counterclockwise"></i> Restablecer
    </button>

    <a href="{{ route('consultas.index') }}" class="btn btn-success">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>

        </div>
    </form>
</div>


<!-- Flatpickr JS (opcional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
window.addEventListener('load', function () {
    const pacienteSelect = document.getElementById('paciente_id');
    const generoInput = document.getElementById('genero');
    const medicoSelect = document.getElementById('medico');
    const especialidadInput = document.getElementById('especialidad');
    const fechaConsultaInput = document.getElementById('fecha_consulta');
    const horaSelect = document.getElementById('hora');
    const totalPagarInput = document.getElementById('total_pagar');
    const contenedorTotalPagar = document.getElementById('contenedor_total_pagar');
    const labelHora = document.getElementById('label_hora');

    const preciosPorEspecialidad = {
        "Cardiología": 900.00,
        "Pediatría": 500.00,
        "Dermatología": 900.00,
        "Medicina General": 800.00,
        "Psiquiatría": 500.00,
        "Neurología": 1000.00,
        "Radiología": 700.00
    };

    const horaPrev = @json(old('hora', \Carbon\Carbon::parse($consulta->hora)->format('g:i A')));

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

        if (!medico || !fecha) return;

        horaSelect.innerHTML = '';
        const defaultOption = new Option('-- Selecciona hora --', '');
        horaSelect.appendChild(defaultOption);

        const inmediataOption = new Option('Inmediata', 'inmediata');

        // ✅ Si Laravel indicó que inmediata está ocupada, lo marcamos
        if (typeof inmediataOcupada !== 'undefined' && inmediataOcupada) {
            inmediataOption.text = 'Inmediata (Ocupada)';
            inmediataOption.disabled = true;
        }

        horaSelect.appendChild(inmediataOption);

        const horas = [];
        let minutos = 8 * 60;
        const fin = 17 * 60;

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
                    const option = new Option(hora12, hora12);

                    if (horasOcupadas.includes(hora24)) {
                        option.text = hora12 + ' (Ocupada)';
                        if (hora12 !== horaPrev) {
                            option.disabled = true;
                        }
                    }

                    horaSelect.appendChild(option);
                });

                if (horaPrev) {
                    const match = Array.from(horaSelect.options).find(op => op.value === horaPrev);
                    if (match) match.selected = true;
                }

                actualizarVisibilidadTotalPagar();
            })
            .catch(err => {
                console.error('Error cargando horas:', err);
                horas.forEach(hora12 => {
                    const option = new Option(hora12, hora12);
                    horaSelect.appendChild(option);
                });
                actualizarVisibilidadTotalPagar();
            });
    }

    function actualizarVisibilidadTotalPagar() {
        const horaSeleccionada = horaSelect.value;

        if (horaSeleccionada === 'inmediata') {
            contenedorTotalPagar.style.display = 'block';

            if (labelHora) {
                labelHora.textContent = 'Hora (Inmediata Ocupada) 🚨';
                labelHora.style.color = '#d9534f';
                labelHora.style.fontWeight = 'bold';
            }

            const selectedMedico = medicoSelect.options[medicoSelect.selectedIndex];
            const especialidad = selectedMedico ? selectedMedico.getAttribute('data-especialidad') : '';

            if (especialidad && preciosPorEspecialidad[especialidad]) {
                totalPagarInput.value = preciosPorEspecialidad[especialidad].toFixed(2);
            } else {
                totalPagarInput.value = '';
            }
        } else {
            contenedorTotalPagar.style.display = 'none';
            totalPagarInput.value = '';

            if (labelHora) {
                labelHora.textContent = 'Hora';
                labelHora.style.color = '';
                labelHora.style.fontWeight = '';
            }
        }
    }

    function autocompletarPaciente() {
        const opt = pacienteSelect.options[pacienteSelect.selectedIndex];
        if (!opt) return;
        document.getElementById('fecha_nacimiento').value = opt.getAttribute('data-nacimiento') || '';
        document.getElementById('identidad').value = opt.getAttribute('data-identidad') || '';
        document.getElementById('telefono').value = opt.getAttribute('data-telefono') || '';
        document.getElementById('correo').value = opt.getAttribute('data-correo') || '';
        document.getElementById('direccion').value = opt.getAttribute('data-direccion') || '';
        generoInput.value = opt.getAttribute('data-genero') || '';
    }

    medicoSelect.addEventListener('change', () => {
        const especialidad = medicoSelect.selectedOptions[0].getAttribute('data-especialidad') || '';
        especialidadInput.value = especialidad;
        if (especialidad && preciosPorEspecialidad[especialidad]) {
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
    pacienteSelect.addEventListener('change', autocompletarPaciente);

    if (pacienteSelect.value) autocompletarPaciente();
    if (medicoSelect.value && fechaConsultaInput.value) {
        cargarHorasDisponibles();
    } else {
        actualizarVisibilidadTotalPagar();
    }

    const formulario = horaSelect.closest("form");

    const camposControlados = [
        pacienteSelect,
        generoInput,
        medicoSelect,
        especialidadInput,
        fechaConsultaInput,
        horaSelect,
        totalPagarInput
    ];

    const estadoCampos = {};

    camposControlados.forEach(campo => {
        estadoCampos[campo.id] = {
            valorInicial: campo.value,
            modificado: false
        };
        campo.addEventListener('change', () => {
            estadoCampos[campo.id].modificado = true;
        });
    });

    formulario.addEventListener('reset', () => {
        setTimeout(() => {
            camposControlados.forEach(campo => {
                const estado = estadoCampos[campo.id];
                if (!estado.modificado) {
                    campo.value = estado.valorInicial;
                    if (campo.tagName.toLowerCase() === 'select') {
                        campo.dispatchEvent(new Event('change'));
                    }
                } else {
                    estado.valorInicial = campo.value;
                }
                estado.modificado = false;
            });
        }, 50);
    });
});
</script>
@endsection