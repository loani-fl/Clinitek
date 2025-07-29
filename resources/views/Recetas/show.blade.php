@extends('layouts.app')

@section('content')
<style>
    .custom-card {
        position: relative;
        background-color: #ffffffcc; /* Blanco con ligera transparencia */
        padding: 2.5rem 3rem;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid #d1d5db; /* Gris claro */
        overflow: hidden;
        max-width: 900px;
        margin: 3rem auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #2c3e50;
    }
    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 900px;
        height: 900px;
        background-image: url('{{ asset("images/barra.png") }}');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        opacity: 0.05;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }
    .custom-card > * {
        position: relative;
        z-index: 1;
    }

    h2 {
        color: #000000; /* negro */
        font-weight: 700;
        margin-bottom: 1.5rem;
        border-bottom: 3px solid #000000; /* línea negra */
        padding-bottom: 0.4rem;
        font-size: 2.2rem;
        letter-spacing: 0.05em;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.8rem 3rem;
        margin-bottom: 2.5rem;
        font-size: 1.1rem;
        color: #34495e;
    }

    .info-label {
        font-weight: 600;
        color: #5d6d7e;
        margin-bottom: 0.3rem;
        display: block;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.6rem;
        font-size: 1rem;
        color: #2d3a4a;
        box-shadow: 0 6px 15px rgb(0 0 0 / 0.06);
        border-radius: 12px;
        overflow: hidden;
        background-color: #fff;
        transition: box-shadow 0.3s ease;
    }
    table:hover {
        box-shadow: 0 10px 25px rgb(0 0 0 / 0.1);
    }
    thead tr {
        background-color: #c8dafc;
        color: #21325e;
        font-weight: 600;
    }
    th, td {
        padding: 1rem 1.3rem;
        text-align: left;
        vertical-align: middle;
    }
    tbody tr {
        border-radius: 12px;
        box-shadow: 0 1px 4px rgb(0 0 0 / 0.08);
        transition: background-color 0.25s ease;
    }
    tbody tr:hover {
        background-color: #e6f0ff;
    }
    tbody tr td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }
    tbody tr td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .btn-back {
        display: block;
        width: max-content;
        margin: 3rem auto 0;
        background-color: #28a745; /* verde bootstrap */
        color: #fff;
        padding: 0.8rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 6px 18px rgba(40, 167, 69, 0.6);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }
    .btn-back:hover {
        background-color: #218838;
        box-shadow: 0 8px 25px rgba(33, 136, 56, 0.8);
        color: #f0f0f0;
    }
</style>

<div class="custom-card">
    <h2>Receta Médica #{{ $receta->id }}</h2>

    <div class="info-grid">
        <div>
            <span class="info-label">Paciente</span>
            {{ $receta->consulta->paciente->nombre }} {{ $receta->consulta->paciente->apellidos }}
        </div>
        <div>
            <span class="info-label">Edad</span>
            @php
                $edad = $receta->consulta->paciente->fecha_nacimiento
                    ? \Carbon\Carbon::parse($receta->consulta->paciente->fecha_nacimiento)->age
                    : 'N/A';
            @endphp
            {{ $edad }} años
        </div>
        <div>
            <span class="info-label">Fecha</span>
            {{ $receta->created_at->format('d/m/Y') }}
        </div>
        <div>
            <span class="info-label">Médico</span>
            Dr. {{ $receta->consulta->medico->nombre ?? 'No disponible' }} {{ $receta->consulta->medico->apellidos ?? '' }}
        </div>
        <div>
            <span class="info-label">Especialidad</span>
            {{ $receta->consulta->medico->especialidad ?? 'No especificada' }}
        </div>
    </div>

    @if($receta->medicamentos->isEmpty())
        <p><em>No hay medicamentos registrados.</em></p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Medicamento</th>
                    <th>Indicaciones</th>
                    <th>Dosis</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receta->medicamentos as $med)
                    <tr>
                        <td>{{ $med->nombre }}</td>
                        <td>{{ $med->pivot->indicaciones }}</td>
                        <td>{{ $med->pivot->dosis }}</td>
                        <td>{{ $med->pivot->detalles ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('consultas.show', $receta->consulta->id) }}" class="btn-back">← Regresar </a>
</div>
@endsection
