@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Exámenes para Consulta #{{ $consulta_id }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($examenes->isEmpty())
        <p>No hay exámenes registrados para esta consulta.</p>
    @else
        <ul>
            @foreach($examenes as $examen)
                <li>{{ $examen->nombre }} - {{ $examen->detalle ?? '' }}</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('consultas.show', $consulta_id) }}" class="btn btn-primary">Volver a Consulta</a>
</div>
@endsection

