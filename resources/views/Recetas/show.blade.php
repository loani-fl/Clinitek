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
    </style>

    <div class="custom-card">
        <h2 class="mb-3">Receta #{{ $receta->id }}</h2>
        <p><strong>Paciente:</strong> {{ $receta->consulta->paciente->nombre ?? '' }} {{ $receta->consulta->paciente->apellidos ?? '' }}</p>
        <p><strong>Fecha:</strong> {{ $receta->created_at->format('d/m/Y') }}</p>

        <h3 class="section-title">Medicamentos y prescripciones</h3>

        @if($receta->medicamentos->isEmpty())
            <p>No hay medicamentos registrados en esta receta.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                    <tr>
                        <th>Medicamento</th>
                        <th>Indicaciones</th>
                        <th>Dosis</th>
                        <th>Detalles</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($receta->medicamentos as $medicamento)
                        <tr>
                            <td>{{ $medicamento->nombre }}</td>
                            <td>{{ $medicamento->pivot->indicacion }}</td>
                            <td>{{ $medicamento->pivot->dosis }}</td>
                            <td>{{ $medicamento->pivot->detalles }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('pacientes.index') }}" class="btn btn-success btn-sm px-4">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </div>
@endsection
