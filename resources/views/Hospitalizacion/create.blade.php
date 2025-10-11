@extends('layouts.app')

@section('content')
<style>
/* ===== Fondo de la clínica ===== */
body {
    background-color: #e8f4fc;
    margin: 0;
    padding: 0;
    position: relative;
}
body::before {
    content: "";
    position: fixed;
    top: 50%;
    left: 50%;
    width: 600px;
    height: 600px;
    background-image: url('{{ asset("images/barra.png") }}');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    transform: translate(-50%, -50%);
    opacity: 0.15;
    z-index: 0;
    pointer-events: none;
}

/* ===== Contenedor principal ===== */
.content-wrapper {
    margin-top: 60px;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
    padding: 1rem;
    position: relative;
    z-index: 1;
}

/* ===== Cards internas ===== */
.custom-card { 
    background-color: #fff; 
    border-radius: 1.5rem; 
    padding: 1.5rem 2rem; 
    margin-bottom: 1.5rem;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

.card-header { 
    background-color: transparent; 
    text-align:center; 
    margin-bottom:1rem; 
    padding:0.5rem 0; 
}
.card-header h2 { 
    font-size:2rem; 
    font-weight:bold; 
    color:#003366; 
    margin:0; 
}

.card-section-header {
    background-color: #e1f0ff;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #003366;
    text-align: center;
}

label { font-size:0.85rem; font-weight:600; color:#003366; }
input, select, textarea { font-size:0.85rem !important; }
.row { display: flex; flex-wrap: wrap; margin-left: -0.5rem; margin-right: -0.5rem; }
.row > div { padding-left: 0.5rem; padding-right: 0.5rem; margin-bottom: 1rem; }
.col-4th { flex: 0 0 25%; max-width: 25%; }
.col-half { flex: 0 0 50%; max-width: 50%; }

.btn { font-size:0.9rem; }
.text-danger { color: red; font-size: 0.8rem; margin-top: 0.25rem; }

.btn-guardar { background-color: #ffc107; border-color: #ffc107; color: #000; font-weight: 600; }
.btn-guardar:hover { background-color: #ffb700; border-color: #ffb700; color: #000; }
.btn-limpiar { background-color: #0d6efd; border-color: #0d6efd; color: #fff; font-weight: 600; }
.btn-limpiar:hover { background-color: #0056b3; border-color: #0056b3; color: #fff; }
.btn i { margin-right: 5px; }

/* ===== Estilos de impresión solo cuando se imprime ===== */
.print-mode {
    font-family: Arial, sans-serif;
    font-size: 11pt;
}
.print-mode .btn, 
.print-mode footer { display: none !important; }

.print-mode input,
.print-mode select,
.print-mode textarea {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    outline: none !important;
    color: #000 !important;
    font-size: 11pt !important;
    width: 100% !important;
}
.print-mode textarea {
    display: block !important;
    border: none !important;
    background: transparent !important;
    color: #000 !important;
    font-size: 11pt !important;
    width: 100% !important;
    height: auto !important;
    overflow: visible !important;
    white-space: pre-wrap !important;
    word-wrap: break-word !important;
    padding: 0 !important;
    resize: none !important;
    box-shadow: none !important;
}
.print-mode label {
    font-weight: bold !important;
    font-size: 11pt !important;
    color: #000 !important;
}
</style>

<div class="content-wrapper {{ session('imprimir') ? 'print-mode' : '' }}">
    <div class="card custom-card">
        <div class="card-header">
            <h2>Transferencia a hospitalización</h2>
        </div>

        @if(session('imprimir') && isset($hospitalizacion))
            {{-- VISTA SOLO PARA IMPRESIÓN --}}
            <div class="custom-card">
                <div class="card-section-header">Datos del Paciente</div>
                <div class="row">
                    <div class="col-4th"><label>Nombres:</label><input type="text" value="{{ $hospitalizacion->paciente->nombre ?? '' }}" readonly></div>
                    <div class="col-4th"><label>Apellidos:</label><input type="text" value="{{ $hospitalizacion->paciente->apellidos ?? '' }}" readonly></div>
                    <div class="col-4th"><label>Identidad:</label><input type="text" value="{{ $hospitalizacion->paciente->identidad ?? '' }}" readonly></div>
                    <div class="col-4th"><label>Teléfono:</label><input type="text" value="{{ $hospitalizacion->paciente->telefono ?? '' }}" readonly></div>
                </div>
                <div class="row">
                    <div class="col-4th"><label>Sexo:</label><input type="text" value="{{ $hospitalizacion->paciente->genero ?? '' }}" readonly></div>
                </div>

                <div class="card-section-header">Signos Vitales</div>
                <div class="row">
                    <div class="col-4th"><label>Presión Arterial:</label><input type="text" value="{{ $hospitalizacion->emergencia->pa ?? '' }}" readonly></div>
                    <div class="col-4th"><label>Frecuencia Cardíaca:</label><input type="number" value="{{ $hospitalizacion->emergencia->fc ?? '' }}" readonly></div>
                    <div class="col-4th"><label>Temperatura (°C):</label><input type="number" step="0.1" value="{{ $hospitalizacion->emergencia->temp ?? '' }}" readonly></div>
                </div>

                <div class="card-section-header">Datos de Hospitalización</div>
                <div class="row mb-3">
                    <div class="col-half">
                        <label>Acompañante:</label>
                        <textarea rows="2" readonly>{{ $hospitalizacion->acompanante ?? '' }}</textarea>
                    </div>
                    <div class="col-half">
                        <label>Fecha y hora de ingreso:</label>
                        <input type="datetime-local" value="{{ $hospitalizacion->fecha_ingreso ?? '' }}" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-half">
                        <label>Motivo de hospitalización:</label>
                        <textarea rows="3" readonly>{{ $hospitalizacion->motivo ?? '' }}</textarea>
                    </div>
                    <div class="col-half">
                        <label>Hospital de destino:</label>
                        <input type="text" value="{{ $hospitalizacion->hospital ?? '' }}" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-half">
                        <label>Médico que remite:</label>
                        <input type="text" value="{{ $hospitalizacion->medico->nombre ?? '' }} {{ $hospitalizacion->medico->apellidos ?? '' }}" readonly>
                    </div>
                    <div class="col-half">
                        <label>Nombre de la clínica:</label>
                        <input type="text" value="{{ $hospitalizacion->clinica ?? '' }}" readonly>
                    </div>
                </div>
            </div>
        @else
            {{-- FORMULARIO NORMAL --}}
            <form id="hospitalizacionForm" method="POST" action="{{ route('hospitalizaciones.store') }}">
                @csrf
                <input type="hidden" name="identidad" value="{{ $paciente->identidad ?? $emergencia->identidad ?? '' }}">
                <input type="hidden" name="emergencia_id" value="{{ $emergencia->id ?? '' }}">

                {{-- Datos del Paciente --}}
                <div class="custom-card">
                    <div class="card-section-header">Datos del Paciente</div>
                    <div class="row">
                        <div class="col-4th"><label>Nombres:</label><input type="text" class="form-control" value="{{ $paciente->nombre ?? $emergencia->nombres ?? '' }}" readonly></div>
                        <div class="col-4th"><label>Apellidos:</label><input type="text" class="form-control" value="{{ $paciente->apellidos ?? $emergencia->apellidos ?? '' }}" readonly></div>
                        <div class="col-4th"><label>Identidad:</label><input type="text" class="form-control" value="{{ $paciente->identidad ?? $emergencia->identidad ?? '' }}" readonly></div>
                        <div class="col-4th"><label>Teléfono:</label><input type="text" class="form-control" value="{{ $paciente->telefono ?? $emergencia->telefono ?? '' }}" readonly></div>
                    </div>
                    <div class="row">
                        <div class="col-4th"><label>Sexo:</label><input type="text" class="form-control" value="{{ $paciente->genero ?? $emergencia->sexo ?? '' }}" readonly></div>
                    </div>

                    <div class="card-section-header">Signos Vitales</div>
                    <div class="row">
                        <div class="col-4th"><label>Presión Arterial:</label><input type="text" class="form-control" value="{{ $emergencia->pa ?? '' }}" readonly></div>
                        <div class="col-4th"><label>Frecuencia Cardíaca:</label><input type="number" class="form-control" value="{{ $emergencia->fc ?? '' }}" readonly></div>
                        <div class="col-4th"><label>Temperatura (°C):</label><input type="number" step="0.1" class="form-control" value="{{ $emergencia->temp ?? '' }}" readonly></div>
                    </div>
                </div>

                {{-- Datos de Hospitalización --}}
                <div class="custom-card">
                    <div class="card-section-header">Datos de Hospitalización</div>
                    <div class="row mb-3">
                        <div class="col-half">
                            <label>Acompañante:</label>
                            <textarea name="acompanante" rows="2" class="form-control">{{ old('acompanante') }}</textarea>
                            @error('acompanante')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-half">
                            <label>Fecha y hora de ingreso:</label>
                            <input type="datetime-local" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso', now()->format('Y-m-d\TH:i')) }}">
                            @error('fecha_ingreso')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-half">
                            <label>Motivo de hospitalización:</label>
                            <textarea name="motivo" rows="3" class="form-control">{{ old('motivo') }}</textarea>
                            @error('motivo')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-half">
                            <label>Hospital de destino:</label>
                            <select name="hospital" class="form-control">
                                <option value="" selected disabled>Seleccione un hospital</option>
                                @foreach([
                                    'Hospital Mario Catarino Rivas',
                                    'Hospital Regional Atlántida',
                                    'Hospital San Felipe',
                                    'Hospital Escuela Dr. Mario Rivas',
                                    'Hospital de Occidente',
                                    'Hospital Mario Mendoza',
                                    'Hospital Atlántida La Ceiba',
                                    'Hospital Leonardo Martínez',
                                    'Hospital Regional Gabriela Alvarado'
                                ] as $hospital)
                                    <option value="{{ $hospital }}" {{ old('hospital') == $hospital ? 'selected' : '' }}>{{ $hospital }}</option>
                                @endforeach
                            </select>
                            @error('hospital')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-half">
                            <label>Médico que remite:</label>
                            <select name="medico_id" class="form-control">
                                <option value="" selected disabled>Seleccione un médico</option>
                                @foreach($medicos as $medico)
                                    <option value="{{ $medico->id }}" {{ old('medico_id') == $medico->id ? 'selected' : '' }}>
                                        {{ $medico->nombre }} {{ $medico->apellidos }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medico_id')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-half">
                            <label>Nombre de la clínica:</label>
                            <input type="text" name="clinica" class="form-control" maxlength="100" value="{{ old('clinica') }}">
                            @error('clinica')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-center gap-3 mt-4 w-100">
                    <button type="submit" class="btn btn-guardar"><i class="bi bi-printer"></i> Guardar e imprimir</button>
                    <button type="button" class="btn btn-limpiar" id="btnLimpiar"><i class="bi bi-trash"></i> Limpiar</button>
                    <a href="{{ route('emergencias.index') }}" class="btn btn-success">&#8592; Regresar</a>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnLimpiar = document.getElementById('btnLimpiar');
    const form = document.getElementById('hospitalizacionForm');

    btnLimpiar.addEventListener('click', function() {
        form.querySelectorAll('input:not([readonly]):not([name="fecha_ingreso"]), textarea').forEach(input => input.value = '');
        form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        form.querySelectorAll('.text-danger').forEach(span => span.textContent = '');
    });
});

@if(session('imprimir'))
<script>
    window.addEventListener('load', function() {
        window.print();
    });
</script>
@endif
</script>

@endsection
