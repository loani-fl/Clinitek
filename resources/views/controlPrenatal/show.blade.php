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
            font-size: 1rem; 
            color: #003366; 
            display: block; 
        }
        .info-value { 
            font-size: 1.05rem; 
            color: #222; 
            margin-top: 4px; 
        }
        .info-block { 
            padding: 6px 8px; 
        }
        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 0.6rem;
            color: #0b5ed7;
            border-bottom: 2px solid #0b5ed7;
            padding-bottom: 4px;
        }
        @media (max-width: 768px) {
            .info-value { font-size: 1rem; }
        }
    </style>

    <div class="container mt-3">
        <div class="card custom-card shadow-sm border rounded-4">
            <div class="card-header text-center py-3" style="background-color:#fff; border-bottom:4px solid #0d6efd;">
                <h3 class="mb-0 fw-bold text-dark">📋 Detalles del Control Prenatal</h3>
            </div>

            <div class="card-body px-3 py-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Datos Personales de la Paciente --}}
                <div class="section-title">🧍‍♀️ Datos Personales de la Paciente</div>
                <div class="row gy-2">
                    <div class="col-md-6 info-block">
                        <span class="info-label">Nombre Completo:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->nombre_completo }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Edad:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->edad }} años</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Estado Civil:</span>
                        <div class="info-value text-capitalize">{{ str_replace('_', ' ', $controlPrenatal->paciente->estado_civil) }}</div>
                    </div>
                    <div class="col-md-6 info-block">
                        <span class="info-label">Número de Identidad:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->numero_identidad }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Fecha de Nacimiento:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->fecha_nacimiento->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Teléfono:</span>
                        <div class="info-value">{{ $controlPrenatal->paciente->telefono }}</div>
                    </div>
                    <div class="col-md-12 info-block">
                        <span class="info-label">Dirección:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->paciente->direccion }}</textarea>
                    </div>
                </div>

                {{-- Datos Obstétricos --}}
                <div class="section-title">🩺 Datos Obstétricos</div>
                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">FUM (Fecha Última Menstruación):</span>
                        <div class="info-value">{{ $controlPrenatal->fecha_ultima_menstruacion->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">FPP (Fecha Probable de Parto):</span>
                        <div class="info-value">{{ $controlPrenatal->fecha_probable_parto->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Semanas de Gestación:</span>
                        <div class="info-value">{{ $controlPrenatal->semanas_gestacion }} semanas</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Gestaciones (G):</span>
                        <div class="info-value">{{ $controlPrenatal->numero_gestaciones }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Partos (P):</span>
                        <div class="info-value">{{ $controlPrenatal->numero_partos }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Abortos (A):</span>
                        <div class="info-value">{{ $controlPrenatal->numero_abortos }}</div>
                    </div>
                    <div class="col-md-3 info-block">
                        <span class="info-label">Hijos Vivos:</span>
                        <div class="info-value">{{ $controlPrenatal->numero_hijos_vivos }}</div>
                    </div>
                    @if($controlPrenatal->tipo_partos_anteriores || $controlPrenatal->complicaciones_previas)
                        @if($controlPrenatal->tipo_partos_anteriores)
                        <div class="col-md-6 info-block">
                            <span class="info-label">Tipo de Partos Anteriores:</span>
                            <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->tipo_partos_anteriores }}</textarea>
                        </div>
                        @endif
                        @if($controlPrenatal->complicaciones_previas)
                        <div class="col-md-6 info-block">
                            <span class="info-label">Complicaciones Previas:</span>
                            <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->complicaciones_previas }}</textarea>
                        </div>
                        @endif
                        @if($controlPrenatal->tipo_partos_anteriores && !$controlPrenatal->complicaciones_previas)
                        <div class="col-md-6"></div>
                        @endif
                    @endif
                </div>

                {{-- Antecedentes Médicos --}}
                <div class="section-title">⚕️ Antecedentes Médicos</div>
                @if($controlPrenatal->enfermedades_cronicas || $controlPrenatal->alergias || $controlPrenatal->cirugias_previas || $controlPrenatal->medicamentos_actuales || $controlPrenatal->habitos || $controlPrenatal->antecedentes_familiares)
                <div class="row gy-2">
                    @if($controlPrenatal->enfermedades_cronicas)
                    <div class="col-md-6 info-block">
                        <span class="info-label">Enfermedades Crónicas:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->enfermedades_cronicas }}</textarea>
                    </div>
                    @endif
                    @if($controlPrenatal->alergias)
                    <div class="col-md-6 info-block">
                        <span class="info-label">Alergias:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->alergias }}</textarea>
                    </div>
                    @endif
                    @if($controlPrenatal->cirugias_previas)
                    <div class="col-md-6 info-block">
                        <span class="info-label">Cirugías Previas:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->cirugias_previas }}</textarea>
                    </div>
                    @endif
                    @if($controlPrenatal->medicamentos_actuales)
                    <div class="col-md-6 info-block">
                        <span class="info-label">Medicamentos Actuales:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->medicamentos_actuales }}</textarea>
                    </div>
                    @endif
                    @if($controlPrenatal->habitos)
                    <div class="col-md-6 info-block">
                        <span class="info-label">Hábitos:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->habitos }}</textarea>
                    </div>
                    @endif
                    @if($controlPrenatal->antecedentes_familiares)
                    <div class="col-md-6 info-block">
                        <span class="info-label">Antecedentes Familiares:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->antecedentes_familiares }}</textarea>
                    </div>
                    @endif
                </div>
                @else
                <div class="alert alert-info text-center mt-2">
                    Sin antecedentes médicos registrados
                </div>
                @endif

                {{-- Datos del Control Prenatal --}}
                <div class="section-title">📋 Datos del Control Prenatal</div>
                <div class="row gy-2">
                    <div class="col-md-4 info-block">
                        <span class="info-label">Fecha del Control:</span>
                        <div class="info-value">{{ $controlPrenatal->fecha_control->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Presión Arterial:</span>
                        <div class="info-value">{{ $controlPrenatal->presion_arterial }} mmHg</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Temperatura:</span>
                        <div class="info-value">{{ $controlPrenatal->temperatura }} °C</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Frecuencia Cardíaca Materna:</span>
                        <div class="info-value">{{ $controlPrenatal->frecuencia_cardiaca_materna }} BPM</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Peso Actual:</span>
                        <div class="info-value">{{ $controlPrenatal->peso_actual }} kg</div>
                    </div>
                    <div class="col-md-4 info-block">
                        <span class="info-label">Edema:</span>
                        <div class="info-value text-capitalize">{{ $controlPrenatal->edema }}</div>
                    </div>
                    @if($controlPrenatal->altura_uterina)
                    <div class="col-md-4 info-block">
                        <span class="info-label">Altura Uterina:</span>
                        <div class="info-value">{{ $controlPrenatal->altura_uterina }} cm</div>
                    </div>
                    @endif
                    @if($controlPrenatal->latidos_fetales)
                    <div class="col-md-4 info-block">
                        <span class="info-label">Latidos Fetales:</span>
                        <div class="info-value">{{ $controlPrenatal->latidos_fetales }} BPM</div>
                    </div>
                    @endif
                    @if($controlPrenatal->movimientos_fetales)
                    <div class="col-md-4 info-block">
                        <span class="info-label">Movimientos Fetales:</span>
                        <div class="info-value">{{ $controlPrenatal->movimientos_fetales }}</div>
                    </div>
                    @endif
                    @if($controlPrenatal->presentacion_fetal)
                    <div class="col-md-4 info-block">
                        <span class="info-label">Presentación Fetal:</span>
                        <div class="info-value text-capitalize">{{ str_replace('_', ' ', $controlPrenatal->presentacion_fetal) }}</div>
                    </div>
                    @endif
                    @if($controlPrenatal->fecha_proxima_cita)
                    <div class="col-md-4 info-block">
                        <span class="info-label">Próxima Cita:</span>
                        <div class="info-value">{{ $controlPrenatal->fecha_proxima_cita->format('d/m/Y') }}</div>
                    </div>
                    @endif
                    @if($controlPrenatal->resultados_examenes)
                    <div class="col-md-12 info-block">
                        <span class="info-label">Resultados de Exámenes:</span>
                        <textarea class="form-control" rows="3" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->resultados_examenes }}</textarea>
                    </div>
                    @endif
                    @if($controlPrenatal->observaciones)
                    <div class="col-md-12 info-block">
                        <span class="info-label">Observaciones:</span>
                        <textarea class="form-control" rows="3" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->observaciones }}</textarea>
                    </div>
                    @endif
                </div>

                {{-- Tratamientos y Recomendaciones --}}
                <div class="section-title">💉 Tratamientos y Recomendaciones</div>
                @if($controlPrenatal->suplementos || $controlPrenatal->vacunas_aplicadas || $controlPrenatal->indicaciones_medicas)
                <div class="row gy-2">
                    @if($controlPrenatal->suplementos)
                    <div class="col-md-4 info-block">
                        <span class="info-label">Suplementos:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->suplementos }}</textarea>
                    </div>
                    @endif
                    @if($controlPrenatal->vacunas_aplicadas)
                    <div class="col-md-4 info-block">
                        <span class="info-label">Vacunas Aplicadas:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->vacunas_aplicadas }}</textarea>
                    </div>
                    @endif
                    @if($controlPrenatal->indicaciones_medicas)
                    <div class="col-md-4 info-block">
                        <span class="info-label">Indicaciones Médicas:</span>
                        <textarea class="form-control" rows="2" readonly style="resize:none; background-color:#f8f9fa;">{{ $controlPrenatal->indicaciones_medicas }}</textarea>
                    </div>
                    @endif
                </div>
                @else
                <div class="alert alert-info text-center mt-2">
                    Sin tratamientos o recomendaciones registrados
                </div>
                @endif

                {{-- Información de registro --}}
                <div class="section-title">📝 Información de Registro</div>
                <div class="row gy-2">
                    <div class="col-md-6 info-block">
                        <span class="info-label">Registrado:</span>
                        <div class="info-value">{{ $controlPrenatal->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                    <div class="col-md-6 info-block">
                        <span class="info-label">Última actualización:</span>
                        <div class="info-value">{{ $controlPrenatal->updated_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="text-center pt-4">
                    <form action="{{ route('controles-prenatales.destroy', $controlPrenatal) }}" method="POST" 
                          onsubmit="return confirm('¿Está seguro de eliminar este control prenatal?');" 
                          style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm px-4 shadow-sm">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                    <a href="{{ route('controles-prenatales.edit', $controlPrenatal) }}" class="btn btn-warning btn-sm px-4 shadow-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('controles-prenatales.index') }}" class="btn btn-success btn-sm px-4 shadow-sm">
                        <i class="fas fa-list"></i> Ver Listado
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection