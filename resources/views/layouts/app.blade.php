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

        /* NAVBAR CON ALTURA FIJA - SIEMPRE VISIBLE */
        .header {
            height: 70px;
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
            width: 100%;
        }

        .header .logo-section {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .header .right-section {
            display: flex;
            align-items: center;
            gap: 15px;
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
            padding-top: 90px;
        }

        footer {
            background-color: #ffffff;
            color: #333;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
            border-top: 1px solid #ccc;
        }

        /* PERFIL ESTILO GOOGLE CON NOMBRE */
        .user-profile-dropdown {
            position: relative;
        }

        .user-profile-trigger {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: opacity 0.2s;
            outline: none;
        }

        .user-profile-trigger:focus {
            outline: none;
        }

        .user-profile-trigger:hover {
            opacity: 0.9;
        }

        .user-avatar-header {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .user-profile-trigger:hover .user-avatar-header {
            border-color: rgba(255, 255, 255, 0.8);
            transform: scale(1.05);
        }

        .user-name-header {
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Dropdown Personalizado */
        .profile-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            min-width: 320px;
            padding: 16px;
            display: none;
            z-index: 1050;
            animation: dropdownSlideIn 0.2s ease-out;
        }

        .profile-dropdown-menu.show {
            display: block;
        }

        @keyframes dropdownSlideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-dropdown-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 1px solid #e0e0e0;
        }

        .profile-dropdown-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
            border: 3px solid #007BFF;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 2rem;
            text-transform: uppercase;
        }

        .profile-dropdown-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #202124;
            margin-bottom: 4px;
            text-align: center;
        }

        .profile-dropdown-email {
            font-size: 0.875rem;
            color: #5f6368;
            text-align: center;
        }

        .profile-dropdown-actions {
            padding-top: 12px;
        }

        .profile-dropdown-link {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            color: #5f6368;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.2s;
            font-size: 0.9rem;
        }

        .profile-dropdown-link:hover {
            background-color: #f1f3f4;
            color: #202124;
        }

        .profile-dropdown-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .profile-dropdown-divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 8px 0;
        }

        .profile-dropdown-link.logout {
            color: #d93025;
        }

        .profile-dropdown-link.logout:hover {
            background-color: #fce8e6;
            color: #d93025;
        }

        /* Tabla responsive */
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

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .user-name-header {
                display: none; /* Ocultar nombre en móvil */
            }
        }

        @media (max-width: 576px) {
            .header {
                height: 60px;
                min-height: 60px;
                max-height: 60px;
            }

            .header .right-section {
                gap: 10px;
            }

            .header .logo-section img {
                height: 32px;
            }

            .header .logo-section .fw-bold {
                font-size: 1.2rem;
            }

            main {
                padding: 10px 5px;
                padding-top: 75px;
            }

            .user-avatar-header {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }

            .profile-dropdown-menu {
                min-width: 280px;
                right: -10px;
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

    {{-- Barra de navegación superior fija --}}
    <div class="header">
        <div class="logo-section">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek">
            <div class="fw-bold">Clinitek</div>
        </div>

        <div class="right-section">
            {{-- Perfil de usuario estilo Google con nombre - SIEMPRE VISIBLE --}}
            <div class="user-profile-dropdown">
                <button class="user-profile-trigger" type="button" onclick="toggleProfileDropdown(event)">
                    @auth
                        @if(auth()->user()->photo)
                            <img src="{{ asset(auth()->user()->photo) }}?v={{ time() }}" 
                                 alt="Perfil" 
                                 class="user-avatar-header">
                        @else
                            <div class="user-avatar-header">
                                {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}
                            </div>
                        @endif
                        <span class="user-name-header">{{ auth()->user()->name ?? auth()->user()->email }}</span>
                    @else
                        <div class="user-avatar-header">
                            ?
                        </div>
                        <span class="user-name-header">Invitado</span>
                    @endauth
                </button>

                <div class="profile-dropdown-menu" id="profileDropdown">
                    @auth
                        <div class="profile-dropdown-header">
                            @if(auth()->user()->photo)
                                <img src="{{ asset(auth()->user()->photo) }}?v={{ time() }}" 
                                     alt="Perfil" 
                                     class="profile-dropdown-avatar">
                            @else
                                <div class="profile-dropdown-avatar">
                                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 2)) }}
                                </div>
                            @endif
                            <div class="profile-dropdown-name">
                                {{ auth()->user()->name ?? 'Usuario' }}
                            </div>
                            <div class="profile-dropdown-email">
                                {{ auth()->user()->email }}
                            </div>
                        </div>

                        <div class="profile-dropdown-actions">
                            <a href="{{ route('profile.edit') }}" class="profile-dropdown-link">
                                <i class="bi bi-person-circle"></i>
                                Mi perfil
                            </a>
                            <div class="profile-dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="profile-dropdown-link logout" style="width: 100%; border: none; background: none; text-align: left;">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="profile-dropdown-actions">
                            <a href="{{ route('login.form') }}" class="profile-dropdown-link">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Iniciar sesión
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Menú hamburguesa --}}
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    ☰
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="{{ route('puestos.index') }}"><i class="bi bi-briefcase"></i> Crear puesto</a></li>
                    <li><a class="dropdown-item" href="{{ route('empleados.index') }}"><i class="bi bi-person-badge"></i> Registrar empleado</a></li>
                    <li><a class="dropdown-item" href="{{ route('medicos.index') }}"><i class="bi bi-heart-pulse"></i> Registrar médico</a></li>
                    <li><a class="dropdown-item" href="{{ route('consultas.index') }}"><i class="bi bi-journal-medical"></i> Registrar consulta</a></li>
                    <li><a class="dropdown-item" href="{{ route('pacientes.index') }}"><i class="bi bi-people"></i> Registrar paciente</a></li>
                    <li><a class="dropdown-item" href="{{ route('farmacias.index') }}"><i class="bi bi-bag-heart"></i> Registrar Farmacia</a></li>
                    <li><a class="dropdown-item" href="{{ route('rayosx.index') }}"><i class="bi bi-x-diamond"></i> Registrar rayos x</a></li>
                    <li><hr class="dropdown-divider"></li>
                    @auth
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle"></i> Ver Perfil
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    @endauth
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

    <script>
        function toggleProfileDropdown(event) {
            event.preventDefault();
            event.stopPropagation();
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const trigger = document.querySelector('.user-profile-trigger');
            
            if (dropdown && trigger && !dropdown.contains(event.target) && !trigger.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Prevenir que el dropdown se cierre al hacer clic dentro de él
        document.getElementById('profileDropdown')?.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    </script>

    @stack('scripts')
</body>
</html>