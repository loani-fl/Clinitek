@extends('layouts.app')

@section('title', 'Detalle de Ultrasonido')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Orden de Ultrasonido #{{ $ultrasonido->id }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Paciente:</strong> {{ $ultrasonido->paciente->nombre }} {{ $ultrasonido->paciente->apellidos }}</p>
            <p><strong>Fecha:</strong> {{ $ultrasonido->fecha }}</p>
            <p><strong>Total:</strong> L. {{ number_format($ultrasonido->total, 2) }}</p>

            <hr>

            <h5>Exámenes realizados:</h5>
            <ul>
                @if($ultrasonido->higado)     <li>Hígado</li> @endif
                @if($ultrasonido->vesicula)   <li>Vesícula</li> @endif
                @if($ultrasonido->bazo)       <li>Bazo</li> @endif
                @if($ultrasonido->vejiga)     <li>Vejiga</li> @endif
                @if($ultrasonido->ovarico)    <li>Ovarios</li> @endif
                @if($ultrasonido->utero)      <li>Útero</li> @endif
                @if($ultrasonido->tiroides)   <li>Tiroides</li> @endif
            </ul>

            <a href="{{ route('ultrasonidos.index') }}" class="btn btn-secondary mt-3">Regresar al listado</a>
        </div>
    </div>
</div>
@endsection
