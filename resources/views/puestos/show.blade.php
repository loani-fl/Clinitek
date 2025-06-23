@extends('layouts.app')

@section('title', 'Detalle del Puesto')

@push('styles')
<style>
    /* Opcional: estilos para negrita en valores si quieres */
    .list-group-item strong {
        color: rgb(58, 60, 63);
    }
</style>
@endpush

@section('content')
<div class="w-100" style="background-color: #007BFF;">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
        <div class="d-flex gap-3 flex-wrap">
        <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
            <a href="{{ route('empleado.create') }}" class="text-decoration-none text-white fw-semibold">Registrar empleado</a>
        
        </div>
    </div>
</div>
<div class="container-fluid py-4 px-5 bg-light min-vh-120">
    <div class="card shadow rounded-4 border-0" style="background-color: #e3f2fd;">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #64b5f6;">
            <h4 class="mb-0">
                <i class="bi bi-briefcase-fill me-2"></i> Detalle del Puesto
            </h4>
           
        </div>

        <div class="card-body">
            <div class="row g-4">
                {{-- Columna izquierda --}}
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent"><strong>Código:</strong> {{ $puesto->codigo }}</li>
                        <li class="list-group-item bg-transparent"><strong>Nombre del Puesto:</strong> {{ $puesto->nombre }}</li>
                        <li class="list-group-item bg-transparent"><strong>Área / Departamento:</strong> {{ $puesto->area }}</li>
                    </ul>
                </div>

                {{-- Columna derecha --}}
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent"><strong>Sueldo:</strong> Lps. {{ number_format($puesto->sueldo, 2) }}</li>
                        <li class="list-group-item bg-transparent"><strong>Función del Puesto:</strong><br>
                            <span style="white-space: pre-line;">{{ $puesto->funcion }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
         <div class="d-flex justify-content-center">
    <a href="{{ route('puestos.index') }}" class="btn btn-success btn-sm px-4 shadow-sm d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
</div>

    </div>
</div>


@endsection