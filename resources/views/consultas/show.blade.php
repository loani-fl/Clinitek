@extends('layouts.app')

@section('content')
<!-- Header azul fijo -->
<div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050;">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
        <div class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</div>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('puestos.create') }}" class="text-decoration-none text-white fw-semibold">Crear puesto</a>
            <a href="{{ route('medicos.create') }}" class="text-decoration-none text-white fw-semibold">Registrar médico</a>
        </div>
    </div>
</div>

<!-- Fondo con imagen y compensación de header -->
<div class="w-100 d-flex justify-content-center align-items-start" style="background-image: url('/images/fondo_claro2.jpg'); background-size: cover; background-position: center; padding-top: 100px; padding-bottom: 40px;">
    <div class="card custom-card shadow-sm border rounded-4 w-100 mx-3" style="max-width: 900px;">

        <!-- Título personalizado -->
        <div class="card-header text-center py-3" style="background-color: transparent; border-bottom: 3px solid #007BFF;">
            <h2 class="mb-0 fw-bold text-black" style="font-size: 2.25rem;">Detalle de consulta médica</h2>
        </div>

        <div class="card-body px-4 py-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Paciente:</strong><br>
                    {{ $consulta->paciente->nombre }} {{ $consulta->paciente->apellidos }}
                </div>
                <div class="col-md-6">
                    <strong>Género:</strong><br>
                    {{ $consulta->sexo }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Fecha:</strong><br>
                    {{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}
                </div>
                <div class="col-md-6">
                    <strong>Hora:</strong><br>
                    @if($consulta->hora)
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $consulta->hora)->format('h:i A') }}
                    @else
                        <span class="badge bg-success">Inmediata</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Médico:</strong><br>
                    {{ $consulta->medico->nombre }} {{ $consulta->medico->apellidos }}
                </div>
                <div class="col-md-6">
                    <strong>Especialidad:</strong><br>
                    {{ $consulta->especialidad }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Motivo:</strong>
                    <p>{{ $consulta->motivo }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Síntomas:</strong>
                    <p>{{ $consulta->sintomas }}</p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('consultas.index') }}" class="btn btn-success">← Regresar</a>
            </div>
        </div>
    </div>
</div>

<!-- Estilos para la imagen de fondo dentro del card -->
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
        background-color: #fff;
        border-color: #91cfff;
        position: relative;
        overflow: hidden;
    }
</style>
@endsection
