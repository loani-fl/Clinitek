<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Clínica - @yield('title', 'Inicio')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    {{-- Estilos personalizados --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            background-color: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* para que flex funcione bien y el footer quede abajo */
        }
        main {
            flex: 1; /* ocupa todo el espacio disponible */
            width: 100vw;
            padding: 20px 15px;
            padding-top: 60px; /* espacio para navbar fixed */
        }
        footer {
            background-color: #ffffff;
            color: #333;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
            border-top: 1px solid #ccc;
        }

        .navbar .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
            width: 100%;
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            min-width: 800px;
        }

        .table th, .table td {
            padding: 8px 6px;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        @media (max-width: 576px) {
            main {
                padding: 10px 5px;
                padding-top: 60px; /* mantener espacio para navbar */
            }
            .table th, .table td {
                font-size: 0.8rem;
                padding: 6px 4px;
            }
            .btn {
                font-size: 0.85rem;
                padding: 6px 12px;
            }
            .header-content {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }
        }

        @media (min-width: 769px) {
            .table th, .table td {
                padding: 12px 10px;
                font-size: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Barra de navegación superior fija con menú desplegable --}}
    <div class="header d-flex justify-content-between align-items-center px-3 py-2 fixed-top" style="background-color: #007BFF;">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto;">
            <div class="fw-bold text-white ms-2" style="font-size: 1.5rem;">Clinitek</div>
        </div>

        <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                ☰
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="{{ route('puestos.create') }}">Crear puesto</a></li>
                <li><a class="dropdown-item" href="{{ route('empleado.create') }}">Registrar empleado</a></li>
                <li><a class="dropdown-item" href="{{ route('medicos.create') }}">Registrar médico</a></li>
                <li><a class="dropdown-item" href="{{ route('consultas.create') }}">Registrar consulta</a></li>
                <li><a class="dropdown-item" href="{{ route('pacientes.create') }}">Registrar paciente</a></li>
            </ul>
        </div>
    </div>

    {{-- Contenido principal --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer>
        © 2025 Clínitek. Todos los derechos reservados.
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('scripts')
</body>
</html>
