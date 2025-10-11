@extends('layouts.app')

@section('content')
<style>
/* ===== Estilos normales ===== */
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
.content-wrapper {
    margin-top: 60px;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
    padding: 1rem;
    position: relative;
    z-index: 1;
}
.custom-card { 
    background-color: #fff; 
    border-radius: 1.5rem; 
    padding: 1rem 1.5rem; 
    margin-bottom: 1rem;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

/* ===== Estilos de impresión ===== */
@media print {
    /* Ocultar elementos innecesarios */
    .btn, .btn-imprimir, nav, .fixed-top, .alert, .acciones,
    .dropdown, a, button, .text-center.pt-4, footer {
        display: none !important;
    }

    @page { size: A4; margin: 1.5cm 1.8cm; }

    body {
        font-family: Arial, sans-serif !important;
        font-size: 11pt !important;
        color: #000 !important;
        background: #fff !important;
        margin-bottom: 0 !important;
    }

    .container, .custom-card {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: #fff !important;
    }

    .custom-card::before { opacity: 0.02 !important; }

    /* Encabezado Clinitek */
    .print-header {
        display: flex !important;
        align-items: center;
        justify-content: flex-start;
        gap: 20px;
        margin-bottom: 15px;
        border-bottom: 2px solid #004AAD;
        padding-bottom: 8px;
    }
    .print-header img { 
        width: 120px !important; 
        height: auto !important;
    }
    .print-header h1 {
        font-family: 'Poppins', sans-serif !important;
        font-size: 24pt !important;
        font-weight: 900 !important;
        color: #004AAD !important;
        margin: 0 !important;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    /* Título de reporte */
    .titulo-reporte {
        text-align: center !important;
        font-size: 16pt !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        margin: 15px 0 20px 0 !important;
        color: #004AAD !important;
        border-bottom: 2px solid #004AAD;
        padding-bottom: 4px;
        letter-spacing: 1px;
    }

    /* Títulos de secciones con fondo uniforme */
    .section-title {
        color: #004AAD !important;           /* texto azul oscuro */
        font-weight: bold !important;
        text-transform: uppercase !important;
        border-bottom: 2px solid #004AAD !important;
        margin-top: 10px !important;
        margin-bottom: 5px !important;
        font-size: 10pt !important;
        background-color: #b3d7ff !important; /* azul claro de fondo */
        padding: 3px 6px !important;
        border-radius: 3px !important;
    }

    .row.gy-3 {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        column-gap: 15px !important;
        row-gap: 2px !important;
    }

    .row.gy-3 strong {
        color: #004AAD !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
        font-size: 9pt !important;
    }

    .info-box {
        border: 1px solid #004AAD !important;
        border-radius: 3px !important;
        padding: 4px 6px !important;
        margin-bottom: 4px !important;
        page-break-inside: avoid !important;
        background-color: #fff !important; /* campos en blanco */
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 6px !important;
        font-size: 9pt !important;
    }
    th, td {
        border: 1px solid #004AAD !important;
        padding: 4px 6px !important;
        text-align: left !important;
    }
    th {
        background: #b6d7ff !important;
        color: #000 !important;
        font-weight: bold !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<div class="content-wrapper">
    <div class="card custom-card">
        @if(isset($hospitalizacion))
            <div class="print-mode">
                <div class="print-header">
                    <img src="{{ asset('images/logo2.jpg') }}" alt="Logo Clinitek">
                    <h1>Clínitek</h1>
                </div>

                <div class="titulo-reporte">Transferencia hospitalaria</div>

                <div class="section-title">Datos del Paciente</div>
                <div class="row gy-3">
                    <div class="info-box"><strong>Nombres:</strong> {{ $hospitalizacion->paciente->nombre ?? '' }}</div>
                    <div class="info-box"><strong>Apellidos:</strong> {{ $hospitalizacion->paciente->apellidos ?? '' }}</div>
                    <div class="info-box"><strong>Identidad:</strong> {{ $hospitalizacion->paciente->identidad ?? '' }}</div>
                    <div class="info-box"><strong>Teléfono:</strong> {{ $hospitalizacion->paciente->telefono ?? '' }}</div>
                    <div class="info-box"><strong>Sexo:</strong> {{ $hospitalizacion->paciente->genero ?? '' }}</div>
                </div>

                <div class="section-title">Signos Vitales</div>
                <div class="row gy-3">
                    <div class="info-box"><strong>Presión Arterial:</strong> {{ $hospitalizacion->emergencia->pa ?? $hospitalizacion->paciente->emergencias()->latest()->first()->pa ?? 'No registrado' }}</div>
                    <div class="info-box"><strong>Frecuencia Cardíaca:</strong> {{ $hospitalizacion->emergencia->fc ?? $hospitalizacion->paciente->emergencias()->latest()->first()->fc ?? 'No registrado' }}</div>
                    <div class="info-box"><strong>Temperatura (°C):</strong> {{ $hospitalizacion->emergencia->temp ?? $hospitalizacion->paciente->emergencias()->latest()->first()->temp ?? 'No registrado' }}</div>
                </div>

                <div class="section-title">Datos de Hospitalización</div>
                <div class="row gy-3">
                    <div class="info-box"><strong>Acompañante:</strong> {{ $hospitalizacion->acompanante ?? '' }}</div>
                    <div class="info-box"><strong>Fecha y hora de ingreso:</strong> {{ $hospitalizacion->fecha_ingreso ?? '' }}</div>
                    <div class="info-box"><strong>Motivo:</strong> {{ $hospitalizacion->motivo ?? '' }}</div>
                    <div class="info-box"><strong>Hospital de destino:</strong> {{ $hospitalizacion->hospital ?? '' }}</div>
                    <div class="info-box"><strong>Médico que remite:</strong> {{ $hospitalizacion->medico->nombre ?? '' }} {{ $hospitalizacion->medico->apellidos ?? '' }}</div>
                    <div class="info-box"><strong>Clínica:</strong> {{ $hospitalizacion->clinica ?? '' }}</div>
                </div>
            </div>

            <script>
                window.addEventListener('load', function() {
                    window.print();
                    window.onafterprint = function() {
                        @if($hospitalizacion->paciente)
                            window.location.href = "{{ route('hospitalizaciones.create', $hospitalizacion->paciente->id) }}";
                        @else
                            window.location.href = "{{ route('hospitalizaciones.index') }}";
                        @endif
                    };
                });
            </script>

        @else
            <form method="POST" action="{{ route('hospitalizaciones.store') }}">
                @csrf
                <p>Formulario de creación de hospitalización...</p>
            </form>
        @endif
    </div>
</div>
@endsection
