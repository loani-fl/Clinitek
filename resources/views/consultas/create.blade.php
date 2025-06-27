@extends('layouts.app')

@section('content')
<style>
    html, body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
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
        box-sizing: border-box;
    }

    /* Estilo para tablas y contenido que puede ser ancho */
    .table-responsive {
        overflow-x: auto;
        max-width: 100%;
    }

    /* Ajusta inputs, selects, textareas */
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

    /* Padding más reducido */
    .custom-card {
        padding-left: 20px;
        padding-right: 20px;
        max-width: 1000px;
        width: 100%;
    }

    form {
        padding-left: 10px;
        padding-right: 10px;
    }

    footer {
        position: fixed;
        bottom: 0;
        width: 100vw;
        height: 35px;
        background-color: #f8f9fa;
        padding: 10px 0;
        text-align: center;
        font-size: 0.9rem;
        color: #6c757d;
        z-index: 999;
        border-top: 1px solid #dee2e6;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 800px; /* tamaño más pequeño */
        height: 800px; /* tamaño más pequeño */
        background-image: url('/images/logo2.jpg');
        background-size: contain;  /* ajusta sin recortar */
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.15;  /* transparencia baja para que no moleste */
        transform: translate(-50%, -50%);
        pointer-events: none; /* para que no interfiera con clicks */
        z-index: 0;
    }
</style>

<!-- Barra de navegación fija -->
<div class="header d-flex justify-content-between align-items-center px-3 py-2" style="background-color: #007BFF; position: sticky; top: 0; z-index: 1030;">
    <div class="d-flex align-items-center">
        <!-- Logo con margen reducido para acercar el texto -->
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" 
             style="height: 40px; width: auto; margin-right: 6px;">

        <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
    </div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>
</div>

<!-- Formulario más compacto -->
<div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 30px;">
    <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
        <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Registro de consulta médica</h5>
    </div>
    <form action="{{ route('consultas.store') }}" method="POST" novalidate>
        @csrf

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
                                {{ old('paciente_id') == $p->id ? 'selected' : '' }}>
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
                    <input type="text" id="identidad" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-3">
                    <label>Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" class="form-control form-control-sm" readonly>
                </div>

                <div class="col-md-2">
                    <label for="genero">Genero <span class="text-danger">*</span></label>
                    <select id="genero" name="genero" class="form-select form-select-sm @error('genero') is-invalid @enderror" required>
                        <option value="">-- Selecciona --</option>
                        <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    </select>
                    @error('genero')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
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

            <!-- INFORMACIÓN DE LA CONSULTA MÉDICA -->
            <h5 class="text-dark fw-bold mt-4 mb-3">Información de la consulta médica</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="fecha_consulta">Fecha <span class="text-danger">*</span></label>
                    <input type="date" id="fecha_consulta" name="fecha" class="form-control form-control-sm @error('fecha') is-invalid @enderror"
                        value="{{ old('fecha', now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required>
                    @error('fecha')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label for="hora">Hora <span class="text-danger">*</span></label>
                    <select id="hora" name="hora" class="form-select form-select-sm @error('hora') is-invalid @enderror" required>
                        <option value="">-- Selecciona hora --</option>
                        {{-- Opciones serán generadas dinámicamente con JS --}}
                    </select>
                    @error('hora')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="especialidad">Especialidad <span class="text-danger">*</span></label>
                    <input type="text" id="especialidad" name="especialidad" class="form-control form-control-sm @error('especialidad') is-invalid @enderror" readonly value="{{ old('especialidad') }}" required>
                    @error('especialidad')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="medico">Médico que atiende <span class="text-danger">*</span></label>
                    <select name="medico_id" id="medico" class="form-select form-select-sm @error('medico_id') is-invalid @enderror" required>
                        <option value="">-- Médico que atiende --</option>
                        @foreach($medicos as $m)
                            <option 
                                value="{{ $m->id }}" 
                                data-nombre="{{ $m->nombre }}"
                                data-especialidad="{{ $m->especialidad }}"
                                {{ old('medico_id') == $m->id ? 'selected' : '' }}
                            >
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
                <textarea name="motivo" rows="2" class="form-control form-control-sm @error('motivo') is-invalid @enderror" required>{{ old('motivo') }}</textarea>
                @error('motivo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="sintomas">Síntomas <span class="text-danger">*</span></label>
                <textarea name="sintomas" rows="2" class="form-control form-control-sm @error('sintomas') is-invalid @enderror" required>{{ old('sintomas') }}</textarea>
                @error('sintomas')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div id="contenedor_total_pagar" style="display:none;">
    <div class="col-md-2">
        <label for="total_pagar">Total a pagar <span id="total_asterisco" class="text-danger">*</span></label>
        <div class="input-group input-group-sm">
            <span class="input-group-text">L.</span>
            <input 
                type="number" 
                step="0.01" 
                min="0" 
                id="total_pagar" 
                name="total_pagar" 
                class="form-control @error('total_pagar') is-invalid @enderror" 
                value="{{ old('total_pagar') }}" 
                readonly
                required
            >
        </div>
        @error('total_pagar')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>



            <!-- Botones centrados -->
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Registrar
                </button>
                <button type="button" id="btnLimpiar" class="btn btn-warning">
                    <i class="bi bi-trash"></i> Limpiar
                </button>
                <a href="{{ route('consultas.index') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Footer fijo -->
<footer>
    © 2025 Clínitek. Todos los derechos reservados.
</footer>

<!-- Flatpickr JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const pacienteSelect = document.getElementById('paciente_id');
    const generoSelect = document.getElementById('genero');
    const medicoSelect = document.getElementById('medico');
    const especialidadInput = document.getElementById('especialidad');
    const fechaConsultaInput = document.getElementById('fecha_consulta');
    const horaSelect = document.getElementById('hora');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const form = document.querySelector('form');
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
            // Actualizar total sólo si la consulta es inmediata
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

        horaSelect.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '-- Selecciona hora --';
        horaSelect.appendChild(defaultOption);

        const inmediataOption = document.createElement('option');
        inmediataOption.value = 'inmediata';
        inmediataOption.textContent = 'Inmediata';
        horaSelect.appendChild(inmediataOption);

        if (!medico || !fecha) return;

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
                    const option = document.createElement('option');
                    option.value = hora12;
                    option.textContent = hora12;
                    if (horasOcupadas.includes(hora24)) {
                        option.disabled = true;
                        option.textContent += ' (Ocupada)';
                    }
                    horaSelect.appendChild(option);
                });

                const horaPrev = "{{ old('hora') }}";
                if (horaPrev) {
                    const optMatch = Array.from(horaSelect.options).find(opt => opt.value === horaPrev);
                    if (optMatch && !optMatch.disabled) horaSelect.value = horaPrev;
                }

                actualizarVisibilidadTotalPagar(); // actualizar al cargar horas disponibles
            })
            .catch(err => {
                console.error('Error cargando horas:', err);
                horas.forEach(hora12 => {
                    const option = document.createElement('option');
                    option.value = hora12;
                    option.textContent = hora12;
                    horaSelect.appendChild(option);
                });
                actualizarVisibilidadTotalPagar(); // actualizar al cargar horas disponibles
            });
    }

    if (medicoSelect.value) {
        const selected = medicoSelect.options[medicoSelect.selectedIndex];
        const especialidad = selected.getAttribute('data-especialidad') || '';
        especialidadInput.value = especialidad;

        if (especialidad && preciosPorEspecialidad.hasOwnProperty(especialidad)) {
            totalPagarInput.value = preciosPorEspecialidad[especialidad].toFixed(2);
        }
    }


    btnLimpiar.addEventListener('click', function (e) {
    e.preventDefault(); // Evita el comportamiento por defecto si el botón es de tipo submit

    // 🔁 Reset del formulario
    form.reset();

    // 🔁 Limpiar manualmente valores (por si reset no afecta algunos campos)
    pacienteSelect.value = '';
    medicoSelect.value = '';
    horaSelect.innerHTML = `
        <option value="">-- Selecciona hora --</option>
        <option value="inmediata">Inmediata</option>
    `;
    especialidadInput.value = '';
    totalPagarInput.value = '';
    
    // 🔁 Limpiar campos autocompletados
    document.getElementById('identidad').value = '';
    document.getElementById('fecha_nacimiento').value = '';
    document.getElementById('telefono').value = '';
    document.getElementById('correo').value = '';
    document.getElementById('direccion').value = '';
    document.getElementById('genero').value = ''; // ← importante: campo hidden de género

    // 🔁 Ocultar campos dinámicos
    contenedorTotalPagar.style.display = 'none';

    // 🔁 Quitar clases de validación (visual)
    form.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
        el.classList.remove('is-invalid', 'is-valid');
    });

    // 🔁 Eliminar todos los mensajes de error generados (invalid-feedback)
    form.querySelectorAll('.invalid-feedback').forEach(feedback => {
        feedback.remove();
    });

    // 🔁 También puedes limpiar los textos en .text-danger si usaste etiquetas <span>
    form.querySelectorAll('.text-danger').forEach(span => {
        span.textContent = '';
    });
});
    // Inicializa visibilidad al cargar la página
    actualizarVisibilidadTotalPagar();
});
</script>