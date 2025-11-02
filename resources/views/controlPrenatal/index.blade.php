@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
            <div class="card shadow-lg border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2">
                                <i class="bi bi-heart-pulse-fill"></i> Ginecología
                            </h2>
                            <p class="mb-0 opacity-75">Sistema de Control Prenatal</p>
                        </div>
                        <a href="{{ route('controles-prenatales.create') }}" class="btn btn-light btn-lg shadow">
                            <i class="bi bi-plus-circle"></i> Registro de Control Prenatal
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-clipboard-data" style="font-size: 3rem; color: #667eea;"></i>
                    </div>
                    <h3 class="fw-bold" style="color: #667eea;">{{ $totalControles }}</h3>
                    <p class="text-muted mb-0">Total de Controles</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-calendar-check" style="font-size: 3rem; color: #f093fb;"></i>
                    </div>
                    <h3 class="fw-bold" style="color: #f093fb;">{{ $controlesHoy }}</h3>
                    <p class="text-muted mb-0">Controles Hoy</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-clock-history" style="font-size: 3rem; color: #4facfe;"></i>
                    </div>
                    <h3 class="fw-bold" style="color: #4facfe;">{{ $proximasCitas->count() }}</h3>
                    <p class="text-muted mb-0">Próximas Citas</p>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge-fill text-warning"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('controles-prenatales.create') }}" class="btn btn-primary w-100 py-3 shadow-sm">
                                <i class="bi bi-plus-lg d-block mb-2" style="font-size: 1.5rem;"></i>
                                Nuevo Control
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('controles-prenatales.index') }}" class="btn btn-info w-100 py-3 shadow-sm text-white">
                                <i class="bi bi-list-ul d-block mb-2" style="font-size: 1.5rem;"></i>
                                Ver Todos
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('pacientes.index') }}" class="btn btn-success w-100 py-3 shadow-sm">
                                <i class="bi bi-people-fill d-block mb-2" style="font-size: 1.5rem;"></i>
                                Pacientes
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="/" class="btn btn-secondary w-100 py-3 shadow-sm">
                                <i class="bi bi-house-fill d-block mb-2" style="font-size: 1.5rem;"></i>
                                Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximas Citas -->
        @if($proximasCitas->count() > 0)
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar2-event text-primary"></i> Próximas Citas Programadas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Paciente</th>
                                    <th>Identidad</th>
                                    <th>Fecha de Cita</th>
                                    <th>Semanas de Gestación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proximasCitas as $cita)
                                <tr>
                                    <td>
                                        <i class="bi bi-person-circle text-primary"></i>
                                        {{ $cita->paciente->nombre_completo }}
                                    </td>
                                    <td>{{ $cita->paciente->numero_identidad }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $cita->fecha_proxima_cita->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>{{ $cita->semanas_gestacion }} semanas</td>
                                    <td>
                                        <a href="{{ route('controles-prenatales.show', $cita) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
</style>
@endpush
@endsection