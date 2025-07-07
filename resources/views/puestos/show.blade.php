@extends('layouts.app')

@section('title', 'Detalle del Puesto')

@push('styles')
<style>
    body {
        background-color: #e8f4fc;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 800px;
        height: 800px;
        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.15;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }

    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        border-color: #91cfff;
        position: relative;
        overflow: hidden;
        margin: 70px auto 2rem; /* margen superior aumentado para navbar fijo */
        padding: 1.5rem 5rem 5rem 5rem; /* menos padding arriba para acercar el contenido */
        border: 1px solid #91cfff;
        border-radius: 12px;
    }

    .list-group-item strong {
        color: #000;
        font-weight: bold;
    }

    /* Estilos navbar */
    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100vw;
        z-index: 1030;
        padding: 0.5rem 1rem;
        box-sizing: border-box;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header .fw-bold {
        font-size: 1.5rem;
        color: white;
    }

    .header .nav-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .header .nav-buttons a {
        color: white !important;
        font-weight: 600;
        text-decoration: none;
    }

    .header .nav-buttons a:hover {
        text-decoration: underline;
    }

    .custom-card-header {
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.75rem;
        margin-bottom: 0.5rem; /* menos margen para pegar más al contenido */
        font-size: 1.5rem;
        color: #000;
        font-weight: 700;
        text-align: center;
    }

    .btn-regresar {
        font-size: 0.9rem;
        padding: 0.4rem 1.2rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        display: block;
        margin: 2rem auto 0 auto;
        width: max-content;
    }

    .row.g-4 {
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .list-group-item {
        background-color: transparent;
        border: none;
        padding-left: 0;
        padding-right: 0;
        font-size: 1rem;
        color: #222;
    }

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

        html, body, #app {
            margin: 0 !important;
            padding: 0 !important;
        }

        .container, .container-fluid, .row, .col {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>
@endpush

@section('content')

<!-- Barra de navegación superior -->
<div class="header">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
        <span class="fw-bold">Clinitek</span>
    </div>

    <div class="nav-buttons">
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

@endsection


