<!DOCTYPE html>
<html lang="es">
  


<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Clinitek')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Token CSRF para formularios -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 y Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            background-color: #f8f9fa;
            font-family: sans-serif;
        }

        .contenido {
            min-height: 100vh; /* Ocupa todo el alto de la pantalla */
            padding: 20px;
            box-sizing: border-box;
        }

        footer {
            margin-top: 3rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Clinitek</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
              <div class="d-flex gap-2">
        <a href="{{ route('puestos.index') }}" class="btn btn-light">
            <i class="bi bi-briefcase-fill"></i> Crear puesto
        </a>

        <a href="{{ route('empleados.create') }}" class="btn btn-light">
            <i class="bi bi-person-plus-fill"></i> Registrar empleado
        </a>
    </div>
</div>
    </div>
</nav>

<div class="contenido">
    <header>
        <h2 class="text-primary">@yield('title', '')</h2>
    </header>

    @yield('content')

    <footer>
        &copy; {{ date('Y') }} Clínitek. Todos los derechos reservados.
    </footer>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para ocultar mensaje de éxito después de unos segundos -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            setTimeout(() => {
                alert.classList.add('fade');
                alert.classList.add('show');
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500);
            }, 4000);
        }
    });
</script>

@stack('scripts')
</body>
</html>
