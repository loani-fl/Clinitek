<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CLINITEK - Página Principal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #e6f0ff 0%, #f0f7ff 100%); color: #003366; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh; }

        /* Navbar superior moderna */
        .top-navbar {
            background: linear-gradient(135deg, #004aad 0%, #0066cc 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0, 74, 173, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-image { height: 65px; width: auto; max-width: 200px; object-fit: contain; display: block; }
        .logo-text h1 { color: white; font-size: 1.8rem; font-weight: 700; margin: 0; letter-spacing: 1px; }
        .logo-text p { color: rgba(255, 255, 255, 0.9); font-size: 0.85rem; margin: 0; }

        /* Sección de navegación derecha */
        .nav-right-section {
            display: flex;
            align-items: center;
            gap: 1rem;
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
            cursor: pointer;
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

        /* Botón menú hamburguesa */
        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            font-size: 1.2rem;
            padding: 0.4rem 0.8rem;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.8);
            color: white;
        }

        /* Hero section */
        .hero-section { max-width: 1400px; margin: 2rem auto 1.5rem; padding: 0 2rem; text-align: center; }
        .hero-title { font-size: 2.2rem; font-weight: 700; color: #003366; margin-bottom: 0.5rem; animation: fadeInUp 0.8s ease; }
        .hero-subtitle { font-size: 1.1rem; color: #0066cc; margin-bottom: 1.5rem; font-weight: 500; animation: fadeInUp 0.8s ease 0.2s both; }

        /* Contenedor principal con fondo */
        .services-container { max-width: 1400px; margin: 0 auto 2rem; padding: 0 2rem; }
        .services-wrapper { background: white; border-radius: 30px; padding: 2rem 2rem; box-shadow: 0 10px 40px rgba(0, 74, 173, 0.15); border: 2px solid rgba(0, 74, 173, 0.1); }

        /* Grid de servicios */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .service-card {
            background: linear-gradient(145deg, #f8fbff 0%, #ffffff 100%);
            padding: 1.5rem 1.2rem;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 74, 173, 0.08);
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(0, 74, 173, 0.1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            height: 100%;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #004aad, #0066cc, #00aaff);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .service-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0, 74, 173, 0.2); border-color: #004aad; background: white; }
        .service-card:hover::before { transform: scaleX(1); }

        .icon-circle {
            width: 70px; height: 70px; margin: 0 auto 1rem;
            background: linear-gradient(135deg, #e6f0ff 0%, #cce0ff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s ease;
            position: relative;
        }

        .service-card:hover .icon-circle { background: linear-gradient(135deg, #004aad, #0066cc); transform: scale(1.1) rotate(10deg); box-shadow: 0 8px 25px rgba(0, 74, 173, 0.3); }
        .service-icon { font-size: 2.2rem; color: #004aad; transition: all 0.4s ease; }
        .service-card:hover .service-icon { color: white; transform: scale(1.15); }

        .service-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.6rem; color: #003366; transition: color 0.3s ease; }
        .service-card:hover .service-title { color: #004aad; }
        .service-desc { color: #666; font-size: 0.85rem; line-height: 1.5; transition: color 0.3s ease; }
        .service-card:hover .service-desc { color: #444; }

        .badge-24h { position: absolute; top: 1rem; right: 1rem; background: linear-gradient(135deg, #ff6b6b, #ff8e53); color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; box-shadow: 0 2px 10px rgba(255, 107, 107, 0.4); z-index: 10; }

        footer { background: linear-gradient(135deg, #003366 0%, #004aad 100%); color: white; padding: 0.8rem 0; margin-top: 2rem; text-align: center; }
        .footer-content { max-width: 1400px; margin: 0 auto; padding: 0 2rem; }
        .footer-content p { margin: 0.2rem 0; opacity: 0.9; font-size: 0.9rem; }
        .footer-icons { margin-top: 0.5rem; display: flex; justify-content: center; gap: 1.2rem; }
        .footer-icons i { font-size: 1.2rem; color: white; transition: all 0.3s ease; cursor: pointer; }
        .footer-icons i:hover { transform: translateY(-3px) scale(1.2); color: #cce0ff; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .service-card { animation: fadeInUp 0.6s ease backwards; }
        .service-card:nth-child(1) { animation-delay: 0.1s; }
        .service-card:nth-child(2) { animation-delay: 0.2s; }
        .service-card:nth-child(3) { animation-delay: 0.3s; }
        .service-card:nth-child(4) { animation-delay: 0.4s; }
        .service-card:nth-child(5) { animation-delay: 0.5s; }

        /* Responsive */
        @media (max-width: 1200px) {
            .services-grid { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 768px) {
            .user-name {
                display: none;
            }

            .menu-text {
                display: none;
            }

            .btn-menu {
                padding: 0.6rem;
            }

            .user-profile-badge {
                padding: 6px;
            }

            .navbar-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-right-section {
                width: 100%;
                justify-content: center;
            }

            .hero-title {
                font-size: 2rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .services-wrapper {
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
<!-- Navbar superior -->
<nav class="top-navbar">
    <div class="navbar-content">
        <div class="logo-section">
            <img src="{{ asset('images/Barra.png') }}" alt="CLINITEK" class="logo-image">
            <div class="logo-text">
                <h1>CLINITEK</h1>
                <p>Sistema de Gestión Médica</p>
            </div>
        </div>
        
        <div class="nav-right-section">
            @if(auth()->check())
            {{-- Perfil de usuario estilo Google con nombre --}}
            <div class="user-profile-dropdown">
                <button class="user-profile-trigger" type="button" onclick="toggleProfileDropdown(event)">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                             alt="Perfil" 
                             class="user-avatar-header">
                    @else
                        <div class="user-avatar-header">
                            {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}
                        </div>
                    @endif
                    <span class="user-name-header">{{ auth()->user()->name ?? auth()->user()->email }}</span>
                </button>

                <div class="profile-dropdown-menu" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
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
                </div>
            </div>

            {{-- Menú hamburguesa --}}
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                    ☰
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-circle"></i> Ver Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
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
            @endif
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <h2 class="hero-title">Bienvenido a tu clínica de confianza</h2>
    <p class="hero-subtitle">Tu salud en manos expertas — atención integral para toda la familia</p>
</section>

<!-- Servicios -->
<div class="services-container">
    <div class="services-wrapper">
        <!-- Primera fila - GESTIÓN ADMINISTRATIVA -->
        <div class="services-grid">
            <a href="{{ route('puestos.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-briefcase service-icon"></i>
                    </div>
                    <h3 class="service-title">Gestión de Puestos</h3>
                    <p class="service-desc">Administración de puestos y cargos del personal médico.</p>
                </article>
            </a>
            <a href="{{ route('empleados.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-person-badge service-icon"></i>
                    </div>
                    <h3 class="service-title">Gestión de Empleados</h3>
                    <p class="service-desc">Control y administración del personal de la clínica.</p>
                </article>
            </a>
            <a href="{{ route('pacientes.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-people service-icon"></i>
                    </div>
                    <h3 class="service-title">Gestión de Pacientes</h3>
                    <p class="service-desc">Registro y seguimiento de historiales médicos de pacientes.</p>
                </article>
            </a>
            <a href="{{ route('usuarios.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-person-plus service-icon"></i>
                    </div>
                    <h3 class="service-title">Gestión de Usuarios</h3>
                    <p class="service-desc">Administración de usuarios y accesos al sistema.</p>
                </article>
            </a>
        </div>

        <!-- Segunda fila - continuación gestión + emergencia -->
        <div class="services-grid">
            <a href="{{ route('roles.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-shield-lock service-icon"></i>
                    </div>
                    <h3 class="service-title">Gestión de Roles</h3>
                    <p class="service-desc">Control de roles y permisos de usuarios en el sistema.</p>
                </article>
            </a>
            <a href="{{ route('emergencias.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <span class="badge-24h">24/7</span>
                    <div class="icon-circle">
                        <i class="bi bi-hospital service-icon"></i>
                    </div>
                    <h3 class="service-title">Emergencia</h3>
                    <p class="service-desc">Atención inmediata y profesional las 24 horas para situaciones críticas.</p>
                </article>
            </a>
            <a href="{{ route('medicos.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-heart-pulse service-icon"></i>
                    </div>
                    <h3 class="service-title">Medicina Especializada</h3>
                    <p class="service-desc">Especialidades médicas para todas tus necesidades de salud.</p>
                </article>
            </a>
            <a href="{{ route('consultas.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-journal-medical service-icon"></i>
                    </div>
                    <h3 class="service-title">Consultas Médicas</h3>
                    <p class="service-desc">Consultas personalizadas para diagnóstico y tratamiento.</p>
                </article>
            </a>
        </div>

        <!-- Tercera fila -->
        <div class="services-grid">
            <a href="{{ route('sesiones.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-chat-heart-fill service-icon"></i>
                    </div>
                    <h3 class="service-title">Psicología</h3>
                    <p class="service-desc">Sesiones con expertos y exámenes psicométricos confiables.</p>
                </article>
            </a>
            <a href="{{ route('farmacias.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-bag-heart service-icon"></i>
                    </div>
                    <h3 class="service-title">Farmacia</h3>
                    <p class="service-desc">Medicamentos y productos farmacéuticos de alta calidad.</p>
                </article>
            </a>
            <a href="{{ route('rayosx.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-x-diamond service-icon"></i>
                    </div>
                    <h3 class="service-title">Rayos X</h3>
                    <p class="service-desc">Imágenes diagnósticas con equipos modernos y precisos.</p>
                </article>
            </a>
            <a href="{{ route('ultrasonidos.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-easel3 service-icon"></i>
                    </div>
                    <h3 class="service-title">Ultrasonidos</h3>
                    <p class="service-desc">Estudios de ultrasonido confiables y detallados para diagnóstico médico.</p>
                </article>
            </a>
        </div>

        <!-- Cuarta fila -->
        <div class="services-grid">
            <a href="{{ route('inventario.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-clipboard-data-fill service-icon"></i>
                    </div>
                    <h3 class="service-title">Inventario</h3>
                    <p class="service-desc">Control completo del inventario y suministros médicos.</p>
                </article>
            </a>
            <a href="{{ route('controles-prenatales.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-heart-pulse-fill service-icon"></i>
                    </div>
                    <h3 class="service-title">Ginecología</h3>
                    <p class="service-desc">Control prenatal y atención especializada para la mujer.</p>
                </article>
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="footer-content">
        <p style="font-weight: 600; font-size: 1.1rem;">CLINITEK</p>
        <p>&copy; 2025 Clinitek. Todos los derechos reservados.</p>
        <div class="footer-icons">
            <i class="bi bi-facebook"></i>
            <i class="bi bi-instagram"></i>
            <i class="bi bi-whatsapp"></i>
            <i class="bi bi-envelope"></i>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>