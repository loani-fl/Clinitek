

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4 px-5 bg-light min-vh-100">
    <div class="card shadow rounded-4 border-0" style="background-color: #e3f2fd;">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #64b5f6;">
            <h4 class="mb-0">
                <i class="bi bi-person-badge-fill me-2"></i> Detalles del Empleado
            </h4>
            <a href="<?php echo e(route('empleados.index')); ?>" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card-body">
            <div class="row g-4">

                
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent"><strong>Nombres:</strong> <?php echo e($empleado->nombres); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Apellidos:</strong> <?php echo e($empleado->apellidos); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Identidad:</strong> <?php echo e($empleado->identidad); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Correo:</strong> <?php echo e($empleado->correo); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Teléfono:</strong> <?php echo e($empleado->telefono); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Estado Civil:</strong> <?php echo e($empleado->estado_civil); ?></li>
                        <li class="list-group-item bg-transparent">
                            <strong>Género:</strong> 
                            <span class="badge 
                                <?php echo e($empleado->genero === 'Masculino' ? 'bg-primary' : 
                                   ($empleado->genero === 'Femenino' ? 'bg-warning text-dark' : 'bg-info')); ?>">
                                <?php echo e($empleado->genero); ?>

                            </span>
                        </li>
                        <li class="list-group-item bg-transparent"><strong>Fecha de Nacimiento:</strong> <?php echo e(\Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y')); ?></li>
                    </ul>
                </div>

                
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent"><strong>Fecha de Ingreso:</strong> <?php echo e(\Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y')); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Salario:</strong> <?php echo e($empleado->salario ? 'Lps. ' . number_format($empleado->salario, 2) : 'No especificado'); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Área:</strong> <?php echo e($empleado->puesto->area ?? 'No especificada'); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Turno Asignado:</strong> <?php echo e($empleado->turno_asignado); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Puesto:</strong> <?php echo e($empleado->puesto->nombre ?? 'No especificado'); ?></li>
                        <li class="list-group-item bg-transparent">
                            <strong>Estado:</strong>
                            <?php if($empleado->estado == 'Activo' || $empleado->estado == 1 || $empleado->estado === true): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </li>
                        <li class="list-group-item bg-transparent">
                            <strong>Dirección:</strong><br>
                            <span style="white-space: pre-line;"><?php echo e($empleado->direccion); ?></span>
                        </li>
                        <li class="list-group-item bg-transparent">
                            <strong>Observaciones:</strong><br>
                            <span style="white-space: pre-line;"><?php echo e($empleado->observaciones ?: 'Sin observaciones.'); ?></span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<footer class="bg-light text-center py-2 border-top mt-4" style="font-size: 0.85rem;">
    © 2025 Clínitek. Todos los derechos reservados.
</footer>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/empleados/show.blade.php ENDPATH**/ ?>