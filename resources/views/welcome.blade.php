<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CLINITEK - Página Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background: #e6f0ff; /* azul clarito suave */
            color: #003366; /* azul oscuro para texto */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background: #004aad; /* azul fuerte */
            color: white;
            padding: 3rem 1rem 2rem;
            text-align: center;
            border-radius: 0 0 40px 40px;
            box-shadow: 0 4px 10px rgba(0, 74, 173, 0.4);
            position: relative;
        }
        .header h1 {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }
        .header p {
            font-size: 1.3rem;
            font-weight: 500;
            opacity: 0.85;
        }
        /* Posicionar dropdown en esquina superior derecha */
        .dropdown-menu {
            min-width: 220px;
        }
        .registro-dropdown {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
        }
        .registro-btn {
            background-color: #002f70;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            box-shadow: 0 3px 8px rgba(0, 74, 173, 0.5);
            transition: background-color 0.3s ease;
        }
        .registro-btn:hover, .registro-btn:focus {
            background-color: #001f4a;
            color: #aad4ff;
        }

        .features {
            max-width: 960px;
            margin: 4rem auto 3rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 2rem;
        }
        .feature-card {
            background: white;
            padding: 2rem 1.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 74, 173, 0.15);
            text-align: center;
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 74, 173, 0.3);
        }
        .feature-icon {
            font-size: 3.5rem;
            color: #004aad;
            margin-bottom: 1rem;
        }
        .feature-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .feature-desc {
            color: #555;
            font-size: 1rem;
        }
        footer {
            text-align: center;
            padding: 1rem 0;
            font-size: 0.9rem;
            color: #004aad;
            background: #cce0ff;
            margin-top: 5rem;
            border-top: 2px solid #004aad;
        }
    </style>
</head>
<body>

    <header class="header">
        <h1>CLINITEK</h1>
        <p>Tu salud en manos expertas — atención integral para toda la familia</p>

        <!-- Dropdown Registro -->
        <div class="dropdown registro-dropdown">
            <button class="registro-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Registros
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('puestos.index') }}">Registrar Puesto</a></li>
                <li><a class="dropdown-item" href="{{ route('empleado.index') }}">Registrar Empleado</a></li>
                <li><a class="dropdown-item" href="{{ route('pacientes.index') }}">Registrar Paciente</a></li>
            </ul>
        </div>
    </header>

    <main>
        <section class="features" aria-label="Servicios de la clínica">

            <a href="{{ route('farmacias.index') }}" style="text-decoration: none; color: inherit;">
            <article class="feature-card">
                <i class="bi bi-bag-heart feature-icon" title="Farmacia"></i>
                <h3 class="feature-title">Farmacia</h3>
                <p class="feature-desc">Contamos con farmacia equipada con medicamentos y productos de alta calidad para tu bienestar.</p>
            </article>
            </a>

            <article class="feature-card">
            <i class="bi bi-eyedropper feature-icon" title="Laboratorio"></i>
                <h3 class="feature-title">Laboratorio</h3>
                <p class="feature-desc">Laboratorio clínico con tecnología avanzada para diagnósticos rápidos y confiables.</p>
            </article>

           <a href="{{ route('emergencias.index') }}" style="text-decoration: none; color: inherit;">
    <article class="feature-card">
        <i class="bi bi-hospital feature-icon" title="Área de Emergencia"></i>
        <h3 class="feature-title">Emergencia</h3>
        <p class="feature-desc">
            Atención inmediata y profesional las 24 horas para situaciones críticas y urgentes.
        </p>
    </article>
</a>


         <a href="{{ route('rayosx.index') }}" style="text-decoration: none; color: inherit;">
    <article class="feature-card" role="button" aria-label="Abrir formulario de Orden de Rayos X">
        <i class="bi bi-x-diamond feature-icon" title="Rayos X"></i>
        <h3 class="feature-title">Rayos X</h3>
        <p class="feature-desc">Servicio de imágenes diagnósticas con equipos modernos para mayor precisión.</p>
    </article>
</a>


            <a href="{{ route('medicos.index') }}" style="text-decoration: none; color: inherit;">
    <article class="feature-card">
        <i class="bi bi-heart-pulse feature-icon" title="Medicina Especializada"></i>
        <h3 class="feature-title">Medicina con Especialidades</h3>
        <p class="feature-desc">Diversas especialidades médicas para cubrir todas tus necesidades de salud.</p>
    </article>
</a>


          <a href="{{ route('consultas.index') }}" style="text-decoration: none; color: inherit;">
            <article class="feature-card">
                <i class="bi bi-journal-medical feature-icon" title="Consultas"></i>
                <h3 class="feature-title">Consultas</h3>
                <p class="feature-desc">Consultas médicas personalizadas para un diagnóstico y tratamiento adecuados.</p>
            </article>
            </a>


        </section>
    </main>

    <footer>
        &copy; 2025 Clinitek. Todos los derechos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
