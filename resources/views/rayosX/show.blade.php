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
    white-space: normal;   /* permite saltos de línea */
    overflow-wrap: break-word; /* rompe palabras largas si es necesario */
}


    /* Descripción del examen */
    .examen-card p {
    max-height: none;      /* sin límite de altura */
    overflow: visible;     /* nada de scroll */
    line-height: 1.4rem;   /* un poco más de espacio entre líneas */
}



   /* Modal navegación */
.modal-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 2rem;
    color: #ff8c00; /* color naranja brillante */
    text-shadow: 1px 1px 4px rgba(0,0,0,0.7); /* sombra para resaltar */
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
    max-height: none;           /* sin límite de altura */
    overflow-y: auto;           /* scroll vertical si fuera muy largo */
    white-space: normal;        /* texto normal */
    overflow-wrap: break-word;  /* evita que palabras largas rompan el diseño */
    line-height: 1.4rem;
    font-size: 0.9rem;
}

    /* Estilos para impresión - Adaptados para análisis de rayos X */
    @media print {
        /* Ocultar elementos innecesarios */
        .btn-imprimir,
        .mt-4.text-center,
        .modal,
        .modal-nav {
            display: none !important;
        }
        
        /* Configuración básica */
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            margin: 0 !important;
            padding: 0 !important;
            font-family: Arial, sans-serif !important;
            font-size: 11pt !important;
            line-height: 1.4 !important;
            color: #000 !important;
            background: white !important;
        }
        
        .custom-card {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            background: white !important;
        }
        
        /* Header elegante */
        .header-flex {
            background: #003366 !important;
            color: white !important;
            padding: 20px !important;
            text-align: center !important;
            margin-bottom: 25px !important;
            border-radius: 8px !important;
            -webkit-print-color-adjust: exact;
            justify-content: center !important;
        }
        
        .header-flex h2, .section-title {
            font-size: 22pt !important;
            font-weight: bold !important;
            margin: 0 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            color: white !important;
            border: none !important;
            padding: 0 !important;
        }
        
        /* Información de la clínica en el header */
        .header-flex::before {
            content: "CLINITEK - ANÁLISIS DE RAYOS X";
            display: block;
            font-size: 10pt;
            margin-bottom: 5px;
            opacity: 0.9;
        }
        
        /* Títulos de sección mejorados */
        h4 {
            background: #f0f8ff !important;
            color: #003366 !important;
            padding: 12px 15px !important;
            margin: 20px 0 15px 0 !important;
            border-left: 4px solid #003366 !important;
            font-size: 14pt !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            -webkit-print-color-adjust: exact;
            page-break-after: avoid;
        }
        
        /* Datos del paciente */
        .datos-paciente-flex {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
            margin-bottom: 20px !important;
            padding: 0 !important;
        }
        
        .datos-paciente-flex li {
            background: #f8f9fa !important;
            padding: 10px !important;
            border-radius: 4px !important;
            border-left: 2px solid #003366 !important;
            font-size: 10pt !important;
            border-bottom: none !important;
            -webkit-print-color-adjust: exact;
            display: block !important;
        }
        
        .datos-paciente-flex li strong {
            color: #003366 !important;
            font-weight: bold !important;
            font-size: 9pt !important;
            text-transform: uppercase !important;
            display: block !important;
            margin-bottom: 3px !important;
            width: auto !important;
        }
        
        /* Información del médico analista */
        h4 + p {
            background: #f8f9fa !important;
            padding: 10px 15px !important;
            border-radius: 4px !important;
            border-left: 2px solid #003366 !important;
            font-size: 11pt !important;
            font-weight: bold !important;
            -webkit-print-color-adjust: exact;
        }
        
        /* Tarjetas de exámenes */
        .examen-card {
            background: white !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
            padding: 20px !important;
            margin-bottom: 25px !important;
            border-bottom: 3px solid #007BFF !important;
            -webkit-print-color-adjust: exact;
            page-break-inside: avoid;
        }
        
        .examen-nombre {
            background: #007BFF !important;
            color: white !important;
            padding: 10px 15px !important;
            margin: -20px -20px 15px -20px !important;
            border-radius: 8px 8px 0 0 !important;
            font-size: 14pt !important;
            text-align: center !important;
            -webkit-print-color-adjust: exact;
        }
        
        /* Galería de imágenes para impresión */
        .img-gallery {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 15px !important;
            margin-top: 15px !important;
        }
        
        .img-card {
            background: white !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 6px !important;
            padding: 10px !important;
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
            max-height: 200px !important;
            object-fit: contain !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 4px !important;
        }
        
        .img-description {
            font-size: 9pt !important;
            color: #555 !important;
            margin-top: 8px !important;
            text-align: center !important;
            line-height: 1.2 !important;
        }
        
        /* Texto informativo si no hay imágenes */
        .examen-card p {
            font-size: 10pt !important;
            color: #666 !important;
            font-style: italic !important;
            text-align: center !important;
            margin: 10px 0 !important;
        }
        
        /* Primera sección */
        h4:first-of-type {
            margin-top: 5px !important;
        }
        
        /* Footer */
        .custom-card::after {
            content: "Análisis generado por Sistema Clinitek";
            display: block;
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8pt;
            color: #6c757d;
            font-style: italic;
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
        <li><strong>Nombre:</strong> {{ $orden->paciente->nombre ?? $orden->nombres ?? 'N/A' }}</li>
        <li><strong>Apellidos:</strong> {{ $orden->paciente->apellidos ?? $orden->apellidos ?? 'N/A' }}</li>
        <li><strong>Identidad:</strong> {{ $orden->paciente->identidad ?? $orden->identidad ?? 'N/A' }}</li>
        <li><strong>Género:</strong> {{ $orden->paciente->genero ?? $orden->genero ?? 'N/A' }}</li>
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