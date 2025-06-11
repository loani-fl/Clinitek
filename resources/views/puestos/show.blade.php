@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-primary rounded-3">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>Detalle del Puesto</h5>
        </div>

        <div class="card-body">
            <dl class="row">

                <dt class="col-sm-3 fw-semibold">Código:</dt>
                <dd class="col-sm-9">{{ $puesto->codigo }}</dd>

                <dt class="col-sm-3 fw-semibold">Nombre del Puesto:</dt>
                <dd class="col-sm-9">{{ $puesto->nombre }}</dd>

                <dt class="col-sm-3 fw-semibold">Área / Departamento:</dt>
                <dd class="col-sm-9">{{ $puesto->area }}</dd>

                <dt class="col-sm-3 fw-semibold">Sueldo:</dt>
                <dd class="col-sm-9">Lps. {{ number_format($puesto->sueldo, 2) }}</dd>


        
                <dt class="col-sm-3 fw-semibold">Función del Puesto:</dt>
                <dd class="col-sm-9">{{ $puesto->funcion }}</dd>

            </dl>

            <a href="{{ route('puestos.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Regresar
            </a>
        </div>
    </div>
</div>
@endsection


<style>
    body {
        background-color: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    dt {
        font-size: 0.9rem;
    }
    dd {
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .btn-sm {
        font-size: 0.875rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
    }
</style>

