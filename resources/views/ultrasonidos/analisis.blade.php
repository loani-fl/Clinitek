@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { background-color: #e8f4fc; margin:0; padding:0; min-height:100vh; position:relative; }

    .custom-card { 
        max-width:900px; 
        background-color:#fff; 
        margin:20px auto 40px auto; 
        border-radius:1rem; 
        padding:1.5rem 2rem; 
        position:relative; 
        overflow:visible; 
        z-index:1; 
        box-shadow:0 3px 10px rgb(0 0 0 / 0.08); 
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
        background:rgba(255,255,255,0.9); 
        border-radius:1rem; 
        z-index:0; 
    }
    .custom-card > * { position:relative; z-index:1; }

    .section-title { 
        font-size: 1.3rem;
        font-weight: 700; 
        color: #003366; 
        border-bottom: 2px solid #007BFF; 
        padding-bottom: 0.4rem;
        margin-bottom: 1rem;
        user-select: none; 
        text-align: center;
    }

    h4 { 
        color:#003366; 
        font-weight:700; 
        margin-top:1rem; 
        margin-bottom:0.7rem; 
        user-select:none;
        font-size: 0.95rem;
    }

    /* --- DATOS DEL PACIENTE --- */
    .patient-data-inline { 
        display:flex; 
        flex-wrap:wrap; 
        gap:1.5rem; 
        margin-bottom:1.5rem;
        margin-top:1rem;
        font-size:0.85rem;
        font-weight:600;
        color:#000; 
    }
    .patient-data-inline span.label { 
        font-weight:700; 
        color:#004080; 
        margin-right:0.3rem; 
    }
    .patient-data-inline .underline-field {
        font-weight:600; 
        color:#222;
    }

    /* Subrayado para campos */
    .underline-field { 
        border-bottom:1px solid #004080; 
        padding:0 3px; 
    }
    .underline-field.thin { 
        border-bottom:0.5px solid #666; 
        font-weight:600; 
        display:inline-block;
        width:100px;
        text-align:center;
    }

    /* --- Fila médico y fecha --- */
    .form-row { 
        display:flex; 
        flex-wrap:wrap; 
        gap:1.2rem; 
        align-items:flex-end; 
        margin-bottom:1.2rem; 
        margin-top:1.5rem;
    }
    .form-row > div { flex:1 1 220px; }
    
    #medico_id {
        width: 80%; 
        font-size: 0.85rem;
        padding: 0.3rem 0.5rem;
        border: 1.5px solid #004080;
        border-radius: 0.25rem;
    }

    .fecha-field {
        font-size: 0.85rem;
        font-weight: 700;
        color: #222;
        display: inline-block;
        border-bottom: 0.5px solid #666;
        width: 100px;
        text-align: center;
    }

    .fecha-label, .medico-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #004080;
    }

    /* --- Exámenes (Ultrasonidos) --- */
    .examen-card { 
        margin-bottom:2rem; 
        padding-bottom:0.8rem; 
        border-bottom:2px solid #007BFF; 
    }
    .examen-card:last-child { 
        border-bottom:none; 
        margin-bottom:0; 
        padding-bottom:0; 
    }
    .examen-nombre { 
        font-weight:700; 
        font-size:0.95rem;
        color:#004080; 
        user-select:none; 
        margin-bottom:0.5rem; 
        display:flex; 
        align-items:center; 
        justify-content:space-between; 
    }
    .examen-content { 
        display:flex; 
        flex-wrap:wrap; 
        gap:0.8rem; 
    }

    .examen-title {
        margin-top: 2rem;
        text-align: center;
        color: #000;
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* --- Bloques de imágenes --- */
    .image-description-block { 
        display:flex; 
        flex-direction:column; 
        flex:0 0 calc(33.33% - 0.8rem); 
        min-width:180px; 
        border:1px solid #ddd; 
        border-radius:0.4rem; 
        padding:0.5rem;
        position: relative;
    }

    .remove-block {
        position: absolute;
        top: 3px;
        right: 5px;
        color: red;
        font-weight: 700;
        font-size: 0.95rem;
        background: transparent;
        border: none;
        cursor: pointer;
        z-index: 10;
    }

    .preview-container {
        text-align: center;
        margin-bottom: 0.4rem;
    }

    .img-preview { 
        margin-top:0.6rem; 
        max-width:100%; 
        max-height:130px; 
        height:auto; 
        border-radius:0.3rem; 
        object-fit:contain; 
        border:1px solid #ddd; 
        margin-bottom:0.4rem;
        display: none;
    }

    .input-file-container, .textarea-container { 
        width:100%; 
    }

    .textarea-container label {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }

    .descripcion-textarea {
        resize: vertical;
        min-height: 70px;
        font-size: 0.85rem;
    }

    /* Botones */
    .btn-group { 
        display:flex !important; 
        justify-content:center !important; 
        gap:0.6rem; 
        margin-top:1.2rem; 
        align-items:center; 
    }
    .btn-group .btn { 
        min-width:120px; 
        flex:0 0 auto; 
        padding:0.35rem 0.9rem; 
        font-size:0.8rem;
    }
    .btn {
        font-size: 0.95rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .btn-sm {
        font-size: 0.8rem;
        padding: 0.3rem 0.7rem;
    }

    #mensaje-dinamico-container { 
        width:100%; 
        display:flex; 
        flex-direction:column; 
        align-items:center; 
        gap:0.4rem; 
        margin-bottom:0.8rem; 
    }

    .mensaje-error-imagen {
        color: red;
        font-size: 0.75rem;
        margin-bottom: 4px;
    }

    .alert {
        font-size: 0.85rem;
        padding: 0.6rem 1rem;
    }

    @media(max-width:991px) { 
        .examen-content { 
            flex-direction:column; 
        } 
        .image-description-block { 
            flex:1 1 100%; 
        } 
    }
</style>

<div class="custom-card">
    <h2 class="section-title">Análisis de Ultrasonido</h2>
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
        <div><span class="label">Nombres:</span> <span class="underline-field">{{ $orden->paciente->nombre ?? 'N/A' }}</span></div>
        <div><span class="label">Apellidos:</span> <span class="underline-field">{{ $orden->paciente->apellidos ?? 'N/A' }}</span></div>
        <div><span class="label">Identidad:</span> <span class="underline-field">{{ $orden->paciente->identidad ?? 'N/A' }}</span></div>
        <div><span class="label">Género:</span> <span class="underline-field">{{ $orden->paciente->genero ?? 'N/A' }}</span></div>
    </div>

    <!-- Formulario de análisis -->
    <form action="{{ route('ultrasonidos.guardarAnalisis', $orden->id) }}" method="POST" enctype="multipart/form-data" id="form-analisis">
        @csrf

        <!-- Fila médico + fecha -->
        <div class="form-row">
            <div>
                <label for="medico_id" class="form-label medico-label">Médico responsable:</label>
                <select name="medico_id" id="medico_id" class="form-select @error('medico_id') is-invalid @enderror">
                    <option value="">-- Seleccionar Médico --</option>
                    @foreach($medicos as $medico)
                        <option value="{{ $medico->id }}" {{ (old('medico_id', $orden->medico_id ?? '') == $medico->id) ? 'selected' : '' }}>
                            {{ $medico->nombre }} {{ $medico->apellidos }}
                        </option>
                    @endforeach
                </select>
                @error('medico_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="fecha" class="form-label fecha-label">Fecha:</label>
                <div class="fecha-field">{{ $orden->fecha ?? 'N/A' }}</div>
            </div>
        </div>

        <h4 class="examen-title">Ultrasonidos ordenados</h4>

        <!-- Sección de ultrasonidos con imágenes -->
        @if(isset($examenesSeleccionados) && $examenesSeleccionados->isNotEmpty())
            @foreach($examenesSeleccionados as $index => $nombreExamen)
                <div class="examen-card" data-examen-index="{{ $index }}">
                    <div class="examen-nombre">
                        {{ $nombreExamen }}
                        <button type="button" class="btn btn-sm btn-success" id="btn-agregar-{{ $index }}" onclick="addImageBlock({{ $index }}, '{{ str_replace("'", "\\'", $nombreExamen) }}')">
                            <i class="bi bi-plus-circle"></i> Agregar imagen
                        </button>
                    </div>
                    <div class="examen-content" id="examen-content-{{ $index }}">
                        <!-- Los bloques de imágenes se agregarán aquí dinámicamente -->
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center">No hay exámenes registrados para esta orden.</p>
        @endif

        <!-- Botones de acción con estilo uniforme -->
        <div class="d-flex justify-content-center gap-3 mt-4 w-100">
            <button type="submit" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-save"></i>
                <span>Guardar análisis</span>
            </button>

            <a href="{{ route('ultrasonidos.index') }}" class="btn btn-success px-4 py-2 d-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Regresar</span>
            </a>
        </div>

    </form>
</div>

<script>
// Contador global para índices únicos de imágenes por examen
let imageCounters = {};

// Mostrar mensajes dinámicos con scroll a la página
function mostrarMensaje(mensaje, tipo='error') {
    const container = document.getElementById('mensaje-dinamico-container');
    container.innerHTML = '';
    const div = document.createElement('div');
    div.className = 'alert-dinamico ' + tipo;
    div.textContent = mensaje;
    div.style.display = 'inline-block';
    div.style.textAlign = 'center';
    div.style.padding = '7px 11px';
    div.style.borderRadius = '5px';
    div.style.margin = '8px auto';
    div.style.maxWidth = 'fit-content';
    div.style.transition = 'opacity 0.4s, transform 0.4s';
    div.style.opacity = '0';
    div.style.fontWeight = '600';
    div.style.fontSize = '0.85rem';
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

    let msgError = bloque.querySelector('.mensaje-error-imagen');
    if(msgError) msgError.remove();

    if(input.files && input.files[0]){
        const file = input.files[0];
        const ext = file.name.split('.').pop().toLowerCase();
        
        if(!['jpg','jpeg','png'].includes(ext)){
            msgError = document.createElement('div');
            msgError.className = 'mensaje-error-imagen';
            msgError.textContent = 'Solo se permiten imágenes JPG, JPEG o PNG.';
            bloque.insertBefore(msgError, bloque.firstChild);
            input.value = '';
            preview.src='#';
            preview.style.display='none';
            return;
        }
        
        if(file.size > 4 * 1024 * 1024){
            msgError = document.createElement('div');
            msgError.className = 'mensaje-error-imagen';
            msgError.textContent = 'La imagen no debe superar 4 MB.';
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

// Agregar bloque de imagen para un ultrasonido específico
function addImageBlock(examenId, nombreExamen){
    const container = document.getElementById('examen-content-'+examenId);
    if(!container) return;
    
    const blocks = container.querySelectorAll('.image-description-block');
    if(blocks.length >= 3){ 
        document.getElementById('btn-agregar-'+examenId).disabled=true; 
        mostrarMensaje('Máximo 3 imágenes por ultrasonido.', 'info'); 
        return; 
    }

    if(!imageCounters[examenId]) imageCounters[examenId] = 0;
    const index = imageCounters[examenId]++;
    
    const blockDiv = document.createElement('div'); 
    blockDiv.className='image-description-block'; 
    blockDiv.setAttribute('data-block-index', index);

    const removeBtn = document.createElement('button');
    removeBtn.type='button';
    removeBtn.className='remove-block';
    removeBtn.innerHTML='X';
    removeBtn.onclick = function(){ removeBlock(removeBtn, examenId); };
    blockDiv.appendChild(removeBtn);

    const previewDiv=document.createElement('div'); 
    previewDiv.className='preview-container text-center mb-2';
    const imgPreview=document.createElement('img'); 
    imgPreview.id=`preview_${examenId}_${index}`; 
    imgPreview.className='img-preview'; 
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
    const label = document.createElement('label');
    label.className = 'form-label mb-1';
    label.textContent = 'Descripción:';
    const textarea=document.createElement('textarea'); 
    textarea.name=`descripciones[${examenId}][]`; 
    textarea.className='form-control descripcion-textarea'; 
    textarea.rows=3; 
    textarea.maxLength=200;
    textareaDiv.appendChild(label);
    textareaDiv.appendChild(textarea);

    blockDiv.appendChild(previewDiv);
    blockDiv.appendChild(textareaDiv);
    container.appendChild(blockDiv);

    if(container.querySelectorAll('.image-description-block').length >= 3) 
        document.getElementById('btn-agregar-'+examenId).disabled=true;
}

// Eliminar bloque y actualizar límite
function removeBlock(button, examenId){
    const block = button.closest('.image-description-block');
    if(block){
        const examenContent = block.closest('.examen-content');
        block.remove();
        const addBtn = document.getElementById('btn-agregar-'+examenId);
        if(addBtn && examenContent.querySelectorAll('.image-description-block').length < 3){
            addBtn.disabled = false;
        }
    }
}

// Validaciones al enviar formulario
document.getElementById('form-analisis').addEventListener('submit', function(event){
    const medico = this.querySelector('#medico_id');
    if(!medico.value){ 
        mostrarMensaje('El médico es obligatorio.', 'error'); 
        medico.focus();
        event.preventDefault();
        return;
    }

    const examenesCards = this.querySelectorAll('.examen-card');

    for(let i=0; i<examenesCards.length; i++){
        const card = examenesCards[i];
        const nombreExamen = card.querySelector('.examen-nombre').childNodes[0].textContent.trim();
        const bloques = card.querySelectorAll('.image-description-block');

        if(bloques.length === 0){
            mostrarMensaje(`Se debe realizar al menos un análisis de "${nombreExamen}".`, 'error');
            event.preventDefault();
            return;
        }

        for(let j=0; j<bloques.length; j++){
            const bloque = bloques[j];
            const fileInput = bloque.querySelector('input[type="file"]');
            const textarea = bloque.querySelector('textarea');

            if(!fileInput || fileInput.files.length===0){
                mostrarMensaje(`Falta imagen en "${nombreExamen}".`, 'error'); 
                if(fileInput) fileInput.focus();
                event.preventDefault();
                return;
            }

            const ext = fileInput.files[0].name.split('.').pop().toLowerCase();
            if(!['jpg','jpeg','png'].includes(ext)){
                fileInput.focus();
                event.preventDefault();
                return;
            }

            if(!textarea.value.trim()){
                mostrarMensaje(`Falta descripción en "${nombreExamen}".`, 'error'); 
                textarea.focus();
                event.preventDefault();
                return;
            }
        }
    }
});

// Inicializar con un bloque por cada ultrasonido al cargar
document.addEventListener('DOMContentLoaded', function(){
    const contenedores = document.querySelectorAll('.examen-content');
    contenedores.forEach((container) => {
        const examenId = container.id.replace('examen-content-', '');
        const nombreExamen = container.parentElement.querySelector('.examen-nombre').childNodes[0].textContent.trim();
        addImageBlock(parseInt(examenId), nombreExamen);
    });
});
</script>

@endsection