@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #e8f4fc;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        position: relative;
    }

    .custom-card {
        max-width: 1000px;
        background-color: #fff;
        margin: 40px auto 60px auto;
        border-radius: 1.5rem;
        padding: 2rem 2.5rem;
        position: relative;
        overflow: visible;
        z-index: 1;
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);

        background-image: url('/images/logo2.jpg');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 1;
    }

    .custom-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.85);
        border-radius: 1.5rem;
        z-index: 0;
    }

    .custom-card > * {
        position: relative;
        z-index: 1;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #003366;
        border-bottom: 3px solid #007BFF;
        padding-bottom: 0.3rem;
        margin-bottom: 1rem;
        user-select: none;
    }

    .patient-data-grid {
        margin-bottom: 2rem;
    }

    .patient-data-grid strong {
        color: rgb(3, 12, 22);
        font-weight: 600;
    }

    .underline-field {
        border-bottom: 1px solid #000;
        min-height: 1.4rem;
        line-height: 1.4rem;
        padding-left: 6px;
        padding-right: 6px;
        font-size: 1rem;
        flex: 1;
        user-select: none;
        color: #222;
    }

    .patient-data-grid .row > div {
        display: flex;
        align-items: center;
        margin-bottom: 0.8rem;
    }

    label {
        font-weight: 600;
        color: #222;
    }

    .form-control, .form-select {
        border-radius: 0.375rem;
        box-shadow: none;
        border: 1px solid #ced4da;
        font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007BFF;
        box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 0.25);
    }

    .examen-card {
        margin-bottom: 2.5rem;
        padding-bottom: 1rem;
        /* Línea azul inferior */
        border-bottom: 3px solid #007BFF;
    }

    /* Quitamos la línea al último examen para que no quede extra */
    .examen-card:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .examen-nombre {
        font-weight: 700;
        font-size: 1.3rem;
        color: #004080;
        user-select: none;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .examen-nombre button {
        font-size: 0.9rem;
        padding: 0.25rem 0.75rem;
        min-width: auto;
    }

    .examen-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .image-description-block {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        align-items: flex-start;
    }

    .input-file-container {
        flex: 0 0 280px;
        display: flex;
        flex-direction: column;
    }

    .preview-container {
        flex: 0 0 160px;
        text-align: center;
    }

    .img-preview {
        margin-top: 0.8rem;
        max-width: 150px;
        max-height: 110px;
        border-radius: 0.4rem;
        object-fit: contain;
        border: 1px solid #ddd;
    }

    .textarea-container {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    textarea.form-control {
        font-size: 0.9rem;
        color: rgba(34, 34, 34, 0.6);
        min-height: 60px;
        max-height: 100px;
        resize: vertical;
    }

    .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .btn-group {
        display: flex !important;
        justify-content: center !important;
        gap: 0.75rem;
        margin-top: 1.5rem;
        align-items: center;
    }

    .btn-group .btn {
        min-width: 140px;
        max-width: auto;
        flex: 0 0 auto;
        padding: 0.40rem 1rem;
        font-size: 0.95rem;
    }

    @media (max-width: 575px) {
        .custom-card {
            padding: 1rem 1rem;
            margin: 20px 1rem 40px 1rem;
        }
        .examen-content {
            flex-direction: column !important;
        }
        .input-file-container,
        .preview-container,
        .textarea-container {
            min-width: 100% !important;
        }
        .img-preview {
            max-width: 100%;
            height: auto;
        }
    }
</style>

<div class="custom-card">

    <h2 class="section-title text-center mb-4">Área de radiología</h2>

    {{-- Datos del paciente --}}
    @php
        $paciente = $orden->pacienteClinica ?? $orden->pacienteRayosX;
    @endphp
    <div class="section-title">Datos del Paciente</div>
    <div class="patient-data-grid">
        <div class="row">
            <div class="col-md-8 d-flex align-items-center">
                <strong class="me-2">Nombres - Apellidos:</strong>
                <div class="underline-field no-select">
                    {{ $paciente->nombre ?? 'N/A' }} {{ $paciente->apellidos ?? '' }}
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <strong class="me-2">Fecha:</strong>
                <div class="underline-field no-select">
                    {{ $orden->fecha ? \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') : 'N/A' }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 d-flex align-items-center">
                <strong class="me-2">Identidad:</strong>
                <div class="underline-field no-select">
                    {{ $paciente->identidad ?? 'N/A' }}
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <strong class="me-2">Edad:</strong>
                <div class="underline-field no-select">
                    @if(!empty($paciente->fecha_nacimiento))
                        {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-center">
                <strong class="me-2">Teléfono:</strong>
                <div class="underline-field no-select">
                    {{ $paciente->telefono ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario análisis --}}
    <form method="POST" action="{{ route('rayosx.guardarAnalisis', $orden->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Médico Analista --}}
        <div class="patient-data-grid mb-4">
            <div class="row">
                <div class="col-md-3 d-flex align-items-center">
                    <strong>Médico analista:</strong>
                </div>
                <div class="col-md-9">
                    <select
                        name="medico_analista_id"
                        class="form-select form-control-sm @error('medico_analista_id') is-invalid @enderror"
                        style="max-width: 280px;"
                        required
                    >
                        <option value="">Seleccione médico analista</option>
                        @foreach($medicosRadiologos as $medico)
                            <option value="{{ $medico->id }}"
                                {{ old('medico_analista_id', $orden->medico_analista_id ?? '') == $medico->id ? 'selected' : '' }}>
                                {{ $medico->nombre }} {{ $medico->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    @error('medico_analista_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Exámenes --}}
        <div class="section-title">Rayos x solicitados</div>

        @foreach($orden->examenes as $examen)
            <div class="examen-card">

                <div class="examen-nombre">
                    <span>{{ $examenes[$examen->examen_codigo] ?? ucfirst(str_replace('_', ' ', $examen->examen_codigo)) }}</span>
                    <button type="button" class="btn btn-sm btn-success" onclick="addImageBlock({{ $examen->id }})">
                        <i class="bi bi-plus-circle"></i> Agregar imagen
                    </button>
                </div>

                <div class="examen-content" id="examen-content-{{ $examen->id }}">

                    <div class="image-description-block" data-block-index="0">
                        <div class="input-file-container">
                            <label for="imagen_{{ $examen->id }}_0" class="form-label mb-1">Subir imagen del examen (máx. 5MB):</label>
                            <input
                                type="file"
                                name="examenes[{{ $examen->id }}][imagenes][]"
                                id="imagen_{{ $examen->id }}_0"
                                class="form-control form-control-sm @error('examenes.' . $examen->id . '.imagenes') is-invalid @enderror"
                                accept="image/*"
                                onchange="previewImage(event, '{{ $examen->id }}_0')"
                            >
                        </div>

                        <div class="preview-container">
                            @if($examen->imagen_path)
                                <img
                                    id="preview_{{ $examen->id }}_0"
                                    src="{{ asset('storage/' . $examen->imagen_path) }}"
                                    alt="Imagen de examen {{ $examenes[$examen->examen_codigo] ?? $examen->examen_codigo }}"
                                    class="img-preview"
                                >
                            @else
                                <img
                                    id="preview_{{ $examen->id }}_0"
                                    src="#"
                                    alt="Vista previa de imagen"
                                    class="img-preview"
                                    style="display:none;"
                                >
                            @endif
                        </div>

                        <div class="textarea-container">
                            <label for="descripcion_{{ $examen->id }}_0" class="form-label mb-1">Descripción:</label>
                            <textarea
                                name="examenes[{{ $examen->id }}][descripciones][]"
                                id="descripcion_{{ $examen->id }}_0"
                                class="form-control form-control-sm @error('examenes.' . $examen->id . '.descripciones') is-invalid @enderror"
                                rows="3"
                            >{{ old("examenes.$examen->id.descripciones.0", $examen->descripcion ?? '') }}</textarea>
                        </div>
                    </div>

                </div>

            </div>
        @endforeach

        <div class="btn-group">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-save"></i> Guardar Análisis
            </button>

            <a href="{{ route('rayosx.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Regresar
            </a>
        </div>
    </form>
</div>

<script>
    function previewImage(event, examenIdIndex) {
        const input = event.target;
        const preview = document.getElementById('preview_' + examenIdIndex);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }

    function addImageBlock(examenId) {
        const container = document.getElementById('examen-content-' + examenId);
        if (!container) return;

        const blocks = container.querySelectorAll('.image-description-block');
        const index = blocks.length;

        const blockDiv = document.createElement('div');
        blockDiv.className = 'image-description-block';
        blockDiv.setAttribute('data-block-index', index);

        const inputFileDiv = document.createElement('div');
        inputFileDiv.className = 'input-file-container';

        const labelFile = document.createElement('label');
        labelFile.className = 'form-label mb-1';
        labelFile.setAttribute('for', `imagen_${examenId}_${index}`);
        labelFile.textContent = 'Subir imagen del examen (máx. 5MB):';

        const inputFile = document.createElement('input');
        inputFile.type = 'file';
        inputFile.name = `examenes[${examenId}][imagenes][]`;
        inputFile.id = `imagen_${examenId}_${index}`;
        inputFile.className = 'form-control form-control-sm';
        inputFile.accept = 'image/*';
        inputFile.setAttribute('onchange', `previewImage(event, '${examenId}_${index}')`);

        inputFileDiv.appendChild(labelFile);
        inputFileDiv.appendChild(inputFile);

        const previewDiv = document.createElement('div');
        previewDiv.className = 'preview-container';

        const imgPreview = document.createElement('img');
        imgPreview.id = `preview_${examenId}_${index}`;
        imgPreview.className = 'img-preview';
        imgPreview.style.display = 'none';
        imgPreview.alt = 'Vista previa de imagen';

        previewDiv.appendChild(imgPreview);

        const textareaDiv = document.createElement('div');
        textareaDiv.className = 'textarea-container';

        const labelDesc = document.createElement('label');
        labelDesc.className = 'form-label mb-1';
        labelDesc.setAttribute('for', `descripcion_${examenId}_${index}`);
        labelDesc.textContent = 'Descripción:';

        const textarea = document.createElement('textarea');
        textarea.name = `examenes[${examenId}][descripciones][]`;
        textarea.id = `descripcion_${examenId}_${index}`;
        textarea.className = 'form-control form-control-sm';
        textarea.rows = 3;

        textareaDiv.appendChild(labelDesc);
        textareaDiv.appendChild(textarea);

        blockDiv.appendChild(inputFileDiv);
        blockDiv.appendChild(previewDiv);
        blockDiv.appendChild(textareaDiv);

        container.appendChild(blockDiv);
    }
</script>
@endsection
