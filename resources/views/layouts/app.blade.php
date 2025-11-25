<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Clínica - @yield('title', 'Inicio')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            min-height: 100vh;
        }

        /* NAVBAR CON ALTURA FIJA */
        .header {
            height: 70px; /* Altura fija del navbar */
            min-height: 70px;
            max-height: 70px;
            background-color: #007BFF;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header .logo-section {
            display: flex;
            align-items: center;
            height: 100%;
        }

        /* Contenedor derecho para perfil y menú */
        .header .right-section {
            display: flex;
            align-items: center;
            gap: 15px; /* Espacio entre perfil y menú */
            height: 100%;
        }

        .header .logo-section img {
            height: 40px;
            width: auto;
        }

        .header .logo-section .fw-bold {
            font-size: 1.5rem;
            color: white;
            margin-left: 0.5rem;
        }

        main {
            flex: 1;
            width: 100vw;
            padding: 20px 15px;
            padding-top: 90px; /* 70px del navbar + 20px de espacio */
        }

        footer {
            background-color: #ffffff;
            color: #333;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
            border-top: 1px solid #ccc;
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

        /* USER PROFILE */
        .user-profile-container {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 14px 6px 6px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.08) 100%);
            border-radius: 50px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-profile-badge:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0.12) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-color: rgba(255, 255, 255, 0.35);
        }

        .user-profile-badge:active {
            transform: translateY(0);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
        }

        .user-profile-badge:hover .user-avatar {
            transform: scale(1.08);
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.4);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .user-name {
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
            letter-spacing: 0.3px;
            white-space: nowrap;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Animación de entrada */
        @keyframes fadeInSlideRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .user-profile-badge {
            animation: fadeInSlideRight 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* RESPONSIVE */
        @media (max-width: 576px) {
            .header {
                height: 60px;
                min-height: 60px;
                max-height: 60px;
            }

            .header .right-section {
                gap: 10px; /* Reduce el espacio en móviles */
            }

            .header .logo-section img {
                height: 32px;
            }

            .header .logo-section .fw-bold {
                font-size: 1.2rem;
            }

            main {
                padding: 10px 5px;
                padding-top: 75px; /* 60px del navbar + 15px de espacio */
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

            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }

            .user-name {
                font-size: 0.8rem;
                max-width: 100px;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    {{-- Barra de navegación superior fija con menú desplegable --}}
    <div class="header">
        <div class="logo-section">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek">
            <div class="fw-bold">Clinitek</div>
        </div>

        <div class="right-section">
            @if(auth()->check())
            <div class="user-profile-container">
                <div class="user-profile-badge">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}
                    </div>
                    <span class="user-name">
                        {{ auth()->user()->name ?? auth()->user()->email }}
                    </span>
                </div>
            </div>
            @endif

            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    ☰
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="{{ route('puestos.index') }}">Crear puesto</a></li>
                    <li><a class="dropdown-item" href="{{ route('empleado.index') }}">Registrar empleado</a></li>
                    <li><a class="dropdown-item" href="{{ route('medicos.index') }}">Registrar médico</a></li>
                    <li><a class="dropdown-item" href="{{ route('consultas.index') }}">Registrar consulta</a></li>
                    <li><a class="dropdown-item" href="{{ route('pacientes.index') }}">Registrar paciente</a></li>
                    <li><a class="dropdown-item" href="{{ route('farmacias.index') }}">Registrar Farmacia</a></li>
                    <li><a class="dropdown-item" href="{{ route('rayosx.index') }}">Registrar rayos x</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
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