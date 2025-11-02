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
        max-width: 900px;
        background-color: #fff;
        margin: 20px auto 40px auto;
        border-radius: 1rem;
        padding: 1.5rem 2rem;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        position: relative;
        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.9);
        border-radius: 1rem;
        z-index: 0;
    }

    .custom-card > * {
        position: relative;
        z-index: 1;
    }

    /* Header */
    .header-flex {
        text-align: center;
        margin-bottom: 1rem;
    }

    .header-flex h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
        color: #003366;
        border-bottom: 2px solid #007BFF;
        padding-bottom: 0.4rem;
        display: inline-block;
    }

    h4 {
        color: #003366;
        font-weight: 700;
        margin-top: 1rem;
        margin-bottom: 0.7rem;
        font-size: 0.95rem;
    }

    /* Datos del paciente */
    .datos-paciente-flex {
        display: flex;
        gap: 1.5rem;
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
        font-size: 0.85rem;
    }

    .datos-paciente-flex li strong {
        width: 80px;
        color: #004080;
        font-weight: 700;
    }

    /* Médico info */
    .medico-info {
        font-size: 0.85rem;
        color: #222;
        font-weight: 600;
        margin: 0.5rem 0 1rem 0;
    }

    .medico-info strong {
        color: #004080;
        font-weight: 700;
    }

    /* Tarjetas de exámenes */
    .examen-card {
        margin-bottom: 2rem;
        padding-bottom: 0.8rem;
        border-bottom: 2px solid #007BFF;
    }

    .examen-card:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .examen-nombre {
        font-weight: 700;
        font-size: 0.95rem;
        color: #004080;
        margin-bottom: 0.5rem;
    }

    /* Galería de imágenes */
    .img-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .img-card {
        flex: 1 1 calc(33.33% - 0.8rem);
        max-width: calc(33.33% - 0.8rem);
        border-radius: 0.4rem;
        overflow: hidden;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #ddd;
        padding: 0.5rem;
    }

    .img-card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }

    @media (max-width: 992px) {
        .img-card {
            flex: 1 1 calc(50% - 0.8rem);
            max-width: calc(50% - 0.8rem);
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
        height: 130px;
        display: block;
        border-radius: 0.3rem;
        object-fit: cover;
    }

    .img-description {
        font-size: 0.75rem;
        color: #555;
        margin-top: 0.4rem;
        text-align: center;
        line-height: 1.2;
        white-space: normal;
        overflow-wrap: break-word;
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

    [id^="modal-desc-"] {
        overflow-y: auto;
        white-space: normal;
        overflow-wrap: break-word;
        line-height: 1.4rem;
        font-size: 0.85rem;
    }

    .no-images {
        font-size: 0.85rem;
        color: #666;
        font-style: italic;
        text-align: center;
        padding: 1rem;
    }

    .btn-group-bottom {
        display: flex;
        justify-content: center;
        gap: 0.6rem;
        margin-top: 1.2rem;
    }

    .btn-group-bottom .btn {
        min-width: 120px;
        padding: 0.35rem 0.9rem;
        font-size: 0.8rem;
    }
</style>

<div class="custom-card">
    <!-- Header con título -->
    <div class="header-flex">
        <h2>Informe de Ultrasonido</h2>
    </div>

    <!-- Datos del paciente -->
    <h4>Datos del paciente</h4>
    <ul class="datos-paciente-flex">
        <li><strong>Nombre</strong> {{ $orden->paciente->nombre ?? 'N/A' }}</li>
        <li><strong>Apellidos</strong> {{ $orden->paciente->apellidos ?? 'N/A' }}</li>
        <li><strong>Identidad</strong> {{ $orden->paciente->identidad ?? 'N/A' }}</li>
        <li><strong>Género</strong> {{ $orden->paciente->genero ?? 'N/A' }}</li>
        <li><strong>Edad</strong> {{ $orden->paciente->edad ?? 'No especificada' }}</li>
        <li><strong>Fecha estudio</strong> {{ $orden->fecha ?? $orden->created_at->format('d/m/Y') }}</li>
    </ul>

    <!-- Médico responsable -->
    <h4>Médico Responsable</h4>
    <p class="medico-info">
        <strong>Médico</strong> {{ $orden->medico ? $orden->medico->nombre . ' ' . $orden->medico->apellidos : 'Sin asignar' }}
    </p>

    <!-- Exámenes realizados -->
    <h4>Ultrasonidos realizados</h4>
    
    @php
        $examenesPorNombre = $orden->imagenes->groupBy('examen_nombre');
    @endphp

    @forelse ($examenesPorNombre as $nombreExamen => $imagenes)
        <div class="examen-card">
            <div class="examen-nombre">
                {{ $nombreExamen ?? 'Ultrasonido sin especificar' }}
            </div>

            @if($imagenes && $imagenes->count() > 0)
                <div class="img-gallery" id="gallery-{{ $loop->index }}">
                    @foreach ($imagenes as $index => $imagen)
                        @if($imagen->ruta)
                            <div class="img-card" onclick="openModal({{ $loop->parent->index }}, {{ $index }})">
                                <img src="{{ asset('storage/' . $imagen->ruta) }}" 
                                     class="img-preview" 
                                     alt="{{ $imagen->descripcion ?? 'Imagen de ultrasonido' }}" />
                                <p class="img-description">
                                    {{ $imagen->descripcion ?? 'Sin descripción' }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Modal -->
                <div class="modal fade" id="modal-{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content position-relative bg-dark">
                            <span class="modal-nav modal-prev" onclick="prevImage({{ $loop->index }})">&lt;</span>
                            <span class="modal-nav modal-next" onclick="nextImage({{ $loop->index }})">&gt;</span>
                            <img id="modal-img-{{ $loop->index }}" src="" alt="">
                            <div id="modal-desc-{{ $loop->index }}" class="p-2 text-center text-white"></div>
                        </div>
                    </div>
                </div>
            @else
                <p class="no-images">Sin imágenes registradas</p>
            @endif
        </div>
    @empty
        <p class="no-images">No hay ultrasonidos registrados para esta orden.</p>
    @endforelse

    <!-- Botón volver -->
    <div class="btn-group-bottom">
        <a href="{{ route('ultrasonidos.index') }}" class="btn btn-success">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>
</div>

<script>
    const examsImages = {};
    @foreach ($examenesPorNombre as $index => $imagenes)
        examsImages[{{ $loop->index }}] = [
            @foreach ($imagenes as $imagen)
                { src: "{{ asset('storage/' . $imagen->ruta) }}", desc: "{{ $imagen->descripcion ?? '' }}" },
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
        currentIndex[examenId] = (currentIndex[examenId] + 1) % examsImages[examenId].length;
        document.getElementById(`modal-img-${examenId}`).src = examsImages[examenId][currentIndex[examenId]].src;
        document.getElementById(`modal-desc-${examenId}`).innerText = examsImages[examenId][currentIndex[examenId]].desc;
    }

    function prevImage(examenId) {
        currentIndex[examenId] = (currentIndex[examenId] - 1 + examsImages[examenId].length) % examsImages[examenId].length;
        document.getElementById(`modal-img-${examenId}`).src = examsImages[examenId][currentIndex[examenId]].src;
        document.getElementById(`modal-desc-${examenId}`).innerText = examsImages[examenId][currentIndex[examenId]].desc;
    }
</script>
@endsection
