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

    h4 {
        color: #003366;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        user-select: none;
    }

    /* Flex container para Nombre e Identidad en línea */
    .datos-paciente-flex {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        padding-left: 0;
        list-style: none;
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
        /* Línea azul inferior */
        border-bottom: 3px solid #007BFF;
    }

    /* Quitamos la línea al último examen */
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

    .examen-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .input-file-container {
        flex: 0 0 100%;
        display: flex;
        flex-direction: column;
        margin-top: 0.8rem;
    }

    .textarea-container {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .textarea-container textarea {
        font-size: 0.9rem;
        color: rgba(34, 34, 34, 0.6);
        min-height: 60px;
        max-height: 100px;
        resize: vertical;
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        transition: border-color 0.2s ease-in-out;
    }

    .textarea-container textarea:focus {
        border-color: #007BFF;
        box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 0.25);
        outline: none;
    }

    .img-preview {
        margin-top: 0.8rem;
        max-width: 150px;
        max-height: 110px;
        border-radius: 0.4rem;
        object-fit: contain;
        border: 1px solid #ddd;
    }

    .row > .col-md-3 {
        margin-bottom: 1rem;
    }

    .form-check {
        margin-top: 0.5rem;
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
    <h2 class="section-title">Análisis de drden rayos x</h2>

    {{-- Datos del paciente --}}
    <h4>Datos del paciente</h4>
   <ul class="datos-paciente-flex">
    <li><strong>Nombre:</strong> {{ $orden->paciente->nombre ?? $orden->nombres ?? 'N/A' }}</li>
    <li><strong>Apellidos:</strong> {{ $orden->paciente->apellidos ?? $orden->apellidos ?? 'N/A' }}</li>
    <li><strong>Identidad:</strong> {{ $orden->paciente->identidad ?? $orden->identidad ?? 'N/A' }}</li>
    <li><strong>Género:</strong> {{ $orden->paciente->genero ?? $orden->genero ?? 'N/A' }}</li>
</ul>

    <hr>

    {{-- Formulario --}}
    <form action="{{ route('rayosx.storeAnalisis', $orden->id) }}" method="POST" enctype="multipart/form-data" id="form-analisis">
        @csrf

        <div class="mb-3">
            <label for="medico_analista_id" class="form-label"><strong>Médico analista:</strong></label>
            <select name="medico_analista_id" id="medico_analista_id" class="form-select @error('medico_analista_id') is-invalid @enderror" required>
                <option value="">Seleccionar Médico Analista (Radiológos)</option>
                @foreach ($medicosRadiologos as $medico)
                    <option value="{{ $medico->id }}"
                        {{ (old('medico_analista_id', $orden->medico_analista_id ?? '') == $medico->id) ? 'selected' : '' }}>
                        {{ $medico->nombre }} {{ $medico->apellidos }}
                    </option>
                @endforeach
            </select>
            @error('medico_analista_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <h4>Exámenes realizados</h4>
        @forelse ($orden->examenes as $examen)
            <div class="examen-card">
                <div class="examen-nombre">
                    {{ $examenesNombres[$examen->examen_codigo] ?? $examen->examen_codigo }}
                </div>

                <div class="examen-content">
                    {{-- Imágenes existentes con opción de eliminar --}}
                    @if($examen->imagenes && $examen->imagenes->count() > 0)
                        <div class="row">
                            @foreach ($examen->imagenes as $imagen)
                                <div class="col-md-3 mb-2 text-center">
                                <img src="{{ asset('storage/' . $imagen->ruta) }}" alt="Imagen examen" class="img-preview" />
                                    <div class="form-check mt-1">
                                        <input type="checkbox" name="eliminar_imagenes[]" value="{{ $imagen->id }}" class="form-check-input" id="eliminar_img_{{ $imagen->id }}">
                                        <label for="eliminar_img_{{ $imagen->id }}" class="form-check-label">Eliminar</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Subida de nuevas imágenes --}}
                    <div class="input-file-container">
                        <label>Agregar imágenes:</label>
                        <input type="file" name="imagenes[{{ $examen->id }}][]" class="form-control form-control-sm" accept="image/*" multiple>
                    </div>

                    {{-- Campo descripción --}}
                    <div class="textarea-container mt-2">
                        <label>Descripción:</label>
                        <textarea name="descripciones[{{ $examen->id }}]" class="form-control" rows="3">{{ old("descripciones.{$examen->id}", $examen->descripcion) }}</textarea>
                    </div>
                </div>
            </div>
        @empty
            <p>No hay exámenes registrados para esta orden.</p>
        @endforelse

        {{-- Botones --}}
        <div class="btn-group mt-4">
            <a href="{{ route('rayosx.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Regresar
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-save"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('form-analisis').addEventListener('submit', function(event) {
        const form = this;
        const examenes = form.querySelectorAll('.examen-card');
        const errorId = 'form-error-message';
        let errorDiv = document.getElementById(errorId);

        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = errorId;
            errorDiv.style.color = '#b22222';
            errorDiv.style.fontWeight = '700';
            errorDiv.style.marginBottom = '1rem';
            errorDiv.style.padding = '0.75rem 1rem';
            errorDiv.style.border = '2px solid #b22222';
            errorDiv.style.borderRadius = '0.5rem';
            errorDiv.style.backgroundColor = '#ffe6e6';
            errorDiv.style.userSelect = 'none';
            form.prepend(errorDiv);
        }
        errorDiv.textContent = '';
        errorDiv.style.display = 'none';

        let todosValidos = true;

        examenes.forEach(examenCard => {
            const inputFile = examenCard.querySelector('input[type="file"]');
            const textarea = examenCard.querySelector('textarea');

            // Verificamos imágenes seleccionadas y descripción no vacía
            const tieneImagen = inputFile && inputFile.files.length > 0;
            const descripcionValida = textarea && textarea.value.trim() !== '';

            // Además puede haber imágenes ya existentes que no se eliminaron
            const imagenesPrevias = examenCard.querySelectorAll('img.img-preview');
            let imagenesPreviasNoEliminadas = false;
            if (imagenesPrevias.length > 0) {
                let eliminados = 0;
                const checkboxesEliminar = examenCard.querySelectorAll('input[type="checkbox"][name="eliminar_imagenes[]"]');
                checkboxesEliminar.forEach(cb => {
                    if (cb.checked) eliminados++;
                });
                if ((imagenesPrevias.length - eliminados) > 0) {
                    imagenesPreviasNoEliminadas = true;
                }
            }

            // Validamos que haya al menos una imagen (nueva o previa no eliminada) y descripción
            if (!((tieneImagen || imagenesPreviasNoEliminadas) && descripcionValida)) {
                todosValidos = false;
            }
        });

        if (!todosValidos) {
            event.preventDefault();
            errorDiv.textContent = 'Cada examen debe tener al menos una imagen (existente o nueva) y una descripción no vacía para guardar.';
            errorDiv.style.display = 'block';
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endsection