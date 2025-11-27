@extends('layouts.app')

@section('title', 'Detalle del Puesto')

@section('content')
<style>
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
        margin: 2rem auto;
        padding: 1rem;
        border: 1px solid #91cfff;
        border-radius: 12px;
    }

    .funcion-texto {
        display: block;
        width: 100%;
        min-height: 5rem;
        padding: 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #212529;
         background-color: #fff; /* Blanco */
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        white-space: pre-wrap;
    }

    .custom-card-header {
        background-color: #fff;
        border-bottom: 4px solid #0d6efd;
        text-align: center;
        font-size: 2rem;
        font-weight: bold;
        color: #000;
        padding: 0.75rem;
    }

    .header {
        background-color: #007BFF;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100vw;
        z-index: 1030;
        padding: 0.5rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 56px;
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

    .row-info > div {
        margin-bottom: 1rem;
    }

    .btn-regresar {
        font-size: 0.85rem;
        padding: 0.4rem 1.2rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
</style>

<!-- Barra de navegación fija -->
<!--  <div class="header">
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
        <span class="fw-bold">Clinitek</span>
    </div>

    <div class="nav-buttons">
        <a href="{{ route('puestos.create') }}">Crear puesto</a>
        <a href="{{ route('empleados.create') }}">Registrar empleado</a>
        <a href="{{ route('medicos.create') }}">Registrar médico</a>
    </div>
</div>
-->

<!-- Contenedor principal -->
<div class="container mt-5 pt-4" style="max-width: 1000px;">
    <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
        <div class="custom-card-header">
            Detalle del Puesto
        </div>

        <div class="card-body px-4 py-3">
            <div class="row row-info gy-3">
                <div class="col-md-3">
                    <strong>Código:</strong><br>{{ $puesto->codigo }}
                </div>
                <div class="col-md-3">
                    <strong>Nombre del Puesto:</strong><br>{{ $puesto->nombre }}
                </div>
                <div class="col-md-3">
                    <strong>Área / Departamento:</strong><br>{{ $puesto->area }}
                </div>
                <div class="col-md-3">
                    <strong>Sueldo:</strong><br>Lps. {{ number_format($puesto->sueldo, 2) }}
                </div>
            </div>

            <div class="row mt-4 justify-content-center">
                <div class="col-md-12">
                    <strong>Función del Puesto:</strong><br>
                    <div class="funcion-texto">{{ $puesto->funcion }}</div>
                </div>
            </div>
        </div>

        <!-- Botón Regresar -->
        <div class="text-center pb-4">
            <a href="{{ route('puestos.index') }}"
               class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </div>
</div>
@endsection

