@extends('layouts.app')

@section('content')
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
            max-width: 1000px;
            background-color: #fff;
            border-color: #91cfff;
            position: relative;
            overflow: hidden;
            margin: 2rem auto;
            padding: 1.5rem 2rem;
            border: 1px solid #91cfff;
            border-radius: 12px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 1rem;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 0.25rem;
        }

        p {
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .label {
            font-weight: 700;
            color: #212529;
        }
    </style>

    <!-- Barra fija -->
    <div class="w-100 fixed-top" style="background-color: #007BFF; z-index: 1050; height: 56px;">
        <div class="d-flex justify-content-between align-items-center px-3" style="height: 56px;">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/barra.png') }}" alt="Logo Clinitek" style="height: 40px; width: auto; margin-right: 6px;">
                <span class="fw-bold text-white" style="font-size: 1.5rem;">Clinitek</span>
            </div>

            <!-- Menú desplegable funcional -->
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" id="menuMedico" data-bs-toggle="dropdown" aria-expanded="false">
                    ☰
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuMedico">
                    <li><a class="dropdown-item" href="{{ route('puestos.create') }}">Crear puesto</a></li>
                    <li><a class="dropdown-item" href="{{ route('empleado.create') }}">Registrar empleado</a></li>
                    <li><a class="dropdown-item" href="{{ route('medicos.create') }}">Registrar médico</a></li>
                    <li><a class="dropdown-item" href="{{ route('consultas.create') }}">Registrar consulta</a></li>
                    <li><a class="dropdown-item" href="{{ route('pacientes.create') }}">Registrar paciente</a></li>
                </ul>
            </div>

        </div>
    </div>

    <div class="container mt-5 pt-3" style="max-width: 1000px;">
        <div class="card custom-card shadow-sm border rounded-4 mx-auto w-100 mt-4">
            <div class="card-header text-center py-2" style="background-color: #fff; border-bottom: 4px solid #0d6efd;">
                <h5 class="mb-0 fw-bold text-dark" style="font-size: 2.25rem;">Detalles del diagnóstico</h5>


            </div>
            <!-- Menú desplegable Talleres de diagnóstico -->
            <div class="dropdown mt-3">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="talleresDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 1rem;">
                    Más Opciones
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="talleresDropdown" style="min-width: 200px;">
                    <li>
                        <a href="{{ route('examenes.show', $diagnostico->id) }}">Ver orden de examen</a>

                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('recetas.show', $diagnostico->paciente->id) }}">
                            Recetas médicas
                        </a>
                    </li>
                </ul>
            </div>



        @if (session('success'))
                <div id="alert-success" class="alert alert-success shadow-sm mt-3" role="alert">
                    {{ session('success') }}



                </div>

                <script>
                    // Ocultar el mensaje después de 4 segundos (4000 milisegundos)
                    setTimeout(() => {
                        const alert = document.getElementById('alert-success');
                        if (alert) {
                            alert.classList.add('fade');
                            alert.style.transition = 'opacity 0.5s ease';
                            alert.style.opacity = '0';
                            setTimeout(() => alert.remove(), 500); // Elimina del DOM después de la animación
                        }
                    }, 4000);
                </script>
            @endif



            <div class="card-body px-4 py-3">

            <div class="section-title">Información del Paciente</div>
<div class="row">
    <div class="col-md-4 mb-2">
        <p><span class="label">Nombre Completo:</span><br> {{ $diagnostico->paciente->nombre }} {{ $diagnostico->paciente->apellidos }}</p>
    </div>
    <div class="col-md-4 mb-2">
        <p><span class="label">Edad:</span><br> {{ \Carbon\Carbon::parse($diagnostico->paciente->fecha_nacimiento)->age }} años</p>
    </div>
    <div class="col-md-4 mb-2">
        <p><span class="label">Identidad:</span><br> {{ $diagnostico->paciente->identidad }}</p>
    </div>
    <div class="col-md-4 mb-2">
        <p><span class="label">Teléfono:</span><br> {{ $diagnostico->paciente->telefono ?? 'No especificado' }}</p>
    </div>
    <div class="col-md-4 mb-2">
        <p><span class="label">Género:</span><br> {{ $diagnostico->paciente->genero ?? 'No especificado' }}</p>
    </div>
</div>


                <div class="section-title mt-4">Detalles del Diagnóstico</div>
                <p><span class="label">Título:</span><br>{{ $diagnostico->titulo }}</p>
                <p><span class="label">Descripción:</span><br>{!! nl2br(e($diagnostico->descripcion)) !!}</p>
                <p><span class="label">Tratamiento:</span><br>{!! nl2br(e($diagnostico->tratamiento)) !!}</p>
                <p><span class="label">Observaciones:</span><br>{!! nl2br(e($diagnostico->observaciones)) !!}</p>

                <hr class="my-4">

                @php use Illuminate\Support\Str; @endphp

<hr class="my-4">
<h6 class="border-bottom pb-1 mb-3" style="font-size: 0.95rem;">Diagnósticos anteriores del paciente</h6>

@if ($diagnosticosAnteriores->isEmpty())
    <p class="text-muted" style="font-size: 0.85rem;">No hay diagnósticos anteriores registrados para este paciente.</p>
@else
    <div class="accordion" id="diagnosticosAnterioresAccordion">
        @foreach ($diagnosticosAnteriores as $d)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-{{ $d->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $d->id }}" aria-expanded="false" aria-controls="collapse-{{ $d->id }}" style="font-size: 0.85rem;">
                        {{ $d->created_at->format('d/m/Y') }} — {{ Str::limit($d->titulo, 50) }}
                    </button>
                </h2>
                <div id="collapse-{{ $d->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $d->id }}" data-bs-parent="#diagnosticosAnterioresAccordion">
                    <div class="accordion-body" style="font-size: 0.85rem;">
                        <p><strong>Título:</strong><br>{{ $d->titulo }}</p>
                        <p><strong>Descripción:</strong><br>{!! nl2br(e($d->descripcion)) !!}</p>
                        <p><strong>Tratamiento:</strong><br>{!! nl2br(e($d->tratamiento)) !!}</p>
                        <p><strong>Observaciones:</strong><br>{!! nl2br(e($d->observaciones)) !!}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif





                <div class="text-center pt-4">
                    <a href="{{ route('consultas.show', $diagnostico->id) }}" class="btn btn-success btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                    <a href="{{ route('diagnosticos.edit', $diagnostico->id) }}" class="btn btn-primary btn-sm px-4 shadow-sm d-inline-flex align-items-center gap-2 ms-2" style="font-size: 0.85rem;">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection
