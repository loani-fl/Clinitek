@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">




<style>
    body { background-color: #e8f4fc; margin:0; padding:0; min-height:100vh; position:relative; }

    .custom-card { 
        max-width:1200px; 
        background-color:#fff; 
        margin:40px auto 60px auto; 
        border-radius:1.5rem; 
        padding:2rem 2.5rem; 
        position:relative; 
        overflow:visible; 
        z-index:1; 
        box-shadow:0 4px 12px rgb(0 0 0 / 0.1); 
        background-image: url('/images/logo2.jpg'); 
        background-size:contain; 
        background-repeat:no-repeat; 
        background-position:center; 
        opacity:1; 
    }
    .custom-card::before { 
        content:""; 
        position:absolute; 
        inset:0; 
        background:rgba(255,255,255,0.85); 
        border-radius:1.5rem; 
        z-index:0; 
    }
    .custom-card > * { position:relative; z-index:1; }

  .section-title { 
    font-size: 2rem;          /* tamaño más grande */
    font-weight: 700; 
    color: #003366; 
    border-bottom: 3px solid #007BFF; 
    padding-bottom: 0.5rem;  /* aumenta espacio entre texto y línea azul */
    margin-bottom: 1.5rem;   /* separa de lo siguiente */
    user-select: none; 
    text-align: center;       /* centra el título */
}


    h4 { 
        color:#003366; 
        font-weight:700; 
        margin-top:1.5rem; 
        margin-bottom:1rem; 
        user-select:none; 
    }

    /* --- DATOS DEL PACIENTE --- */
    .patient-data-inline { 
        display:flex; 
        flex-wrap:wrap; 
        gap:2rem; 
        margin-bottom:2rem; /* 🔹 espacio extra abajo */
        margin-top:1.5rem; /* 🔹 espacio debajo del título */
        font-size:1.2rem; /* 🔹 letra más grande */
        font-weight:600; /* 🔹 más negrita */
        color:#000; 
    }
    .patient-data-inline span.label { 
        font-weight:800; 
        color:#004080; 
        margin-right:0.4rem; 
    }
    .patient-data-inline .underline-field {
        font-weight:700; 
        color:#222;
    }

    /* Subrayado para campos */
    .underline-field { 
        border-bottom:1px solid #004080; 
        padding:0 4px; 
    }
  .underline-field.thin { 
    border-bottom:0.5px solid #666; 
    font-weight:600; 
    display:inline-block;      /* 🔹 permite ajustar el ancho */
    width:110px;                /* 🔹 longitud de la línea, ajusta a tu gusto */
    text-align:center;         /* 🔹 opcional, centra el texto si hay */
}


    /* --- Fila médico y fecha --- */
    .form-row { 
        display:flex; 
        flex-wrap:wrap; 
        gap:1.5rem; 
        align-items:flex-end; 
        margin-bottom:1.5rem; 
        margin-top:2rem; /* 🔹 separación desde los datos del paciente */
    }
    .form-row > div { flex:1 1 250px; }
 #medico_analista_id {
    width: 80%; 
    font-size: 1rem; 
    padding: 0.35rem 0.5rem; /* un poco más alto */
    border: 1.5px solid #004080; /* borde más grueso y azul */
    border-radius: 0.25rem;
}


    /* --- Exámenes --- */
    .examen-card { margin-bottom:2.5rem; padding-bottom:1rem; border-bottom:3px solid #007BFF; }
    .examen-card:last-child { border-bottom:none; margin-bottom:0; padding-bottom:0; }
    .examen-nombre { font-weight:700; font-size:1.3rem; color:#004080; user-select:none; margin-bottom:0.5rem; display:flex; align-items:center; justify-content:space-between; }
    .examen-content { display:flex; flex-wrap:wrap; gap:1rem; }
    .image-description-block { display:flex; flex-direction:column; flex:0 0 calc(33.33% - 1rem); min-width:200px; border:1px solid #ddd; border-radius:0.5rem; padding:0.5rem; }
    .input-file-container, .textarea-container { width:100%; }
    .img-preview { margin-top:0.8rem; max-width:100%; max-height:150px; height:auto; border-radius:0.4rem; object-fit:contain; border:1px solid #ddd; margin-bottom:0.5rem; }

    /* Botones */
    .btn-group { display:flex !important; justify-content:center !important; gap:0.75rem; margin-top:1.5rem; align-items:center; }
    .btn-group .btn { min-width:140px; flex:0 0 auto; padding:0.4rem 1rem; font-size:0.95rem; }

    #mensaje-dinamico-container { width:100%; display:flex; flex-direction:column; align-items:center; gap:0.5rem; margin-bottom:1rem; }

    @media(max-width:991px) { 
        .examen-content { flex-direction:column; } 
        .image-description-block { flex:1 1 100%; } 
    }

    .fecha-field {
    font-size: 1.2rem;       /* igual que patient-data-inline */
    font-weight: 700;        /* negrita */
    color: #222;             /* igual que los datos del paciente */
    display: inline-block;   /* para poder controlar el ancho de la línea */
    border-bottom: 0.5px solid #666; /* línea fina debajo */
    width: 110px;            /* ajusta longitud de la línea */
    text-align: center;      /* centra el texto si quieres */
}

.fecha-label {
    font-size: 1.2rem;    /* igual que patient-data-inline */
    font-weight: 800;     /* negrita */
    color: #004080;       /* azul como los labels de paciente */
}
.medico-label {
    font-size: 1.2rem;    /* igual que patient-data-inline */
    font-weight: 800;     /* negrita */
    color: #004080;       /* azul como los labels de paciente */
}

.examen-title {
    margin-top: 3rem;       /* separación desde el select */
    text-align: center;      /* centrado */
    color: #000;             /* negro */
    font-weight: 700;        /* negrita */
    font-size: 2rem;       /* tamaño similar a los títulos */
}



</style>

<div class="custom-card">
    <h2 class="section-title">Análisis de orden rayos x</h2>
    <div id="mensaje-dinamico-container"></div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Datos del paciente -->
    <h4>Datos del paciente:</h4>
    <div class="patient-data-inline">
        <div><span class="label">Nombre:</span> <span class="underline-field">{{ $orden->paciente->nombre ?? $orden->nombres ?? 'N/A' }}</span></div>
        <div><span class="label">Apellidos:</span> <span class="underline-field">{{ $orden->paciente->apellidos ?? $orden->apellidos ?? 'N/A' }}</span></div>
        <div><span class="label">Identidad:</span> <span class="underline-field">{{ $orden->paciente->identidad ?? $orden->identidad ?? 'N/A' }}</span></div>
        <div><span class="label">Género:</span> <span class="underline-field">{{ $orden->paciente->genero ?? $orden->genero ?? 'N/A' }}</span></div>
    </div>

    <!-- Formulario de análisis -->
    <form action="{{ route('rayosx.storeAnalisis', $orden->id) }}" method="POST" enctype="multipart/form-data" id="form-analisis">
        @csrf

        <!-- Fila médico + fecha -->
        <div class="form-row">
            <div>
                <label for="medico_analista_id" class="form-label medico-label">Médico analista:</label>

                <select name="medico_analista_id" id="medico_analista_id" class="form-select @error('medico_analista_id') is-invalid @enderror">
                    <option value="">Seleccionar Médico Analista (Radiológos)</option>
                    @foreach ($medicosRadiologos as $medico)
                        <option value="{{ $medico->id }}" {{ (old('medico_analista_id', $orden->medico_analista_id ?? '') == $medico->id) ? 'selected' : '' }}>
                            {{ $medico->nombre }} {{ $medico->apellidos }}
                        </option>
                    @endforeach
                </select>
                @error('medico_analista_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
   <div>
    <label for="fecha" class="form-label fecha-label">Fecha:</label>
    <div class="fecha-field">{{ $orden->fecha ?? 'N/A' }}</div>
</div>

        </div>
       <h4 class="examen-title">Rayos x ordenados </h4>

        @forelse ($orden->examenes as $examen)
            @php $bloquesImagenes = $examen->imagenes ?? collect(); @endphp
            <div class="examen-card">
                <div class="examen-nombre">
                    {{ $examenesNombres[$examen->examen_codigo] ?? $examen->examen_codigo }}
                    <button type="button" class="btn btn-sm btn-success" id="btn-agregar-{{ $examen->id }}" onclick="addImageBlock({{ $examen->id }})">
                        <i class="bi bi-plus-circle"></i> Agregar imagen
                    </button>
                </div>
                <div class="examen-content" id="examen-content-{{ $examen->id }}">
                    @foreach($bloquesImagenes as $i => $bloque)
                        <div class="image-description-block" data-block-index="{{ $i }}">
                            <div class="preview-container text-center mb-2">
                                <img src="{{ asset('storage/'.$bloque->ruta) }}" class="img-preview" alt="Imagen existente">
                            </div>
                            <div class="textarea-container mt-2">
                                <label class="form-label mb-1">Descripción:</label>
                                <textarea name="descripciones[{{ $examen->id }}][]" class="form-control descripcion-textarea" rows="3" maxlength="200">{{ $bloque->descripcion }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p>No hay exámenes registrados para esta orden.</p>
        @endforelse

        <div class="btn-group mt-4">

            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save"></i> Guardar análisis</button>
            <a href="{{ route('rayosx.index') }}" class="btn btn-success btn-sm">
    <i class="bi bi-arrow-left-circle"></i> volver
</a>

        </div>
    </form>
</div>

<script>
// Mostrar mensajes dinámicos con scroll a la página
function mostrarMensaje(mensaje, tipo='error') {
    const container = document.getElementById('mensaje-dinamico-container');
    container.innerHTML = '';
    const div = document.createElement('div');
    div.className = 'alert-dinamico ' + tipo;
    div.textContent = mensaje;
    div.style.display = 'inline-block';
    div.style.textAlign = 'center';
    div.style.padding = '8px 12px';
    div.style.borderRadius = '6px';
    div.style.margin = '10px auto';
    div.style.maxWidth = 'fit-content';
    div.style.transition = 'opacity 0.4s, transform 0.4s';
    div.style.opacity = '0';
    div.style.fontWeight = '600';
    div.style.fontSize = '0.95rem';
    div.style.whiteSpace = 'pre-line';
    if(tipo === 'error'){ 
        div.style.backgroundColor = '#f8d7da'; 
        div.style.color = '#721c24'; 
        div.style.border = '1px solid #f5c6cb'; 
    } else if(tipo === 'exito'){ 
        div.style.backgroundColor = '#d4edda'; 
        div.style.color = '#155724'; 
        div.style.border = '1px solid #c3e6cb'; 
    } else if(tipo === 'info'){ 
        div.style.backgroundColor = '#d1ecf1'; 
        div.style.color = '#0c5460'; 
        div.style.border = '1px solid #bee5eb'; 
    }
    container.appendChild(div);

    // Scroll a toda la página donde se muestra el mensaje
    const rect = div.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    window.scrollTo({ top: rect.top + scrollTop - 100, behavior: 'smooth' });

    setTimeout(() => { div.style.opacity = '1'; div.style.transform = 'translateY(0)'; }, 50);
    setTimeout(() => { div.style.opacity = '0'; setTimeout(() => { if(div.parentNode) container.removeChild(div); }, 400); }, 4000);
}

// Previsualizar imagen con mensaje de error dentro del bloque
function previewImage(event, examenIdIndex) {
    const input = event.target;
    const preview = document.getElementById('preview_'+examenIdIndex);
    const bloque = input.closest('.image-description-block');

    // Eliminar mensaje de error previo
    let msgError = bloque.querySelector('.mensaje-error-imagen');
    if(msgError) msgError.remove();

    if(input.files && input.files[0]){
        const file = input.files[0];
        const ext = file.name.split('.').pop().toLowerCase();
        if(!['jpg','jpeg','png'].includes(ext)){
            // Crear mensaje dentro del bloque
            msgError = document.createElement('div');
            msgError.className = 'mensaje-error-imagen';
            msgError.textContent = 'Solo se permiten imágenes JPG, JPEG o PNG.';
            msgError.style.color = 'red';
            msgError.style.fontSize = '0.85rem';
            msgError.style.marginBottom = '5px';
            bloque.insertBefore(msgError, bloque.firstChild);

            input.value = '';
            preview.src='#';
            preview.style.display='none';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e){ 
            preview.src=e.target.result; 
            preview.style.display='block'; 
        }
        reader.readAsDataURL(file);
    } else { 
        preview.src='#'; 
        preview.style.display='none'; 
    }
}

// Agregar bloque de imagen con X
function addImageBlock(examenId){
    const container = document.getElementById('examen-content-'+examenId);
    if(!container) return;
    const blocks = container.querySelectorAll('.image-description-block');
    if(blocks.length >= 3){ 
        document.getElementById('btn-agregar-'+examenId).disabled=true; 
        mostrarMensaje('Máximo 3 imágenes por examen.', 'info'); 
        return; 
    }

    const index = blocks.length;
    const blockDiv = document.createElement('div'); 
    blockDiv.className='image-description-block'; 
    blockDiv.setAttribute('data-block-index', index);
    blockDiv.style.position='relative';

    const removeBtn = document.createElement('button');
    removeBtn.type='button';
    removeBtn.className='remove-block';
    removeBtn.style.position='absolute';
    removeBtn.style.top='5px';
    removeBtn.style.right='5px';
    removeBtn.style.color = 'red';
    removeBtn.style.fontWeight = '700';
    removeBtn.style.fontSize = '1.2rem';
    removeBtn.style.background = 'transparent';
    removeBtn.style.border = 'none';
    removeBtn.style.cursor = 'pointer';
    removeBtn.innerHTML='X';
    removeBtn.onclick = function(){ removeBlock(removeBtn); };
    blockDiv.appendChild(removeBtn);

    const previewDiv=document.createElement('div'); 
    previewDiv.className='preview-container text-center mb-2';
    const imgPreview=document.createElement('img'); 
    imgPreview.id=`preview_${examenId}_${index}`; 
    imgPreview.className='img-preview'; 
    imgPreview.style.display='none'; 
    imgPreview.alt='Vista previa de imagen';

    const inputFileDiv=document.createElement('div'); 
    inputFileDiv.className='input-file-container mt-2';
    const inputFile=document.createElement('input'); 
    inputFile.type='file'; 
    inputFile.name=`imagenes[${examenId}][]`; 
    inputFile.id=`imagen_${examenId}_${index}`; 
    inputFile.className='form-control form-control-sm'; 
    inputFile.accept='.jpg,.jpeg,.png'; 
    inputFile.addEventListener('change', function(e){ previewImage(e, `${examenId}_${index}`); });
    inputFileDiv.appendChild(inputFile);

    previewDiv.appendChild(imgPreview); 
    previewDiv.appendChild(inputFileDiv);

    const textareaDiv=document.createElement('div'); 
    textareaDiv.className='textarea-container mt-2';
    const textarea=document.createElement('textarea'); 
    textarea.name=`descripciones[${examenId}][]`; 
    textarea.className='form-control descripcion-textarea'; 
    textarea.rows=3; 
    textarea.maxLength=200;
    textareaDiv.appendChild(textarea);

    blockDiv.appendChild(previewDiv);
    blockDiv.appendChild(textareaDiv);
    container.appendChild(blockDiv);

    if(container.querySelectorAll('.image-description-block').length >= 3) 
        document.getElementById('btn-agregar-'+examenId).disabled=true;
}

// Eliminar bloque y actualizar límite
function removeBlock(button){
    const block = button.closest('.image-description-block');
    if(block){
        const examenContent = block.closest('.examen-content');
        block.remove();
        const addBtn = document.getElementById('btn-agregar-'+examenContent.id.replace('examen-content-',''));
        if(addBtn && examenContent.querySelectorAll('.image-description-block').length < 3){
            addBtn.disabled = false;
        }
    }
}

// Inicializar bloques automáticamente al cargar la página
document.addEventListener('DOMContentLoaded', function(){
    const examenes=@json($orden->examenes->pluck('id'));
    examenes.forEach(examenId=>{
        const container=document.getElementById('examen-content-'+examenId);
        if(container){
            const bloquesExistentes = container.querySelectorAll('.image-description-block');
            if(bloquesExistentes.length === 0){
                addImageBlock(examenId);
            }
            if(container.querySelectorAll('.image-description-block').length >= 3){
                document.getElementById('btn-agregar-'+examenId).disabled=true;
            }
        }
    });
});

// Validaciones al enviar formulario (manteniendo mensajes globales)
document.getElementById('form-analisis').addEventListener('submit', function(event){
    const medicoAnalista = this.querySelector('#medico_analista_id');
    if(!medicoAnalista.value){ 
        mostrarMensaje('El médico analista es obligatorio.', 'error'); 
        medicoAnalista.focus();
        event.preventDefault();
        return;
    }

    const examenesCards = this.querySelectorAll('.examen-card');

    for(let i=0; i<examenesCards.length; i++){
        const card = examenesCards[i];
        const nombreExamen = card.querySelector('.examen-nombre').childNodes[0].textContent.trim();
        const bloques = card.querySelectorAll('.image-description-block');

        if(bloques.length === 0){
            mostrarMensaje(`Debes agregar al menos un bloque para el examen "${nombreExamen}".`, 'error');
            event.preventDefault();
            return;
        }

        for(let j=0; j<bloques.length; j++){
            const bloque = bloques[j];
            const fileInput = bloque.querySelector('input[type="file"]');
            const textarea = bloque.querySelector('textarea');

            if(!fileInput || fileInput.files.length===0){
                mostrarMensaje(`Falta imagen en el bloque ${j+1} del examen "${nombreExamen}".`, 'error'); 
                if(fileInput) fileInput.focus();
                event.preventDefault();
                return;
            }

            const ext = fileInput.files[0].name.split('.').pop().toLowerCase();
            if(!['jpg','jpeg','png'].includes(ext)){
                // Mensaje dentro del bloque ya se maneja en previewImage
                fileInput.focus();
                event.preventDefault();
                return;
            }

            if(!textarea.value.trim()){
                mostrarMensaje(`Falta descripción en el bloque ${j+1} del examen "${nombreExamen}".`, 'error'); 
                textarea.focus();
                event.preventDefault();
                return;
            }
        }
    }
});
</script>



@endsection