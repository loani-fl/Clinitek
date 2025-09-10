@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <div class="alert alert-warning shadow-sm rounded-4">
        <h4 class="mb-3">Este paciente no tiene recetas registradas.</h4>
        <a href="{{ route('consultas.index') }}" class="btn btn-success">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>
</div>
@endsection
