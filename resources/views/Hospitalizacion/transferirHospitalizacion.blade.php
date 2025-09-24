@extends('layouts.app')

@section('content')
<style>
body { background-color: #e8f4fc; margin: 0; padding: 0; }
.content-wrapper { margin-top: 60px; max-width: 900px; margin-left:auto; margin-right:auto; padding:1rem; position: relative; }
.custom-card::before {
    content: "";
    position: absolute;
    top: 50%; left: 50%;
    width: 800px; height: 800px;
    background-image: url('{{ asset("images/logo2.jpg") }}');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.15;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 0;
}
.custom-card { background-color:#fff; border-radius:1.5rem; padding:2rem; overflow:hidden; position: relative; z-index:1; }
.card-header { background-color: transparent; border-bottom: 3px solid #007BFF; text-align:center; margin-bottom:1rem; padding:0.5rem 0; }
.card-header h2 { font-size:2rem; font-weight:bold; color:#000; margin:0; }
label { font-size:0.85rem; font-weight:600; color:#003366; }
input, select, textarea { font-size:0.85rem !important; }
.row { display: flex; flex-wrap: wrap; margin-left: -0.5rem; margin-right: -0.5rem; }
.row > div { padding-left: 0.5rem; padding-right: 0.5rem; margin-bottom: 1rem; }
.col-4th { flex: 0 0 25%; max-width: 25%; }
.col-half { flex: 0 0 50%; max-width: 50%; }
.btn { font-size:0.9rem; }
.text-danger { color: red; font-size: 0.8rem; margin-top: 0.25rem; }

/* Botones */
.btn-imprimir {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    font-weight: 600;
}
.btn-imprimir:hover { background-color: #ffb700; border-color: #ffb700; color: #000; }

.btn-limpiar {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
    font-weight: 600;
}
.btn-limpiar:hover { background-color: #0056b3; border-color: #0056b3; color: #fff; }

.btn i { margin-right: 5px; }

/* Estilos para impresión */
@media print {
    /* Ocultar botones y footer */
    .btn, footer {
        display: none !important;
    }

    /* Inputs y selects como texto plano */
    input, select {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        outline: none !important;
        color: #000 !important;
        font-size: 11pt !important;
        width: 100% !important;
    }

    /* Textareas como texto plano, sin borde ni scroll, ajustados al contenido */
    textarea {
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

    /* Campos específicos de Acompañante y Motivo */
    #acompanante,
    #motivo {
        min-height: auto !important;
        max-height: none !important;
        border: none !important;
        background: transparent !important;
    }

    /* Labels */
    label {
        font-weight: bold !important;
        font-size: 11pt !important;
        color: #000 !important;
        margin-bottom: 2px !important;
        display: block !important;
    }

    /* Contenedor */
    .custom-card {
        padding: 10px 20px !important;
        border: none !important;
        box-shadow: none !important;
        background: white !important;
    }

    /* Títulos */
    h2, h4 {
        text-align: center !important;
        font-weight: bold !important;
        margin: 15px 0 !important;
    }
}
</style>

<div class="content-wrapper">
    <div class="card custom-card">
        <div class="card-header">
            <h2>Transferencia a hospitalización</h2>
        </div>

        <div id="hospitalizacionForm">
            <div class="row mb-3">
                <div class="col-4th"><label>Nombres:</label><input type="text" class="form-control" value="{{ $emergencia->nombres }}" disabled></div>
                <div class="col-4th"><label>Apellidos:</label><input type="text" class="form-control" value="{{ $emergencia->apellidos }}" disabled></div>
                <div class="col-4th"><label>Identidad:</label><input type="text" class="form-control" value="{{ $emergencia->identidad }}" disabled></div>
                <div class="col-4th"><label>Teléfono:</label><input type="text" class="form-control" value="{{ $emergencia->telefono }}" disabled></div>
            </div>

            <div class="row mb-3">
                <div class="col-4th"><label>Fecha de Nacimiento:</label><input type="date" class="form-control" value="{{ $emergencia->fecha_nacimiento }}" disabled></div>
                <div class="col-4th"><label>Sexo:</label><input type="text" class="form-control" value="{{ $emergencia->sexo }}" disabled></div>
            </div>

            <div class="row mb-3">
                <div class="col-half">
                    <label>Acompañante:</label>
                    <textarea id="acompanante" maxlength="100" rows="2" class="form-control"
                        @if(empty($emergencia->identidad)) disabled placeholder="Campo no editable para pacientes sin documentos" @endif>{{ old('acompanante') }}</textarea>
                    <span id="error-acompanante" class="text-danger d-none">El campo Acompañante es obligatorio.</span>
                </div>
                <div class="col-half">
                    <label>Fecha y hora de ingreso:</label>
                    <input type="datetime-local" class="form-control" id="fecha_ingreso" value="{{ now()->format('Y-m-d\TH:i') }}" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-half">
                    <label>Motivo de hospitalización:</label>
                    <textarea id="motivo" maxlength="150" rows="3" class="form-control"
                        @if(empty($emergencia->identidad)) disabled placeholder="Campo no editable para pacientes sin documentos" @endif>{{ old('motivo') }}</textarea>
                    <span id="error-motivo" class="text-danger d-none">El campo Motivo de hospitalización es obligatorio.</span>
                </div>
            </div>

            <h4 class="mt-4">Signos Vitales</h4>
            <div class="row mb-3">
                <div class="col-4th"><label>Presión Arterial:</label><input type="text" class="form-control" value="{{ $emergencia->pa ?? '' }}" disabled></div>
                <div class="col-4th"><label>Frecuencia Cardíaca:</label><input type="number" class="form-control" value="{{ $emergencia->fc ?? '' }}" disabled></div>
                <div class="col-4th"><label>Temperatura (°C):</label><input type="number" step="0.1" class="form-control" value="{{ $emergencia->temp ?? '' }}" disabled></div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4 w-100">
                <button type="button" class="btn btn-imprimir" id="btnTransferir">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
                <button type="button" class="btn btn-limpiar" id="btnLimpiar">
                    <i class="bi bi-trash"></i> Limpiar
                </button>
                <a href="{{ route('emergencias.index', $emergencia->id) }}" class="btn btn-success">
                    &#8592; Regresar
                </a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const acompananteField = document.getElementById('acompanante');
    const motivoField = document.getElementById('motivo');
    const errorAcompanante = document.getElementById('error-acompanante');
    const errorMotivo = document.getElementById('error-motivo');

    document.getElementById('btnLimpiar').addEventListener('click', function() {
        if (!acompananteField.disabled) {
            acompananteField.value = '';
            errorAcompanante.classList.add('d-none');
        }
        if (!motivoField.disabled) {
            motivoField.value = '';
            errorMotivo.classList.add('d-none');
        }
    });

    document.getElementById('btnTransferir').addEventListener('click', function() {
        const identidad = "{{ $emergencia->identidad }}";
        let valido = true;

        if (identidad && identidad.trim() !== "") {
            if (acompananteField.value.trim() === "") {
                errorAcompanante.classList.remove('d-none');
                valido = false;
            } else {
                errorAcompanante.classList.add('d-none');
            }

            if (motivoField.value.trim() === "") {
                errorMotivo.classList.remove('d-none');
                valido = false;
            } else {
                errorMotivo.classList.add('d-none');
            }
        }

        if (!valido) return;

        window.print();
    });
});
</script>
@endsection
