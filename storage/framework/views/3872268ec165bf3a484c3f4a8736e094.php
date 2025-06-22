

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
</style>

<div class="d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 56px);">
    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2">
            <h2 class="mb-0">Registrar nuevo médico</h2>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php
                    $maxNacimiento = now()->subYears(18)->format('Y-m-d');
                    $minIngreso = now()->subMonth()->format('Y-m-d');
                    $maxIngreso = now()->addMonth()->format('Y-m-d');
                ?>

                <form method="POST" action="<?php echo e(route('medicos.store')); ?>" enctype="multipart/form-data" id="formMedico" novalidate>
                    <?php echo csrf_field(); ?>

             <div class="row mb-3">
    <div class="col-md-2">
        <label for="nombre" class="form-label">Nombre: <span class="text-danger">*</span></label>
        <input type="text" name="nombre" maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
            class="form-control <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(old('nombre') && !$errors->has('nombre') ? 'is-valid' : ''); ?>"
            value="<?php echo e(old('nombre')); ?>" required>
        <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message ?: 'Solo se permiten letras. Ingrese este dato.'); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label for="apellidos" class="form-label">Apellidos: <span class="text-danger">*</span></label>
        <input type="text" name="apellidos" maxlength="50" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
            class="form-control <?php $__errorArgs = ['apellidos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(old('apellidos') && !$errors->has('apellidos') ? 'is-valid' : ''); ?>"
            value="<?php echo e(old('apellidos')); ?>" required>
        <?php $__errorArgs = ['apellidos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message ?: 'Solo se permiten letras.'); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label for="numero_identidad" class="form-label">Identidad: <span class="text-danger">*</span></label>
        <input type="text" name="numero_identidad" maxlength="13" minlength="13"
            class="form-control <?php $__errorArgs = ['numero_identidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(old('numero_identidad') && !$errors->has('numero_identidad') ? 'is-valid' : ''); ?>"
            value="<?php echo e(old('numero_identidad')); ?>" required>
        <?php $__errorArgs = ['numero_identidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback">
                <?php if(str_contains($message, 'required')): ?>
                    Ingrese este dato.
                <?php elseif(str_contains($message, 'size')): ?>
                    Debe tener exactamente 13 números.
                <?php elseif(str_contains($message, 'regex')): ?>
                    Solo se admiten números.
                <?php elseif(str_contains($message, 'unique')): ?>
                    Este número de identidad ya está registrado.
                <?php else: ?>
                    <?php echo e($message); ?>

                <?php endif; ?>
            </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento: <span class="text-danger">*</span></label>
        <input type="date" name="fecha_nacimiento" 
            class="form-control <?php $__errorArgs = ['fecha_nacimiento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(old('fecha_nacimiento') && !$errors->has('fecha_nacimiento') ? 'is-valid' : ''); ?>"
            value="<?php echo e(old('fecha_nacimiento')); ?>" 
            required
            max="<?php echo e(\Carbon\Carbon::now()->subYears(18)->format('Y-m-d')); ?>">
        <?php $__errorArgs = ['fecha_nacimiento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label for="telefono" class="form-label">Teléfono: <span class="text-danger">*</span></label>
        <input type="text" name="telefono" maxlength="8" minlength="8"
            class="form-control <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(old('telefono') && !$errors->has('telefono') ? 'is-valid' : ''); ?>"
            value="<?php echo e(old('telefono')); ?>" required>
        <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback">
                <?php if(str_contains($message, 'required')): ?>
                    Ingrese este dato.
                <?php elseif(str_contains($message, 'digits')): ?>
                    Debe contener exactamente 8 números.
                <?php elseif(str_contains($message, 'regex')): ?>
                    Debe iniciar con 2, 3, 8 o 9 y solo contener números.
                <?php elseif(str_contains($message, 'numeric')): ?>
                    Solo se admiten números.
                <?php elseif(str_contains($message, 'unique')): ?>
                    Este número de teléfono ya existe.
                <?php else: ?>
                    <?php echo e($message); ?>

                <?php endif; ?>
            </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label for="correo" class="form-label">Correo: <span class="text-danger">*</span></label>
        <input type="email" name="correo" maxlength="50"
            placeholder="ejemplo@dominio.com"
            class="form-control <?php $__errorArgs = ['correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(old('correo') && !$errors->has('correo') ? 'is-valid' : ''); ?>"
            value="<?php echo e(old('correo')); ?>" required>
        <?php $__errorArgs = ['correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback">
                <?php if(str_contains($message, 'required')): ?>
                    Ingrese este dato.
                <?php elseif(str_contains($message, 'regex')): ?>
                    El correo debe contener un @ y un dominio válido como .com, .es, .edu, etc.
                <?php elseif(str_contains($message, 'unique')): ?>
                    Este correo electrónico ya existe.
                <?php else: ?>
                    <?php echo e($message); ?>

                <?php endif; ?>
            </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-2">
        <label for="genero" class="form-label">Género: <span class="text-danger">*</span></label>
        <select name="genero" class="form-select <?php $__errorArgs = ['genero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(old('genero') && !$errors->has('genero') ? 'is-valid' : ''); ?>" required>
            <option value="">Seleccionar</option>
            <option value="Masculino" <?php echo e(old('genero') == 'Masculino' ? 'selected' : ''); ?>>Masculino</option>
            <option value="Femenino" <?php echo e(old('genero') == 'Femenino' ? 'selected' : ''); ?>>Femenino</option>
            <option value="Otro" <?php echo e(old('genero') == 'Otro' ? 'selected' : ''); ?>>Otro</option>
        </select>
        <?php $__errorArgs = ['genero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback">Por favor, elija una opción.</div>
        <?php else: ?>
            <?php if(old('genero') === ''): ?>
                <div class="invalid-feedback d-block">Elija una opción para el campo género.</div>
            <?php endif; ?>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label for="especialidad" class="form-label">Especialidad: <span class="text-danger">*</span></label>
        <select name="especialidad" id="especialidad" class="form-select <?php $__errorArgs = ['especialidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(old('especialidad') && !$errors->has('especialidad') ? 'is-valid' : ''); ?>" required>
            <option value="">Seleccionar</option>
            <option value="Cardiología" <?php echo e(old('especialidad') == 'Cardiología' ? 'selected' : ''); ?>>Cardiología</option>
            <option value="Neurología" <?php echo e(old('especialidad') == 'Neurología' ? 'selected' : ''); ?>>Neurología</option>
            <option value="Pediatría" <?php echo e(old('especialidad') == 'Pediatría' ? 'selected' : ''); ?>>Pediatría</option>
            <option value="Dermatología" <?php echo e(old('especialidad') == 'Dermatología' ? 'selected' : ''); ?>>Dermatología</option>
            <option value="Psiquiatría" <?php echo e(old('especialidad') == 'Psiquiatría' ? 'selected' : ''); ?>>Psiquiatría</option>
            <option value="Radiología" <?php echo e(old('especialidad') == 'Radiología' ? 'selected' : ''); ?>>Radiología</option>
        </select>
        <?php $__errorArgs = ['especialidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback">Seleccione una especialidad.</div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label class="form-label">Salario</label>
        <input type="text" id="salario_mostrado" class="form-control" readonly>
        <input type="hidden" name="salario" id="salario_real" value="<?php echo e(old('salario', $medico->salario ?? '')); ?>">
    </div>

    <div class="col-md-2">
        <label for="fecha_ingreso" class="form-label">Fecha de Ingreso: <span class="text-danger">*</span></label>
        <input type="date" name="fecha_ingreso" 
            class="form-control <?php $__errorArgs = ['fecha_ingreso'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php echo e(old('fecha_ingreso') && !$errors->has('fecha_ingreso') ? 'is-valid' : ''); ?>"
            value="<?php echo e(old('fecha_ingreso')); ?>" 
            required
            min="<?php echo e($minIngreso); ?>" 
            max="<?php echo e($maxIngreso); ?>">
        <?php $__errorArgs = ['fecha_ingreso'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-4">
        <label for="foto" class="form-label">Foto:</label>
        <input type="file" name="foto" id="foto" class="form-control form-control-sm <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
        <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback">Suba una imagen válida. Opcional.</div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="form-text">Opcional. Formato: JPG, PNG.</div>
    </div>
</div>

<div class="mb-2" style="max-width: 40%;">
    <label class="form-label">Observaciones:</label>
    <textarea name="observaciones" maxlength="200" class="form-control <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"><?php echo e(old('observaciones')); ?></textarea>
    <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">No puede exceder 200 caracteres.</div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <div class="form-text">Campo opcional (máx. 200 caracteres).</div>
</div>

<div class="d-flex justify-content-center mt-5 gap-4 flex-wrap">
    
    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
        <i class="bi bi-save"></i> Registrar
    </button>

    
    <button type="button" id="btnLimpiar" class="btn btn-warning btn-sm px-4 shadow-sm">
        <i class="bi bi-trash"></i> Limpiar
    </button>

    
    <a href="<?php echo e(route('medicos.index')); ?>" class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left-circle"></i> Cancelar
    </a>
</div>


</div>


<script>
   document.addEventListener('DOMContentLoaded', function () {
    const especialidadSelect = document.getElementById('especialidad');
    const salarioMostrado = document.getElementById('salario_mostrado');
    const salarioReal = document.getElementById('salario_real');

    const salariosPorEspecialidad = {
        "Cardiología": 15000,
        "Neurología": 24800,
        "Pediatría": 27500,
        "Dermatología": 14200,
        "Psiquiatría": 14700,
        "Radiología": 16300,
    };

    function formatearMoneda(valor) {
        return 'L ' + Number(valor).toLocaleString('es-HN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }

    function actualizarSalario() {
        const especialidad = especialidadSelect.value;
        if (salariosPorEspecialidad.hasOwnProperty(especialidad)) {
            const salario = salariosPorEspecialidad[especialidad];
            salarioReal.value = salario; // valor sin formato para backend
            salarioMostrado.value = formatearMoneda(salario); // valor con formato para mostrar
        } else {
            salarioReal.value = '';
            salarioMostrado.value = '';
        }
    }

    especialidadSelect.addEventListener('change', actualizarSalario);

    // Al cargar la página, mostrar el salario según especialidad o salario guardado
    if (especialidadSelect.value) {
        actualizarSalario();
    } else if (salarioReal.value) {
        salarioMostrado.value = formatearMoneda(salarioReal.value);
    }
});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/medicos/create.blade.php ENDPATH**/ ?>