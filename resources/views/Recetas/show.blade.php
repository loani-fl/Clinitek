@extends('layouts.app')

@section('content')
<style>
    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        border-color: #91cfff;
        margin: 2rem auto;
        padding: 1rem;
        border: 1px solid #91cfff;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 1rem;
        border-bottom: 3px solid #0d6efd;
        padding-bottom: 0.25rem;
    }
    .receta-item {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background-color: #f8f9fa;
        box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }
</style>

<div class="dropdown">
    <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        ☰
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
        <li><a class="dropdown-item" href="{{ route('puestos.create') }}">Crear puesto</a></li>
        <li><a class="dropdown-item" href="{{ route('empleado.create') }}">Registrar empleado</a></li>
        <li><a class="dropdown-item" href="{{ route('medicos.create') }}">Registrar médico</a></li>
    </ul>
</div>

<div class="container mt-5">
    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 2rem;">Recetas médicas de {{ $paciente->nombre }} {{ $paciente->apellidos }}</h5>
            <a href="{{ route('pacientes.index') }}" class="btn btn-success btn-sm">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        <div class="card-body">
            <!-- Formulario para buscar por fecha -->
            <form method="GET" action="{{ route('recetas.show', $paciente->id) }}" class="row g-2 mb-4">
                <div class="col-auto">
                    <label for="fecha" class="form-label">Buscar por fecha:</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>

            @php
                $recetasQuery = $paciente->recetas()->with('consulta.medico')->orderBy('recetas.created_at', 'desc');
                if(request('fecha')) {
                    $recetasQuery->whereDate('recetas.created_at', request('fecha'));
                }
                $recetas = $recetasQuery->get();
            @endphp

            @if($recetas->isEmpty())
                <p class="text-muted">No hay recetas registradas para este paciente en esa fecha.</p>
            @else
                @foreach($recetas as $receta)
                    <div class="receta-item">
                        <p><strong>Fecha:</strong> {{ $receta->created_at->format('d/m/Y') }}</p>
                        <p><strong>Médico:</strong> {{ $receta->consulta->medico->nombre ?? 'No asignado' }}</p>
                        <p><strong>Especialidad:</strong> {{ $receta->consulta->medico->especialidad ?? 'No asignada' }}</p>
                        <hr>
                        <p><strong>Medicamentos y Prescripción:</strong></p>
                        <p style="white-space: pre-line;">{!! nl2br(e($receta->detalles ?? 'Sin detalles')) !!}</p>
                    </div>
                @endforeach
            @endif

            <div class="text-center mt-4">
                <a href="{{ route('pacientes.index') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-arrow-left"></i> Regresar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

