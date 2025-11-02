<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CLINITEK - Página Principal</title>
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

        .logo-image {
            height: 65px;
            width: auto;
            max-width: 200px;
            object-fit: contain;
            display: block;
        }

        .logo-text h1 {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
        }

        .logo-text p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem;
            margin: 0;
        }

        /* Dropdown mejorado */
        .registro-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .registro-btn:hover, .registro-btn:focus {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 74, 173, 0.3);
            border-radius: 12px;
            padding: 0.5rem;
            min-width: 250px;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.8rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            color: #003366;
            font-weight: 500;
        }

        .dropdown-item:hover { background: #e6f0ff; color: #004aad; transform: translateX(5px); }
        .dropdown-item i { margin-right: 0.5rem; color: #004aad; }

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

        /* Responsive */
        @media (max-width: 1200px) {
            .services-grid { grid-template-columns: repeat(2, 1fr); }
            .services-grid.second-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .navbar-content {
                flex-direction: column;
                gap: 1rem;
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
        <div class="dropdown">
            <button class="registro-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-grid-3x3-gap-fill"></i> Registros
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('puestos.index') }}"><i class="bi bi-briefcase"></i> Gestión de Puestos</a></li>
                <li><a class="dropdown-item" href="{{ route('empleado.index') }}"><i class="bi bi-person-badge"></i> Gestión de Empleados</a></li>
                <li><a class="dropdown-item" href="{{ route('pacientes.index') }}"><i class="bi bi-people"></i> Gestión de Pacientes</a></li>
            </ul>
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
        <!-- Primera fila -->
        <div class="services-grid">
            <a href="{{ route('emergencias.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card"><span class="badge-24h">24/7</span><div class="icon-circle"><i class="bi bi-hospital service-icon"></i></div><h3 class="service-title">Emergencia</h3><p class="service-desc">Atención inmediata y profesional las 24 horas para situaciones críticas.</p></article>
            </a>
            <a href="{{ route('medicos.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card"><div class="icon-circle"><i class="bi bi-heart-pulse service-icon"></i></div><h3 class="service-title">Medicina Especializada</h3><p class="service-desc">Especialidades médicas para todas tus necesidades de salud.</p></article>
            </a>
            <a href="{{ route('consultas.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card"><div class="icon-circle"><i class="bi bi-journal-medical service-icon"></i></div><h3 class="service-title">Consultas Médicas</h3><p class="service-desc">Consultas personalizadas para diagnóstico y tratamiento.</p></article>
            </a>
            <a href="{{ route('sesiones.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card"><div class="icon-circle"><i class="bi bi-chat-heart-fill service-icon"></i></div><h3 class="service-title">Psicología</h3><p class="service-desc">Sesiones con expertos y exámenes psicométricos confiables.</p></article>
            </a>
        </div>

        <!-- Segunda fila - 4 columnas -->
        <div class="services-grid">
            <a href="{{ route('farmacias.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card"><div class="icon-circle"><i class="bi bi-bag-heart service-icon"></i></div><h3 class="service-title">Farmacia</h3><p class="service-desc">Medicamentos y productos farmacéuticos de alta calidad.</p></article>
            </a>
            <a href="{{ route('rayosx.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card"><div class="icon-circle"><i class="bi bi-x-diamond service-icon"></i></div><h3 class="service-title">Rayos X</h3><p class="service-desc">Imágenes diagnósticas con equipos modernos y precisos.</p></article>
            </a>
            <a href="{{ route('ultrasonidos.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card"><div class="icon-circle"><i class="bi bi-easel3 service-icon"></i></div><h3 class="service-title">Ultrasonidos</h3><p class="service-desc">Estudios de ultrasonido confiables y detallados para diagnóstico médico.</p></article>
            </a>
            <a href="{{ route('inventario.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card"><div class="icon-circle"><i class="bi bi-clipboard-data-fill service-icon"></i></div><h3 class="service-title">Inventario</h3><p class="service-desc">Control completo del inventario y suministros médicos.</p></article>
            </a>

            <a href="{{ route('ginecologia.index') }}" style="text-decoration: none; color: inherit;">
                <article class="service-card">
                    <div class="icon-circle">
                        <i class="bi bi-heart-pulse-fill service-icon"></i>
                    </div>
                    <h3 class="service-title">Ginecología</h3>
                    <p class="service-desc">
                        Control prenatal y atención especializada para la mujer.
                    </p>
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
</body>
</html>
