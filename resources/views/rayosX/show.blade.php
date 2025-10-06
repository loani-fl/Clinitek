@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        margin: 40px auto 60px auto;
        border-radius: 1.5rem;
        padding: 2rem 2.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: relative;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #003366;
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.3rem;
        margin-bottom: 1rem;
    }

    /* Header con botón */
    .header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .header-flex h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
        color: #003366;
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.3rem;
    }

    /* Estilo del botón imprimir */
    .btn-imprimir {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
        font-weight: 600;
    }
    
    .btn-imprimir:hover {
        background-color: #ffb700;
        border-color: #ffb700;
        color: #000;
    }

    h4 {
        color: #003366;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .datos-paciente-flex {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        list-style: none;
        padding-left: 0;
    }

    .datos-paciente-flex li {
        flex: 1 1 200px;
        padding: 0.3rem 0;
        border-bottom: 1px solid #ccc;
        display: flex;
        gap: 0.5rem;
        color: #222;
        font-weight: 600;
    }

    .datos-paciente-flex li strong {
        width: 80px;
        color: #004080;
    }

    .examen-card {
        margin-bottom: 2.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #007BFF;
    }

    .examen-card:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .examen-nombre {
        font-weight: 700;
        font-size: 1.3rem;
        color: #004080;
        margin-bottom: 0.5rem;
    }

    /* Galería imágenes en fila */
    .img-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .img-card {
        flex: 1 1 calc(33.33% - 1rem);
        max-width: calc(33.33% - 1rem);
        border-radius: 0.6rem;
        overflow: hidden;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        transform-origin: center center;
    }

    @media (hover: hover) {
        .img-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        }
    }

    @media (max-width: 992px) {
        .img-card {
            flex: 1 1 calc(50% - 1rem);
            max-width: calc(50% - 1rem);
        }
    }

    @media (max-width: 576px) {
        .img-card {
            flex: 1 1 100%;
            max-width: 100%;
        }
    }

    .img-preview {
        width: 100%;
        display: block;
        border-radius: 0.4rem;
        object-fit: cover;
    }

    /* Descripción de la imagen */
    .img-description {
        font-size: 0.8rem;
        color: #555;
        margin-top: 0.3rem;
        text-align: center;
        white-space: normal;
        overflow-wrap: break-word;
    }

    /* Descripción del examen */
    .examen-card p {
        max-height: none;
        overflow: visible;
        line-height: 1.4rem;
    }

    /* Modal navegación */
    .modal-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 2rem;
        color: #ff8c00;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
        cursor: pointer;
        z-index: 1055;
        user-select: none;
        transition: color 0.2s ease;
    }

    .modal-nav:hover {
        color: #ffa500; 
    }

    .modal-prev {
        left: 10px;
    }

    .modal-next {
        right: 10px;
    }

    .modal-content img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }

    /* Descripción dentro del modal */
    [id^="modal-desc-"] {
        max-height: none;
        overflow-y: auto;
        white-space: normal;
        overflow-wrap: break-word;
        line-height: 1.4rem;
        font-size: 0.9rem;
    }

    /* ========================================
       ESTILOS DE IMPRESIÓN - MISMO ESTILO QUE CONSULTA
       ======================================== */
    @media print {
        /* Ocultar elementos innecesarios */
        .btn-imprimir,
        .mt-4.text-center,
        .modal,
        .modal-backdrop,
        .modal-nav,
        button,
        a.btn {
            display: none !important;
        }
        
        /* Ocultar el Clinitek grande del centro de la página */
        .custom-card::before {
            display: none !important;
            content: none !important;
        }
        
        /* Mostrar header de impresión */
        .print-header {
            display: block !important;
        }
        
        /* Estilo para el texto Clinitek */
        .print-header .clinitek-text {
            color: #6c757d !important;
            font-size: 22pt !important;
            font-weight: 800 !important;
            font-family: 'Impact', 'Arial Black', sans-serif !important;
            letter-spacing: 1px !important;
            text-transform: uppercase !important;
            display: inline-block !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Configuración de página */
        @page {
            margin: 1.2cm 0.5cm 1.5cm 0.5cm;
            size: A4;
        }
        
        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: Arial, sans-serif !important;
            font-size: 10pt !important;
            line-height: 1.3 !important;
            color: #000 !important;
            background: white !important;
        }
        
        .custom-card {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 10px !important;
            box-shadow: none !important;
            border: none !important;
            background: white !important;
            border-radius: 0 !important;
        }
        
        /* Header con título */
        .header-flex {
            background: white !important;
            color: #0d6efd !important;
            padding: 10px 0 !important;
            margin-bottom: 12px !important;
            text-align: center !important;
            border-bottom: 3px solid #0d6efd !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            display: block !important;
            justify-content: center !important;
        }
        
        .header-flex h2 {
            font-size: 16pt !important;
            font-weight: bold !important;
            margin: 0 !important;
            color: #000 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            border: none !important;
            padding: 0 !important;
        }
        
        /* Títulos de sección principales con línea azul */
        h4 {
            color: #0d6efd !important;
            font-size: 11pt !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            border-bottom: 2px solid #0d6efd !important;
            padding-bottom: 3px !important;
            margin-top: 12px !important;
            margin-bottom: 8px !important;
            background: none !important;
            border-left: none !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        h4:first-of-type {
            margin-top: 0 !important;
        }
        
        /* Datos del paciente en grid 2 columnas */
        .datos-paciente-flex {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 8px 15px !important;
            background: none !important;
            border: none !important;
            padding: 0 !important;
            margin-bottom: 10px !important;
        }
        
        .datos-paciente-flex li {
            padding: 0 !important;
            font-size: 9pt !important;
            line-height: 1.3 !important;
            margin: 0 !important;
            border: none !important;
            background: none !important;
            display: block !important;
            border-bottom: none !important;
        }
        
        /* Labels */
        .datos-paciente-flex li strong {
            color: #0d6efd !important;
            font-weight: bold !important;
            font-size: 8pt !important;
            text-transform: uppercase !important;
            display: inline !important;
            width: auto !important;
        }
        
        .datos-paciente-flex li strong::after {
            content: ": ";
        }
        
        /* Médico analista - mismo formato */
        h4 + p {
            font-size: 9pt !important;
            margin: 0 0 10px 0 !important;
            padding: 0 !important;
            background: none !important;
            border: none !important;
            font-weight: normal !important;
        }
        
        h4 + p::before {
            content: "MÉDICO: ";
            color: #0d6efd !important;
            font-weight: bold !important;
            font-size: 8pt !important;
            text-transform: uppercase !important;
        }
        
        /* Tarjetas de exámenes */
        .examen-card {
            background: none !important;
            border: none !important;
            padding: 0 !important;
            margin-bottom: 15px !important;
            border-bottom: none !important;
            page-break-inside: avoid;
        }
        
        .examen-nombre {
            background: none !important;
            color: #0d6efd !important;
            padding: 0 !important;
            margin: 0 0 8px 0 !important;
            font-size: 10pt !important;
            font-weight: bold !important;
            text-align: left !important;
            text-transform: uppercase !important;
            border-bottom: 1px solid #0d6efd !important;
            padding-bottom: 3px !important;
        }
        
        /* Galería de imágenes - 2 columnas como consulta */
        .img-gallery {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 8px !important;
            margin-top: 8px !important;
            margin-bottom: 10px !important;
        }
        
        .img-card {
            background: none !important;
            border: 1px solid #ddd !important;
            border-radius: 0 !important;
            padding: 5px !important;
            page-break-inside: avoid;
            transform: none !important;
            transition: none !important;
            cursor: default !important;
            flex: none !important;
            max-width: none !important;
        }
        
        .img-preview {
            width: 100% !important;
            height: auto !important;
            max-height: 150px !important;
            object-fit: contain !important;
            border: none !important;
            border-radius: 0 !important;
        }
        
        .img-description {
            font-size: 7pt !important;
            color: #555 !important;
            margin-top: 3px !important;
            text-align: center !important;
            line-height: 1.2 !important;
        }
        
        /* Texto cuando no hay imágenes */
        .examen-card > p {
            font-size: 9pt !important;
            color: #666 !important;
            font-style: italic !important;
            margin: 5px 0 !important;
            padding: 0 !important;
            background: none !important;
        }
        
        /* Footer */
        .custom-card::after {
            content: "Sistema Clinitek";
            display: block !important;
            text-align: center !important;
            font-size: 7pt !important;
            color: #999 !important;
            border-top: 1px solid #ddd !important;
            padding-top: 6px !important;
            margin-top: 12px !important;
        }
    }
</style>



<div class="custom-card">
    <!-- Header con título y botón -->
    <div class="header-flex">
        <h2>Ver análisis de Rayos X</h2>
        <!-- Botón Imprimir Reporte -->
        <button onclick="window.print()" class="btn btn-imprimir btn-sm d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="fas fa-print"></i> Imprimir Reporte
        </button>
    </div>

    {{-- Datos del paciente --}}
    <h4>Datos del paciente</h4>
    <ul class="datos-paciente-flex">
        <li><strong>Nombre</strong> {{ $orden->paciente->nombre ?? $orden->nombres ?? 'N/A' }}</li>
        <li><strong>Apellidos</strong> {{ $orden->paciente->apellidos ?? $orden->apellidos ?? 'N/A' }}</li>
        <li><strong>Identidad</strong> {{ $orden->paciente->identidad ?? $orden->identidad ?? 'N/A' }}</li>
        <li><strong>Género</strong> {{ $orden->paciente->genero ?? $orden->genero ?? 'N/A' }}</li>
    </ul>

    {{-- Médico Analista --}}
    <h4>Médico Analista</h4>
    <p>
        {{ optional($orden->medicoAnalista)->nombre ?? 'N/A' }}
        {{ optional($orden->medicoAnalista)->apellidos ?? '' }}
    </p>

    {{-- Exámenes realizados --}}
    <h4>Exámenes realizados</h4>
    @forelse ($orden->examenes as $examen)
    <div class="examen-card">
        <div class="examen-nombre">
            {{ $examenesNombres[$examen->examen_codigo] ?? $examen->examen_codigo }}
        </div>

        {{-- Imágenes --}}
        @if($examen->imagenes && $examen->imagenes->count() > 0)
            <div class="img-gallery" id="gallery-{{ $examen->id }}">
                @foreach ($examen->imagenes as $index => $imagen)
                    @if($imagen->ruta)
                        <div class="img-card" onclick="openModal({{ $examen->id }}, {{ $index }})">
                            <img src="{{ asset('storage/' . $imagen->ruta) }}" 
                                 class="img-preview" 
                                 alt="{{ $imagen->descripcion ?? 'Imagen de examen' }}" />
                            <p class="img-description">
                                {{ $imagen->descripcion ?? 'Sin descripción' }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modal-{{ $examen->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content position-relative bg-dark">
                        <span class="modal-nav modal-prev" onclick="prevImage({{ $examen->id }})">&lt;</span>
                        <span class="modal-nav modal-next" onclick="nextImage({{ $examen->id }})">&gt;</span>
                        <img id="modal-img-{{ $examen->id }}" src="" alt="">
                        <div id="modal-desc-{{ $examen->id }}" class="p-2 text-center text-white"></div>
                    </div>
                </div>
            </div>
        @else
            <p>Sin imágenes registradas</p>
        @endif
    </div>
    @empty
        <p>No hay exámenes registrados para esta orden.</p>
    @endforelse

    <div class="mt-4 text-center">
        <a href="{{ route('rayosx.index') }}" class="btn btn-success">
            <i class="bi bi-arrow-left-circle"></i> Regresar
        </a>
    </div>
</div>

<script>
    const examsImages = {};
    @foreach ($orden->examenes as $examen)
        examsImages[{{ $examen->id }}] = [
            @foreach ($examen->imagenes as $imagen)
                {
                    src: "{{ asset('storage/' . $imagen->ruta) }}",
                    desc: "{{ $imagen->descripcion ?? '' }}"
                },
            @endforeach
        ];
    @endforeach

    const currentIndex = {};

    function openModal(examenId, index) {
        currentIndex[examenId] = index;
        const modal = new bootstrap.Modal(document.getElementById(`modal-${examenId}`));
        document.getElementById(`modal-img-${examenId}`).src = examsImages[examenId][index].src;
        document.getElementById(`modal-desc-${examenId}`).innerText = examsImages[examenId][index].desc;
        modal.show();
    }

    function nextImage(examenId) {
        currentIndex[examenId]++;
        if(currentIndex[examenId] >= examsImages[examenId].length) currentIndex[examenId] = 0;
        document.getElementById(`modal-img-${examenId}`).src = examsImages[examenId][currentIndex[examenId]].src;
        document.getElementById(`modal-desc-${examenId}`).innerText = examsImages[examenId][currentIndex[examenId]].desc;
    }

    function prevImage(examenId) {
        currentIndex[examenId]--;
        if(currentIndex[examenId] < 0) currentIndex[examenId] = examsImages[examenId].length - 1;
        document.getElementById(`modal-img-${examenId}`).src = examsImages[examenId][currentIndex[examenId]].src;
        document.getElementById(`modal-desc-${examenId}`).innerText = examsImages[examenId][currentIndex[examenId]].desc;
    }
</script>
@endsection