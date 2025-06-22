<?php $__env->startSection('title', 'Lista de Puestos'); ?>

<?php $__env->startSection('content'); ?>
<style>
    body {
        background-color: #e8f4fc;
    }
    .custom-card {
        background-color: #f0faff;
        border-color: #91cfff;
    }
    .table thead th {
        background-color: #d0e9ff;
        color: #003f6b;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .btn-outline-info,
    .btn-outline-warning,
    .btn-outline-danger {
        transition: 0.2s;
    }
    .btn-outline-info:hover {
        background-color: #d0f0ff;
        color: #0d6efd;
    }
    .btn-outline-warning:hover {
        background-color: #fff4d0;
        color: #ffc107;
    }
    .btn-outline-danger:hover {
        background-color: #ffe0e0;
        color: #dc3545;
    }
</style>

<div class="container-fluid mt-4">
    <div class="card custom-card shadow rounded-4 border-0 w-100">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center flex-wrap px-4 py-3">
            <h4 class="mb-0">Lista de Puestos</h4>
            <a href="<?php echo e(route('puestos.create')); ?>" class="btn btn-light text-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-briefcase-fill"></i>
                Nuevo Puesto
            </a>
        </div>




        <div class="card-body px-4 py-4">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <?php if($puestos->isEmpty()): ?>
                <div class="alert alert-info shadow-sm" role="alert">
                    <i class="bi bi-info-circle me-2"></i> No hay puestos registrados aún.
                </div>
            <?php else: ?>
                 <div class="table-responsive flex-grow-1 overflow-auto px-3 pb-3">
            <table class="table table-bordered table-striped mb-0">
                        <thead class="text-uppercase small">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Departamento</th>
                                <th style="width: 220px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="fw-semibold text-secondary"><?php echo e($puesto->id); ?></td>
                                    <td><?php echo e($puesto->codigo); ?></td>
                                    <td class="fw-medium"><?php echo e($puesto->nombre); ?></td>
                                    <td><?php echo e($puesto->area); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('puestos.show', $puesto->id)); ?>" class="btn btn-sm btn-outline-info me-2" title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('puestos.edit', $puesto)); ?>" class="btn btn-sm btn-outline-warning me-2" title="Editar Puesto">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                       
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/puestos/index.blade.php ENDPATH**/ ?>