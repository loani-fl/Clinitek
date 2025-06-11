@extends('layouts.app')

@section('content')
<style>
    
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        max-width: 97%;
        background-color: #f0faff;
        border-color: #91cfff;
    }
    label {
        font-size: 0.85rem;
    }
    input, select, textarea {
        font-size: 0.85rem !important;
    }

    /* Ícono validación correcta (pleca verde) */
.valid-feedback {
    display: block;
    font-size: 0.85rem;
    color: #198754; /* verde Bootstrap */
}

/* Ajuste para mostrar el icono check dentro del input */
.form-control.is-valid {
    border-color: #198754;
    padding-right: 2.5rem; /* espacio para el icono */
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%23198754' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' class='bi bi-check-lg' viewBox='0 0 16 16'%3e%3cpath d='M13 4.5 6 11.5 3 8.5'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem 1rem;
}

/* Estilo para labels con error (cuando el campo es inválido) */
label.is-invalid {
    color: #dc3545; /* rojo Bootstrap para error */
    font-weight: 600;
}


</style>

<div class="card custom-card shadow-sm border rounded-4 w-100">
    <div class="card-header bg-primary text-white py-2">
        <h5 class="mb-0">Registro de un nuevo empleado</h5>
    </div>

    <div class="card-body">
        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success py-2 small">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('empleados.store') }}" method="POST" novalidate>
            @csrf

            <div class="row g-5">
               {{-- Aquí van tus campos... --}}

               {{-- Nombres --}}
               <div class="col-md-3">
                   <label for="nombres" class="form-label fw-semibold text-muted">Nombres <span class="text-danger">*</span></label>
                   <input type="text" name="nombres" id="nombres" class="form-control form-control-sm" value="{{ old('nombres') }}" required maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                       title="Solo se permiten letras y espacios">
                   @error('nombres')
                       <div class="text-danger small">{{ $message }}</div>
                   @enderror
               </div>

               {{-- Apellidos --}}
               <div class="col-md-3">
                   <label for="apellidos" class="form-label fw-semibold text-muted">Apellidos <span class="text-danger">*</span></label>
                   <input type="text" name="apellidos" id="apellidos" class="form-control form-control-sm" value="{{ old('apellidos') }}" required maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                       title="Solo se permiten letras y espacios">
                   @error('apellidos')
                       <div class="text-danger small">{{ $message }}</div>
                   @enderror
               </div>

               {{-- Identidad --}}
               <div class="col-md-3">
                   <label for="identidad" class="form-label fw-semibold text-muted">Identidad <span class="text-danger">*</span></label>
                   <input type="text" name="identidad" id="identidad" class="form-control form-control-sm" value="{{ old('identidad') }}" required>
                   @error('identidad')
                       <div class="text-danger small">{{ $message }}</div>
                   @enderror
               </div>

               {{-- Teléfono --}}
               <div class="col-md-3">
                   <label for="telefono" class="form-label fw-semibold text-muted">Teléfono <span class="text-danger">*</span></label>
                   <input type="text" name="telefono" id="telefono" class="form-control form-control-sm" value="{{ old('telefono') }}" required>
                   <div id="telefono-error" class="text-danger small" style="display:none;">El teléfono debe comenzar con 2, 3 o 8 y contener exactamente 8 dígitos numéricos.</div>
                   @error('telefono')
                       <div class="text-danger small">{{ $message }}</div>
                   @enderror
               </div>

               {{-- Correo --}}
               <div class="col-md-4">
                   <label for="correo" class="form-label fw-semibold text-muted">Correo <span class="text-danger">*</span></label>
                   <input type="email" name="correo" id="correo" class="form-control form-control-sm" value="{{ old('correo') }}" required maxlength="30">
                   <div id="correo-error" class="text-danger small" style="display:none;">
                       El correo debe tener máximo 30 caracteres, contener solo letras, números, una (@) y hasta 3 puntos (.) como máximo.
                   </div>
                   @error('correo')
                       <div class="text-danger small">{{ $message }}</div>
                   @enderror
               </div>

               {{-- Fecha Ingreso --}}
               @php
                   $anio = now()->year;
                   $min = $anio . '-05-01';
                   $max = $anio . '-08-31';
               @endphp

               <div class="col-md-3">
                   <label for="fecha_ingreso" class="form-label fw-semibold text-muted">
                       Fecha de Ingreso <span class="text-danger">*</span>
                   </label>
                   <input type="date"
                          name="fecha_ingreso"
                          id="fecha_ingreso"
                          class="form-control form-control-sm"
                          value="{{ old('fecha_ingreso') }}"
                          min="{{ $min }}"
                          max="{{ $max }}"
                          required>
                   @error('fecha_ingreso')
                       <div class="text-danger small">{{ $message }}</div>
                   @enderror
               </div>

               {{-- Fecha Nacimiento --}}
               @php
                   $anioMax = now()->subYears(18)->format('Y-m-d');
               @endphp

               <div class="col-md-3">
                   <label for="fecha_nacimiento" class="form-label fw-semibold text-muted">
                       Fecha de Nacimiento <span class="text-danger">*</span>
                   </label>
                   <input type="date" 
                          name="fecha_nacimiento" 
                          id="fecha_nacimiento" 
                          class="form-control form-control-sm" 
                          value="{{ old('fecha_nacimiento') }}" 
                          max="{{ $anioMax }}" 
                          required>
                   @error('fecha_nacimiento')
                       <div class="text-danger small">{{ $message }}</div>
                   @enderror
               </div>

               {{-- Género --}}
               <div class="col-md-3">
                   <label for="genero" class="form-label fw-semibold text-muted">Género <span class="text-danger">*</span></label>
                   <select name="genero" id="genero" class="form-select form-select-sm" required>
                       <option value="">-- Selecciona --</option>
                       <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                       <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                       <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                   </select>
                   @error('genero')
                       <div class="text-danger small">{{ $message }}</div>
                   @enderror
               </div>

               {{-- Estado Civil --}}
               <div class="col-md-3">
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
               <div class="col-md-3">
                   <label for="puesto_id" class="form-label fw-semibold text-muted">Puesto <span class="text-danger">*</span></label>
                   <select name="puesto_id" id="puesto_id" class="form-select form-select-sm" required>
                       <option value="">-- Selecciona un puesto --</option>
                       @foreach($puestos as $puesto)
                           <option value="{{ $puesto->id }}" data-sueldo="{{ $puesto->sueldo }}" {{ old('puesto_id') == $puesto->id ? 'selected' : '' }}>
                               {{ $puesto->nombre }}
                           </option>
                       @endforeach
                   </select>
                   @error('puesto_id')
                       <div class="text-danger small">{{ $message }}</div>
                   @enderror
               </div>

               {{-- Sueldo (solo visible) --}}
               <div class="col-md-2">
                   <label for="sueldo_label" class="form-label fw-semibold text-muted">Sueldo (Lps.)</label>
                   <input type="hidden" name="salario" id="salario" value="{{ old('salario') }}">
                   <input type="text" id="sueldo_label" class="form-control form-control-sm" readonly>
               </div>

               {{-- Dirección y Observaciones --}}
               <div class="row mt-4">
                   {{-- Dirección --}}
                   <div class="col-md-4">
                       <label for="direccion" class="form-label fw-semibold text-muted">Dirección <span class="text-danger">*</span></label>
                       <textarea name="direccion" id="direccion" class="form-control form-control-sm" rows="3" required>{{ old('direccion') }}</textarea>
                       @error('direccion')
                           <div class="text-danger small">{{ $message }}</div>
                       @enderror
                   </div>

                  {{-- Observaciones --}}
<div class="col-md-4">
    <label for="observaciones" class="form-label fw-semibold text-muted">Observaciones <span class="text-danger">*</span></label>
    <textarea name="observaciones" id="observaciones" class="form-control form-control-sm" rows="3" required>{{ old('observaciones') }}</textarea>
    @error('observaciones')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

            </div>

            {{-- Botones --}}
            <div class="d-flex justify-content-center mt-5 gap-4">
                <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                    <i class="bi bi-plus-circle"></i> Registrar
                </button>
                <button type="button" id="btnLimpiar" class="btn btn-secondary btn-sm px-4 shadow-sm">
                    <i class="bi bi-x-circle"></i> Limpiar
                </button>
            </div>
        </form>
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
                    field.classList.add('is-invalid');
                }
            }
        }
    });


    


</script>

@endsection