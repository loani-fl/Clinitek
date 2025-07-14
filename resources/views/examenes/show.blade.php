@extends('layouts.app')

@section('content')
<style>
    .custom-card {
        max-width: 800px;
        margin: 2rem auto;
        padding: 1.5rem;
        background-color: #fff;
        border: 1px solid #91cfff;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #0d6efd;
        border-bottom: 3px solid #0d6efd;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .label {
        font-weight: bold;
    }
</style>

<div class="card custom-card">
    <h2 class="section-title">Orden de Examen</h2>

    @php
        $consulta = \App\Models\Consulta::with('paciente', 'medico')->find($consulta_id);
        $paciente = $consulta->paciente;
        $medico = $consulta->medico;
    @endphp

    <p><span class="label">ID de consulta:</span> {{ $consulta->id }}</p>
    <p><span class="label">Nombre del paciente:</span> {{ $paciente->nombre }} {{ $paciente->apellidos }}</p>
    <p><span class="label">Identidad:</span> {{ $paciente->identidad }}</p>
    <p><span class="label">Edad:</span> {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años</p>
    <p><span class="label">Fecha de consulta:</span> {{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</p>
    <p><span class="label">Médico:</span> {{ $medico->nombre ?? 'No asignado' }}</p>

    <hr>

    <h5><i class="bi bi-file-earmark-medical"></i> <strong>Exámenes Solicitados:</strong></h5>
    <ul>
        @foreach($examenes as $examen)
            <li>{{ $examen->nombre }}</li>
        @endforeach
    </ul>

    <div class="text-center mt-4">
        <a href="{{ route('consultas.show', $consulta_id) }}" class="btn btn-success">
            <i class="bi bi-arrow-left"></i> Volver a Consulta
        </a>
    </div>
</div>
@endsection
