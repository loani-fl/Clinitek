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
    display: block !important;
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

.datos-paciente-ocultos {
    display: none;
}

.paciente-seleccionado .datos-paciente-ocultos {
    display: block;
}

.text-optional {
    color: #6c757d;
    font-weight: normal;
    font-size: 0.9rem;
}

.char-counter {
    font-size: 0.875rem;
    color: #6c757d;
    text-align: right;
    margin-top: 0.25rem;
}

.char-counter.text-danger {
    color: #dc3545 !important;
}

/* Estilos para secciones */
.seccion-datos {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #dee2e6;
}

.seccion-titulo {
    font-size: 1.1rem;
    font-weight: 600;
    color: #007BFF;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #007BFF;
}

.dato-label {
    font-weight: 600;
    color: #212529;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
    display: block;
}

/* Mensaje de éxito */
.alert-success-custom {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    border-left: 4px solid #28a745;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.alert-success-custom i {
    font-size: 1.5rem;
    color: #28a745;
}

.alert-success-custom strong {
    font-size: 1.1rem;
}
</style>

<div class="custom-card">
    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Nuevo control prenatal</h2>
    </div>

    @if(session('success'))
        <div class="alert-success-custom" id="mensajeExito">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>{{ session('success') }}</strong>
            </div>
        </div>
    @endif

    <form action="{{ route('controles-prenatales.store') }}" method="POST" id="formControlPrenatal">
        @csrf

        <!-- SECCIÓN 1: SELECCIÓN DE PACIENTE -->
        <div class="mb-4" id="seccionPaciente">
            <h5 class="text-dark fw-bold mb-3">
                <i class="fas fa-user-circle"></i> Selección de paciente
            </h5>
            
            <div class="d-flex align-items-center mb-4" style="gap: 1.25rem;">
                <label class="fw-bold mb-0" style="white-space: nowrap; font-size: 0.95rem;">
                    <i class="fas fa-search"></i> Buscar paciente
                </label>
                <div style="position: relative; flex-grow: 1; max-width: 450px;">
                    <input 
                        type="text" 
                        id="buscarIdentidad" 
                        name="identidad"
                        class="form-control" 
                        placeholder="Buscar por nombre, apellido o identidad..."
                        maxlength="50"
                        autocomplete="off"
                        style="padding-right: 40px; height: 38px; font-size: 0.9rem;">
                    <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                    <div id="listaResultados" class="lista-resultados" style="display: none;"></div>
                </div>
                <a href="{{ route('pacientes.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Registrar paciente
                </a>
            </div>
            <div id="mensajeBusqueda" class="mt-1"></div>

            <input type="hidden" name="paciente_existente" id="paciente_existente" value="{{ old('paciente_existente') }}">

            <div class="datos-paciente-ocultos" id="datosPacienteSeleccionada">
                <hr class="my-3">
                
                <input type="hidden" name="nombre" id="nombre" value="{{ old('nombre') }}">
                <input type="hidden" name="apellidos" id="apellidos" value="{{ old('apellidos') }}">
                <input type="hidden" name="identidad" id="identidad" value="{{ old('identidad') }}">
                <input type="hidden" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                <input type="hidden" name="edad" id="edad" value="{{ old('edad') }}">
                <input type="hidden" name="telefono" id="telefono" value="{{ old('telefono') }}">
                <input type="hidden" name="correo" id="correo" value="{{ old('correo') }}">
                <input type="hidden" name="tipo_sangre" id="tipo_sangre" value="{{ old('tipo_sangre') }}">
                <input type="hidden" name="genero" id="genero" value="{{ old('genero') }}">
                <input type="hidden" name="direccion" id="direccion" value="{{ old('direccion') }}">
                <input type="hidden" name="padecimientos" id="padecimientos" value="{{ old('padecimientos') }}">
                <input type="hidden" name="medicamentos" id="medicamentos" value="{{ old('medicamentos') }}">
                <input type="hidden" name="alergias" id="alergias" value="{{ old('alergias') }}">
                <input type="hidden" name="historial_quirurgico" id="historial_quirurgico" value="{{ old('historial_quirurgico') }}">
                <input type="hidden" name="historial_clinico" id="historial_clinico" value="{{ old('historial_clinico') }}">
                
                <!-- Datos del Paciente -->
                <div class="seccion-datos">
                    <div class="seccion-titulo">
                        <i class="fas fa-user"></i> Datos del paciente
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="dato-label">Nombre(s): <span class="text-danger">*</span></label>
                            <input type="text" id="nombre_label" class="form-control" readonly 
                                   style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-3">
                            <label class="dato-label">Apellidos: <span class="text-danger">*</span></label>
                            <input type="text" id="apellidos_label" class="form-control" readonly 
                                   style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-3">
                            <label class="dato-label">Identidad: <span class="text-danger">*</span></label>
                            <input type="text" id="identidad_label" class="form-control" readonly 
                                   style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-3">
                            <label class="dato-label">Fecha de Nacimiento: <span class="text-danger">*</span></label>
                            <input type="text" id="fecha_nacimiento_label" class="form-control" readonly 
                                   style="background-color: #e9ecef;">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label class="dato-label">Edad: <span class="text-danger">*</span></label>
                            <input type="text" id="edad_label" class="form-control" readonly 
                                   style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-2">
                            <label class="dato-label">Teléfono: <span class="text-danger">*</span></label>
                            <input type="text" id="telefono_label" class="form-control" readonly 
                                   style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-3">
                            <label class="dato-label">Tipo de Sangre (opcional):</label>
                            <input type="text" id="tipo_sangre_label" class="form-control" readonly 
                                   style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-2">
                            <label class="dato-label">Género: <span class="text-danger">*</span></label>
                            <input type="text" id="genero_label" class="form-control" readonly 
                                   style="background-color: #e9ecef;">
                        </div>
                        <div class="col-md-3">
                            <label class="dato-label">Correo electrónico:</label>
                            <input type="text" id="correo_label" class="form-control" readonly 
                                   style="background-color: #e9ecef;">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="dato-label">Dirección: <span class="text-danger">*</span></label>
                            <textarea id="direccion_label" class="form-control" rows="2" readonly 
                                      style="background-color: #e9ecef; resize: none;"></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="dato-label">Alergias:</label>
                            <textarea id="alergias_label" class="form-control" rows="2" readonly 
                                      style="background-color: #e9ecef; resize: none;"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="dato-label">Medicamentos:</label>
                            <textarea id="medicamentos_label" class="form-control" rows="2" readonly 
                                      style="background-color: #e9ecef; resize: none;"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="dato-label">Padecimientos:</label>
                            <textarea id="padecimientos_label" class="form-control" rows="2" readonly 
                                      style="background-color: #e9ecef; resize: none;"></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="dato-label">Historial quirúrgico:</label>
                            <textarea id="historial_quirurgico_label" class="form-control" rows="2" readonly 
                                      style="background-color: #e9ecef; resize: none;"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="dato-label">Historial clínico:</label>
                            <textarea id="historial_clinico_label" class="form-control" rows="2" readonly 
                                      style="background-color: #e9ecef; resize: none;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="seccionesBloqueadas" class="seccion-bloqueada">
            <!-- SECCIÓN 2: DATOS OBSTÉTRICOS -->
            <div class="mb-4">
                <h5 class="text-dark fw-bold mb-3">Datos obstétricos</h5>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Fecha última menstruación <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_ultima_menstruacion" id="fecha_ultima_menstruacion"
                               class="form-control @error('fecha_ultima_menstruacion') is-invalid @enderror" 
                               value="{{ old('fecha_ultima_menstruacion') }}"
                               max="{{ date('Y-m-d') }}">
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
                        <input type="number" name="semanas_gestacion" id="semanas_gestacion"
                               class="form-control @error('semanas_gestacion') is-invalid @enderror" 
                               value="{{ old('semanas_gestacion') }}" 
                               min="0" max="42" placeholder="0-42">
                        <small class="text-muted">Rango: 0-42 semanas</small>
                        @error('semanas_gestacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Número de partos <span class="text-danger">*</span></label>
                        <input type="number" name="numero_partos" id="numero_partos"
                               class="form-control @error('numero_partos') is-invalid @enderror" 
                               value="{{ old('numero_partos') }}" 
                               min="0" max="20" placeholder="0-20">
                        <small class="text-muted">Rango: 0-20</small>
                        @error('numero_partos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label>Número de abortos <span class="text-danger">*</span></label>
                        <input type="number" name="numero_abortos" id="numero_abortos"
                               class="form-control @error('numero_abortos') is-invalid @enderror" 
                               value="{{ old('numero_abortos') }}" 
                               min="0" max="20" placeholder="0-20">
                        <small class="text-muted">Rango: 0-20</small>
                        @error('numero_abortos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Tipo de partos anteriores <span class="text-optional">(Opcional)</span></label>
                        <textarea name="tipo_partos_anteriores" id="tipo_partos_anteriores" rows="2"
                                  class="form-control @error('tipo_partos_anteriores') is-invalid @enderror" 
                                  placeholder="Natural, cesárea, etc." maxlength="255">{{ old('tipo_partos_anteriores') }}</textarea>
                        <div class="char-counter">
                            <span id="counter_tipo_partos_anteriores">0</span>/255
                        </div>
                        @error('tipo_partos_anteriores')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label>Complicaciones previas <span class="text-optional">(Opcional)</span></label>
                        <textarea name="complicaciones_previas" id="complicaciones_previas" rows="2"
                                  class="form-control @error('complicaciones_previas') is-invalid @enderror" 
                                  placeholder="Preeclampsia, hemorragias, etc." maxlength="500">{{ old('complicaciones_previas') }}</textarea>
                        <div class="char-counter">
                            <span id="counter_complicaciones_previas">0</span>/500
                        </div>
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
                               value="{{ old('fecha_control', date('Y-m-d')) }}"
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
                </div>

                <div class="row mb-3">
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

                    <div class="col-md-4">
                        <label>Altura uterina (cm) <span class="text-optional">(Opcional)</span></label>
                        <input type="number" name="altura_uterina" id="altura_uterina"
                               class="form-control @error('altura_uterina') is-invalid @enderror" 
                               value="{{ old('altura_uterina') }}" 
                               step="0.1" min="0" max="50" placeholder="25.0">
                        <small class="text-muted">Rango: 0-50 cm</small>
                        @error('altura_uterina')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
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

                    <div class="col-md-4">
                        <label>Latidos fetales (lpm) <span class="text-optional">(Opcional)</span></label>
                        <input type="number" name="latidos_fetales" id="latidos_fetales"
                               class="form-control @error('latidos_fetales') is-invalid @enderror" 
                               value="{{ old('latidos_fetales') }}" 
                               min="100" max="180" placeholder="120-160">
                        <small class="text-muted">Rango: 100-180 lpm</small>
                        @error('latidos_fetales')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Movimientos fetales <span class="text-optional">(Opcional)</span></label>
                        <input type="text" name="movimientos_fetales" id="movimientos_fetales" maxlength="100"
                               class="form-control @error('movimientos_fetales') is-invalid @enderror" 
                               value="{{ old('movimientos_fetales') }}" 
                               placeholder="Presentes/Ausentes">
                        <div class="char-counter">
                            <span id="counter_movimientos_fetales">0</span>/100
                        </div>
                        @error('movimientos_fetales')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Presentación fetal <span class="text-optional">(Opcional)</span></label>
                        <select name="presentacion_fetal" id="presentacion_fetal"
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
                        <label>Resultados de exámenes <span class="text-optional">(Opcional)</span></label>
                        <textarea name="resultados_examenes" id="resultados_examenes" rows="2"
                                  class="form-control @error('resultados_examenes') is-invalid @enderror" 
                                  placeholder="Laboratorio, ultrasonido, etc." maxlength="500">{{ old('resultados_examenes') }}</textarea>
                        <div class="char-counter">
                            <span id="counter_resultados_examenes">0</span>/500
                        </div>
                        @error('resultados_examenes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Observaciones <span class="text-optional">(Opcional)</span></label>
                        <textarea name="observaciones" id="observaciones" rows="3"
                                  class="form-control @error('observaciones') is-invalid @enderror" 
                                  placeholder="Observaciones adicionales del control" maxlength="500">{{ old('observaciones') }}</textarea>
                        <div class="char-counter">
                            <span id="counter_observaciones">0</span>/500
                        </div>
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
                        <label>Suplementos <span class="text-optional">(Opcional)</span></label>
                        <textarea name="suplementos" id="suplementos" rows="3"
                                  class="form-control @error('suplementos') is-invalid @enderror" 
                                  placeholder="Ácido fólico, hierro, calcio, etc." maxlength="300">{{ old('suplementos') }}</textarea>
                        <div class="char-counter">
                            <span id="counter_suplementos">0</span>/300
                        </div>
                        @error('suplementos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Vacunas aplicadas <span class="text-optional">(Opcional)</span></label>
                        <textarea name="vacunas_aplicadas" id="vacunas_aplicadas" rows="3"
                                  class="form-control @error('vacunas_aplicadas') is-invalid @enderror" 
                                  placeholder="Tdap, Influenza, etc." maxlength="300">{{ old('vacunas_aplicadas') }}</textarea>
                        <div class="char-counter">
                            <span id="counter_vacunas_aplicadas">0</span>/300
                        </div>
                        @error('vacunas_aplicadas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Indicaciones médicas <span class="text-optional">(Opcional)</span></label>
                        <textarea name="indicaciones_medicas" id="indicaciones_medicas" rows="3"
                                  class="form-control @error('indicaciones_medicas') is-invalid @enderror" 
                                  placeholder="Recomendaciones y cuidados" maxlength="500">{{ old('indicaciones_medicas') }}</textarea>
                        <div class="char-counter">
                            <span id="counter_indicaciones_medicas">0</span>/500
                        </div>
                        @error('indicaciones_medicas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Fecha de próxima cita <span class="text-optional">(Opcional)</span></label>
                        <input type="date" name="fecha_proxima_cita" id="fecha_proxima_cita"
                               class="form-control @error('fecha_proxima_cita') is-invalid @enderror" 
                               value="{{ old('fecha_proxima_cita') }}">
                        @error('fecha_proxima_cita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary px-4 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Registrar
                </button>

                <button type="button" id="btnLimpiar" class="btn btn-warning px-4 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-trash"></i> Limpiar
                </button>

                <a href="{{ route('controles-prenatales.index') }}" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </div>
    </form>
</div>

<div id="mensajeSeleccionPaciente" class="mensaje-seleccion">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Debe seleccionar una paciente antes de continuar.</strong>
</div>

<script>
// ========== VALIDACIONES Y CONTADORES DE CARACTERES ==========
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar contadores
    const textareasConContador = [
        'tipo_partos_anteriores', 'complicaciones_previas', 'movimientos_fetales',
        'resultados_examenes', 'observaciones', 'suplementos', 
        'vacunas_aplicadas', 'indicaciones_medicas'
    ];
    
    textareasConContador.forEach(id => {
        const textarea = document.getElementById(id);
        const counter = document.getElementById('counter_' + id);
        
        if (textarea && counter) {
            // Actualizar contador inicial
            counter.textContent = textarea.value.length;
            
            // Actualizar en cada input
            textarea.addEventListener('input', function() {
                counter.textContent = this.value.length;
                if (this.value.length >= parseInt(this.getAttribute('maxlength'))) {
                    counter.classList.add('text-danger');
                } else {
                    counter.classList.remove('text-danger');
                }
            });
        }
    });

    // === SOLO LETRAS EN CAMPOS DE TEXTO ===
    const camposSoloLetras = [
        'tipo_partos_anteriores',
        'complicaciones_previas',
        'resultados_examenes',
        'observaciones',
        'suplementos',
        'vacunas_aplicadas',
        'indicaciones_medicas'
    ];

    const soloLetras = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
    const limpiar = /[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g;

    camposSoloLetras.forEach(id => {
        const campo = document.getElementById(id);
        if (!campo) return;

        // Bloquear teclas que no sean letras o espacio
        campo.addEventListener('keypress', e => {
            const char = e.key;
            if (!soloLetras.test(char) && !['Backspace','Delete','Tab','Enter'].includes(char)) {
                e.preventDefault();
            }
        });

        // Limpiar texto pegado o insertado con caracteres no permitidos
        campo.addEventListener('input', () => {
            campo.value = campo.value.replace(limpiar, '');
            const contador = document.getElementById('counter_' + id);
            if (contador) contador.textContent = campo.value.length;
        });
    });
    
    // Validación de campos numéricos para bloquear caracteres no permitidos
    const camposNumericos = ['semanas_gestacion', 'numero_partos', 'numero_abortos', 
                             'frecuencia_cardiaca_materna', 'latidos_fetales'];
    
    camposNumericos.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('keypress', function(e) {
                // Solo permitir números
                if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && 
                    e.key !== 'ArrowLeft' && e.key !== 'ArrowRight' && e.key !== 'Tab') {
                    e.preventDefault();
                }
            });

                // === LIMITAR LONGITUD DE CAMPOS NUMÉRICOS ===
    const limites = {
        'altura_uterina': 2,
        'peso_actual': 2,
        'latidos_fetales': 3,
        'numero_partos': 2,
        'numero_abortos': 2,
        'semanas_gestacion': 2
    };

    Object.entries(limites).forEach(([id, maxLength]) => {
        const campo = document.getElementById(id);
        if (!campo) return;

        campo.addEventListener('input', function() {
            if (this.value.length > maxLength) {
                this.value = this.value.slice(0, maxLength);
            }
        });
    });

            
            // Validar rango al cambiar valor
            input.addEventListener('blur', function() {
                const min = parseInt(this.getAttribute('min'));
                const max = parseInt(this.getAttribute('max'));
                const value = parseInt(this.value);
                
                // Limpiar validaciones previas solo las dinámicas
                const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
                if (feedbackExistente) {
                    feedbackExistente.remove();
                }
                this.classList.remove('is-invalid');
                
                if (this.value && (value < min || value > max)) {
                    this.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback rango-error d-block';
                    feedback.style.display = 'block';
                    feedback.textContent = `El valor debe estar entre ${min} y ${max}`;
                    this.parentElement.appendChild(feedback);
                }
            });
            
            // Limpiar error al escribir
            input.addEventListener('input', function() {
                const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
                if (feedbackExistente && this.value) {
                    const min = parseInt(this.getAttribute('min'));
                    const max = parseInt(this.getAttribute('max'));
                    const value = parseInt(this.value);
                    
                    if (value >= min && value <= max) {
                        feedbackExistente.remove();
                        this.classList.remove('is-invalid');
                    }
                }
            });
        }
    });
    
    // Validación de temperatura
    const temperatura = document.getElementById('temperatura');
    if (temperatura) {
        temperatura.addEventListener('input', function(e) {
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
            
            // Limpiar error mientras escribe si está en rango
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
            if (feedbackExistente && valor) {
                const value = parseFloat(valor);
                if (value >= 35 && value <= 42) {
                    feedbackExistente.remove();
                    this.classList.remove('is-invalid');
                }
            }
        });
        
        temperatura.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            const min = 35;
            const max = 42;
            
            // Limpiar validaciones previas solo las dinámicas
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }
            this.classList.remove('is-invalid');
            
            if (this.value && (value < min || value > max)) {
                this.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback rango-error d-block';
                feedback.style.display = 'block';
                feedback.textContent = `La temperatura debe estar entre ${min} y ${max} °C`;
                this.parentElement.appendChild(feedback);
            }
        });
    }
    
    // Validación de peso
    const peso = document.getElementById('peso_actual');
    if (peso) {
        peso.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            const min = 30;
            const max = 200;
            
            // Limpiar validaciones previas solo las dinámicas
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }
            this.classList.remove('is-invalid');
            
            if (this.value && (value < min || value > max)) {
                this.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback rango-error d-block';
                feedback.style.display = 'block';
                feedback.textContent = `El peso debe estar entre ${min} y ${max} kg`;
                this.parentElement.appendChild(feedback);
            }
        });
        
        peso.addEventListener('input', function() {
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
            if (feedbackExistente && this.value) {
                const value = parseFloat(this.value);
                if (value >= 30 && value <= 200) {
                    feedbackExistente.remove();
                    this.classList.remove('is-invalid');
                }
            }
        });
    }
    
    // Validación de altura uterina
    const alturaUterina = document.getElementById('altura_uterina');
    if (alturaUterina) {
        alturaUterina.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            const min = 0;
            const max = 50;
            
            // Limpiar validaciones previas solo las dinámicas
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }
            this.classList.remove('is-invalid');
            
            if (this.value && (value < min || value > max)) {
                this.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback rango-error d-block';
                feedback.style.display = 'block';
                feedback.textContent = `La altura uterina debe estar entre ${min} y ${max} cm`;
                this.parentElement.appendChild(feedback);
            }
        });
        
        alturaUterina.addEventListener('input', function() {
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
            if (feedbackExistente && this.value) {
                const value = parseFloat(this.value);
                if (value >= 0 && value <= 50) {
                    feedbackExistente.remove();
                    this.classList.remove('is-invalid');
                }
            }
        });
    }
    
    // Validación de latidos fetales
    const latidosFetales = document.getElementById('latidos_fetales');
    if (latidosFetales) {
        latidosFetales.addEventListener('blur', function() {
            const value = parseInt(this.value);
            const min = 100;
            const max = 180;
            
            // Limpiar validaciones previas solo las dinámicas
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }
            this.classList.remove('is-invalid');
            
            if (this.value && (value < min || value > max)) {
                this.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback rango-error d-block';
                feedback.style.display = 'block';
                feedback.textContent = `Los latidos fetales deben estar entre ${min} y ${max} lpm`;
                this.parentElement.appendChild(feedback);
            }
        });
        
        latidosFetales.addEventListener('input', function() {
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.rango-error');
            if (feedbackExistente && this.value) {
                const value = parseInt(this.value);
                if (value >= 100 && value <= 180) {
                    feedbackExistente.remove();
                    this.classList.remove('is-invalid');
                }
            }
        });
    }
    
    // Validación de presión arterial
    const presionArterial = document.getElementById('presion_arterial');
    if (presionArterial) {
        presionArterial.addEventListener('input', function(e) {
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
            
            // Limpiar error mientras escribe si está en formato correcto
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.formato-error');
            if (feedbackExistente && /^\d{2,3}\/\d{2,3}$/.test(this.value)) {
                feedbackExistente.remove();
                this.classList.remove('is-invalid');
            }
        });
        
        presionArterial.addEventListener('blur', function() {
            // Limpiar validaciones previas solo las dinámicas
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.formato-error');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }
            this.classList.remove('is-invalid');
            
            if (this.value && !/^\d{2,3}\/\d{2,3}$/.test(this.value)) {
                this.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback formato-error d-block';
                feedback.style.display = 'block';
                feedback.textContent = 'El formato debe ser: 120/80';
                this.parentElement.appendChild(feedback);
            }
        });
    }
    
    // Validación de frecuencia cardíaca
    const frecuenciaCardiaca = document.getElementById('frecuencia_cardiaca_materna');
    if (frecuenciaCardiaca) {
        frecuenciaCardiaca.addEventListener('input', function(e) {
            let valor = this.value.replace(/[^0-9]/g, '');
            
            if (valor.length > 3) {
                valor = valor.substring(0, 3);
            }
            
            this.value = valor;
        });
    }
    
    // Validación de fecha próxima cita
    const fechaProximaCita = document.getElementById('fecha_proxima_cita');
    const fechaControl = document.querySelector('input[name="fecha_control"]');
    
    if (fechaProximaCita && fechaControl) {
        fechaProximaCita.addEventListener('change', function() {
            // Limpiar validaciones previas solo las dinámicas
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.fecha-error');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }
            this.classList.remove('is-invalid');
            
            if (this.value && fechaControl.value) {
                if (this.value <= fechaControl.value) {
                    this.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback fecha-error d-block';
                    feedback.style.display = 'block';
                    feedback.textContent = 'La fecha de próxima cita debe ser posterior a la fecha del control';
                    this.parentElement.appendChild(feedback);
                }
            }
        });
    }
    
    // Validación de fechas obstétricas
    const fechaUltimaMenstruacion = document.getElementById('fecha_ultima_menstruacion');
    const fechaProbableParto = document.getElementById('fecha_probable_parto');
    
    if (fechaUltimaMenstruacion && fechaProbableParto) {
        fechaProbableParto.addEventListener('change', function() {
            // Limpiar validaciones previas solo las dinámicas
            const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.fecha-error');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }
            this.classList.remove('is-invalid');
            
            if (this.value && fechaUltimaMenstruacion.value) {
                if (this.value <= fechaUltimaMenstruacion.value) {
                    this.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback fecha-error d-block';
                    feedback.style.display = 'block';
                    feedback.textContent = 'La fecha probable de parto debe ser posterior a la fecha de última menstruación';
                    this.parentElement.appendChild(feedback);
                }
            }
        });
        
        fechaUltimaMenstruacion.addEventListener('change', function() {
            if (fechaProbableParto.value && this.value) {
                // Limpiar validaciones previas del campo de fecha probable de parto solo las dinámicas
                const feedbackExistente = fechaProbableParto.parentElement.querySelector('.invalid-feedback.fecha-error');
                if (feedbackExistente) {
                    feedbackExistente.remove();
                }
                fechaProbableParto.classList.remove('is-invalid');
                
                if (fechaProbableParto.value <= this.value) {
                    fechaProbableParto.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback fecha-error d-block';
                    feedback.style.display = 'block';
                    feedback.textContent = 'La fecha probable de parto debe ser posterior a la fecha de última menstruación';
                    fechaProbableParto.parentElement.appendChild(feedback);
                }
            }
        });
    }
    
    actualizarEstadoSecciones();
    
    const pacienteExistenteId = document.getElementById('paciente_existente').value;
    if (pacienteExistenteId) {
        cargarPacienteExistente(pacienteExistenteId);
    }
    
    // Limpiar mensajes de "campo obligatorio" al escribir
    const camposObligatorios = [
        'fecha_ultima_menstruacion', 'fecha_probable_parto', 'semanas_gestacion',
        'numero_partos', 'numero_abortos', 'presion_arterial', 'temperatura',
        'peso_actual', 'frecuencia_cardiaca_materna', 'edema'
    ];
    
    camposObligatorios.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.required-error');
                    if (feedbackExistente) {
                        feedbackExistente.remove();
                    }
                }
            });
            
            input.addEventListener('change', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    const feedbackExistente = this.parentElement.querySelector('.invalid-feedback.required-error');
                    if (feedbackExistente) {
                        feedbackExistente.remove();
                    }
                }
            });
        }
    });
    
    // Ocultar mensaje de éxito después de 5 segundos
    const mensajeExito = document.getElementById('mensajeExito');
    if (mensajeExito) {
        setTimeout(() => {
            mensajeExito.style.transition = 'opacity 0.5s ease-out';
            mensajeExito.style.opacity = '0';
            setTimeout(() => {
                mensajeExito.remove();
            }, 500);
        }, 5000);
    }
});

// ========== VALIDACIÓN DE FORMULARIO ANTES DE ENVIAR ==========
document.getElementById('formControlPrenatal').addEventListener('submit', function(e) {
    const pacienteId = document.getElementById('paciente_existente').value;
    const mensajeSeleccion = document.getElementById('mensajeSeleccionPaciente');
    
    if (!pacienteId) {
        e.preventDefault();
        mensajeSeleccion.style.display = 'block';
        setTimeout(() => {
            mensajeSeleccion.style.display = 'none';
        }, 4000);
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    }
    
    // Validar campos obligatorios
    const camposObligatorios = [
        { id: 'fecha_ultima_menstruacion', nombre: 'Fecha última menstruación' },
        { id: 'fecha_probable_parto', nombre: 'Fecha probable de parto' },
        { id: 'semanas_gestacion', nombre: 'Semanas de gestación' },
        { id: 'numero_partos', nombre: 'Número de partos' },
        { id: 'numero_abortos', nombre: 'Número de abortos' },
        { id: 'presion_arterial', nombre: 'Presión arterial' },
        { id: 'temperatura', nombre: 'Temperatura' },
        { id: 'peso_actual', nombre: 'Peso actual' },
        { id: 'frecuencia_cardiaca_materna', nombre: 'Frecuencia cardíaca materna' },
        { id: 'edema', nombre: 'Edema' }
    ];
    
    let camposFaltantes = [];
    let primerCampoInvalido = null;
    
    camposObligatorios.forEach(campo => {
        const input = document.getElementById(campo.id) || document.querySelector(`[name="${campo.id}"]`);
        if (input && !input.value.trim()) {
            camposFaltantes.push(campo.nombre);
            input.classList.add('is-invalid');
            
            // Agregar mensaje de error si no existe
            const feedbackExistente = input.parentElement.querySelector('.invalid-feedback.required-error');
            if (!feedbackExistente) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback required-error d-block';
                feedback.style.display = 'block';
                feedback.textContent = `Este campo es obligatorio`;
                input.parentElement.appendChild(feedback);
            }
            
            if (!primerCampoInvalido) primerCampoInvalido = input;
        } else if (input) {
            input.classList.remove('is-invalid');
            const feedbackExistente = input.parentElement.querySelector('.invalid-feedback.required-error');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }
        }
    });
    
    // Validar que no haya errores de rango
    const erroresRango = document.querySelectorAll('.invalid-feedback.rango-error');
    if (erroresRango.length > 0) {
        e.preventDefault();
        erroresRango[0].parentElement.querySelector('input, textarea, select').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    if (camposFaltantes.length > 0) {
        e.preventDefault();
        if (primerCampoInvalido) {
            primerCampoInvalido.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return false;
    }
    
    // Validar formato de presión arterial
    const presion = document.getElementById('presion_arterial').value;
    if (!/^\d{2,3}\/\d{2,3}$/.test(presion)) {
        e.preventDefault();
        const presionInput = document.getElementById('presion_arterial');
        presionInput.classList.add('is-invalid');
        
        // Limpiar mensaje previo solo dinámicos
        const feedbackExistente = presionInput.parentElement.querySelector('.invalid-feedback.formato-error');
        if (feedbackExistente) {
            feedbackExistente.remove();
        }
        
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback formato-error d-block';
        feedback.style.display = 'block';
        feedback.textContent = 'El formato de presión arterial debe ser: 120/80';
        presionInput.parentElement.appendChild(feedback);
        presionInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
});

// ========== CONTROL DE SECCIONES BLOQUEADAS ==========
function actualizarEstadoSecciones() {
    const pacienteId = document.getElementById('paciente_existente').value;
    const seccionesBloqueadas = document.getElementById('seccionesBloqueadas');
    const mensajeSeleccion = document.getElementById('mensajeSeleccionPaciente');
    const datosPaciente = document.getElementById('datosPacienteSeleccionada');
    
    if (pacienteId) {
        seccionesBloqueadas.classList.remove('seccion-bloqueada');
        mensajeSeleccion.style.display = 'none';
        datosPaciente.style.display = 'block';
        document.getElementById('seccionPaciente').classList.add('paciente-seleccionado');
    } else {
        seccionesBloqueadas.classList.add('seccion-bloqueada');
        mensajeSeleccion.style.display = 'none';
        datosPaciente.style.display = 'none';
        document.getElementById('seccionPaciente').classList.remove('paciente-seleccionado');
    }
}

// ========== BÚSQUEDA DE PACIENTES (SOLO FEMENINO) ==========
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
            // Filtrar solo pacientes femeninos
            const pacientesFemeninos = data.pacientes.filter(p => p.genero === 'Femenino');
            
            if (pacientesFemeninos.length > 0) {
                let html = '';
                pacientesFemeninos.forEach(p => {
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
                listaDiv.innerHTML = '<div class="no-resultados"><i class="fas fa-user-slash"></i><br>No se encontraron pacientes femeninos</div>';
                listaDiv.style.display = 'block';
            }
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
    cargarPacienteExistente(id, true);
}

function cargarPacienteExistente(id, esSeleccionManual = false) {
    fetch(`{{ url('pacientes/datos') }}/${id}`)
        .then(res => res.json())
        .then(data => {
            if(data.success && data.paciente) {
                const p = data.paciente;
                
                // Validar que sea femenino
                if (p.genero !== 'Femenino') {
                    if (esSeleccionManual) {
                        document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-warning mt-2"><i class="fas fa-exclamation-triangle"></i> Solo se pueden seleccionar pacientes de género femenino para controles prenatales.</div>';
                        setTimeout(() => { document.getElementById('mensajeBusqueda').innerHTML = ''; }, 5000);
                    }
                    return;
                }
                
                document.getElementById('paciente_existente').value = id;
                
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

                // Llenar los inputs visuales
                document.getElementById('nombre_label').value = p.nombre || '';
                document.getElementById('apellidos_label').value = p.apellidos || '';
                document.getElementById('identidad_label').value = p.identidad || '';
                
                let fechaNac = '';
                let fechaNacInput = '';
                let edad = '';
                
                if (p.fecha_nacimiento) {
                    try {
                        fechaNacInput = p.fecha_nacimiento.split('T')[0];
                        const partes = fechaNacInput.split('-');
                        fechaNac = `${partes[2]}/${partes[1]}/${partes[0]}`;
                        
                        // Calcular edad
                        const hoy = new Date();
                        const fechaNacimiento = new Date(fechaNacInput);
                        let edadNum = hoy.getFullYear() - fechaNacimiento.getFullYear();
                        const m = hoy.getMonth() - fechaNacimiento.getMonth();
                        if (m < 0 || (m === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                            edadNum--;
                        }
                        edad = edadNum + ' años';
                        document.getElementById('edad').value = edadNum;
                    } catch (e) {
                        fechaNac = p.fecha_nacimiento;
                        fechaNacInput = p.fecha_nacimiento;
                    }
                }
                
                document.getElementById('fecha_nacimiento').value = fechaNacInput;
                document.getElementById('fecha_nacimiento_label').value = fechaNac;
                document.getElementById('edad_label').value = edad;
                
                document.getElementById('telefono_label').value = p.telefono || '';
                document.getElementById('direccion_label').value = p.direccion || '';
                document.getElementById('genero_label').value = p.genero || '';
                document.getElementById('correo_label').value = p.correo || '';
                document.getElementById('tipo_sangre_label').value = p.tipo_sangre || '';
                document.getElementById('padecimientos_label').value = p.padecimientos || '';
                document.getElementById('medicamentos_label').value = p.medicamentos || '';
                document.getElementById('alergias_label').value = p.alergias || '';
                document.getElementById('historial_quirurgico_label').value = p.historial_quirurgico || '';
                document.getElementById('historial_clinico_label').value = p.historial_clinico || '';

                document.getElementById('buscarIdentidad').value = p.identidad || '';
                document.getElementById('listaResultados').style.display = 'none';

                actualizarEstadoSecciones();
            } else {
                if (esSeleccionManual) {
                    document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-danger mt-2">No se encontraron datos del paciente</div>';
                }
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
        if(label) label.value = '';
    });
    
    // Limpiar campo de edad también
    const edadLabel = document.getElementById('edad_label');
    if(edadLabel) edadLabel.value = '';
    
    actualizarEstadoSecciones();
}

document.addEventListener('click', function(e) {
    const buscarInput = document.getElementById('buscarIdentidad');
    const listaDiv = document.getElementById('listaResultados');
    if(e.target !== buscarInput && !listaDiv.contains(e.target)) {
        listaDiv.style.display = 'none';
    }
});

// ========== LIMPIAR FORMULARIO ==========
document.getElementById('btnLimpiar').addEventListener('click', () => {
    const form = document.querySelector('form');
    
    form.reset();
    
    // Limpiar todas las validaciones
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // Limpiar mensajes de error dinámicos
    document.querySelectorAll('.rango-error, .formato-error, .fecha-error, .required-error').forEach(el => el.remove());
    
    // Resetear contadores
    document.querySelectorAll('.char-counter span').forEach(span => {
        if (span.id.startsWith('counter_')) {
            span.textContent = '0';
            span.classList.remove('text-danger');
        }
    });
    
    document.getElementById('buscarIdentidad').value = '';
    document.getElementById('listaResultados').style.display = 'none';
    document.getElementById('listaResultados').innerHTML = '';
    document.getElementById('mensajeBusqueda').innerHTML = '';
    
    limpiarCamposPaciente();
    
    document.querySelector('input[name="fecha_control"]').value = '{{ date("Y-m-d") }}';
    
    // Scroll al inicio
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>

@endsection