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

    /* Imagen de fondo dentro de la card */
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

    /* Capa blanca semitransparente para mejorar contraste */
    .custom-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.85);
        border-radius: 1.5rem;
        z-index: 0;
    }

    .custom-card > * {
        position: relative; /* Para estar sobre la capa ::before */
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
        margin-bottom: 2rem;
    }

    .img-preview {
        margin-top: 0.8rem;
        max-width: 320px;
        max-height: 220px;
        border-radius: 0.4rem;
        object-fit: contain;
        border: 1px solid #ddd;
    }

    textarea.form-control {
        font-size: 0.9rem;
        color: rgba(34, 34, 34, 0.6);
        min-height: 60px;
        max-height: 100px;
        resize: vertical;
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


    .examen-card.d-flex {
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .examen-nombre {
        flex: 0 0 150px;
        font-weight: 700;
        font-size: 1.1rem;
        color: #004080;
    }

    .input-file-container {
        flex: 0 0 240px;
        display: flex;
        flex-direction: column;
    }

    .textarea-container {
        flex: 1 1 300px;
        display: flex;
        flex-direction: column;
    }

    .preview-container {
        flex: 0 0 160px;
    }

    .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    @media (max-width: 575px) {
        .custom-card {
            padding: 1rem 1rem;
            margin: 20px 1rem 40px 1rem;
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
            <div class="examen-card d-flex">

                <div class="examen-nombre">
                    {{ $examenes[$examen->examen] ?? $examen->examen }}
                </div>

                <div class="input-file-container">
                    <label for="imagen_{{ $examen->id }}" class="form-label mb-1">Resultado (máx. 5MB):</label>
                    <input
                        type="file"
                        name="examenes[{{ $examen->id }}][imagen]"
                        id="imagen_{{ $examen->id }}"
                        class="form-control form-control-sm @error('examenes.' . $examen->id . '.imagen') is-invalid @enderror"
                        accept="image/*"
                        onchange="previewImage(event, {{ $examen->id }})"
                    >
                    @error('examenes.' . $examen->id . '.imagen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="textarea-container">
                    <label for="descripcion_{{ $examen->id }}" class="form-label mb-1">Descripción:</label>
                    <textarea
                        name="examenes[{{ $examen->id }}][descripcion]"
                        id="descripcion_{{ $examen->id }}"
                        class="form-control form-control-sm @error('examenes.' . $examen->id . '.descripcion') is-invalid @enderror"
                        rows="2"
                    >{{ old("examenes.$examen->id.descripcion", $examen->descripcion ?? '') }}</textarea>
                    @error('examenes.' . $examen->id . '.descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="preview-container">
                    @if($examen->imagen)
                        <img
                            id="preview_{{ $examen->id }}"
                            src="{{ asset('storage/' . $examen->imagen) }}"
                            alt="Imagen de examen {{ $examenes[$examen->examen] ?? $examen->examen }}"
                            class="img-preview"
                            style="max-width: 150px; max-height: 110px;"
                        >
                    @else
                        <img
                            id="preview_{{ $examen->id }}"
                            src="#"
                            alt="Vista previa de imagen"
                            class="img-preview"
                            style="display:none; max-width: 150px; max-height: 110px;"
                        >
                    @endif
                </div>

            </div>
        @endforeach

        {{-- Botones --}}
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
    // Mostrar preview de imagen al seleccionar
    function previewImage(event, examenId) {
        const input = event.target;
        const preview = document.getElementById('preview_' + examenId);
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
</script>
@endsection
