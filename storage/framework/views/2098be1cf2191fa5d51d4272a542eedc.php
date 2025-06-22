<?php $__env->startSection('title', ''); ?>

<?php $__env->startSection('content'); ?>
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        max-width: 85%;
        background-color: #f0faff;
        border-color: #91cfff;
    }
 

    label {
        font-size: 0.78rem;
        margin-bottom: 0.2rem;
        color: #5a5a5a;
    }
    input, select, textarea {
        font-size: 0.85rem !important;
    }
    .btn-sm {
        font-size: 0.85rem;
        padding: 0.45rem 1rem;
        border-radius: 6px;
    }
    .alert-danger, .alert-warning, .alert-success {
        font-size: 0.8rem;
    }
    #editPuestoForm {
    padding-left: 5rem; /* Ajusta la cantidad para mover más o menos a la derecha */
}

</style>

<div class="d-flex justify-content-center mt-4 mb-5">

    <div class="card custom-card shadow-sm border rounded-4 w-100">
        <div class="card-header bg-primary text-white py-2 rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-pencil-fill me-2"></i> Editar Puesto</h5>
        </div>

        <div class="card-body">
            
            <?php if(session('info')): ?>
                <div class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3">
                    <i class="bi bi-info-circle-fill"></i>
                    <div><?php echo e(session('info')); ?></div>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 small rounded-3 mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                    <div><?php echo e(session('success')); ?></div>
                </div>
            <?php endif; ?>

        

            <form method="POST" action="<?php echo e(route('puestos.update', $puesto)); ?>" class="needs-validation" novalidate id="editPuestoForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
<div class="row g-10">
    <div class="col-md-3">
        <label for="codigo" class="form-label">Código del Puesto <span class="text-danger">*</span></label>
        <input type="text" name="codigo" id="codigo"
            class="form-control form-control-sm <?php $__errorArgs = ['codigo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            value="<?php echo e(old('codigo', $puesto->codigo)); ?>"
            required maxlength="10"
            pattern="[A-Za-z0-9\-]{1,10}"
            title="Máximo 10 caracteres. Solo letras, números y guiones.">
        <?php $__errorArgs = ['codigo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-3">
        <label for="nombre" class="form-label">Nombre del Puesto <span class="text-danger">*</span></label>
        <input type="text" name="nombre" id="nombre"
            class="form-control form-control-sm <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            required maxlength="50"
            pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]+"
            title="Solo letras (mayúsculas o minúsculas) y espacios. No se permiten números ni caracteres especiales."
            value="<?php echo e(old('nombre', $puesto->nombre)); ?>">
        <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-3">
        
    </div>
</div>


<div class="row g-4 mt-1">
    <div class="col-md-3">
        <label for="area" class="form-label">Departamento o Área <span class="text-danger">*</span></label>
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
            <?php $__currentLoopData = ['Administración', 'Recepción', 'Laboratorio', 'Farmacia', 'Enfermería', 'Mantenimiento']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($area); ?>"
                    <?php echo e(old('area', $puesto->area) == $area ? 'selected' : ''); ?>

                    data-sueldo="<?php echo e(['Administración'=>20000,'Recepción'=>15000,'Laboratorio'=>18000,'Farmacia'=>17000,'Enfermería'=>19000,'Mantenimiento'=>14000][$area]); ?>">
                    <?php echo e($area); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-3">
        <label for="sueldo" class="form-label">Sueldo (Lps.) <span class="text-danger">*</span></label>
        <input type="text" name="sueldo" id="sueldo"
            class="form-control form-control-sm <?php $__errorArgs = ['sueldo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            required pattern="^\d{1,5}(\.\d{1,2})?$"
            title="Solo números. Hasta 5 dígitos y 2 decimales."
            inputmode="decimal"
            value="<?php echo e(old('sueldo', $puesto->sueldo)); ?>">
        <?php $__errorArgs = ['sueldo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-3">
        
    </div>
</div>



<div class="row g-4 mt-1">
    <div class="col-md-6">
        <label for="funcion" class="form-label">Funciones <span class="text-danger">*</span></label>
        <textarea name="funcion" id="funcion"
            class="form-control form-control-sm <?php $__errorArgs = ['funcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            rows="3" required maxlength="50"
            title="Máximo 50 caracteres."><?php echo e(old('funcion', $puesto->funcion ?? '')); ?></textarea>
        <?php $__errorArgs = ['funcion'];
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
</div>


<div class="mt-4 d-flex justify-content-start gap-5">

    
    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-pencil-fill" style="font-size: 1.2rem;"></i> Actualizar
    </button>

    
    <button type="reset" id="btnRestablecer" class="btn btn-warning d-flex align-items-center gap-2"
            title="Restablecer formulario a valores originales">
        <i class="bi bi-arrow-clockwise" style="font-size: 1.1rem;"></i> Restablecer
    </button>

    
    <a href="<?php echo e(route('empleados.index')); ?>" class="btn btn-success d-flex align-items-center gap-2">
        <i class="bi bi-x-circle" style="font-size: 1.1rem;"></i> Cancelar
    </a>
</div>


</div>


            </form>
        </div>
    </div>
</div>

<script>
    function autoFillSueldo() {
        const selectArea = document.getElementById('area');
        const sueldoInput = document.getElementById('sueldo');
        const sueldosBase = {
            "Administración": 20000,
            "Recepción": 15000,
            "Laboratorio": 18000,
            "Farmacia": 17000,
            "Enfermería": 19000,
            "Mantenimiento": 14000
        };
        const selectedValue = selectArea.value;
        const sueldoBase = sueldosBase[selectedValue] || '';
        if (sueldoBase && (sueldoInput.value === '' || sueldoInput.value === sueldoInput.dataset.prevValue)) {
            sueldoInput.value = sueldoBase;
            sueldoInput.dataset.prevValue = sueldoBase;
        }
    }

    window.onload = () => {
        // Guardar valores iniciales
        const form = document.getElementById('editPuestoForm');
        form._initialValues = {
            codigo: form.codigo.value,
            nombre: form.nombre.value,
            area: form.area.value,
            sueldo: form.sueldo.value,
            funcion: form.funcion.value,
        };

        document.getElementById('btnRestablecer').addEventListener('click', () => {
            // Restaurar valores iniciales
            form.codigo.value = form._initialValues.codigo;
            form.nombre.value = form._initialValues.nombre;
            form.area.value = form._initialValues.area;
            form.sueldo.value = form._initialValues.sueldo;
            form.funcion.value = form._initialValues.funcion;

            // Actualizar sueldo base si corresponde
            autoFillSueldo();

            // Remover clases de error si hay
            ['codigo', 'nombre', 'area', 'sueldo', 'funcion'].forEach(id => {
                const el = document.getElementById(id);
                el.classList.remove('is-invalid');
            });
        });

        // Para la función de autoFillSueldo, guardar el valor previo
        const sueldoInput = document.getElementById('sueldo');
        sueldoInput.dataset.prevValue = sueldoInput.value || '';
    };
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/puestos/edit.blade.php ENDPATH**/ ?>