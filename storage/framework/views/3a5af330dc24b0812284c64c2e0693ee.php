

<?php $__env->startSection('content'); ?>
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
    /* Validación correcta */
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

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 160px);">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <h2 class="mb-0">Registrar un nuevo empleado</h2>
        </div>



        <form action="<?php echo e(route('empleados.store')); ?>" method="POST" novalidate>
            <?php echo csrf_field(); ?>

            <div class="row g-3">

<div class="row g-4 mt-3 px-4">
    
    <div class="col-md-2 position-relative">
        <label for="nombres" class="form-label fw-semibold text-muted">Nombres <span class="text-danger">*</span></label>
        <input type="text" name="nombres" id="nombres"   class="form-control form-control-sm solo-letras" value="<?php echo e(old('nombres')); ?>" required maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
        <div id="error-nombres" class="text-danger small" style="display:none;">No se permiten números ni caracteres especiales.</div>
        <?php $__errorArgs = ['nombres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-md-2">
        <label for="apellidos" class="form-label fw-semibold text-muted">Apellidos <span class="text-danger">*</span></label>
        <input type="text" name="apellidos" id="apellidos"    class="form-control form-control-sm solo-letras" value="<?php echo e(old('apellidos')); ?>" required maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios">
        <div id="error-apellidos" class="text-danger small" style="display:none;">No se permiten números ni caracteres especiales.</div>
        <?php $__errorArgs = ['apellidos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>


<div class="col-md-2">
    <label for="identidad" class="form-label fw-semibold text-muted">Identidad <span class="text-danger">*</span></label>
    <input type="text" name="identidad" id="identidad" 
        class="form-control form-control-sm solo-numeros"
        value="<?php echo e(old('identidad')); ?>" required maxlength="13">
        <div id="error-identidad" class="text-danger small" style="display:none;">No se permiten letras ni caracteres especiales.</div>

    <?php $__errorArgs = ['identidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>


<div class="col-md-2">
    <label for="telefono" class="form-label fw-semibold text-muted">Teléfono <span class="text-danger">*</span></label>
    <input type="text" name="telefono" id="telefono" 
        class="form-control form-control-sm solo-numeros"
        value="<?php echo e(old('telefono')); ?>" required maxlength="8">
        <div id="error-telefono" class="text-danger small" style="display:none;">No se permiten letras ni caracteres especiales.</div>

    <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

 
<div class="col-md-2">
    <label for="correo" class="form-label fw-semibold text-muted">Correo <span class="text-danger">*</span></label>
    <input type="email" name="correo" id="correo" 
        class="form-control form-control-sm correo-validado" 
        value="<?php echo e(old('correo')); ?>" 
        required maxlength="30"
        pattern="^[a-zA-Z0-9._]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?$"
        title="Debe contener al menos una '@' y un punto. Solo letras, números, '.', '_' permitidos.">
    <?php $__errorArgs = ['correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
        <div class="text-danger small"><?php echo e($message); ?></div> 
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    
    <div id="correo-error" class="text-danger small" style="display:none;">
        El correo debe contener al menos una "@" y un punto, sin caracteres especiales no permitidos.
    </div>
</div>


    
    <div class="col-md-2">
        <?php
            $anio = now()->year;
            $min = $anio . '-05-01';
            $max = $anio . '-08-31';
        ?>
        <label for="fecha_ingreso" class="form-label fw-semibold text-muted">Fecha de Ingreso <span class="text-danger">*</span></label>
        <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control form-control-sm" value="<?php echo e(old('fecha_ingreso')); ?>" min="<?php echo e($min); ?>" max="<?php echo e($max); ?>" required>
        <?php $__errorArgs = ['fecha_ingreso'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>


<div class="row g-4 mt-3 px-4">
    
    <div class="col-md-2">
        <?php $anioMax = now()->subYears(18)->format('Y-m-d'); ?>
        <label for="fecha_nacimiento" class="form-label fw-semibold text-muted">Fecha de Nacimiento <span class="text-danger">*</span></label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control form-control-sm" value="<?php echo e(old('fecha_nacimiento')); ?>" max="<?php echo e($anioMax); ?>" required>
        <?php $__errorArgs = ['fecha_nacimiento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-md-2">
        <label for="genero" class="form-label fw-semibold text-muted">Género <span class="text-danger">*</span></label>
        <select name="genero" id="genero" class="form-select form-select-sm" required>
            <option value="">-- Selecciona --</option>
            <option value="Masculino" <?php echo e(old('genero') == 'Masculino' ? 'selected' : ''); ?>>Masculino</option>
            <option value="Femenino" <?php echo e(old('genero') == 'Femenino' ? 'selected' : ''); ?>>Femenino</option>
            <option value="Otro" <?php echo e(old('genero') == 'Otro' ? 'selected' : ''); ?>>Otro</option>
        </select>
        <?php $__errorArgs = ['genero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-md-2">
        <label for="estado_civil" class="form-label fw-semibold text-muted">Estado Civil</label>
        <select name="estado_civil" id="estado_civil" class="form-select form-select-sm">
            <option value="">-- Selecciona --</option>
            <option value="Soltero" <?php echo e(old('estado_civil') == 'Soltero' ? 'selected' : ''); ?>>Soltero</option>
            <option value="Casado" <?php echo e(old('estado_civil') == 'Casado' ? 'selected' : ''); ?>>Casado</option>
            <option value="Divorciado" <?php echo e(old('estado_civil') == 'Divorciado' ? 'selected' : ''); ?>>Divorciado</option>
            <option value="Viudo" <?php echo e(old('estado_civil') == 'Viudo' ? 'selected' : ''); ?>>Viudo</option>
        </select>
    </div>

    
    <div class="col-md-2">
        <label for="area" class="form-label fw-semibold text-muted">Área / Departamento <span class="text-danger">*</span></label>
        <select name="area" id="area" class="form-select form-select-sm <?php $__errorArgs = ['area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required onchange="autoFillSueldo()">
            <option value="">-- Selecciona un área --</option>
            <option value="Administración" <?php echo e(old('area') == 'Administración' ? 'selected' : ''); ?> data-sueldo="15000">Administración</option>
            <option value="Recepción" <?php echo e(old('area') == 'Recepción' ? 'selected' : ''); ?> data-sueldo="12000">Recepción</option>
            <option value="Laboratorio" <?php echo e(old('area') == 'Laboratorio' ? 'selected' : ''); ?> data-sueldo="18000">Laboratorio</option>
            <option value="Farmacia" <?php echo e(old('area') == 'Farmacia' ? 'selected' : ''); ?> data-sueldo="16000">Farmacia</option>
            <option value="Enfermería" <?php echo e(old('area') == 'Enfermería' ? 'selected' : ''); ?> data-sueldo="17000">Enfermería</option>
            <option value="Mantenimiento" <?php echo e(old('area') == 'Mantenimiento' ? 'selected' : ''); ?> data-sueldo="11000">Mantenimiento</option>
        </select>
        <?php $__errorArgs = ['area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-md-2">
        <label for="puesto_id" class="form-label fw-semibold text-muted">Puesto <span class="text-danger">*</span></label>
        <select name="puesto_id" id="puesto_id" class="form-select form-select-sm" required>
            <option value="">-- Selecciona un puesto --</option>
            <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($puesto->id); ?>" data-sueldo="<?php echo e($puesto->sueldo); ?>" <?php echo e(old('puesto_id') == $puesto->id ? 'selected' : ''); ?>>
                    <?php echo e($puesto->nombre); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['puesto_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-md-2">
        <label for="sueldo_label" class="form-label fw-semibold text-muted">Sueldo (Lps.)</label>
        <input type="hidden" name="salario" id="salario" value="<?php echo e(old('salario')); ?>">
        <input type="text" id="sueldo_label" class="form-control form-control-sm" readonly>
    </div>
</div>


<div class="row g-4 mt-3 px-4">
    
    <div class="col-md-2">
        <label for="turno_asignado" class="form-label fw-semibold text-muted">Turno Asignado <span class="text-danger">*</span></label>
        <select name="turno_asignado" id="turno_asignado" class="form-select form-select-sm" required>
            <option value="">-- Selecciona --</option>
            <option value="mañana" <?php echo e(old('turno_asignado') == 'mañana' ? 'selected' : ''); ?>>Mañana</option>
            <option value="tarde" <?php echo e(old('turno_asignado') == 'tarde' ? 'selected' : ''); ?>>Tarde</option>
            <option value="noche" <?php echo e(old('turno_asignado') == 'noche' ? 'selected' : ''); ?>>Noche</option>
        </select>
        <?php $__errorArgs = ['turno_asignado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-md-5">
        <label for="direccion" class="form-label fw-semibold text-muted">Dirección <span class="text-danger">*</span></label>
        <textarea name="direccion" id="direccion" class="form-control form-control-sm" rows="3" required maxlength="200"><?php echo e(old('direccion')); ?></textarea>
        <?php $__errorArgs = ['direccion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="col-md-5">
        <label for="observaciones" class="form-label fw-semibold text-muted">Observaciones <span class="text-danger">*</span></label>
        <textarea name="observaciones" id="observaciones" class="form-control form-control-sm" rows="3" required maxlength="350"><?php echo e(old('observaciones')); ?></textarea>
        <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

  <div class="col-md-3">
    <label for="foto" class="form-label fw-semibold text-muted">Foto</label>
    <input type="file" name="foto" id="foto" class="form-control form-control-sm" accept="image/*">
    
    <button type="button" id="btnGuardarFoto" class="btn btn-primary btn-sm mt-2">Guardar Foto</button>

    <div id="previewFoto" class="mt-3">
        <!-- Aquí se mostrará la imagen cargada -->
    </div>

    <div id="mensajeFoto" class="text-success small mt-2" style="display:none;">Foto guardada correctamente.</div>
</div>






            </div>
  
<div class="d-flex justify-content-center mt-5 gap-4 flex-wrap">
    
    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm" onclick="this.disabled=true; this.form.submit();">
        <i class="bi bi-plus-circle"></i> Registrar
    </button>

    
    <button type="button" id="btnLimpiar" class="btn btn-warning btn-sm px-4 shadow-sm">
        <i class="bi bi-trash"></i> Limpiar
    </button>

    
    <a href="<?php echo e(route('empleados.index')); ?>" class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Limpiar formulario
    const btnLimpiar = document.getElementById('btnLimpiar');
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', () => {
            localStorage.clear();  // Limpia localStorage

            const form = document.querySelector('form');
            if (form) {
                form.reset(); // Resetea el formulario (vuelve a valores old())

                // Vacía todos los campos para sobreescribir los valores old()
                form.querySelectorAll('input, select, textarea').forEach(el => {
                    if (el.tagName.toLowerCase() === 'select') {
                        el.selectedIndex = 0; // Opción vacía
                    } else {
                        el.value = '';
                    }
                    // Remover clases de validación
                    el.classList.remove('is-valid', 'is-invalid');
                });
            }

            const sueldoLabel = document.getElementById('sueldo_label');
            const salarioInput = document.getElementById('salario');
            if (sueldoLabel) sueldoLabel.value = '';
            if (salarioInput) salarioInput.value = '';

            // Ocultar todos los mensajes de error visibles (div con clase text-danger small)
            const mensajesError = document.querySelectorAll('.text-danger.small');
            mensajesError.forEach(div => {
                div.style.display = 'none';
            });
        });
    }

    // Mostrar sueldo al seleccionar puesto
    const puestoSelect = document.getElementById('puesto_id');
    const sueldoLabel = document.getElementById('sueldo_label');
    const salarioInput = document.getElementById('salario');
    if (puestoSelect) {
        puestoSelect.addEventListener('change', () => {
            const option = puestoSelect.options[puestoSelect.selectedIndex];
            const sueldo = option.getAttribute('data-sueldo') || '';
            if (sueldoLabel) sueldoLabel.value = sueldo ? parseFloat(sueldo).toFixed(2) : '';
            if (salarioInput) salarioInput.value = sueldo || '';
        });
        // Inicializar sueldo al cargar página si hay valor seleccionado
        puestoSelect.dispatchEvent(new Event('change'));
    }

    // Validar teléfono en tiempo real
    const telefonoInput = document.getElementById('telefono');
    const telefonoError = document.getElementById('telefono-error');
    if (telefonoInput) {
        telefonoInput.addEventListener('input', () => {
            const val = telefonoInput.value.trim();
            const regex = /^[238]\d{7}$/;
            if (regex.test(val)) {
                if (telefonoError) telefonoError.style.display = 'none';
                telefonoInput.setCustomValidity('');
            } else {
                if (telefonoError) telefonoError.style.display = 'block';
                telefonoInput.setCustomValidity('Teléfono inválido');
            }
        });
    }

    // Validar correo en tiempo real
    const correoInput = document.getElementById('correo');
    const correoError = document.getElementById('correo-error');
    if (correoInput) {
        correoInput.addEventListener('input', function () {
            // Bloquea caracteres inválidos
            this.value = this.value.replace(/[^a-zA-Z0-9@._]/g, '');

            const val = this.value.trim();
            const regex = /^[a-zA-Z0-9._]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?$/;

            if (regex.test(val) && val.length <= 30) {
                correoInput.classList.remove('is-invalid');
                correoInput.classList.add('is-valid');
                correoInput.setCustomValidity('');
                if (correoError) correoError.style.display = 'none';
            } else {
                correoInput.classList.remove('is-valid');
                correoInput.classList.add('is-invalid');
                correoInput.setCustomValidity('Correo inválido');
                if (correoError) correoError.style.display = 'block';
            }
        });
    }

    // Guardar y cargar datos en localStorage
    const form = document.querySelector('form');
    if (form) {
        form.querySelectorAll('input, select, textarea').forEach(el => {
            el.addEventListener('input', () => {
                localStorage.setItem('empleado_' + el.name, el.value);
            });
        });

        // Al cargar la página, cargar datos guardados en localStorage si el campo está vacío (no hay old())
        form.querySelectorAll('input, select, textarea').forEach(el => {
            if (!el.value) {
                const saved = localStorage.getItem('empleado_' + el.name);
                if (saved !== null) {
                    el.value = saved;
                }
            }
        });
        // Volver a disparar evento change para actualizar sueldo si el puesto se cargó desde localStorage
        if (puestoSelect) puestoSelect.dispatchEvent(new Event('change'));

        // Validación dinámica y cambio de clases para campos required
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
                if (!field.classList.contains('is-invalid')) {
                    field.classList.add('is-invalid');
                }
            }
        }
    }

    // Validar solo letras para nombres/apellidos con mensaje
    const camposLetras = [
        { input: document.getElementById('nombres'), errorDiv: document.getElementById('error-nombres') },
        { input: document.getElementById('apellidos'), errorDiv: document.getElementById('error-apellidos') }
    ];
    camposLetras.forEach(({input, errorDiv}) => {
        if (!input || !errorDiv) return;
        input.addEventListener('input', function () {
            const original = this.value;
            const nuevoValor = original.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, '');
            this.value = nuevoValor;
            errorDiv.style.display = (original !== nuevoValor) ? 'block' : 'none';
        });
    });

    // Validar solo números para identidad/teléfono con mensaje
    const camposNumeros = [
        { input: document.getElementById('identidad'), errorDiv: document.getElementById('error-identidad') },
        { input: document.getElementById('telefono'), errorDiv: document.getElementById('error-telefono') }
    ];
    camposNumeros.forEach(({input, errorDiv}) => {
        if (!input || !errorDiv) return;
        input.addEventListener('input', function () {
            const original = this.value;
            const nuevoValor = original.replace(/[^0-9]/g, '');
            this.value = nuevoValor;
            errorDiv.style.display = (original !== nuevoValor) ? 'block' : 'none';
        });
    });

});

document.getElementById('btnGuardarFoto').addEventListener('click', function () {
    const inputFoto = document.getElementById('foto');
    const preview = document.getElementById('previewFoto');
    const mensaje = document.getElementById('mensajeFoto');

    if (!inputFoto.files || inputFoto.files.length === 0) {
        alert('Por favor selecciona una foto antes de guardar.');
        return;
    }

    const formData = new FormData();
    formData.append('foto', inputFoto.files[0]);

    fetch('/empleados/guardar-foto', {  // Aquí la ruta que crees para guardar solo la foto
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mensaje.style.display = 'block';
            mensaje.textContent = 'Foto guardada correctamente.';

            // Mostrar preview con la ruta que te devuelve el servidor
            preview.innerHTML = `<img src="${data.url}" alt="Foto subida" class="img-thumbnail" style="max-width:150px;">`;
        } else {
            alert('Error al guardar la foto.');
        }
    })
    .catch(() => alert('Error al guardar la foto.'));
});
const inputFoto = document.getElementById('foto');
const preview = document.getElementById('previewFoto');

inputFoto.addEventListener('change', () => {
    const file = inputFoto.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}" alt="Previsualización" class="img-thumbnail" style="max-width:150px;">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});


</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/empleados/create.blade.php ENDPATH**/ ?>