

<?php $__env->startSection('content'); ?>

    <div class="container">
        <div class="mb-3">
            <form id="estadoForm" action="<?php echo e(route('medicos.toggleEstado', $medico->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <button type="submit"
                        id="estadoBtn"
                        class="btn <?php echo e($medico->estado ? 'btn-outline-danger' : 'btn-outline-success'); ?>"
                        title="<?php echo e($medico->estado ? 'Desactivar médico' : 'Activar médico'); ?>">
                    <i id="iconoEstado" class="bi <?php echo e($medico->estado ? 'bi-person-dash' : 'bi-person-check'); ?>"></i>
                    <span id="textoEstado"><?php echo e($medico->estado ? 'Desactivar Médico' : 'Activar Médico'); ?></span>
                </button>
            </form>

            

            <div class="card shadow-sm rounded-3">
                <div class="card shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Editar Médico</h4>
                        <a href="<?php echo e(route('medicos.index')); ?>" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver a la lista
                        </a>
                    </div>

                    <div class="card-body">

<form class="needs-validation" novalidate action="<?php echo e(route('medicos.update', $medico->id)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

   
<div class="row g-3">
    <div class="col-md-2">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" maxlength="50"
               class="form-control <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('nombre', $medico->nombre)); ?>" required>
        <div class="invalid-feedback">Completa este dato</div>
        <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label class="form-label">Apellidos</label>
        <input type="text" name="apellidos" maxlength="50"
               class="form-control <?php $__errorArgs = ['apellidos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('apellidos', $medico->apellidos)); ?>" required>
        <div class="invalid-feedback">Completa este dato</div>
        <?php $__errorArgs = ['apellidos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label class="form-label">Especialidad</label>
        <select name="especialidad" id="especialidad" class="form-select <?php $__errorArgs = ['especialidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
            <option value="">Seleccionar</option>
            <?php
                $especialidades = ['Cardiología', 'Neurología', 'Pediatría', 'Dermatología', 'Psiquiatría', 'Radiología'];
            ?>
            <?php $__currentLoopData = $especialidades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $esp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($esp); ?>" <?php echo e(old('especialidad', $medico->especialidad) == $esp ? 'selected' : ''); ?>><?php echo e($esp); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['especialidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" maxlength="8" pattern="[983][0-9]{7}"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
               class="form-control <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('telefono', $medico->telefono)); ?>" required>
        <div class="invalid-feedback">Debe comenzar con 9, 8 o 3</div>
        <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" maxlength="30"
               class="form-control <?php $__errorArgs = ['correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('correo', $medico->correo)); ?>" required>
        <?php $__errorArgs = ['correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label class="form-label">Identidad</label>
        <input type="text" name="numero_identidad" maxlength="13"
               class="form-control <?php $__errorArgs = ['numero_identidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('numero_identidad', $medico->numero_identidad)); ?>" required>
        <div class="invalid-feedback">Completa este dato</div>
        <?php $__errorArgs = ['numero_identidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>


<div class="row g-3 mt-3">
    <div class="col-md-2">
        <label class="form-label">Salario</label>
        <input type="text" id="salario_mostrado" class="form-control" readonly>
        <input type="hidden" name="salario" id="salario_real" value="<?php echo e(old('salario', $medico->salario)); ?>">
        <?php $__errorArgs = ['salario'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback d-block"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label class="form-label">Fecha Nacimiento</label>
        <input type="date" name="fecha_nacimiento"
               class="form-control <?php $__errorArgs = ['fecha_nacimiento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('fecha_nacimiento', $medico->fecha_nacimiento)); ?>"
               min="1950-01-01" max="2005-12-31" required>
        <div class="invalid-feedback">Completa este dato</div>
        <?php $__errorArgs = ['fecha_nacimiento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <?php
        $minFecha = \Carbon\Carbon::now()->subMonth()->format('Y-m-d');
        $maxFecha = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');
    ?>

    <div class="col-md-2">
        <label class="form-label">Fecha Ingreso</label>
        <input type="date" name="fecha_ingreso" id="fecha_ingreso"
               class="form-control <?php $__errorArgs = ['fecha_ingreso'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               value="<?php echo e(old('fecha_ingreso', $medico->fecha_ingreso)); ?>"
               min="<?php echo e($minFecha); ?>" max="<?php echo e($maxFecha); ?>" required>
        <div class="invalid-feedback">Completa este dato</div>
        <?php $__errorArgs = ['fecha_ingreso'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-2">
        <label class="form-label">Género</label>
        <select name="genero" class="form-select <?php $__errorArgs = ['genero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
            <option value="">Seleccionar</option>
            <option value="Masculino" <?php echo e(old('genero', $medico->genero) == 'Masculino' ? 'selected' : ''); ?>>Masculino</option>
            <option value="Femenino" <?php echo e(old('genero', $medico->genero) == 'Femenino' ? 'selected' : ''); ?>>Femenino</option>
            <option value="Otro" <?php echo e(old('genero', $medico->genero) == 'Otro' ? 'selected' : ''); ?>>Otro</option>
        </select>
        <?php $__errorArgs = ['genero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
<div class="row mt-4">
    <div class="col-md-3">
        <label class="form-label">Foto actual</label><br>
        <?php if($medico->foto): ?>
            <img src="<?php echo e(asset('storage/' . $medico->foto)); ?>" alt="Foto actual"
                 class="img-thumbnail mb-2" style="width: 100px; height: 100px; object-fit: cover;">
        <?php else: ?>
            <p class="text-muted">No hay foto</p>
        <?php endif; ?>

        <label class="form-label mt-2">Cambiar foto</label>
        <input type="file" name="foto" id="foto"
               class="form-control form-control-sm <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-5">
        <label class="form-label">Observaciones</label>
        <textarea name="observaciones" maxlength="100" rows="3"
                  class="form-control <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('observaciones', $medico->observaciones)); ?></textarea>
        <?php $__errorArgs = ['observaciones'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

</div>


    <div class="mt-3">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Guardar cambios
    </button>
    <a href="<?php echo e(route('medicos.edit', $medico->id)); ?>" class="btn btn-warning">
        <i class="bi bi-arrow-counterclockwise"></i> Restablecer
    </a>
</div>

</form>


            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script>
        function confirmarCancelacion() {
            if (confirm('¿Deseas cancelar la actualización del médico?')) {
                window.location.href = "<?php echo e(route('medicos.index')); ?>";
            }
        }
    </script>


                <script>
                    document.getElementById('estadoForm').addEventListener('submit', function (e) {
                        e.preventDefault();

                        const form = this;
                        const btn = document.getElementById('estadoBtn');
                        const icon = document.getElementById('iconoEstado');
                        const texto = document.getElementById('textoEstado');

                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: new URLSearchParams(new FormData(form))
                        })
                            .then(res => res.json())
                            .then(data => {
                                // Cambiar clases y texto según el nuevo estado
                                if (data.estado) {
                                    btn.classList.remove('btn-outline-success');
                                    btn.classList.add('btn-outline-danger');
                                    icon.className = 'bi bi-person-dash';
                                    texto.textContent = 'Desactivar Médico';
                                } else {
                                    btn.classList.remove('btn-outline-danger');
                                    btn.classList.add('btn-outline-success');
                                    icon.className = 'bi bi-person-check';
                                    texto.textContent = 'Activar Médico';
                                }
                            })
                            .catch(err => console.error('Error al cambiar estado:', err));
                    });
                </script>


                <script>
                    (() => {
                        'use strict';
                        const forms = document.querySelectorAll('.needs-validation');

                        Array.from(forms).forEach(form => {
                            form.addEventListener('submit', event => {
                                if (!form.checkValidity()) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                }

                                form.classList.add('was-validated');
                            }, false);
                        });
                    })();
                </script>
                <script>
                    (() => {
                        'use strict';
                        const forms = document.querySelectorAll('.needs-validation');

                        Array.from(forms).forEach(form => {
                            form.addEventListener('submit', event => {
                                if (!form.checkValidity()) {
                                    event.preventDefault();
                                    event.stopPropagation();

                                    // Buscar el primer campo inválido
                                    const firstInvalidField = form.querySelector(':invalid');
                                    if (firstInvalidField) {
                                        firstInvalidField.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                        firstInvalidField.focus();
                                    }
                                }

                                form.classList.add('was-validated');
                            }, false);
                        });
                    })();
                </script>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const correoField = document.querySelector('input[name="correo"]');

                        if (correoField) {
                            correoField.addEventListener('invalid', function(e) {
                                e.preventDefault();
                                if (correoField.validity.typeMismatch) {
                                    correoField.setCustomValidity('El correo debe tener un formato válido, incluyendo "@" y "."');
                                } else {
                                    correoField.setCustomValidity('');
                                }
                                correoField.reportValidity();
                            });

                            correoField.addEventListener('input', function() {
                                correoField.setCustomValidity('');
                            });
                        }
                    });
                </script>
                <script>
                    function validarSalario(input) {
                        let valor = input.value;

                        // Forzamos a máximo 5 dígitos enteros y 2 decimales
                        const regex = /^(\d{0,5})(\.\d{0,2})?$/;

                        if (!regex.test(valor)) {
                            valor = valor.slice(0, -1); // elimina último caracter si no cumple
                        }

                        input.value = valor;
                    }
                </script>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const correoInput = document.querySelector('input[name="correo"]');
                        correoInput.addEventListener("invalid", function(event) {
                            event.target.setCustomValidity("Completa este dato");
                        });

                        correoInput.addEventListener("input", function(event) {
                            event.target.setCustomValidity(""); // Quita el mensaje cuando empieza a escribir
                        });
                    });
                </script>

                <?php $__env->startPush('scripts'); ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const fechaIngreso = document.getElementById('fecha_ingreso');

                            fechaIngreso.addEventListener('blur', function () {
                                const valor = fechaIngreso.value;
                                const anio = valor.split('-')[0];

                                if (anio.length !== 4 || isNaN(anio)) {
                                    alert('El año debe tener exactamente 4 dígitos.');
                                    fechaIngreso.value = '';
                                    fechaIngreso.focus();
                                }
                            });
                        });
                    </script>
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
        "Radiología": 16300
    };

    function formatearSalario(valor) {
        return 'L ' + valor.toLocaleString('es-HN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function actualizarSalario() {
        const seleccion = especialidadSelect.value;
        if (salariosPorEspecialidad.hasOwnProperty(seleccion)) {
            const salario = salariosPorEspecialidad[seleccion];
            salarioReal.value = salario;
            salarioMostrado.value = formatearSalario(salario);
        } else {
            salarioReal.value = '';
            salarioMostrado.value = '';
        }
    }

    // Al cambiar la especialidad
    especialidadSelect.addEventListener('change', actualizarSalario);

    // Al cargar la página, si hay especialidad seleccionada y un salario existente
    const seleccionInicial = especialidadSelect.value;
    const salarioInicial = salarioReal.value;

    if (seleccionInicial && salariosPorEspecialidad.hasOwnProperty(seleccionInicial)) {
        actualizarSalario(); // sobreescribe lo anterior si es reconocida
    } else if (salarioInicial) {
        salarioMostrado.value = formatearSalario(parseFloat(salarioInicial));
    }
});
</script>





    <?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/medicos/edit.blade.php ENDPATH**/ ?>