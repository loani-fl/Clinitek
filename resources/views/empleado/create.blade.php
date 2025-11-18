@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0faff;
        max-width: 97%;
        border-color: #91cfff;
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

<!-- Barra de navegación azul médico -->
<!-- Barra de navegación que abarca todo el ancho -->
<div class="w-100" style="background-color: #007BFF;">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
        <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>
</div>


<div class="card custom-card shadow-sm border rounded-4 w-100 mt-4">
    <div class="card-header bg-primary text-white py-2">
        <h3 class="mb-0">Registrar nuevo empleado</h5>
        </div>
        <form action="{{ route('empleado.store') }}" method="POST" novalidate>
         @csrf

            <div class="row g-3">

            <div class="row mt-4">
        <!-- Columna 1 -->
        <div class="col-md-3 d-flex flex-column gap-2">
            {{-- Nombres --}}
            <div>
                <label for="nombres" class="form-label fw-semibold text-muted">Nombres <span class="text-danger">*</span></label>
                <input type="text" name="nombres" id="nombres" class="form-control form-control-sm" value="{{ old('nombres') }}" required maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
                @error('nombres') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Fecha de Nacimiento --}}
            <div>
                @php $fechaMax = now()->subYears(18)->format('Y-m-d'); @endphp
                <label for="fecha_nacimiento" class="form-label fw-semibold text-muted">Fecha de Nacimiento <span class="text-danger">*</span></label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control form-control-sm" value="{{ old('fecha_nacimiento') }}" max="{{ $fechaMax }}" required>
                @error('fecha_nacimiento') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Fecha de Ingreso --}}
            <div>
                @php
                    $hoy = now(); 
                    $minIngreso = $hoy->copy()->subMonth()->format('Y-m-d');
                    $maxIngreso = $hoy->copy()->addMonth()->format('Y-m-d');
                @endphp
                <label for="fecha_ingreso" class="form-label fw-semibold text-muted">Fecha de Ingreso <span class="text-danger">*</span></label>
                <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control form-control-sm" value="{{ old('fecha_ingreso') }}" min="{{ $minIngreso }}" max="{{ $maxIngreso }}" required>
                @error('fecha_ingreso') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Observaciones (último de columna 1) --}}
            <div>
                <label for="observaciones" class="form-label fw-semibold text-muted">Observaciones <span class="text-danger">*</span></label>
                <textarea name="observaciones" id="observaciones" class="form-control form-control-sm" rows="2" required maxlength="100">{{ old('observaciones') }}</textarea>
                @error('observaciones') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Columna 2 -->
        <div class="col-md-3 d-flex flex-column gap-2">
            {{-- Apellidos --}}
            <div>
                <label for="apellidos" class="form-label fw-semibold text-muted">Apellidos <span class="text-danger">*</span></label>
                <input type="text" name="apellidos" id="apellidos" class="form-control form-control-sm" value="{{ old('apellidos') }}" required maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
                @error('apellidos') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Género --}}
            <div>
                <label for="genero" class="form-label fw-semibold text-muted">Género <span class="text-danger">*</span></label>
                <select name="genero" id="genero" class="form-select form-select-sm" required>
                    <option value="">-- Selecciona --</option>
                    <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
                @error('genero') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Área --}}
            <div>
                <label for="area" class="form-label fw-semibold text-muted">Área <span class="text-danger">*</span></label>
                <select name="area" id="area" class="form-select form-select-sm" required onchange="autoFillSueldo()">
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
        </div>

        <!-- Columna 3 -->
        <div class="col-md-3 d-flex flex-column gap-2">
            {{-- Identidad --}}
            <div>
                <label for="identidad" class="form-label fw-semibold text-muted">Identidad <span class="text-danger">*</span></label>
                <input type="text" name="identidad" id="identidad" class="form-control form-control-sm" value="{{ old('identidad') }}" required>
                @error('identidad') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Estado Civil --}}
            <div>
                <label for="estado_civil" class="form-label fw-semibold text-muted">Estado Civil</label>
                <select name="estado_civil" id="estado_civil" class="form-select form-select-sm">
                    <option value="">-- Selecciona --</option>
                    <option value="Soltero" {{ old('estado_civil') == 'Soltero' ? 'selected' : '' }}>Soltero</option>
                    <option value="Casado" {{ old('estado_civil') == 'Casado' ? 'selected' : '' }}>Casado</option>
                    <option value="Divorciado" {{ old('estado_civil') == 'Divorciado' ? 'selected' : '' }}>Divorciado</option>
                    <option value="Viudo" {{ old('estado_civil') == 'Viudo' ? 'selected' : '' }}>Viudo</option>
                </select>
            </div>

            {{-- Puesto --}}
            <div>
                <label for="puesto_id" class="form-label fw-semibold text-muted">Puesto <span class="text-danger">*</span></label>
                <select name="puesto_id" id="puesto_id" class="form-select form-select-sm" required>
                    <option value="">-- Selecciona un puesto --</option>
                    @foreach($puestos as $puesto)
                        <option value="{{ $puesto->id }}" data-sueldo="{{ $puesto->sueldo }}" {{ old('puesto_id') == $puesto->id ? 'selected' : '' }}>
                            {{ $puesto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('puesto_id') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Salario (solo lectura) (último de columna 3) --}}
            <div>
                <label for="salario" class="form-label fw-semibold text-muted">Salario (Lps.)</label>
                <input type="text" name="salario" id="salario" class="form-control form-control-sm" readonly value="{{ old('salario') ?? '' }}" placeholder="Se asigna automáticamente">
                @error('salario') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- Columna 4 -->
        <div class="col-md-3 d-flex flex-column gap-2">
            {{-- Teléfono --}}
            <div>
                <label for="telefono" class="form-label fw-semibold text-muted">Teléfono <span class="text-danger">*</span></label>
                <input type="text" name="telefono" id="telefono" class="form-control form-control-sm" value="{{ old('telefono') }}" required>
                @error('telefono') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Correo --}}
            <div>
                <label for="correo" class="form-label fw-semibold text-muted">Correo <span class="text-danger">*</span></label>
                <input type="email" name="correo" id="correo" class="form-control form-control-sm" value="{{ old('correo') }}" required maxlength="30">
                @error('correo') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Turno --}}
            <div>
                <label for="turno_asignado" class="form-label fw-semibold text-muted">Turno Asignado <span class="text-danger">*</span></label>
                <select name="turno_asignado" id="turno_asignado" class="form-select form-select-sm" required>
                    <option value="">-- Selecciona --</option>
                    <option value="Mañana" {{ old('turno_asignado') == 'Mañana' ? 'selected' : '' }}>Mañana</option>
                    <option value="Tarde" {{ old('turno_asignado') == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                    <option value="Noche" {{ old('turno_asignado') == 'Noche' ? 'selected' : '' }}>Noche</option>
                </select>
                @error('turno_asignado') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Dirección (último de columna 4) --}}
            <div>
                <label for="direccion" class="form-label fw-semibold text-muted">Dirección <span class="text-danger">*</span></label>
                <textarea name="direccion" id="direccion" class="form-control form-control-sm" rows="2" required maxlength="100">{{ old('direccion') }}</textarea>
                @error('direccion') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>


                        {{-- Botones --}}
                    <div class="d-flex justify-content-center mt-5 gap-4">
                        <!-- Botón Registrar: azul con ícono plus-circle -->
                        <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Registrar
                    </button>

                    <!-- Botón Limpiar: amarillo con ícono trash -->
                    <button type="button" id="btnLimpiar" class="btn btn-warning btn-sm px-4 shadow-sm">
                        <i class="bi bi-trash"></i> Limpiar
                    </button>

                    <!-- Botón Regresar: verde con ícono arrow-left -->
                    <a href="{{ route('empleado.index') }}" class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>

                    </div>
    

                    </div>
                </div>

                <script>
                    // Limpiar formulario
                    document.getElementById('btnLimpiar').addEventListener('click', () => {
                    localStorage.clear();  // Limpia localStorage

                    const form = document.querySelector('form');
                    form.reset(); // Resetea el formulario (vuelve a valores old())

                    // Vacía todos los campos para sobreescribir los valores old()
                    form.querySelectorAll('input, select, textarea').forEach(el => {
                        if (el.tagName.toLowerCase() === 'select') {
                            el.selectedIndex = 0; // Opción vacía
                        } else {
                            el.value = '';
                        }
                    });

                    // Limpia campo sueldo (solo lectura)
                    document.getElementById('sueldo_label').value = '';
                    document.getElementById('salario').value = '';
                });


                    // Mostrar sueldo al seleccionar puesto
                    const puestoSelect = document.getElementById('puesto_id');
                    const sueldoLabel = document.getElementById('sueldo_label');
                    const salarioInput = document.getElementById('salario');

                    puestoSelect.addEventListener('change', () => {
                        const option = puestoSelect.options[puestoSelect.selectedIndex];
                        const sueldo = option.getAttribute('data-sueldo') || '';
                        sueldoLabel.value = sueldo ? parseFloat(sueldo).toFixed(2) : '';
                        salarioInput.value = sueldo || '';
                    });

                    // Validar teléfono en tiempo real
                    const telefonoInput = document.getElementById('telefono');
                    const telefonoError = document.getElementById('telefono-error');

                    telefonoInput.addEventListener('input', () => {
                        const val = telefonoInput.value.trim();
                        const regex = /^[238]\d{7}$/;
                        if (regex.test(val)) {
                            telefonoError.style.display = 'none';
                            telefonoInput.setCustomValidity('');
                        } else {
                            telefonoError.style.display = 'block';
                            telefonoInput.setCustomValidity('Teléfono inválido');
                        }
                    });

                    // Validar correo en tiempo real
                    const correoInput = document.getElementById('correo');
                    const correoError = document.getElementById('correo-error');

                    correoInput.addEventListener('input', () => {
                        const val = correoInput.value.trim();
                        // Validar longitud y caracteres simples: letras, números, @, y hasta 3 puntos
                        const puntos = (val.match(/\./g) || []).length;
                        const regex = /^[a-zA-Z0-9@.]+$/;
                        if (val.length <= 30 && regex.test(val) && puntos <= 3) {
                            correoError.style.display = 'none';
                            correoInput.setCustomValidity('');
                        } else {
                            correoError.style.display = 'block';
                            correoInput.setCustomValidity('Correo inválido');
                        }
                    });

                    // Inicializar sueldo al cargar página si hay valor seleccionado
                    window.addEventListener('load', () => {
                        const event = new Event('change');
                        puestoSelect.dispatchEvent(event);
                    });

                    // --- AGREGADO: Guardar y cargar datos del formulario en localStorage ---

                    // Guardar cada campo en localStorage al cambiar (input, select, textarea)
                    document.querySelectorAll('input, select, textarea').forEach(el => {
                        el.addEventListener('input', () => {
                            localStorage.setItem('empleado_' + el.name, el.value);
                        });
                    });

                    // Al cargar la página, cargar datos guardados en localStorage si el campo está vacío (no hay old())
                    window.addEventListener('load', () => {
                        document.querySelectorAll('input, select, textarea').forEach(el => {
                            if (!el.value) {
                                const saved = localStorage.getItem('empleado_' + el.name);
                                if (saved !== null) {
                                    el.value = saved;
                                }
                            }
                        });
                        // Volver a disparar evento change para actualizar sueldo si el puesto se cargó desde localStorage
                        const event = new Event('change');
                        puestoSelect.dispatchEvent(event);
                    });

                    
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
                                // Oculta mensaje de error si existe
                                const errorDiv = field.nextElementSibling;
                                if (errorDiv && errorDiv.classList.contains('error-text')) {
                                    errorDiv.style.display = 'none';
                                    errorDiv.textContent = '';
                                }
                            } else {
                                field.classList.remove('is-valid');
                                // Sólo añadir clase is-invalid si tiene error Laravel o es inválido
                                if(!field.classList.contains('is-invalid')) {
                                    fiseld.classList.add('is-invalid');
                                }
                            }
                        }
                    });

                </script>
                <script>
    document.addEventListener('DOMContentLoaded', function() {
        const puestoSelect = document.getElementById('puesto_id');
        const salarioInput = document.getElementById('salario');

        function actualizarSalario() {
            const seleccionado = puestoSelect.options[puestoSelect.selectedIndex];
            const sueldo = seleccionado.getAttribute('data-sueldo');
            salarioInput.value = sueldo ? Number(sueldo).toLocaleString('es-HN', {minimumFractionDigits: 2}) : '';
        }

        // Ejecutar al cargar la página (si ya hay puesto seleccionado)
        actualizarSalario();

        // Ejecutar cuando cambie el select
        puestoSelect.addEventListener('change', actualizarSalario);
    });
</script>
        </form>


@endsection