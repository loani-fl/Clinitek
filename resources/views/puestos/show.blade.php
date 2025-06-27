@extends('layouts.app')

@section('title', 'Detalle del Puesto')

@push('styles')
<style>
    body {
        background-color: #e8f4fc;
    }

    .custom-card {
        max-width: 800px;
        background-color: rgba(255, 255, 255, 0.95);
        border: 1px solid #91cfff;
        border-radius: 0.5rem;
        position: relative;
        overflow: hidden;
        margin: 2rem auto 4rem auto;
        padding: 2rem;
        box-shadow: 0 0 15px rgba(0,123,255,0.25);
        z-index: 1;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 700px;
        height: 700px;
        background-image: url('{{ asset('images/logo2.jpg') }}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.15;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    label, strong {
        font-weight: 600;
        color: #003f6b;
    }

    /* Barra superior */
    .header {
        background-color: #007BFF;
        position: sticky;
        top: 0;
        z-index: 1030;
        padding: 0.5rem 1rem;
    }
    .header .fw-bold {
        font-size: 1.5rem;
        color: white;
    }
    .header a {
        color: white;
        font-weight: 600;
        text-decoration: none;
    }
    .header a:hover {
        text-decoration: underline;
    }

    /* Card header centrado y color negro */
    .custom-card-header {
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        color: #000;
        font-weight: 700;
        text-align: center;
    }

    /* Botón regresar centrado abajo */
    .btn-regresar {
        font-size: 0.9rem;
        padding: 0.4rem 1.2rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        display: block;
        margin: 2rem auto 0 auto;
        width: max-content;
    }

    /* Centrar contenido de las columnas dentro de la card */
    .row.g-4 {
        max-width: 600px; /* controla el ancho del contenido */
        margin-left: auto;
        margin-right: auto;
    }

    /* Lista grupos */
    .list-group-item {
        background-color: transparent;
        border: none;
        padding-left: 0;
        padding-right: 0;
        font-size: 1rem;
        color: #222;
    }

    .list-group-item strong {
        color: #003f6b;
    }

    /* Footer */
    footer {
        font-size: 0.85rem;
        margin-top: 2rem;
    }

    @media (max-width: 576px) {
        .custom-card {
            padding: 1rem;
            margin: 1rem;
        }
        .custom-card-header {
            font-size: 1.2rem;
        }
        .row.g-4 {
            max-width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
    }
</style>
@endpush

@section('content')

    <!-- Barra de navegación superior -->
    <div class="header d-flex justify-content-between align-items-center px-3 py-2">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
            <span class="fw-bold">Clinitek</span>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}">Crear puesto</a>
            <a href="{{ route('empleado.create') }}">Registrar empleado</a>
            <a href="{{ route('medicos.create') }}">Registrar médico</a>
        </div>
    </div>

    <!-- Contenedor principal -->
    <div class="custom-card">
        <div class="custom-card-header">
            Detalle del Puesto
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Código:</strong> {{ $puesto->codigo }}</li>
                    <li class="list-group-item"><strong>Nombre del Puesto:</strong> {{ $puesto->nombre }}</li>
                    <li class="list-group-item"><strong>Área / Departamento:</strong> {{ $puesto->area }}</li>
                </ul>
            </div>

            <div class="col-md-6">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Sueldo:</strong> Lps. {{ number_format($puesto->sueldo, 2) }}</li>
                    <li class="list-group-item"><strong>Función del Puesto:</strong><br>
                        <span style="white-space: pre-line;">{{ $puesto->funcion }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <a href="{{ route('puestos.index') }}" class="btn btn-success btn-regresar">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    <footer class="bg-light text-center py-2 border-top">
        © 2025 Clínitek. Todos los derechos reservados.
    </footer>

@endsection

