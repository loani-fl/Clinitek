<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Clinitek'); ?></title>
    <!-- Bootstrap CSS (v5.3+) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    
    <?php echo $__env->yieldPushContent('styles'); ?>

    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="bg-light">

    <!-- Navbar principal -->
    <nav class="navbar navbar-expand-lg bg-primary shadow navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="<?php echo e(route('puestos.index')); ?>">
                <i class="bi bi-briefcase-fill me-2"></i>Clinitek
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo e(route('puestos.index')); ?>">Puestos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo e(route('empleados.index')); ?>">Empleados</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo e(route('medicos.index')); ?>">Medicos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Contenido principal -->
    <main class="py-4">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap JS -->


<!-- jQuery (necesario para filtros) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>

<?php /**PATH C:\Users\csosad\Desktop\ProyectoClinitek\Clinitek\resources\views/layouts/app.blade.php ENDPATH**/ ?>