<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica - @yield('title', 'Inicio')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

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
        }

        .navbar .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        main {
            flex: 1;
            width: 100vw;
            padding: 20px 15px;
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

        @media (max-width: 576px) {
            main {
                padding: 10px 5px;
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
    <style>
    html, body {
        height: 100%;
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
    }
</style>


    @stack('styles')
</head>
<body>

   

    {{-- Contenido principal --}}
    <main>
        
        @yield('content')
    </main>

    {{-- Footer blanco con texto oscuro --}}
    <footer>
        © 2025 Clínitek. Todos los derechos reservados.
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>
</html>
