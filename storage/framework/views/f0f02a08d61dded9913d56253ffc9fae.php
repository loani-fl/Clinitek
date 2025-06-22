

<?php $__env->startSection('title'); ?>

<?php $__env->startSection('content'); ?>   

            <div class="card-body">
                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                
                <form action="<?php echo e(route('medicos.index')); ?>" method="GET" class="mb-3 row g-2 align-items-center">
                    <div class="col-auto">
                        <input
                            type="text"
                            name="buscar"
                            class="form-control"
                            placeholder="Buscar por nombre o especialidad"
                            value="<?php echo e(request('buscar')); ?>"
                            style="min-width: 250px;"
                        >
                    </div>

                    <a href="<?php echo e(route('medicos.create')); ?>" class="btn btn-light text-primary d-flex align-items-center gap-2 shadow-sm">
    <i class="bi bi-person-plus-fill"></i>
    Registrar médico
</a>

                    <div class="col-auto">
                        <select name="estado" class="form-select" style="min-width: 130px;">
                            <option value="">-- Todos --</option>
                            <option value="1" <?php echo e(request('estado') === '1' ? 'selected' : ''); ?>>Activo</option>
                            <option value="0" <?php echo e(request('estado') === '0' ? 'selected' : ''); ?>>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-primary">Buscar</button>
                    </div>
                </form>


                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-primary text-primary text-uppercase small">
                        <tr>
                            
                            <th>#</th>
                            <th>Estado</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Especialidad</th>
                            <th>Género</th>
                            <th>Acciones</th>
                            
                        </tr>
                        </thead>

                        <tbody>
                        <?php if($medicos->isEmpty()): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="alert alert-info shadow-sm text-center m-0" role="alert">
                                        <i class="bi bi-info-circle me-2"></i> No hay médicos registrados aún.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $__currentLoopData = $medicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e(($medicos->currentPage() - 1) * $medicos->perPage() + $loop->iteration); ?></td>
                                    <td class="text-center">
                                        <?php if($medico->estado): ?>
                                            <i class="bi bi-circle-fill text-success" title="Activo"></i>
                                        <?php else: ?>
                                            <i class="bi bi-circle-fill text-danger" title="Inactivo"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-medium"><?php echo e($medico->nombre); ?></td>
                                    <td><?php echo e($medico->apellidos); ?></td>
                                    <td class="text-center"><?php echo e($medico->telefono); ?></td>
                                    <td><?php echo e($medico->correo); ?></td>
                                    <td class="text-center"><?php echo e($medico->especialidad); ?></td>
                                    <td class="text-center">
                                        <?php if($medico->genero === 'Masculino'): ?>
                                            <span class="badge bg-primary"><?php echo e($medico->genero); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-info"><?php echo e($medico->genero); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('medicos.show', $medico->id)); ?>" class="btn btn-sm btn-outline-info me-2" title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('medicos.edit', $medico->id)); ?>" class="btn btn-sm btn-outline-warning me-2" title="Editar Médico">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        <?php echo e($medicos->links('pagination::bootstrap-5')); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/medicos/index.blade.php ENDPATH**/ ?>