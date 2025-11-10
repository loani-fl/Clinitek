@extends('layouts.app')

@section('title', 'Detalles del Control Prenatal')

@section('content')
    <style>
        .custom-card::before {
            content: "";
            position: absolute;
            top: 50%; left: 50%;
            width: 800px; height: 800px;
            background-image: url('/images/logo2.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.12;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }
        .custom-card {
            max-width: 1000px;
            background-color: #fff;
            border: 1px solid #91cfff;
            border-radius: 12px;
            margin: 1.5rem auto;
            padding: 1.2rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .info-label { 
            font-weight: 700; 
            font-size: 0.95rem; 
            color: #003366; 
            display: block;
            margin-bottom: 4px;
        }
        .info-value { 
            font-size: 1rem; 
            color: #222; 
            padding: 8px 0;
        }
        .info-value-textarea { 
            font-size: 1rem; 
            color: #222; 
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            min-height: 80px;
            white-space: pre-wrap;
        }
        .info-block { 
            padding: 6px 0;
            margin-bottom: 8px;
        }
        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: #0b5ed7;
            border-bottom: 2px solid #0b5ed7;
            padding-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title:first-of-type {
            margin-top: 0.5rem;
        }
        @media (max-width: 768px) {
            .info-value { font-size: 0.95rem; }
        }
    </style>

    <div class="container mt-3">
        <div class="card custom-card shadow-sm border rounded-4">
            <div class="card-header text-center py-3" style="background-color:#fff; border-bottom:4px solid #0d6efd;">
                <h3 class="mb-0 fw-bold text-dark">Detalles del control prenatal</h3>
            </div>

            <div class="card-body px-3 py-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Datos Personales de la Paciente --}}
                <div class="section-title">
                    <i class="fas fa-user-circle"></i> Datos personales de la paciente
                </div>
                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Nombre completo:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->nombre_completo }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Número de identidad:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->identidad ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Fecha de nacimiento:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->fecha_nacimiento->format('d/m/Y') }}</div>
                    </div>
                </div>
                
                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Edad:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->edad }} años</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Género:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->genero }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Tipo de sangre:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->tipo_sangre ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Teléfono:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->telefono }}</div>
                    </div>
                    <div class="col-md-8 info-block">
                        <span class="info-label">Correo electrónico:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->correo ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="row gy-2">
                    <div class="col-md-12 info-block">
                        <span class="info-label">Dirección:</span>
                        <div class="info-value-textarea">{{ $controlPrenatal->paciente->direccion }}</div>
                    </div>
                </div>

                {{-- Datos Obstétricos --}}
                <div class="section-title">
                    <i class="fas fa-baby"></i> Datos obstétricos
                </div>
                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Fecha última menstruación:</span>
                        <div class="info-value">{{ $controlPrenatal->fecha_ultima_menstruacion->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Fecha probable de parto:</span>
                        <div class="info-value">{{ $controlPrenatal->fecha_probable_parto->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Semanas de gestación:</span>
                        <div class="info-value">{{ $controlPrenatal->semanas_gestacion }} semanas</div>
                    </div>
                </div>
                
                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Número de partos:</span>
                        <div class="info-value">{{ $controlPrenatal->numero_partos }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Número de abortos:</span>
                        <div class="info-value">{{ $controlPrenatal->numero_abortos }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Tipo de partos anteriores:</span>
                        <div class="info-value">{{ $controlPrenatal->tipo_partos_anteriores ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="row gy-2">
                    <div class="col-md-12 info-block">
                        <span class="info-label">Complicaciones anteriores:</span>
                        <div class="info-value-textarea">{{ $controlPrenatal->complicaciones_previas ?? 'N/A' }}</div>
                    </div>
                </div>

                {{-- Datos del Control Prenatal --}}
                <div class="section-title">
                    <i class="fas fa-heartbeat"></i> Datos del control prenatal
                </div>
                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Fecha del control:</span>
                        <div class="info-value">{{ $controlPrenatal->fecha_control->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Presión arterial:</span>
                        <div class="info-value">{{ $controlPrenatal->presion_arterial }} mmHg</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Temperatura:</span>
                        <div class="info-value">{{ $controlPrenatal->temperatura }} °C</div>
                    </div>
                </div>

                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Peso actual:</span>
                        <div class="info-value">{{ $controlPrenatal->peso_actual }} kg</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Frecuencia cardíaca materna:</span>
                        <div class="info-value">{{ $controlPrenatal->frecuencia_cardiaca_materna }} BPM</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Altura uterina:</span>
                        <div class="info-value">{{ $controlPrenatal->altura_uterina ?? 'N/A' }} {{ $controlPrenatal->altura_uterina ? 'cm' : '' }}</div>
                    </div>
                </div>

                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Edema:</span>
                        <div class="info-value text-capitalize">{{ $controlPrenatal->edema }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Latidos fetales:</span>
                        <div class="info-value">{{ $controlPrenatal->latidos_fetales ?? 'N/A' }} {{ $controlPrenatal->latidos_fetales ? 'BPM' : '' }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Movimientos fetales:</span>
                        <div class="info-value">{{ $controlPrenatal->movimientos_fetales ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Presentación fetal:</span>
                        <div class="info-value text-capitalize">{{ $controlPrenatal->presentacion_fetal ? str_replace('_', ' ', $controlPrenatal->presentacion_fetal) : 'N/A' }}</div>
                    </div>
                </div>

                <div class="row gy-2">
                    <div class="col-md-12 info-block">
                        <span class="info-label">Resultados de exámenes:</span>
                        <div class="info-value-textarea">{{ $controlPrenatal->resultados_examenes ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="row gy-2">
                    <div class="col-md-12 info-block">
                        <span class="info-label">Observaciones:</span>
                        <div class="info-value-textarea">{{ $controlPrenatal->observaciones ?? 'N/A' }}</div>
                    </div>
                </div>

                {{-- Tratamientos y Recomendaciones --}}
                <div class="section-title">
                    <i class="fas fa-pills"></i> Tratamientos y recomendaciones
                </div>
                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Suplementos:</span>
                        <div class="info-value">{{ $controlPrenatal->suplementos ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Vacunas aplicadas:</span>
                        <div class="info-value">{{ $controlPrenatal->vacunas_aplicadas ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Indicaciones médicas:</span>
                        <div class="info-value">{{ $controlPrenatal->indicaciones_medicas ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="row gy-2">
                    <div class="col-md-6 info-block">
                        <span class="info-label">Fecha de próxima cita:</span>
                        <div class="info-value">{{ $controlPrenatal->fecha_proxima_cita ? $controlPrenatal->fecha_proxima_cita->format('d/m/Y') : 'N/A' }}</div>
                    </div>
                    <div class="col-md-6 info-block">
                        <span class="info-label">Registrado:</span>
                        <div class="info-value">{{ $controlPrenatal->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="text-center pt-4">
                    <a href="{{ route('controles-prenatales.index') }}" class="btn btn-success px-4">
                        <i class="bi bi-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection