@extends('layouts.app')

@section('title', 'Listado de Puestos')

@section('content')
 <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #e8f4fc; /* fondo azul clarito */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            background-color: #007BFF;
        }

        .header .fw-bold {
            font-size: 1.75rem;
            color: #0d6efd;
        }

        .header .btn {
            color: black;
            border-width: 2px;
        }

        .contenedor-principal {
    flex-grow: 1;
    display: flex;
    justify-content: center;
    align-items: start;
    padding: 0 3rem; /* MÁS margen lateral */
    margin: 0;
    width: 100vw;
}

.custom-card {
    flex-grow: 1;
    background-color: #ffffff;  /* fondo blanco */
    border-color: #91cfff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    max-width: 1000px;
    width: 100%;
    padding: 1.5rem;
}


        .card-header {
            background-color: transparent !important;
            border: none;
        }

        .card-header h5 {
            color: #0d6efd;
            font-weight: bold;
        }

        .table-responsive {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0 1rem 1rem 1rem;
        }

        thead tr {
            background-color: #cce5ff; /* azul más suave */
            color: #003e7e;
        }

        tbody tr:hover {
            background-color: #e9f2ff;
        }

        table tbody tr {
            height: 50px;
        }

        label {
            font-size: 0.85rem;
        }

        input, select, textarea {
            font-size: 0.85rem !important;
        }

        .btn-white-border {
            background-color: white !important;
            border-width: 2px;
            box-shadow: none !important;
        }

        .estado-activo i {
            color: #00c851 !important;
        }

        .estado-inactivo i {
            color: #ff3547 !important;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100vw;
            height: 50px;
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
            z-index: 999;
            border-top: 1px solid #dee2e6;
        }

        .custom-card::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 800px; /* tamaño más pequeño */
            height: 800px; /* tamaño más pequeño */
            background-image: url('/images/logo2.jpg');
            background-size: contain;  /* ajusta sin recortar */
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.15;  /* transparencia baja para que no moleste */
            transform: translate(-50%, -50%);
            pointer-events: none; /* para que no interfiera con clicks */
            z-index: 0;
        }
    </style>

{{-- Barra superior fija --}}
<div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050;">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
            <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>
</div>


{{-- Contenedor principal --}}
<div class="card custom-card shadow-sm border rounded-4 mx-auto w-100" style="margin-top: 70px;">
    {{-- Encabezado con botón Inicio a la derecha --}}
    
    <div class="card-header position-relative py-2 d-flex justify-content-center align-items-center" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
        <h5 class="mb-0 fw-bold text-dark text-center" style="font-size: 2.25rem;">Listado de Puestos</h5>
        <a href="{{ route('inicio') }}" class="btn btn-light position-absolute end-0 top-50 translate-middle-y me-2">
            <i class="bi bi-house-door"></i> Inicio
        </a>
        

    </div>

    <div class="p-3">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 p-3 mb-4 small">
                {{ session('success') }}
            </div>
        @endif

        @if($puestos->isEmpty())
            <div class="alert alert-info shadow-sm" role="alert">
                No hay puestos registrados aún.
            </div>
        @else
           <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($puestos as $index => $puesto)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $puesto->codigo }}</td>
                                <td>{{ $puesto->nombre }}</td>
                                <td>{{ $puesto->area }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('puestos.show', $puesto->id) }}" class="btn btn-white-border btn-outline-info btn-sm" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('puestos.edit', $puesto) }}" class="btn btn-white-border btn-outline-warning btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
