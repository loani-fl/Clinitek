@extends('layouts.app')

@section('content')

<style>
.custom-card {
    max-width: 1200px;
    background-color: rgba(255, 255, 255, 0.95);
    border: 1px solid #91cfff;
    border-radius: 0.5rem;
    position: relative;
    overflow: hidden;
    margin: 2rem auto;
    padding: 2rem;
    box-shadow: 0 0 15px rgba(0,123,255,0.25);
    z-index: 1;
    min-height: 650px;
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

input.is-invalid, textarea.is-invalid, select.is-invalid {
    border-color: red;
}

.invalid-feedback {
    display: block;
}

.alert-custom {
    padding: 0.75rem;
    border-radius: 0.25rem;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.lista-resultados {
    position: absolute;
    z-index: 1000;
    background: white;
    border: 1px solid #ced4da;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    max-height: 280px;
    overflow-y: auto;
    width: 100%;
    margin-top: 0;
}

.resultado-item {
    padding: 0.875rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f1f3f5;
    transition: all 0.2s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.resultado-item:hover {
    background: linear-gradient(to right, #e3f2fd, #f8f9fa);
    border-left: 3px solid #007BFF;
    padding-left: calc(1rem - 3px);
}

.resultado-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.375rem 0.375rem;
}

.resultado-info {
    flex-grow: 1;
}

.resultado-nombre {
    color: #2c3e50;
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.resultado-identidad {
    font-size: 0.875rem;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.resultado-icono {
    color: #007BFF;
    font-size: 1.25rem;
}

.no-resultados {
    padding: 1.5rem;
    text-align: center;
    color: #6c757d;
    font-style: italic;
}

.form-control-lg {
    height: calc(3rem + 2px);
    font-size: 1rem;
    border: 2px solid #ced4da;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #007BFF;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
}

.form-control:focus {
    border-color: #007BFF;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
}

#fecha_nacimiento {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-align: center;
    letter-spacing: 1px;
}

.row.mb-3 .col-md-3 {
    min-width: 0;
}

/* Radios Documentado / Indocumentado */
.mb-4.d-flex.gap-3 label {
    font-size: 1.3rem;
}

/* Título Datos básicos y contacto */
.docFields h5.mb-3.text-dark.fw-bold {
    font-size: 1.4rem;
}

/* Etiquetas de los campos */
.docFields label {
    font-size: 1.1rem;
}

/* Títulos Motivo de la emergencia y Signos Vitales */
h5.mt-4.mb-3.text-dark.fw-bold {
    font-size: 1.35rem;
}

/* Inputs, selects y textareas */
.docFields input.form-control,
.docFields select.form-select,
.docFields textarea.form-control {
    font-size: 1.05rem;
}

#buscarIdentidad {
    font-size: 1rem;
    height: 42px;
}

.alert-custom,
.lista-resultados .resultado-item,
.lista-resultados .no-resultados {
    font-size: 1rem;
}
</style>


<div class="custom-card">
    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Registro de Emergencia</h2>
    </div>

    @php
        $docFieldsVisible = old('documentado', 1) == 1;
        $fechaActual = old('fecha', date('Y-m-d'));
        $horaActual = old('hora', date('H:i:s'));
    @endphp

    <form id="formEmergencia" action="{{ route('emergencias.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Radio Documentado / Indocumentado --}}
        <div class="mb-4 d-flex gap-3">
            <label>
                <input type="radio" name="documentado" value="1" {{ $docFieldsVisible ? 'checked' : '' }} onclick="toggleDocs()"> Documentado
            </label>
            <label>
                <input type="radio" name="documentado" value="0" {{ !$docFieldsVisible ? 'checked' : '' }} onclick="toggleDocs()"> Indocumentado
            </label>
        </div>

        {{-- Campos Documentado --}}
        <div class="docFields" style="display: {{ $docFieldsVisible ? 'block' : 'none' }};">
            
            <h5 class="mb-3 text-dark fw-bold">
                <i class="fas fa-user-circle"></i> Datos básicos y contacto
            </h5>

            {{-- Buscador de pacientes --}}
            <div class="d-flex align-items-center mb-4" style="gap: 1.25rem;">
                <label class="fw-bold mb-0" style="white-space: nowrap; font-size: 0.95rem;">
                    <i class="fas fa-search"></i> BUSCAR PACIENTE
                </label>
                <div style="position: relative; flex-grow: 1; max-width: 250px;">
                    <input 
                        type="text" 
                        id="buscarIdentidad" 
                        class="form-control" 
                        placeholder="Buscar por identidad..."
                        maxlength="13"
                        autocomplete="off"
                        style="padding-right: 40px; height: 38px; font-size: 0.9rem;">
                    <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                    <div id="listaResultados" class="lista-resultados" style="display: none;"></div>
                </div>
            </div>
            <div id="mensajeBusqueda" class="mt-1"></div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Nombre(s): <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="nombres" 
                        id="nombres"
                        maxlength="50"
                        class="form-control @error('nombres') is-invalid @enderror" 
                        value="{{ old('nombres') }}">
                    @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label>Apellidos: <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="apellidos" 
                        id="apellidos"
                        maxlength="50"
                        class="form-control @error('apellidos') is-invalid @enderror" 
                        value="{{ old('apellidos') }}">
                    @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label>Identidad: <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="identidad" 
                        id="identidad"
                        maxlength="13"
                        placeholder="Ej: 0703200201564"
                        class="form-control @error('identidad') is-invalid @enderror" 
                        value="{{ old('identidad') }}">
                    @error('identidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label>Fecha de Nacimiento: <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="fecha_nacimiento" 
                        id="fecha_nacimiento"
                        maxlength="10" 
                        placeholder="dd/mm/aaaa"
                        class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                        value="{{ old('fecha_nacimiento') }}">
                    @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Edad: <span class="text-danger">*</span></label>
                    <input 
                        type="number" 
                        name="edad" 
                        id="edadDocumentado" 
                        min="0" 
                        max="105"
                        class="form-control @error('edad') is-invalid @enderror"
                        value="{{ old('edad') }}">
                    @error('edad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label>Teléfono: <span class="text-danger">*</span></label>
                    <input type="text" name="telefono" id="telefono" maxlength="8" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label>Tipo de Sangre (opcional):</label>
                    <select name="tipo_sangre" id="tipo_sangre" class="form-select @error('tipo_sangre') is-invalid @enderror">
                        <option value="">Seleccione...</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_sangre')==$tipo?'selected':'' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_sangre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label>Género: <span class="text-danger">*</span></label>
                    <select name="genero" id="genero" class="form-select @error('genero') is-invalid @enderror">
                        <option value="">Seleccione...</option>
                        <option value="Femenino" {{ old('genero')=='Femenino'?'selected':'' }}>Femenino</option>
                        <option value="Masculino" {{ old('genero')=='Masculino'?'selected':'' }}>Masculino</option>
                        <option value="Otro" {{ old('genero')=='Otro'?'selected':'' }}>Otro</option>
                    </select>
                    @error('genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label>Dirección: <span class="text-danger">*</span></label>
                    <textarea name="direccion" id="direccion" rows="2" maxlength="300" class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion') }}</textarea>
                    @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Campos Indocumentado --}}
        <div class="indocFields" style="display: {{ !$docFieldsVisible ? 'block' : 'none' }};">
            <div class="row mb-3 align-items-end">
                <div class="col-md-6">
                    <label>Foto del paciente: <span class="text-danger">*</span></label>
                    <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror">
                    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Motivo de la emergencia (Indocumentado) --}}
            <h5 class="mt-4 mb-3 text-dark fw-bold">Motivo de la emergencia</h5>
            <div class="row mb-3 align-items-end">
                <div class="col-md-6">
                    <textarea name="motivo" id="motivoIndoc" rows="2" maxlength="300"
                        placeholder="Describa el motivo de la emergencia..."
                        class="form-control @error('motivo') is-invalid @enderror">{{ old('motivo') }}</textarea>
                    @error('motivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha:</label>
                    <input type="date" name="fecha" id="fechaIndoc" class="form-control" value="{{ $fechaActual }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Hora:</label>
                    <input type="time" name="hora" id="horaIndoc" class="form-control" value="{{ $horaActual }}">
                </div>
            </div>
        </div>

        {{-- Motivo de la emergencia (Documentado) --}}
        <div class="docFields" style="display: {{ $docFieldsVisible ? 'block' : 'none' }};">
            <h5 class="mt-4 mb-3 text-dark fw-bold">Motivo de la emergencia</h5>
            <div class="row mb-3 align-items-end">
                <div class="col-md-6">
                    <textarea name="motivo" id="motivoDoc" rows="2" maxlength="300"
                        placeholder="Describa el motivo de la emergencia..."
                        class="form-control @error('motivo') is-invalid @enderror">{{ old('motivo') }}</textarea>
                    @error('motivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label">Fecha:</label>
                    <input type="date" name="fecha" id="fechaDoc" class="form-control" value="{{ $fechaActual }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Hora:</label>
                    <input type="time" name="hora" id="horaDoc" class="form-control" value="{{ $horaActual }}">
                </div>
            </div>
        </div>

        {{-- Signos vitales --}}
        <h5 class="mt-4 mb-3 text-dark fw-bold">Signos Vitales</h5>
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Presión Arterial: <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    name="pa" 
                    id="pa"
                    placeholder="Ej: 120/80"
                    class="form-control @error('pa') is-invalid @enderror" 
                    value="{{ old('pa') }}"
                    maxlength="7">
                <small class="text-muted">Rango: 40-250 / 20-150 mmHg</small>
                @error('pa') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label>Frecuencia Cardíaca (lpm): <span class="text-danger">*</span></label>
                <input 
                    type="number" 
                    name="fc" 
                    id="fc"
                    placeholder="Ej: 75"
                    class="form-control @error('fc') is-invalid @enderror" 
                    value="{{ old('fc') }}">
                <small class="text-muted">Rango: 20-250 lpm</small>
                @error('fc') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label>Temperatura (°C): <span class="text-danger">*</span></label>
                <input 
                    type="number" 
                    name="temp" 
                    id="temp"
                    placeholder="Ej: 36.5"
                    class="form-control @error('temp') is-invalid @enderror" 
                    value="{{ old('temp') }}"
                    step="0.1">
                <small class="text-muted">Rango: 30-45 °C</small>
                @error('temp') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">Registrar</button>
            <button type="button" id="btnLimpiar" class="btn btn-warning">Limpiar</button>
            <a href="{{ route('emergencias.index') }}" class="btn btn-success">Regresar</a>
        </div>
    </form>
</div>

<script>
// ========== TOGGLE DOCUMENTADO/INDOCUMENTADO ==========
function toggleDocs(){
    let doc = document.querySelector('input[name="documentado"][value="1"]').checked;

    document.querySelectorAll('.docFields').forEach(el => {
        el.style.display = doc ? 'block' : 'none';
        el.querySelectorAll('input, select, textarea').forEach(input => input.disabled = !doc);
    });

    document.querySelectorAll('.indocFields').forEach(el => {
        el.style.display = doc ? 'none' : 'block';
        el.querySelectorAll('input, select, textarea').forEach(input => input.disabled = doc);
        if(doc) resetIndocFields(el);
    });
}

function resetIndocFields(container) {
    container.querySelectorAll('input, select, textarea').forEach(input => {
        if(input.name === 'fecha' || input.name === 'hora') return;
        if(input.type === 'file') {
            input.value = '';
        } else if(input.tagName.toLowerCase() === 'select') {
            input.selectedIndex = 0;
        } else {
            input.value = '';
        }
    });
}

// ========== VALIDACIONES DE SIGNOS VITALES ==========

// Validación y formato automático de Presión Arterial
document.getElementById('pa').addEventListener('input', function(e) {
    let valor = this.value.replace(/[^0-9]/g, ''); // Solo números
    
    // Limitar longitud total
    if (valor.length > 6) {
        valor = valor.substring(0, 6);
    }
    
    // Insertar barra automáticamente después de 2 o 3 dígitos
    if (valor.length >= 4) {
        // Si hay 4 o más dígitos, insertar la barra
        // Intentar primero con 3 dígitos antes de la barra
        if (valor.length >= 4) {
            let sistolica = valor.substring(0, 3);
            let diastolica = valor.substring(3);
            
            // Verificar si la sistólica con 3 dígitos es válida (<=250)
            if (parseInt(sistolica) > 250) {
                // Si es mayor a 250, usar solo 2 dígitos
                sistolica = valor.substring(0, 2);
                diastolica = valor.substring(2);
            }
            
            valor = sistolica + '/' + diastolica;
        }
    }
    
    this.value = valor;
    validarPresionArterial(this.value);
});

function validarPresionArterial(valor) {
    const paInput = document.getElementById('pa');
    let feedback = paInput.parentElement.querySelector('.invalid-feedback:not(.d-block)');
    
    if (!valor) return true;
    
    const partes = valor.split('/');
    if (partes.length === 2 && partes[0] && partes[1]) {
        const sistolica = parseInt(partes[0]);
        const diastolica = parseInt(partes[1]);
        
        let errorMsg = '';
        
        if (sistolica < 40 || sistolica > 250) {
            errorMsg = 'Presión sistólica fuera de rango (40-250 mmHg)';
        } else if (diastolica < 20 || diastolica > 150) {
            errorMsg = 'Presión diastólica fuera de rango (20-150 mmHg)';
        } else if (sistolica <= diastolica) {
            errorMsg = 'La presión sistólica debe ser mayor que la diastólica';
        }
        
        if (errorMsg) {
            paInput.classList.add('is-invalid');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                paInput.parentElement.appendChild(feedback);
            }
            feedback.textContent = errorMsg;
            return false;
        } else {
            paInput.classList.remove('is-invalid');
            if (feedback && !feedback.classList.contains('d-block')) {
                feedback.remove();
            }
            return true;
        }
    }
    
    return true;
}

// Validación de Frecuencia Cardíaca
document.getElementById('fc').addEventListener('input', function(e) {
    let valor = this.value.replace(/[^0-9]/g, '');
    
    if (valor.length > 3) {
        valor = valor.substring(0, 3);
    }
    
    this.value = valor;
    if(valor) validarFrecuenciaCardiaca(parseInt(valor));
});

function validarFrecuenciaCardiaca(valor) {
    const fcInput = document.getElementById('fc');
    let feedback = fcInput.parentElement.querySelector('.invalid-feedback:not(.d-block)');
    
    if (!valor) return true;
    
    if (valor < 20 || valor > 250) {
        fcInput.classList.add('is-invalid');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            fcInput.parentElement.appendChild(feedback);
        }
        feedback.textContent = 'Frecuencia cardíaca fuera de rango (20-250 lpm)';
        return false;
    }
    
    fcInput.classList.remove('is-invalid');
    if (feedback && !feedback.classList.contains('d-block')) {
        feedback.remove();
    }
    return true;
}

// Validación de Temperatura
document.getElementById('temp').addEventListener('input', function(e) {
    let valor = this.value.replace(/[^0-9.]/g, ''); // Solo números y punto

    // Si el usuario está escribiendo y NO hay punto
    if (!valor.includes('.')) {
        // Si tiene más de 2 dígitos, insertar punto automáticamente
        if (valor.length > 2) {
            valor = valor.substring(0, 2) + '.' + valor.substring(2, 3);
        }
    } else {
        // Si ya hay punto, procesar las partes
        const partes = valor.split('.');
        
        // Limitar parte entera a 2 dígitos
        if (partes[0].length > 2) {
            partes[0] = partes[0].substring(0, 2);
        }
        
        // Limitar parte decimal a 1 dígito
        if (partes[1] && partes[1].length > 1) {
            partes[1] = partes[1].substring(0, 1);
        }
        
        valor = partes[0] + '.' + (partes[1] || '');
    }

    this.value = valor;

    if (valor) validarTemperatura(parseFloat(valor));
});

function validarTemperatura(valor) {
    const tempInput = document.getElementById('temp');
    let feedback = tempInput.parentElement.querySelector('.invalid-feedback:not(.d-block)');

    if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        tempInput.parentElement.appendChild(feedback);
    }

    if (isNaN(valor) || valor < 30 || valor > 45) {
        tempInput.classList.add('is-invalid');
        feedback.textContent = 'La temperatura debe estar entre 30°C y 45°C';
        feedback.style.display = 'block';
        return false;
    } else {
        tempInput.classList.remove('is-invalid');
        feedback.textContent = '';
        feedback.style.display = 'none';
        return true;
    }
}

// Limitar edad a 3 dígitos y validar rango 0-105
document.getElementById('edadDocumentado').addEventListener('input', function(e) {
    let valor = this.value.replace(/\D/g, '');
    
    if (valor.length > 3) {
        valor = valor.substring(0, 3);
    }
    
    this.value = valor;
    
    if (valor) {
        validarEdad(parseInt(valor));
    } else {
        this.classList.remove('is-invalid');
        const feedback = this.parentElement.querySelector('.invalid-feedback:not(.d-block)');
        if (feedback) feedback.remove();
    }
});

function validarEdad(valor) {
    const edadInput = document.getElementById('edadDocumentado');
    let feedback = edadInput.parentElement.querySelector('.invalid-feedback:not(.d-block)');
    
    if (valor < 0 || valor > 105) {
        edadInput.classList.add('is-invalid');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            edadInput.parentElement.appendChild(feedback);
        }
        feedback.textContent = 'La edad debe estar entre 0 y 105 años';
        feedback.style.display = 'block';
        return false;
    } else {
        edadInput.classList.remove('is-invalid');
        if (feedback) {
            feedback.remove();
        }
        return true;
    }
}

// ========== LIMPIAR FORMULARIO COMPLETO ==========
document.getElementById('btnLimpiar').addEventListener('click', () => {
    const form = document.getElementById('formEmergencia');
    
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    
    document.getElementById('buscarIdentidad').value = '';
    document.getElementById('listaResultados').style.display = 'none';
    document.getElementById('listaResultados').innerHTML = '';
    document.getElementById('mensajeBusqueda').innerHTML = '';
    
    limpiarCamposPaciente();
    
    document.querySelector('input[name="documentado"][value="1"]').checked = true;
    toggleDocs();
});

// ========== BÚSQUEDA DE PACIENTES ==========
function limpiarCamposPaciente() {
    const campos = ['nombres','apellidos','identidad','fecha_nacimiento','edadDocumentado','telefono','tipo_sangre','genero','direccion'];
    campos.forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            if(el.tagName.toLowerCase() === 'select') el.selectedIndex = 0;
            else el.value = '';
            
            el.removeAttribute('readonly');
            el.style.backgroundColor = '';
            el.style.cursor = '';
            el.style.userSelect = '';
        }
    });
}

let timeoutBusqueda = null;

document.getElementById('buscarIdentidad').addEventListener('input', function() {
    const identidad = this.value.trim();
    const listaDiv = document.getElementById('listaResultados');
    const mensajeDiv = document.getElementById('mensajeBusqueda');

    clearTimeout(timeoutBusqueda);

    if (!identidad) {
        listaDiv.style.display = 'none';
        listaDiv.innerHTML = '';
        mensajeDiv.innerHTML = '';
        limpiarCamposPaciente();
        return;
    }

    timeoutBusqueda = setTimeout(() => {
        buscarPacientes(identidad);
    }, 300);
});

function buscarPacientes(identidad) {
    const listaDiv = document.getElementById('listaResultados');
    const mensajeDiv = document.getElementById('mensajeBusqueda');

    const url = `{{ route('pacientes.buscar') }}?identidad=${encodeURIComponent(identidad)}`;

    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.pacientes.length > 0) {
            let html = '';
            data.pacientes.forEach(p => {
                html += `
                    <div class="resultado-item" onclick="seleccionarPaciente(${p.id})">
                        <div class="resultado-info">
                            <div class="resultado-nombre">${p.nombre} ${p.apellidos}</div>
                            <div class="resultado-identidad"><i class="fas fa-id-card"></i> ${p.identidad}</div>
                        </div>
                        <i class="fas fa-chevron-right resultado-icono"></i>
                    </div>`;
            });
            listaDiv.innerHTML = html;
            listaDiv.style.display = 'block';
            mensajeDiv.innerHTML = '';
        } else {
            listaDiv.innerHTML = '<div class="no-resultados"><i class="fas fa-user-slash"></i><br>No se encontraron pacientes</div>';
            listaDiv.style.display = 'block';
        }
    })
    .catch(() => {
        listaDiv.style.display = 'none';
        mensajeDiv.innerHTML = '<div class="alert alert-danger alert-custom mt-2">Error al buscar pacientes</div>';
    });
}

function seleccionarPaciente(id) {
    fetch(`{{ url('pacientes/datos') }}/${id}`)
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.getElementById('nombres').value = data.paciente.nombres || '';
                document.getElementById('apellidos').value = data.paciente.apellidos || '';
                document.getElementById('identidad').value = data.paciente.identidad || '';
                
                let fechaNacimiento = data.paciente.fecha_nacimiento || '';
                if(fechaNacimiento) {
                    fechaNacimiento = fechaNacimiento.split('T')[0].split(' ')[0];
                    if(fechaNacimiento.includes('-')) {
                        const partes = fechaNacimiento.split('-');
                        fechaNacimiento = `${partes[2]}/${partes[1]}/${partes[0]}`;
                    }
                }
                document.getElementById('fecha_nacimiento').value = fechaNacimiento;
                
                if(data.paciente.edad) document.getElementById('edadDocumentado').value = data.paciente.edad;
                document.getElementById('telefono').value = data.paciente.telefono || '';
                document.getElementById('direccion').value = data.paciente.direccion || '';
                document.getElementById('genero').value = data.paciente.genero || '';
                if(data.paciente.tipo_sangre) document.getElementById('tipo_sangre').value = data.paciente.tipo_sangre;

                const camposBloquear = ['nombres', 'apellidos', 'identidad', 'fecha_nacimiento', 'telefono', 'direccion', 'genero', 'tipo_sangre'];
                camposBloquear.forEach(campo => {
                    const elemento = document.getElementById(campo);
                    if(elemento) {
                        elemento.setAttribute('readonly', true);
                        elemento.style.backgroundColor = '#e9ecef';
                        elemento.style.cursor = 'not-allowed';
                        elemento.style.userSelect = 'none';
                    }
                });

                document.getElementById('buscarIdentidad').value = data.paciente.identidad;
                document.getElementById('listaResultados').style.display = 'none';
                document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-success alert-custom mt-2"><i class="fas fa-check-circle"></i> Datos cargados correctamente</div>';

                setTimeout(() => { document.getElementById('mensajeBusqueda').innerHTML = ''; }, 2000);
            }
        })
        .catch(() => {
            document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-danger alert-custom mt-2">Error al cargar datos del paciente</div>';
        });
}

document.addEventListener('click', function(e) {
    const buscarInput = document.getElementById('buscarIdentidad');
    const listaDiv = document.getElementById('listaResultados');
    if(e.target !== buscarInput && !listaDiv.contains(e.target)) {
        listaDiv.style.display = 'none';
    }
});

// ========== VALIDACIONES DE FORMATO ==========

// Permitir solo letras y espacios en nombres y apellidos
['nombres','apellidos'].forEach(id => {
    document.getElementById(id).addEventListener('keypress', function(e){
        const char = String.fromCharCode(e.which);
        if(!/[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/.test(char)) e.preventDefault();
    });
});

// Permitir solo números en teléfono e identidad
['telefono','identidad'].forEach(id => {
    document.getElementById(id).addEventListener('keypress', function(e){
        const char = String.fromCharCode(e.which);
        if(!/[0-9]/.test(char)) e.preventDefault();
    });
});

// Formatear fecha de nacimiento automáticamente (DD/MM/AAAA)
document.getElementById('fecha_nacimiento').addEventListener('input', function(e) {
    let valor = this.value.replace(/\D/g, '');
    
    if (valor.length >= 2) {
        valor = valor.substring(0, 2) + '/' + valor.substring(2);
    }
    if (valor.length >= 5) {
        valor = valor.substring(0, 5) + '/' + valor.substring(5, 9);
    }
    
    this.value = valor;
});

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', toggleDocs);

document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto');
    const errorDiv = document.createElement('div');
    errorDiv.classList.add('invalid-feedback');
    fotoInput.parentNode.appendChild(errorDiv);

    fotoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        const maxSize = 2 * 1024 * 1024;

        fotoInput.classList.remove('is-invalid');
        errorDiv.textContent = '';

        if (!validTypes.includes(file.type)) {
            fotoInput.classList.add('is-invalid');
            errorDiv.textContent = 'Solo se permiten imágenes JPG, JPEG o PNG.';
            this.value = '';
            return;
        }

        if (file.size > maxSize) {
            fotoInput.classList.add('is-invalid');
            errorDiv.textContent = 'La imagen no debe superar los 2 MB.';
            this.value = '';
            return;
        }
    });
});

// ========== GUARDAR Y RESTAURAR ESTADO DOCUMENTADO / INDOCUMENTADO ==========
document.addEventListener('DOMContentLoaded', function () {
    const savedState = localStorage.getItem('tipoPaciente');
    if (savedState === '1' || savedState === '0') {
        const radio = document.querySelector(`input[name="documentado"][value="${savedState}"]`);
        if (radio) {
            radio.checked = true;
            toggleDocs();
        }
    }

    document.querySelectorAll('input[name="documentado"]').forEach(radio => {
        radio.addEventListener('change', function() {
            localStorage.setItem('tipoPaciente', this.value);
        });
    });
});
</script>

@endsection