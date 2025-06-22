<?php $__env->startSection('content'); ?>
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        max-width: 87vw;
        background-color: #f0faff;
        border-color: #91cfff;
    }
    label {
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    input, select, textarea {
        font-size: 0.85rem !important;
    }
    .error-text {
        font-size: 0.75rem;
        color: #dc3545;
        margin-top: 0.2rem;
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
</style>

<div class="d-flex justify-content-center">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>Registro de un nuevo puesto</h5>
        </div>

        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-3 p-3 mb-4 small">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('puestos.store')); ?>" novalidate>
                <?php echo csrf_field(); ?>

              <div class="row g-4 mt-2 px-4">
    <div class="col-md-3 position-relative">
        <label for="codigo" class="form-label fw-semibold text-muted">Código <span class="text-danger">*</span></label>
        <input type="text" name="codigo" id="codigo" autocomplete="off"
            class="form-control form-control-sm <?php $__errorArgs = ['codigo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            maxlength="10"
            pattern="[A-Za-z0-9\-]{1,10}"
            title="Máximo 10 caracteres. Solo letras, números y guiones."
            value="<?php echo e(old('codigo')); ?>" required>
        <?php $__errorArgs = ['codigo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error-text"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-4 position-relative">
        <label for="nombre" class="form-label fw-semibold text-muted">Nombre del Puesto <span class="text-danger">*</span></label>
        <input type="text" name="nombre" id="nombre" autocomplete="off"
            class="form-control form-control-sm <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            maxlength="50"
            pattern="^[\pL\s]+$"
            title="Solo letras y espacios (puede incluir tildes)."
            value="<?php echo e(old('nombre')); ?>" required>
        <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error-text"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-3 position-relative">
        <label for="area" class="form-label fw-semibold text-muted">Área / Departamento <span class="text-danger">*</span></label>
        <select name="area" id="area"
            class="form-select form-select-sm <?php $__errorArgs = ['area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            required onchange="autoFillSueldo()">
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
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error-text"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<div class="row g-4 mt-3 px-4">
    <div class="col-md-3 position-relative">
        <label for="sueldo" class="form-label fw-semibold text-muted">Sueldo (Lps.) <span class="text-danger">*</span></label>
        <input type="text" name="sueldo" id="sueldo" required readonly
            class="form-control form-control-sm <?php $__errorArgs = ['sueldo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            pattern="^\d{1,5}(\.\d{1,2})?$"
            title="Solo números. Hasta 5 dígitos y 2 decimales."
            inputmode="decimal"
            value="<?php echo e(old('sueldo')); ?>">
        <?php $__errorArgs = ['sueldo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error-text"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-5 position-relative">
        <label for="funcion" class="form-label fw-semibold text-muted">Función del Puesto <span class="text-danger">*</span></label>
        <textarea name="funcion" id="funcion" rows="3"
            class="form-control form-control-sm <?php $__errorArgs = ['funcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            required maxlength="350"
            pattern="^[\pL\pN\s.,áéíóúÁÉÍÓÚñÑ\r\n]+$"
            title="Puede contener letras (incluye tildes y ñ), números, comas, puntos y espacios. Máximo 350 caracteres."><?php echo e(old('funcion')); ?></textarea>
        <?php $__errorArgs = ['funcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error-text"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>


<div class="d-flex justify-content-center mt-4 gap-4 px-4">
    
    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
        <i class="bi bi-plus-circle"></i> Registrar
    </button>

    
    <button type="button" class="btn btn-warning btn-sm px-4 shadow-sm" onclick="limpiarFormulario()">
        <i class="bi bi-trash"></i> Limpiar
    </button>

    
    <a href="<?php echo e(route('empleados.index')); ?>" class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Cancelar
    </a>
</div>


            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function autoFillSueldo() {
        const select = document.getElementById('area');
        const sueldoInput = document.getElementById('sueldo');
        const option = select.options[select.selectedIndex];
        const sueldo = option.getAttribute('data-sueldo');

        if (sueldo && (sueldoInput.value === '' || sueldoInput.value === sueldoInput.dataset.prevValue)) {
            sueldoInput.value = sueldo;
            sueldoInput.dataset.prevValue = sueldo;
            formatSueldo();
        }
    }

    function formatSueldo() {
        const sueldoInput = document.getElementById('sueldo');
        let val = sueldoInput.value.replace(',', '.').trim();
        if (val === '') return;
        let num = parseFloat(val);
        if (!isNaN(num)) {
            sueldoInput.value = num.toFixed(2);
            sueldoInput.dataset.prevValue = sueldoInput.value;
        }
    }

   function limpiarFormulario() {
    const form = document.querySelector('form');
    form.reset();

    form.querySelectorAll('input, select, textarea').forEach(input => {
        if (input.tagName.toLowerCase() === 'select') {
            input.selectedIndex = 0;
        } else {
            input.value = '';
        }

        input.classList.remove('is-invalid', 'is-valid');

        const errorDiv = input.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-text')) {
            errorDiv.textContent = '';
        }
    });

    // Si usas `old()` en los value, borra también el contenido visualmente:
    document.querySelectorAll('[value]').forEach(e => e.removeAttribute('value'));
}


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
                if (!field.classList.contains('is-invalid')) {
                    field.classList.add('is-invalid');
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>













<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/puestos/create.blade.php ENDPATH**/ ?>