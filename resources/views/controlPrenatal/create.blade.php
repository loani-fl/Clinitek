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
    border-color: red;
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
</style>

<div class="custom-card">
    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Nuevo control prenatal</h2>
    </div>

    <form action="{{ route('controles-prenatales.store') }}" method="POST">
        @csrf

        <!-- SECCIÓN 1: SELECCIÓN O REGISTRO DE PACIENTE -->
        <div class="mb-4">
            <h5 class="text-dark fw-bold mb-3">
                <i class="fas fa-user-circle"></i> Datos de la paciente
            </h5>
            
            <!-- Buscador de pacientes -->
            <div class="d-flex align-items-center mb-4" style="gap: 1.25rem;">
                <label class="fw-bold mb-0" style="white-space: nowrap; font-size: 0.95rem;">
                    <i class="fas fa-search"></i> BUSCAR PACIENTE POR IDENTIDAD
                </label>
                <div style="position: relative; flex-grow: 1; max-width: 350px;">
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
            </div>
            <div id="mensajeBusqueda" class="mt-1"></div>

            <input type="hidden" name="paciente_existente" id="paciente_existente" value="{{ old('paciente_existente') }}">

            <!-- Campos para Nueva Paciente -->
            <div id="campos_nueva_paciente">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" maxlength="50"
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre') }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Apellidos <span class="text-danger">*</span></label>
                        <input type="text" name="apellidos" id="apellidos" maxlength="50"
                               class="form-control @error('apellidos') is-invalid @enderror" 
                               value="{{ old('apellidos') }}">
                        @error('apellidos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Identidad <span class="text-danger">*</span></label>
                        <input type="text" name="identidad" id="identidad" maxlength="13"
                               placeholder="0000-0000-00000"
                               class="form-control @error('identidad') is-invalid @enderror" 
                               value="{{ old('identidad') }}">
                        @error('identidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Fecha de nacimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                               class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                               value="{{ old('fecha_nacimiento') }}">
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Teléfono <span class="text-danger">*</span></label>
                        <input type="text" name="telefono" id="telefono" maxlength="8"
                               placeholder="0000-0000"
                               class="form-control @error('telefono') is-invalid @enderror" 
                               value="{{ old('telefono') }}">
                        <small class="text-muted">Debe iniciar con 2, 3, 8 o 9</small>
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Correo electrónico</label>
                        <input type="email" name="correo" id="correo" maxlength="50"
                               placeholder="ejemplo@correo.com"
                               class="form-control @error('correo') is-invalid @enderror" 
                               value="{{ old('correo') }}">
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Tipo de sangre</label>
                        <select name="tipo_sangre" id="tipo_sangre" 
                                class="form-select @error('tipo_sangre') is-invalid @enderror">
                            <option value="">Seleccione...</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_sangre') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                        @error('tipo_sangre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label>Género <span class="text-danger">*</span></label>
                        <select name="genero" id="genero" 
                                class="form-select @error('genero') is-invalid @enderror">
                            <option value="">Seleccione...</option>
                            <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        </select>
                        @error('genero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Dirección <span class="text-danger">*</span></label>
                        <textarea name="direccion" id="direccion" rows="2" maxlength="300"
                                  class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion') }}</textarea>
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Padecimientos</label>
                        <textarea name="padecimientos" id="padecimientos" rows="2" maxlength="200"
                                  class="form-control @error('padecimientos') is-invalid @enderror">{{ old('padecimientos') }}</textarea>
                        @error('padecimientos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label>Medicamentos</label>
                        <textarea name="medicamentos" id="medicamentos" rows="2" maxlength="200"
                                  class="form-control @error('medicamentos') is-invalid @enderror">{{ old('medicamentos') }}</textarea>
                        @error('medicamentos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Alergias</label>
                        <textarea name="alergias" id="alergias" rows="2" maxlength="200"
                                  class="form-control @error('alergias') is-invalid @enderror">{{ old('alergias') }}</textarea>
                        @error('alergias')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label>Historial quirúrgico</label>
                        <textarea name="historial_quirurgico" id="historial_quirurgico" rows="2" maxlength="200"
                                  class="form-control @error('historial_quirurgico') is-invalid @enderror">{{ old('historial_quirurgico') }}</textarea>
                        @error('historial_quirurgico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Historial clínico</label>
                        <textarea name="historial_clinico" id="historial_clinico" rows="2" maxlength="200"
                                  class="form-control @error('historial_clinico') is-invalid @enderror">{{ old('historial_clinico') }}</textarea>
                        @error('historial_clinico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: DATOS OBSTÉTRICOS -->
        <div class="mb-4">
            <h5 class="text-dark fw-bold mb-3">Datos obstétricos</h5>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Fecha ultima menstruación <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_ultima_menstruacion" 
                           class="form-control @error('fecha_ultima_menstruacion') is-invalid @enderror" 
                           value="{{ old('fecha_ultima_menstruacion') }}">
                    @error('fecha_ultima_menstruacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label>Fecha probable de parto <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_probable_parto" 
                           class="form-control @error('fecha_probable_parto') is-invalid @enderror" 
                           value="{{ old('fecha_probable_parto') }}">
                    @error('fecha_probable_parto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label>Semanas de gestación <span class="text-danger">*</span></label>
                    <input type="number" name="semanas_gestacion" min="0" max="42"
                           class="form-control @error('semanas_gestacion') is-invalid @enderror" 
                           value="{{ old('semanas_gestacion') }}" placeholder="0-42">
                    <small class="text-muted">Rango: 0-42 semanas</small>
                    @error('semanas_gestacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Número de gestaciones <span class="text-danger">*</span></label>
                    <input type="number" name="numero_gestaciones" min="1" max="20"
                           class="form-control @error('numero_gestaciones') is-invalid @enderror" 
                           value="{{ old('numero_gestaciones') }}" placeholder="1-20">
                    @error('numero_gestaciones')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label>Número de partos <span class="text-danger">*</span></label>
                    <input type="number" name="numero_partos" min="0" max="20"
                           class="form-control @error('numero_partos') is-invalid @enderror" 
                           value="{{ old('numero_partos') }}" placeholder="0-20">
                    @error('numero_partos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label>Número de abortos <span class="text-danger">*</span></label>
                    <input type="number" name="numero_abortos" min="0" max="20"
                           class="form-control @error('numero_abortos') is-invalid @enderror" 
                           value="{{ old('numero_abortos') }}" placeholder="0-20">
                    @error('numero_abortos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label>Número de hijos vivos <span class="text-danger">*</span></label>
                    <input type="number" name="numero_hijos_vivos" min="0" max="20"
                           class="form-control @error('numero_hijos_vivos') is-invalid @enderror" 
                           value="{{ old('numero_hijos_vivos') }}" placeholder="0-20">
                    @error('numero_hijos_vivos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Tipo de partos anteriores</label>
                    <input type="text" name="tipo_partos_anteriores" maxlength="255"
                           class="form-control @error('tipo_partos_anteriores') is-invalid @enderror" 
                           value="{{ old('tipo_partos_anteriores') }}" 
                           placeholder="Ej: 2 naturales, 1 cesárea">
                    @error('tipo_partos_anteriores')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label>Complicaciones en embarazos previos</label>
                    <textarea name="complicaciones_previas" rows="2"
                              class="form-control @error('complicaciones_previas') is-invalid @enderror">{{ old('complicaciones_previas') }}</textarea>
                    @error('complicaciones_previas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: ANTECEDENTES MÉDICOS -->
        <div class="mb-4">
            <h5 class="text-dark fw-bold mb-3">Antecedentes médicos</h5>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Enfermedades crónicas</label>
                    <textarea name="enfermedades_cronicas" rows="2"
                              class="form-control @error('enfermedades_cronicas') is-invalid @enderror" 
                              placeholder="Diabetes, hipertensión, etc.">{{ old('enfermedades_cronicas') }}</textarea>
                    @error('enfermedades_cronicas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label>Alergias</label>
                    <textarea name="alergias_control" rows="2"
                              class="form-control @error('alergias_control') is-invalid @enderror" 
                              placeholder="Alergias medicamentosas o alimentarias">{{ old('alergias_control') }}</textarea>
                    @error('alergias_control')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Cirugías previas</label>
                    <textarea name="cirugias_previas" rows="2"
                              class="form-control @error('cirugias_previas') is-invalid @enderror" 
                              placeholder="Cirugías realizadas previamente">{{ old('cirugias_previas') }}</textarea>
                    @error('cirugias_previas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label>Medicamentos actuales</label>
                    <textarea name="medicamentos_actuales" rows="2"
                              class="form-control @error('medicamentos_actuales') is-invalid @enderror" 
                              placeholder="Medicamentos que toma actualmente">{{ old('medicamentos_actuales') }}</textarea>
                    @error('medicamentos_actuales')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Hábitos</label>
                    <textarea name="habitos" rows="2"
                              class="form-control @error('habitos') is-invalid @enderror" 
                              placeholder="Tabaquismo, alcohol, ejercicio, etc.">{{ old('habitos') }}</textarea>
                    @error('habitos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label>Antecedentes familiares</label>
                    <textarea name="antecedentes_familiares" rows="2"
                              class="form-control @error('antecedentes_familiares') is-invalid @enderror" 
                              placeholder="Enfermedades hereditarias o familiares">{{ old('antecedentes_familiares') }}</textarea>
                    @error('antecedentes_familiares')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECCIÓN 4: DATOS DEL CONTROL ACTUAL -->
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
                    <input type="number" name="peso_actual" 
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
                    <select name="edema" 
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

        <!-- SECCIÓN 5: TRATAMIENTOS Y RECOMENDACIONES -->
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
    </form>
</div>

<script>
// ========== BÚSQUEDA DE PACIENTES ==========
let timeoutBusqueda = null;

document.getElementById('buscarIdentidad').addEventListener('input', function() {
    const busqueda = this.value.trim();
    const listaDiv = document.getElementById('listaResultados');
    const mensajeDiv = document.getElementById('mensajeBusqueda');

    clearTimeout(timeoutBusqueda);

    if (!busqueda || busqueda.length < 3) {
        listaDiv.style.display = 'none';
        listaDiv.innerHTML = '';
        mensajeDiv.innerHTML = '';
        if (!busqueda) limpiarCamposPaciente();
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
    fetch(`{{ url('pacientes/datos') }}/${id}`)
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.getElementById('paciente_existente').value = id;
                document.getElementById('nombre').value = data.paciente.nombre || '';
                document.getElementById('apellidos').value = data.paciente.apellidos || '';
                document.getElementById('identidad').value = data.paciente.identidad || '';
                document.getElementById('fecha_nacimiento').value = data.paciente.fecha_nacimiento || '';
                document.getElementById('telefono').value = data.paciente.telefono || '';
                document.getElementById('direccion').value = data.paciente.direccion || '';
                document.getElementById('genero').value = data.paciente.genero || '';
                if(data.paciente.correo) document.getElementById('correo').value = data.paciente.correo;
                if(data.paciente.tipo_sangre) document.getElementById('tipo_sangre').value = data.paciente.tipo_sangre;
                if(data.paciente.padecimientos) document.getElementById('padecimientos').value = data.paciente.padecimientos;
                if(data.paciente.medicamentos) document.getElementById('medicamentos').value = data.paciente.medicamentos;
                if(data.paciente.alergias) document.getElementById('alergias').value = data.paciente.alergias;
                if(data.paciente.historial_quirurgico) document.getElementById('historial_quirurgico').value = data.paciente.historial_quirurgico;
                if(data.paciente.historial_clinico) document.getElementById('historial_clinico').value = data.paciente.historial_clinico;

                const camposBloquear = ['nombre', 'apellidos', 'identidad', 'fecha_nacimiento', 'telefono', 'direccion', 'genero', 'tipo_sangre', 'correo', 'padecimientos', 'medicamentos', 'alergias', 'historial_quirurgico', 'historial_clinico'];
                camposBloquear.forEach(campo => {
                    const elemento = document.getElementById(campo);
                    if(elemento) {
                        elemento.setAttribute('readonly', true);
                        elemento.style.backgroundColor = '#e9ecef';
                        elemento.style.cursor = 'not-allowed';
                    }
                });

                document.getElementById('buscarIdentidad').value = data.paciente.identidad;
                document.getElementById('listaResultados').style.display = 'none';
                document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-success mt-2"><i class="fas fa-check-circle"></i> Paciente cargada correctamente</div>';

                setTimeout(() => { document.getElementById('mensajeBusqueda').innerHTML = ''; }, 3000);
            }
        })
        .catch(() => {
            document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-danger mt-2">Error al cargar datos del paciente</div>';
        });
}

function limpiarCamposPaciente() {
    document.getElementById('paciente_existente').value = '';
    const campos = ['nombre','apellidos','identidad','fecha_nacimiento','telefono','correo','tipo_sangre','genero','direccion','padecimientos','medicamentos','alergias','historial_quirurgico','historial_clinico'];
    campos.forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            if(el.tagName.toLowerCase() === 'select') el.selectedIndex = 0;
            else el.value = '';
            
            el.removeAttribute('readonly');
            el.style.backgroundColor = '';
            el.style.cursor = '';
        }
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

// Validación de teléfono - solo 2, 3, 8, 9
document.getElementById('telefono').addEventListener('input', function(e) {
    let valor = this.value.replace(/[^0-9]/g, '');
    
    // Limitar a 8 dígitos
    if (valor.length > 8) {
        valor = valor.substring(0, 8);
    }
    
    // Validar primer dígito
    if (valor.length > 0 && !/^[2389]/.test(valor)) {
        valor = '';
    }
    
    this.value = valor;
});

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

// Permitir solo números en identidad
document.getElementById('identidad').addEventListener('keypress', function(e){
    const char = String.fromCharCode(e.which);
    if(!/[0-9]/.test(char)) e.preventDefault();
});

// Permitir solo letras y espacios en nombres y apellidos
['nombre','apellidos'].forEach(id => {
    document.getElementById(id).addEventListener('keypress', function(e){
        const char = String.fromCharCode(e.which);
        if(!/[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/.test(char)) e.preventDefault();
    });
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