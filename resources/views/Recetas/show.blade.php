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

        .receta-card {
            border: 1px solid #ced4da;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
        }
        td {
            white-space: normal !important;  /* Permite saltos de línea */
            word-break: break-word;            /* Rompe palabras largas para evitar desbordes */
            max-width: 400px;                  /* Limita el ancho máximo de la celda */
            vertical-align: top;               /* Alinea el texto arriba */
        }

    </style>

    <div class="custom-card">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

        <h2 class="mb-3">Historial de Recetas de {{ $paciente->nombre }} {{ $paciente->apellidos }}</h2>

        {{-- Filtro por fecha --}}
        <form method="GET" class="mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="fecha" class="form-label">Buscar por fecha:</label>
                    <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                    <a href="{{ route('recetas.show', $paciente->id) }}" class="btn btn-secondary btn-sm">Limpiar</a>
                </div>
            </div>
        </form>

        @forelse($recetas as $receta)
            @if(!request('fecha') || $receta->created_at->toDateString() === request('fecha'))
                <div class="receta-card">
                    <p><strong>Receta #:</strong> {{ $receta->id }}</p>
                    <p><strong>Fecha:</strong> {{ $receta->created_at->format('d/m/Y') }}</p>

                    @if($receta->medicamentos->isEmpty())
                        <p><em>No hay medicamentos registrados.</em></p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-2">
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
                                        <td>{{ $medicamento->pivot->indicaciones }}</td>
                                        <td>{{ $medicamento->pivot->dosis }}</td>
                                        <td>{{ $medicamento->pivot->detalles }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
        @empty
            <p>No hay recetas registradas para este paciente.</p>
        @endforelse

        <div class="text-center mt-4">
            <a href="{{ route('pacientes.index') }}" class="btn btn-success btn-sm px-4">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert-dismissible');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 300);
        }
    }, 3000); // Oculta el mensaje después de 4 segundos
</script>
@endpush
