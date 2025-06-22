<?php $__env->startSection('title', 'Detalle del Puesto'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Opcional: estilos para negrita en valores si quieres */
    .list-group-item strong {
        color: rgb(58, 60, 63);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4 px-5 bg-light min-vh-100">
    <div class="card shadow rounded-4 border-0" style="background-color: #e3f2fd;">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #64b5f6;">
            <h4 class="mb-0">
                <i class="bi bi-briefcase-fill me-2"></i> Detalle del Puesto
            </h4>
            <a href="<?php echo e(route('puestos.index')); ?>" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
        </div>

        <div class="card-body">
            <div class="row g-4">
                
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent"><strong>Código:</strong> <?php echo e($puesto->codigo); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Nombre del Puesto:</strong> <?php echo e($puesto->nombre); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Área / Departamento:</strong> <?php echo e($puesto->area); ?></li>
                    </ul>
                </div>

                
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent"><strong>Sueldo:</strong> Lps. <?php echo e(number_format($puesto->sueldo, 2)); ?></li>
                        <li class="list-group-item bg-transparent"><strong>Función del Puesto:</strong><br>
                            <span style="white-space: pre-line;"><?php echo e($puesto->funcion); ?></span>
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







<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/puestos/show.blade.php ENDPATH**/ ?>