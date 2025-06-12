<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinica - @yield('title', 'Inicio')</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
  
    
    {{-- Estilos personalizados --}}
    <style>
        /* Reset y configuración base */
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
        
        /* Contenedor que ocupa toda la pantalla */
        .full-width-container {
            width: 100%;
            max-width: 100%;
            padding: 0 15px;
            margin: 0;
        }
        
        /* Navbar responsive */
        .navbar {
            margin-bottom: 0;
        }
        
        .navbar .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        /* Main content que ocupa toda la pantalla */
        main {
            width: 100vw;
            min-height: calc(100vh - 76px); /* Altura total menos navbar */
            padding: 20px 15px;
            margin: 0;
        }
        
        /* Container de la tabla completamente responsivo */
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px;
            width: 100%;
            overflow: hidden;
        }
        
        /* Tabla completamente responsiva */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            width: 100%;
            min-width: 800px; /* Mínimo para que no se comprima demasiado */
            margin-bottom: 0;
        }
        
        .table th, .table td {
            padding: 8px 6px;
            font-size: 0.9rem;
            vertical-align: middle;
        }
        
        /* Responsive breakpoints */
        
        /* Móviles pequeños */
        @media (max-width: 576px) {
            main {
                padding: 10px 5px;
            }
            
            .table-container {
                padding: 10px;
                border-radius: 4px;
            }
            
            .table th, .table td {
                padding: 6px 4px;
                font-size: 0.8rem;
            }
            
            .btn {
                font-size: 0.85rem;
                padding: 6px 12px;
            }
        }
        
        /* Tablets */
        @media (min-width: 577px) and (max-width: 768px) {
            .table {
                min-width: 700px;
            }
        }
        
        /* Desktop pequeño */
        @media (min-width: 769px) and (max-width: 1024px) {
            .table {
                min-width: 900px;
            }
            
            .table th, .table td {
                padding: 10px 8px;
                font-size: 0.95rem;
            }
        }
        
        /* Desktop grande */
        @media (min-width: 1025px) {
            .table {
                min-width: 100%;
            }
            
            .table th, .table td {
                padding: 12px 10px;
                font-size: 1rem;
            }
            
            /* Distribuir columnas proporcionalmente en desktop */
            .table th:nth-child(1), .table td:nth-child(1) { width: 10%; }
            .table th:nth-child(2), .table td:nth-child(2) { width: 15%; }
            .table th:nth-child(3), .table td:nth-child(3) { width: 12%; }
            .table th:nth-child(4), .table td:nth-child(4) { width: 18%; }
            .table th:nth-child(5), .table td:nth-child(5) { width: 12%; }
            .table th:nth-child(6), .table td:nth-child(6) { width: 12%; }
            .table th:nth-child(7), .table td:nth-child(7) { width: 8%; }
            .table th:nth-child(8), .table td:nth-child(8) { width: 13%; }
        }
        
        /* Responsive para correo en todas las pantallas */
        .table td:nth-child(4) {
            word-break: break-word;
            white-space: normal;
            max-width: 200px;
        }
        
        /* Header responsive */
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 576px) {
            .header-content {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    {{-- Barra de navegación - SIN el botón duplicado --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Clinitek</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    {{-- Aquí puedes agregar otros enlaces de navegación si necesitas --}}
                </ul>
            </div>
        </div>
    </nav>

    {{-- Contenido principal que ocupa toda la pantalla --}}
    <main>
        @yield('content')
    </main>

    {{-- Scripts de Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
