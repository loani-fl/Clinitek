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

.form-control, .form-select {
    position: relative;
    z-index: 1;
}

input.is-invalid, textarea.is-invalid, select.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
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

.form-control:focus, .form-select:focus {
    border-color: #007BFF;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
}

h5.text-dark.fw-bold {
    font-size: 1.35rem;
}

label {
    font-size: 1.05rem;
}

.form-control, .form-select {
    font-size: 1.05rem;
}

/* Estilos para mostrar datos como label */
.dato-paciente-label {
    display: block;
    padding: 0.5rem 0;
    font-size: 1.05rem;
    color: #212529;
    font-weight: 500;
    background-color: transparent;
    border: none;
}

/* Secciones bloqueadas */
.seccion-bloqueada {
    position: relative;
    pointer-events: none;
    opacity: 0.5;
}

.seccion-bloqueada::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(248, 249, 250, 0.8);
    z-index: 10;
}

/* Mensaje de advertencia */
.mensaje-seleccion {
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #fff;
    border-left: 4px solid #ffc107;
    color: #856404;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    max-width: 400px;
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.mensaje-seleccion i {
    margin-right: 0.5rem;
    color: #ffc107;
}

/* Ocultar datos del paciente hasta que sea seleccionado */
.datos-paciente-ocultos {
    display: none;
}

/* Mostrar datos cuando paciente está seleccionado */
.paciente-seleccionado .datos-paciente-ocultos {
    display: block;
}
</style>

<div class="custom-card">
    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Nuevo control prenatal</h2>
    </div>

    <form action="{{ route('controles-prenatales.store') }}" method="POST">
        @csrf

        <!-- SECCIÓN 1: SELECCIÓN DE PACIENTE -->
        <div class="mb-4" id="seccionPaciente">
            <h5 class="text-dark fw-bold mb-3">
                <i class="fas fa-user-circle"></i> Selección de Paciente
            </h5>
            
            <!-- Buscador de pacientes -->
            <div class="d-flex align-items-center mb-4" style="gap: 1.25rem;">
                <label class="fw-bold mb-0" style="white-space: nowrap; font-size: 0.95rem;">
                    <i class="fas fa-search"></i> BUSCAR PACIENTE
                </label>
                <div style="position: relative; flex-grow: 1; max-width: 450px;">
                    <input 
                        type="text" 
                        id="buscarIdentidad" 
                        class="form-control" 
                        placeholder="Buscar por nombre, apellido o identidad..."
                        maxlength="50"
                        autocomplete="off"
                        style="padding-right: 40px; height: 38px; font-size: 0.9rem;">
                    <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                    <div id="listaResultados" class="lista-resultados" style="display: none;"></div>
                </div>
                <a href="{{ route('pacientes.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Registrar Paciente
                </a>
            </div>
            <div id="mensajeBusqueda" class="mt-1"></div>

            <input type="hidden" name="paciente_existente" id="paciente_existente" value="{{ old('paciente_existente') }}">

            <!-- Datos de la paciente seleccionada -->
            <div class="datos-paciente-ocultos" id="datosPacienteSeleccionada">
                <hr class="my-3">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Nombre</label>
                        <input type="hidden" name="nombre" id="nombre">
                        <span class="dato-paciente-label" id="nombre_label"></span>
                    </div>

                    <div class="col-md-3">
                        <label>Apellidos</label>
                        <input type="hidden" name="apellidos" id="apellidos">
                        <span class="dato-paciente-label" id="apellidos_label"></span>
                    </div>

                    <div class="col-md-3">
                        <label>Identidad</label>
                        <input type="hidden" name="identidad" id="identidad">
                        <span class="dato-paciente-label" id="identidad_label"></span>
                    </div>

                    <div class="col-md-3">
                        <label>Fecha de nacimiento</label>
                        <input type="hidden" name="fecha_nacimiento" id="fecha_nacimiento">
                        <span class="dato-paciente-label" id="fecha_nacimiento_label"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Teléfono</label>
                        <input type="hidden" name="telefono" id="telefono">
                        <span class="dato-paciente-label" id="telefono_label"></span>
                    </div>

                    <div class="col-md-3">
                        <label>Correo electrónico</label>
                        <input type="hidden" name="correo" id="correo">
                        <span class="dato-paciente-label" id="correo_label"></span>
                    </div>

                    <div class="col-md-3">
                        <label>Tipo de sangre</label>
                        <input type="hidden" name="tipo_sangre" id="tipo_sangre">
                        <span class="dato-paciente-label" id="tipo_sangre_label"></span>
                    </div>

                    <div class="col-md-3">
                        <label>Género</label>
                        <input type="hidden" name="genero" id="genero">
                        <span class="dato-paciente-label" id="genero_label"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Dirección</label>
                        <input type="hidden" name="direccion" id="direccion">
                        <span class="dato-paciente-label" id="direccion_label"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Padecimientos</label>
                        <input type="hidden" name="padecimientos" id="padecimientos">
                        <span class="dato-paciente-label" id="padecimientos_label"></span>
                    </div>

                    <div class="col-md-6">
                        <label>Medicamentos</label>
                        <input type="hidden" name="medicamentos" id="medicamentos">
                        <span class="dato-paciente-label" id="medicamentos_label"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Alergias</label>
                        <input type="hidden" name="alergias" id="alergias">
                        <span class="dato-paciente-label" id="alergias_label"></span>
                    </div>

                    <div class="col-md-6">
                        <label>Historial quirúrgico</label>
                        <input type="hidden" name="historial_quirurgico" id="historial_quirurgico">
                        <span class="dato-paciente-label" id="historial_quirurgico_label"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Historial clínico</label>
                        <input type="hidden" name="historial_clinico" id="historial_clinico">
                        <span class="dato-paciente-label" id="historial_clinico_label"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor de secciones bloqueadas -->
        <div id="seccionesBloqueadas" class="seccion-bloqueada">
            <!-- SECCIÓN 2: DATOS OBSTÉTRICOS -->
            <div class="mb-4">
                <h5 class="text-dark fw-bold mb-3">Datos obstétricos</h5>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Fecha ultima menstruación <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_ultima_menstruacion" id="fecha_ultima_menstruacion"
                               class="form-control @error('fecha_ultima_menstruacion') is-invalid @enderror" 
                               value="{{ old('fecha_ultima_menstruacion') }}">
                        @error('fecha_ultima_menstruacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Fecha probable de parto <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_probable_parto" id="fecha_probable_parto"
                               class="form-control @error('fecha_probable_parto') is-invalid @enderror" 
                               value="{{ old('fecha_probable_parto') }}">
                        @error('fecha_probable_parto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Semanas de gestación <span class="text-danger">*</span></label>
                        <input type="number" name="semanas_gestacion" id="semanas_gestacion" min="0" max="42"
                               class="form-control @error('semanas_gestacion') is-invalid @enderror" 
                               value="{{ old('semanas_gestacion') }}" placeholder="0-42">
                        <small class="text-muted">Rango: 0-42 semanas</small>
                        @error('semanas_gestacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Número de partos <span class="text-danger">*</span></label>
                        <input type="number" name="numero_partos" id="numero_partos" min="0" max="20"
                               class="form-control @error('numero_partos') is-invalid @enderror" 
                               value="{{ old('numero_partos') }}" placeholder="0-20">
                        @error('numero_partos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Número de abortos <span class="text-danger">*</span></label>
                        <input type="number" name="numero_abortos" id="numero_abortos" min="0" max="20"
                               class="form-control @error('numero_abortos') is-invalid @enderror" 
                               value="{{ old('numero_abortos') }}" placeholder="0-20">
                        @error('numero_abortos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Tipo de partos anteriores</label>
                        <input type="text" name="tipo_partos_anteriores" maxlength="255"
                               class="form-control @error('tipo_partos_anteriores') is-invalid @enderror" 
                               value="{{ old('tipo_partos_anteriores') }}" 
                               placeholder="Ej: 2 naturales, 1 cesárea">
                        @error('tipo_partos_anteriores')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Complicaciones en embarazos previos</label>
                        <textarea name="complicaciones_previas" rows="2"
                                  class="form-control @error('complicaciones_previas') is-invalid @enderror">{{ old('complicaciones_previas') }}</textarea>
                        @error('complicaciones_previas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: DATOS DEL CONTROL ACTUAL -->
            <div class="mb-4">
                <h5 class="text-dark fw-bold mb-3">Datos del Control Prenatal</h5>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Fecha del control <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_control" 
                               class="form-control @error('fecha_control') is-invalid @enderror" 
                               value="{{ date('Y-m-d') }}"
                               readonly
                               style="background-color: #e9ecef; cursor: not-allowed;">
                        <small class="text-muted">Fecha actual (no modificable)</small>
                        @error('fecha_control')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Presión arterial <span class="text-danger">*</span></label>
                        <input type="text" name="presion_arterial" id="presion_arterial"
                               class="form-control @error('presion_arterial') is-invalid @enderror" 
                               value="{{ old('presion_arterial') }}" placeholder="120/80" maxlength="7">
                        <small class="text-muted">Formato: 120/80</small>
                        @error('presion_arterial')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Frecuencia cardíaca materna <span class="text-danger">*</span></label>
                        <input type="number" name="frecuencia_cardiaca_materna" id="frecuencia_cardiaca_materna"
                               class="form-control @error('frecuencia_cardiaca_materna') is-invalid @enderror" 
                               value="{{ old('frecuencia_cardiaca_materna') }}" 
                               min="40" max="200" placeholder="60-100">
                        <small class="text-muted">Rango: 40-200 lpm</small>
                        @error('frecuencia_cardiaca_materna')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Temperatura (°C) <span class="text-danger">*</span></label>
                        <input type="number" name="temperatura" id="temperatura"
                               class="form-control @error('temperatura') is-invalid @enderror" 
                               value="{{ old('temperatura') }}" 
                               step="0.1" min="35" max="42" placeholder="36.5">
                        <small class="text-muted">Rango: 35-42 °C</small>
                        @error('temperatura')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Peso actual (kg) <span class="text-danger">*</span></label>
                        <input type="number" name="peso_actual" id="peso_actual"
                               class="form-control @error('peso_actual') is-invalid @enderror" 
                               value="{{ old('peso_actual') }}" 
                               step="0.1" min="30" max="200" placeholder="65.0">
                        <small class="text-muted">Rango: 30-200 kg</small>
                        @error('peso_actual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Altura uterina</label>
                        <input type="number" name="altura_uterina" 
                               class="form-control @error('altura_uterina') is-invalid @enderror" 
                               value="{{ old('altura_uterina') }}" 
                               step="0.1" min="0" max="50" placeholder="25.0">
                        @error('altura_uterina')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Latidos fetales</label>
                        <input type="number" name="latidos_fetales" 
                               class="form-control @error('latidos_fetales') is-invalid @enderror" 
                               value="{{ old('latidos_fetales') }}" 
                               min="100" max="180" placeholder="120-160">
                        <small class="text-muted">Rango: 100-180 lpm</small>
                        @error('latidos_fetales')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Movimientos fetales</label>
                        <input type="text" name="movimientos_fetales" maxlength="100"
                               class="form-control @error('movimientos_fetales') is-invalid @enderror" 
                               value="{{ old('movimientos_fetales') }}" 
                               placeholder="Presentes/Ausentes">
                        @error('movimientos_fetales')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Edema <span class="text-danger">*</span></label>
                        <select name="edema" id="edema"
                                class="form-select @error('edema') is-invalid @enderror">
                            <option value="">Seleccione...</option>
                            <option value="ninguno" {{ old('edema') == 'ninguno' ? 'selected' : '' }}>Ninguno</option>
                            <option value="leve" {{ old('edema') == 'leve' ? 'selected' : '' }}>Leve</option>
                            <option value="moderado" {{ old('edema') == 'moderado' ? 'selected' : '' }}>Moderado</option>
                            <option value="severo" {{ old('edema') == 'severo' ? 'selected' : '' }}>Severo</option>
                        </select>
                        @error('edema')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Presentación fetal</label>
                        <select name="presentacion_fetal" 
                                class="form-select @error('presentacion_fetal') is-invalid @enderror">
                            <option value="">Seleccione...</option>
                            <option value="cefalica" {{ old('presentacion_fetal') == 'cefalica' ? 'selected' : '' }}>Cefálica</option>
                            <option value="podalica" {{ old('presentacion_fetal') == 'podalica' ? 'selected' : '' }}>Podálica</option>
                            <option value="transversa" {{ old('presentacion_fetal') == 'transversa' ? 'selected' : '' }}>Transversa</option>
                            <option value="no_determinada" {{ old('presentacion_fetal') == 'no_determinada' ? 'selected' : '' }}>No Determinada</option>
                        </select>
                        @error('presentacion_fetal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label>Resultados de exámenes</label>
                        <textarea name="resultados_examenes" rows="2"
                                  class="form-control @error('resultados_examenes') is-invalid @enderror" 
                                  placeholder="Laboratorio, ultrasonido, etc.">{{ old('resultados_examenes') }}</textarea>
                        @error('resultados_examenes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Observaciones</label>
                        <textarea name="observaciones" rows="3"
                                  class="form-control @error('observaciones') is-invalid @enderror" 
                                  placeholder="Observaciones adicionales del control">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 4: TRATAMIENTOS Y RECOMENDACIONES -->
            <div class="mb-4">
                <h5 class="text-dark fw-bold mb-3">Tratamientos y Recomendaciones</h5>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Suplementos</label>
                        <textarea name="suplementos" rows="3"
                                  class="form-control @error('suplementos') is-invalid @enderror" 
                                  placeholder="Ácido fólico, hierro, calcio, etc.">{{ old('suplementos') }}</textarea>
                        @error('suplementos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Vacunas aplicadas</label>
                        <textarea name="vacunas_aplicadas" rows="3"
                                  class="form-control @error('vacunas_aplicadas') is-invalid @enderror" 
                                  placeholder="Tdap, Influenza, etc.">{{ old('vacunas_aplicadas') }}</textarea>
                        @error('vacunas_aplicadas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Indicaciones médicas</label>
                        <textarea name="indicaciones_medicas" rows="3"
                                  class="form-control @error('indicaciones_medicas') is-invalid @enderror" 
                                  placeholder="Recomendaciones y cuidados">{{ old('indicaciones_medicas') }}</textarea>
                        @error('indicaciones_medicas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Fecha de próxima cita</label>
                        <input type="date" name="fecha_proxima_cita" 
                               class="form-control @error('fecha_proxima_cita') is-invalid @enderror" 
                               value="{{ old('fecha_proxima_cita') }}">
                        @error('fecha_proxima_cita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary">Registrar control</button>
                <button type="button" id="btnLimpiar" class="btn btn-warning">Limpiar</button>
                <a href="{{ route('controles-prenatales.index') }}" class="btn btn-success">Regresar</a>
            </div>
        </div>
    </form>
</div>

<!-- Mensaje de notificación flotante -->
<div id="mensajeSeleccionPaciente" class="mensaje-seleccion">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Debe seleccionar una paciente antes de continuar.</strong>
</div>

<script>
// ========== CONTROL DE SECCIONES BLOQUEADAS ==========
function actualizarEstadoSecciones() {
    const pacienteId = document.getElementById('paciente_existente').value;
    const seccionesBloqueadas = document.getElementById('seccionesBloqueadas');
    const mensajeSeleccion = document.getElementById('mensajeSeleccionPaciente');
    const datosPaciente = document.getElementById('datosPacienteSeleccionada');
    
    if (pacienteId) {
        // Hay paciente seleccionada - desbloquear secciones
        seccionesBloqueadas.classList.remove('seccion-bloqueada');
        mensajeSeleccion.style.display = 'none';
        datosPaciente.style.display = 'block';
        document.getElementById('seccionPaciente').classList.add('paciente-seleccionado');
    } else {
        // No hay paciente seleccionada - bloquear secciones
        seccionesBloqueadas.classList.add('seccion-bloqueada');
        mensajeSeleccion.style.display = 'none'; // Oculto por defecto
        datosPaciente.style.display = 'none';
        document.getElementById('seccionPaciente').classList.remove('paciente-seleccionado');
    }
}

// Validar antes de enviar el formulario
document.querySelector('form').addEventListener('submit', function(e) {
    const pacienteId = document.getElementById('paciente_existente').value;
    const mensajeSeleccion = document.getElementById('mensajeSeleccionPaciente');
    
    if (!pacienteId) {
        e.preventDefault();
        
        // Mostrar notificación flotante
        mensajeSeleccion.style.display = 'block';
        
        // Ocultar mensaje después de 4 segundos
        setTimeout(() => {
            mensajeSeleccion.style.display = 'none';
        }, 4000);
        
        return false;
    }
});

// Llamar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarEstadoSecciones();
    
    // Si hay un paciente en old() al recargar (errores de validación), cargarlo
    const pacienteExistenteId = document.getElementById('paciente_existente').value;
    if (pacienteExistenteId) {
        cargarPacienteExistente(pacienteExistenteId);
    }
});

// ========== BÚSQUEDA DE PACIENTES ==========
let timeoutBusqueda = null;

document.getElementById('buscarIdentidad').addEventListener('input', function() {
    const busqueda = this.value.trim();
    const listaDiv = document.getElementById('listaResultados');
    const mensajeDiv = document.getElementById('mensajeBusqueda');

    clearTimeout(timeoutBusqueda);

    if (!busqueda) {
        listaDiv.style.display = 'none';
        listaDiv.innerHTML = '';
        mensajeDiv.innerHTML = '';
        limpiarCamposPaciente();
        return;
    }

    // Buscar inmediatamente desde el primer carácter
    timeoutBusqueda = setTimeout(() => {
        buscarPacientes(busqueda);
    }, 300);
});

function buscarPacientes(busqueda) {
    const listaDiv = document.getElementById('listaResultados');
    const mensajeDiv = document.getElementById('mensajeBusqueda');

    const url = `{{ route('pacientes.buscar') }}?identidad=${encodeURIComponent(busqueda)}`;

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
                            <div class="resultado-identidad"><i class="fas fa-id-card"></i> ${p.identidad || 'Sin identidad'}</div>
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
    .catch((error) => {
        console.error('Error:', error);
        listaDiv.style.display = 'none';
        mensajeDiv.innerHTML = '<div class="alert alert-danger mt-2">Error al buscar pacientes</div>';
    });
}

function seleccionarPaciente(id) {
    cargarPacienteExistente(id, true); // true indica que es selección manual
}

function cargarPacienteExistente(id, esSeleccionManual = false) {
    fetch(`{{ url('pacientes/datos') }}/${id}`)
        .then(res => res.json())
        .then(data => {
            if(data.success && data.paciente) {
                const p = data.paciente;
                
                document.getElementById('paciente_existente').value = id;
                
                // Cargar valores en inputs ocultos - con validación
                document.getElementById('nombre').value = p.nombre || '';
                document.getElementById('apellidos').value = p.apellidos || '';
                document.getElementById('identidad').value = p.identidad || '';
                document.getElementById('fecha_nacimiento').value = p.fecha_nacimiento || '';
                document.getElementById('telefono').value = p.telefono || '';
                document.getElementById('direccion').value = p.direccion || '';
                document.getElementById('genero').value = p.genero || 'Femenino';
                document.getElementById('correo').value = p.correo || '';
                document.getElementById('tipo_sangre').value = p.tipo_sangre || '';
                document.getElementById('padecimientos').value = p.padecimientos || '';
                document.getElementById('medicamentos').value = p.medicamentos || '';
                document.getElementById('alergias').value = p.alergias || '';
                document.getElementById('historial_quirurgico').value = p.historial_quirurgico || '';
                document.getElementById('historial_clinico').value = p.historial_clinico || '';

                // Mostrar valores en los labels - mostrando datos reales o N/A
                document.getElementById('nombre_label').textContent = p.nombre || 'N/A';
                document.getElementById('apellidos_label').textContent = p.apellidos || 'N/A';
                document.getElementById('identidad_label').textContent = p.identidad || 'N/A';
                
                // Formatear fecha de nacimiento si existe
                let fechaNac = 'N/A';
                let fechaNacInput = '';
                if (p.fecha_nacimiento) {
                    try {
                        // Extraer solo la parte de la fecha (YYYY-MM-DD)
                        fechaNacInput = p.fecha_nacimiento.split('T')[0];
                        
                        // Crear fecha para mostrar
                        const partes = fechaNacInput.split('-');
                        fechaNac = `${partes[2]}/${partes[1]}/${partes[0]}`; // DD/MM/YYYY
                    } catch (e) {
                        fechaNac = p.fecha_nacimiento;
                        fechaNacInput = p.fecha_nacimiento;
                    }
                }
                
                // Actualizar input oculto con formato correcto
                document.getElementById('fecha_nacimiento').value = fechaNacInput;
                document.getElementById('fecha_nacimiento_label').textContent = fechaNac;
                
                document.getElementById('telefono_label').textContent = p.telefono || 'N/A';
                document.getElementById('direccion_label').textContent = p.direccion || 'N/A';
                document.getElementById('genero_label').textContent = p.genero || 'N/A';
                document.getElementById('correo_label').textContent = p.correo || 'N/A';
                document.getElementById('tipo_sangre_label').textContent = p.tipo_sangre || 'N/A';
                document.getElementById('padecimientos_label').textContent = p.padecimientos || 'N/A';
                document.getElementById('medicamentos_label').textContent = p.medicamentos || 'N/A';
                document.getElementById('alergias_label').textContent = p.alergias || 'N/A';
                document.getElementById('historial_quirurgico_label').textContent = p.historial_quirurgico || 'N/A';
                document.getElementById('historial_clinico_label').textContent = p.historial_clinico || 'N/A';

                document.getElementById('buscarIdentidad').value = p.identidad || '';
                document.getElementById('listaResultados').style.display = 'none';
                
                // Solo mostrar mensaje si viene de la búsqueda manual
                if (esSeleccionManual) {
                    document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-success mt-2"><i class="fas fa-check-circle"></i> Paciente cargada correctamente</div>';
                    setTimeout(() => { document.getElementById('mensajeBusqueda').innerHTML = ''; }, 3000);
                }

                // Actualizar estado de secciones
                actualizarEstadoSecciones();

                // Log para debug - verificar qué datos llegan
                console.log('Datos de paciente cargados:', p);
            } else {
                if (esSeleccionManual) {
                    document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-danger mt-2">No se encontraron datos del paciente</div>';
                }
                console.error('Respuesta sin datos de paciente:', data);
            }
        })
        .catch((error) => {
            console.error('Error al cargar paciente:', error);
            if (esSeleccionManual) {
                document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-danger mt-2">Error al cargar datos del paciente</div>';
            }
        });
}

function limpiarCamposPaciente() {
    document.getElementById('paciente_existente').value = '';
    
    const campos = ['nombre','apellidos','identidad','fecha_nacimiento','telefono','correo','tipo_sangre','genero','direccion','padecimientos','medicamentos','alergias','historial_quirurgico','historial_clinico'];
    campos.forEach(id => {
        const el = document.getElementById(id);
        const label = document.getElementById(id + '_label');
        
        if(el) el.value = '';
        if(label) label.textContent = '';
    });
    
    // Actualizar estado de secciones
    actualizarEstadoSecciones();
}

document.addEventListener('click', function(e) {
    const buscarInput = document.getElementById('buscarIdentidad');
    const listaDiv = document.getElementById('listaResultados');
    if(e.target !== buscarInput && !listaDiv.contains(e.target)) {
        listaDiv.style.display = 'none';
    }
});

// ========== VALIDACIONES DE FORMATO ==========

// Formato automático de Presión Arterial
document.getElementById('presion_arterial').addEventListener('input', function(e) {
    let valor = this.value.replace(/[^0-9]/g, '');
    
    if (valor.length > 6) {
        valor = valor.substring(0, 6);
    }
    
    if (valor.length >= 4) {
        let sistolica = valor.substring(0, 3);
        let diastolica = valor.substring(3);
        
        if (parseInt(sistolica) > 250) {
            sistolica = valor.substring(0, 2);
            diastolica = valor.substring(2);
        }
        
        valor = sistolica + '/' + diastolica;
    }
    
    this.value = valor;
});

// Validación de Frecuencia Cardíaca Materna
document.getElementById('frecuencia_cardiaca_materna').addEventListener('input', function(e) {
    let valor = this.value.replace(/[^0-9]/g, '');
    
    if (valor.length > 3) {
        valor = valor.substring(0, 3);
    }
    
    this.value = valor;
});

// Validación de Temperatura
document.getElementById('temperatura').addEventListener('input', function(e) {
    let valor = this.value.replace(/[^0-9.]/g, '');

    if (!valor.includes('.')) {
        if (valor.length > 2) {
            valor = valor.substring(0, 2) + '.' + valor.substring(2, 3);
        }
    } else {
        const partes = valor.split('.');
        
        if (partes[0].length > 2) {
            partes[0] = partes[0].substring(0, 2);
        }
        
        if (partes[1] && partes[1].length > 1) {
            partes[1] = partes[1].substring(0, 1);
        }
        
        valor = partes[0] + '.' + (partes[1] || '');
    }

    this.value = valor;
});

// ========== LIMPIAR FORMULARIO ==========
document.getElementById('btnLimpiar').addEventListener('click', () => {
    const form = document.querySelector('form');
    
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => {
        if (!el.classList.contains('d-block')) {
            el.remove();
        }
    });
    
    document.getElementById('buscarIdentidad').value = '';
    document.getElementById('listaResultados').style.display = 'none';
    document.getElementById('listaResultados').innerHTML = '';
    document.getElementById('mensajeBusqueda').innerHTML = '';
    
    limpiarCamposPaciente();
    
    // Restaurar fecha actual
    document.querySelector('input[name="fecha_control"]').value = '{{ date("Y-m-d") }}';
});


</script>

@endsection