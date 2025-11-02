@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">📋 Detalles del Control Prenatal</h4>
                    <div>
                        <a href="{{ route('controles-prenatales.edit', $controlPrenatal) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('controles-prenatales.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Sección: Datos Personales -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">🧍‍♀️ Datos Personales de la Paciente</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Nombre Completo:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->paciente->nombre_completo }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <strong>Edad:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->paciente->edad }} años</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <strong>Número de Identidad:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->paciente->numero_identidad }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <strong>Fecha de Nacimiento:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->paciente->fecha_nacimiento->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong>Teléfono:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->paciente->telefono }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong>Estado Civil:</strong>
                                    <p class="mb-0 text-capitalize">{{ str_replace('_', ' ', $controlPrenatal->paciente->estado_civil) }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <strong>Dirección:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->paciente->direccion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección: Datos Obstétricos -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">🩺 Datos Obstétricos</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <strong>FUM:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->fecha_ultima_menstruacion->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong>FPP:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->fecha_probable_parto->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <strong>Semanas de Gestación:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->semanas_gestacion }} semanas</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <strong>Gestaciones (G):</strong>
                                    <p class="mb-0">{{ $controlPrenatal->numero_gestaciones }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <strong>Partos (P):</strong>
                                    <p class="mb-0">{{ $controlPrenatal->numero_partos }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <strong>Abortos (A):</strong>
                                    <p class="mb-0">{{ $controlPrenatal->numero_abortos }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <strong>Hijos Vivos:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->numero_hijos_vivos }}</p>
                                </div>
                            </div>
                            @if($controlPrenatal->tipo_partos_anteriores)
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <strong>Tipo de Partos Anteriores:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->tipo_partos_anteriores }}</p>
                                </div>
                            </div>
                            @endif
                            @if($controlPrenatal->complicaciones_previas)
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <strong>Complicaciones Previas:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->complicaciones_previas }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sección: Antecedentes Médicos -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">⚕️ Antecedentes Médicos</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($controlPrenatal->enfermedades_cronicas)
                                <div class="col-md-6 mb-3">
                                    <strong>Enfermedades Crónicas:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->enfermedades_cronicas }}</p>
                                </div>
                                @endif
                                @if($controlPrenatal->alergias)
                                <div class="col-md-6 mb-3">
                                    <strong>Alergias:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->alergias }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                @if($controlPrenatal->cirugias_previas)
                                <div class="col-md-6 mb-3">
                                    <strong>Cirugías Previas:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->cirugias_previas }}</p>
                                </div>
                                @endif
                                @if($controlPrenatal->medicamentos_actuales)
                                <div class="col-md-6 mb-3">
                                    <strong>Medicamentos Actuales:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->medicamentos_actuales }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                @if($controlPrenatal->habitos)
                                <div class="col-md-6 mb-3">
                                    <strong>Hábitos:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->habitos }}</p>
                                </div>
                                @endif
                                @if($controlPrenatal->antecedentes_familiares)
                                <div class="col-md-6 mb-3">
                                    <strong>Antecedentes Familiares:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->antecedentes_familiares }}</p>
                                </div>
                                @endif
                            </div>
                            @if(!$controlPrenatal->enfermedades_cronicas && !$controlPrenatal->alergias && !$controlPrenatal->cirugias_previas && !$controlPrenatal->medicamentos_actuales && !$controlPrenatal->habitos && !$controlPrenatal->antecedentes_familiares)
                            <p class="text-muted mb-0">Sin antecedentes médicos registrados</p>
                            @endif
                        </div>
                    </div>

                    <!-- Sección: Datos del Control Prenatal Actual -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">📋 Datos del Control Prenatal</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <strong>Fecha del Control:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->fecha_control->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <strong>Presión Arterial:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->presion_arterial }} mmHg</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <strong>Frecuencia Cardíaca:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->frecuencia_cardiaca_materna }} BPM</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <strong>Temperatura:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->temperatura }} °C</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <strong>Peso:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->peso_actual }} kg</p>
                                </div>
                                @if($controlPrenatal->altura_uterina)
                                <div class="col-md-3 mb-3">
                                    <strong>Altura Uterina:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->altura_uterina }} cm</p>
                                </div>
                                @endif
                                @if($controlPrenatal->latidos_fetales)
                                <div class="col-md-3 mb-3">
                                    <strong>Latidos Fetales:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->latidos_fetales }} BPM</p>
                                </div>
                                @endif
                                @if($controlPrenatal->movimientos_fetales)
                                <div class="col-md-3 mb-3">
                                    <strong>Movimientos Fetales:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->movimientos_fetales }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <strong>Edema:</strong>
                                    <p class="mb-0 text-capitalize">{{ $controlPrenatal->edema }}</p>
                                </div>
                                @if($controlPrenatal->presentacion_fetal)
                                <div class="col-md-4 mb-3">
                                    <strong>Presentación Fetal:</strong>
                                    <p class="mb-0 text-capitalize">{{ str_replace('_', ' ', $controlPrenatal->presentacion_fetal) }}</p>
                                </div>
                                @endif
                                @if($controlPrenatal->fecha_proxima_cita)
                                <div class="col-md-4 mb-3">
                                    <strong>Próxima Cita:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->fecha_proxima_cita->format('d/m/Y') }}</p>
                                </div>
                                @endif
                            </div>
                            @if($controlPrenatal->resultados_examenes)
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <strong>Resultados de Exámenes:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->resultados_examenes }}</p>
                                </div>
                            </div>
                            @endif
                            @if($controlPrenatal->observaciones)
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <strong>Observaciones:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->observaciones }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sección: Tratamientos y Recomendaciones -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">💉 Tratamientos y Recomendaciones</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($controlPrenatal->suplementos)
                                <div class="col-md-4 mb-3">
                                    <strong>Suplementos:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->suplementos }}</p>
                                </div>
                                @endif
                                @if($controlPrenatal->vacunas_aplicadas)
                                <div class="col-md-4 mb-3">
                                    <strong>Vacunas Aplicadas:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->vacunas_aplicadas }}</p>
                                </div>
                                @endif
                                @if($controlPrenatal->indicaciones_medicas)
                                <div class="col-md-4 mb-3">
                                    <strong>Indicaciones Médicas:</strong>
                                    <p class="mb-0">{{ $controlPrenatal->indicaciones_medicas }}</p>
                                </div>
                                @endif
                            </div>
                            @if(!$controlPrenatal->suplementos && !$controlPrenatal->vacunas_aplicadas && !$controlPrenatal->indicaciones_medicas)
                            <p class="text-muted mb-0">Sin tratamientos o recomendaciones registrados</p>
                            @endif
                        </div>
                    </div>

                    <!-- Información de registro -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>Registrado:</strong> {{ $controlPrenatal->created_at->format('d/m/Y H:i:s') }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <strong>Última actualización:</strong> {{ $controlPrenatal->updated_at->format('d/m/Y H:i:s') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex justify-content-between mt-4">
                        <form action="{{ route('controles-prenatales.destroy', $controlPrenatal) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este control prenatal?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                        <div>
                            <a href="{{ route('controles-prenatales.edit', $controlPrenatal) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('controles-prenatales.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Ver Listado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection