@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0faff;
    }
    
    .custom-card {
        max-width: 1000px;
        background-color: rgba(255, 255, 255, 0.95);
        border: 1px solid #91cfff;
        border-radius: 0.5rem;
        position: relative;
        overflow: hidden;
        margin: 2rem auto;
        padding: 2rem;
        box-shadow: 0 0 15px rgba(0,123,255,0.25);
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
        opacity: 0.15;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }
    
    label {
        font-size: 0.85rem;
    }
    
    input, select, textarea {
        font-size: 0.85rem !important;
    }
    
    .valid-feedback {
        display: block;
        font-size: 0.85rem;
        color: #198754;
    }
    
    .form-control.is-valid {
        border-color: #198754;
        padding-right: 2.5rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23198754' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' class='bi bi-check-lg' viewBox='0 0 16 16'%3e%3cpath d='M13 4.5 6 11.5 3 8.5'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem 1rem;
    }
    
    label.is-invalid {
        color: #dc3545;
        font-weight: 600;
    }
</style>

<div class="custom-card">
    <!-- Título del formulario con borde azul abajo -->
    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Registrar nuevo empleado</h2>
    </div>

    <form action="{{ route('empleado.store') }}" method="POST" novalidate>
        @csrf

        <!-- Fila 1 -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres') }}" required maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
                @error('nombres') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" value="{{ old('apellidos') }}" required maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
                @error('apellidos') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="identidad" class="form-label">Identidad <span class="text-danger">*</span></label>
                <input type="text" name="identidad" id="identidad" class="form-control" value="{{ old('identidad') }}" required maxlength="13" pattern="\d{13}" title="Debe ingresar exactamente 13 números">
                @error('identidad') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}" required maxlength="8" pattern="[2389]\d{7}" title="Debe comenzar con 2, 3, 8 o 9 y tener 8 dígitos">
                @error('telefono') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Fila 2 -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                @php $fechaMax = now()->subYears(18)->format('Y-m-d'); @endphp
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}" max="{{ $fechaMax }}" required>
                @error('fecha_nacimiento') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
                <select name="genero" id="genero" class="form-select" required>
                    <option value="">-- Selecciona --</option>
                    <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
                @error('genero') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="estado_civil" class="form-label">Estado Civil</label>
                <select name="estado_civil" id="estado_civil" class="form-select">
                    <option value="">-- Selecciona --</option>
                    <option value="Soltero" {{ old('estado_civil') == 'Soltero' ? 'selected' : '' }}>Soltero</option>
                    <option value="Casado" {{ old('estado_civil') == 'Casado' ? 'selected' : '' }}>Casado</option>
                    <option value="Divorciado" {{ old('estado_civil') == 'Divorciado' ? 'selected' : '' }}>Divorciado</option>
                    <option value="Viudo" {{ old('estado_civil') == 'Viudo' ? 'selected' : '' }}>Viudo</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="correo" class="form-label">Correo <span class="text-danger">*</span></label>
                <input type="email" name="correo" id="correo" class="form-control" value="{{ old('correo') }}" required maxlength="30">
                @error('correo') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Fila 3 -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso <span class="text-danger">*</span></label>
                @php
                    $hoy = now(); 
                    $minIngreso = $hoy->copy()->subMonth()->format('Y-m-d');
                    $maxIngreso = $hoy->copy()->addMonth()->format('Y-m-d');
                @endphp
                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso') }}" min="{{ $minIngreso }}" max="{{ $maxIngreso }}" required>
                @error('fecha_ingreso') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="area" class="form-label">Área <span class="text-danger">*</span></label>
                <select name="area" id="area" class="form-select" required>
                    <option value="">-- Selecciona un área --</option>
                    <option value="Administración" data-sueldo="15000" {{ old('area') == 'Administración' ? 'selected' : '' }}>Administración</option>
                    <option value="Recepción" data-sueldo="12000" {{ old('area') == 'Recepción' ? 'selected' : '' }}>Recepción</option>
                    <option value="Laboratorio" data-sueldo="18000" {{ old('area') == 'Laboratorio' ? 'selected' : '' }}>Laboratorio</option>
                    <option value="Farmacia" data-sueldo="16000" {{ old('area') == 'Farmacia' ? 'selected' : '' }}>Farmacia</option>
                    <option value="Enfermería" data-sueldo="17000" {{ old('area') == 'Enfermería' ? 'selected' : '' }}>Enfermería</option>
                    <option value="Mantenimiento" data-sueldo="11000" {{ old('area') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                </select>
                @error('area') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="puesto_id" class="form-label">Puesto <span class="text-danger">*</span></label>
                <select name="puesto_id" id="puesto_id" class="form-select" required>
                    <option value="">-- Selecciona un puesto --</option>
                    @foreach($puestos as $puesto)
                        <option value="{{ $puesto->id }}" data-sueldo="{{ $puesto->sueldo }}" {{ old('puesto_id') == $puesto->id ? 'selected' : '' }}>
                            {{ $puesto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('puesto_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="salario_mostrar" class="form-label">Salario (Lps.)</label>
                <input type="text" id="salario_mostrar" class="form-control" readonly placeholder="Se asigna automáticamente">
                <input type="hidden" name="salario" id="salario" value="{{ old('salario') ?? '' }}">
                @error('salario') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Fila 4 -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="turno_asignado" class="form-label">Turno Asignado <span class="text-danger">*</span></label>
                <select name="turno_asignado" id="turno_asignado" class="form-select" required>
                    <option value="">-- Selecciona --</option>
                    <option value="Mañana" {{ old('turno_asignado') == 'Mañana' ? 'selected' : '' }}>Mañana</option>
                    <option value="Tarde" {{ old('turno_asignado') == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                    <option value="Noche" {{ old('turno_asignado') == 'Noche' ? 'selected' : '' }}>Noche</option>
                </select>
                @error('turno_asignado') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="direccion" class="form-label">Dirección <span class="text-danger">*</span></label>
                <textarea name="direccion" id="direccion" class="form-control" rows="3" required maxlength="100">{{ old('direccion') }}</textarea>
                @error('direccion') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-5">
                <label for="observaciones" class="form-label">Observaciones <span class="text-danger">*</span></label>
                <textarea name="observaciones" id="observaciones" class="form-control" rows="3" required maxlength="100">{{ old('observaciones') }}</textarea>
                @error('observaciones') <div class="text-danger small">{{ $message }}</div> @enderror
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
            <a href="{{ route('empleado.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </form>
</div>

<script>
    // Limpiar formulario COMPLETAMENTE
    document.getElementById('btnLimpiar').addEventListener('click', () => {
        // 1. Limpiar localStorage
        localStorage.clear();
        
        // 2. Obtener el formulario
        const form = document.querySelector('form');
        
        // 3. Resetear formulario
        form.reset();
        
        // 4. Limpiar TODOS los campos manualmente
        form.querySelectorAll('input, select, textarea').forEach(el => {
            if (el.tagName.toLowerCase() === 'select') {
                el.selectedIndex = 0; // Volver a la primera opción
            } else if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = false;
            } else {
                el.value = ''; // Vaciar completamente
            }
            
            // 5. ELIMINAR todas las clases de validación
            el.classList.remove('is-valid', 'is-invalid');
            
            // 6. Limpiar validaciones HTML5
            el.setCustomValidity('');
        });
        
        // 7. Limpiar campos específicos de salario
        const salarioMostrar = document.getElementById('salario_mostrar');
        const salarioInput = document.getElementById('salario');
        if(salarioMostrar) {
            salarioMostrar.value = '';
            salarioMostrar.classList.remove('is-valid', 'is-invalid');
        }
        if(salarioInput) {
            salarioInput.value = '';
        }
        
        // 8. ELIMINAR TODOS los mensajes de error del servidor (Laravel)
        document.querySelectorAll('.text-danger.small, .invalid-feedback, .error-text').forEach(errorMsg => {
            errorMsg.remove();
        });
        
        // 9. Limpiar cualquier alert o mensaje de éxito
        document.querySelectorAll('.alert, .alert-success, .alert-danger, .alert-warning').forEach(alert => {
            alert.remove();
        });
        
        // 10. Enfocar el primer campo del formulario
        const primerCampo = form.querySelector('input, select, textarea');
        if(primerCampo) {
            primerCampo.focus();
        }
        
        console.log('Formulario limpiado completamente');
    });

    // Mostrar sueldo al seleccionar puesto
    const puestoSelect = document.getElementById('puesto_id');
    const salarioMostrar = document.getElementById('salario_mostrar');
    const salarioInput = document.getElementById('salario');

    function actualizarSalario() {
        const seleccionado = puestoSelect.options[puestoSelect.selectedIndex];
        const sueldo = seleccionado ? seleccionado.getAttribute('data-sueldo') : '';
        salarioMostrar.value = sueldo ? Number(sueldo).toLocaleString('es-HN', {minimumFractionDigits: 2}) : '';
        salarioInput.value = sueldo || '';
    }

    window.addEventListener('load', actualizarSalario);
    puestoSelect.addEventListener('change', actualizarSalario);

    // Limitar identidad a solo números (máximo 13)
    const identidadInput = document.getElementById('identidad');
    if(identidadInput) {
        identidadInput.addEventListener('input', (e) => {
            // Eliminar cualquier caracter que no sea número
            e.target.value = e.target.value.replace(/\D/g, '');
            // Limitar a 13 caracteres
            if(e.target.value.length > 13) {
                e.target.value = e.target.value.slice(0, 13);
            }
        });
    }

    // Limitar teléfono a solo números (máximo 8)
    const telefonoInput = document.getElementById('telefono');
    if(telefonoInput) {
        telefonoInput.addEventListener('input', (e) => {
            // Eliminar cualquier caracter que no sea número
            e.target.value = e.target.value.replace(/\D/g, '');
            // Limitar a 8 caracteres
            if(e.target.value.length > 8) {
                e.target.value = e.target.value.slice(0, 8);
            }
            
            // Validar que comience con 2, 3, 8 o 9
            const val = e.target.value.trim();
            const regex = /^[2389]\d{7}$/;
            if (val.length === 8 && regex.test(val)) {
                e.target.setCustomValidity('');
            } else if (val.length > 0) {
                e.target.setCustomValidity('Debe comenzar con 2, 3, 8 o 9 y tener 8 dígitos');
            }
        });
    }

    // Limitar nombres a solo letras y espacios
    const nombresInput = document.getElementById('nombres');
    if(nombresInput) {
        nombresInput.addEventListener('input', (e) => {
            // Solo permitir letras (incluyendo acentos) y espacios
            e.target.value = e.target.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
        });
    }

    // Limitar apellidos a solo letras y espacios
    const apellidosInput = document.getElementById('apellidos');
    if(apellidosInput) {
        apellidosInput.addEventListener('input', (e) => {
            // Solo permitir letras (incluyendo acentos) y espacios
            e.target.value = e.target.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
        });
    }

    // Validar correo en tiempo real
    const correoInput = document.getElementById('correo');
    if(correoInput) {
        correoInput.addEventListener('input', () => {
            const val = correoInput.value.trim();
            const puntos = (val.match(/\./g) || []).length;
            const regex = /^[a-zA-Z0-9@.]+$/;
            if (val.length <= 30 && regex.test(val) && puntos <= 3) {
                correoInput.setCustomValidity('');
            } else {
                correoInput.setCustomValidity('Correo inválido');
            }
        });
    }

    // NO cargar datos automáticamente al entrar
    // El formulario inicia vacío

    // Validación dinámica y cambio de clases
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');

        inputs.forEach(input => {
            input.addEventListener('input', () => validateField(input));
            input.addEventListener('change', () => validateField(input));
        });

        function validateField(field) {
            if (field.checkValidity()) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                const errorDiv = field.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('error-text')) {
                    errorDiv.style.display = 'none';
                    errorDiv.textContent = '';
                }
            } else {
                field.classList.remove('is-valid');
                if(!field.classList.contains('is-invalid')) {
                    field.classList.add('is-invalid');
                }
            }
        }
    });
</script>

@endsection