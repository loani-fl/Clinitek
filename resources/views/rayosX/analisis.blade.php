@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { background-color: #e8f4fc; margin:0; padding:0; min-height:100vh; position:relative; }
    .custom-card { max-width:1200px; background-color:#fff; margin:40px auto 60px auto; border-radius:1.5rem; padding:2rem 2.5rem; position:relative; overflow:visible; z-index:1; box-shadow:0 4px 12px rgb(0 0 0 / 0.1); background-image: url('/images/logo2.jpg'); background-size:contain; background-repeat:no-repeat; background-position:center; opacity:1; }
    .custom-card::before { content:""; position:absolute; inset:0; background:rgba(255,255,255,0.85); border-radius:1.5rem; z-index:0; }
    .custom-card > * { position:relative; z-index:1; }
    .section-title { font-size:1.3rem; font-weight:700; color:#003366; border-bottom:3px solid #007BFF; padding-bottom:0.3rem; margin-bottom:1rem; user-select:none; }
    h4 { color:#003366; font-weight:700; margin-top:1.5rem; margin-bottom:1rem; user-select:none; }
    .datos-paciente-flex { display:flex; gap:2rem; flex-wrap:wrap; margin-bottom:1rem; padding-left:0; list-style:none; }
    .datos-paciente-flex li { flex:1 1 200px; padding:0.3rem 0; border-bottom:1px solid #ccc; display:flex; gap:0.5rem; color:#222; font-weight:600; }
    .datos-paciente-flex li strong { width:80px; color:#004080; }
    .examen-card { margin-bottom:2.5rem; padding-bottom:1rem; border-bottom:3px solid #007BFF; }
    .examen-card:last-child { border-bottom:none; margin-bottom:0; padding-bottom:0; }
    .examen-nombre { font-weight:700; font-size:1.3rem; color:#004080; user-select:none; margin-bottom:0.5rem; display:flex; align-items:center; justify-content:space-between; }
    .examen-content { display:flex; flex-wrap:wrap; gap:1rem; }
    .image-description-block { display:flex; flex-direction:column; flex:0 0 calc(33.33% - 1rem); min-width:200px; border:1px solid #ddd; border-radius:0.5rem; padding:0.5rem; }
    .input-file-container, .textarea-container { width:100%; }
    .img-preview { margin-top:0.8rem; max-width:100%; max-height:150px; height:auto; border-radius:0.4rem; object-fit:contain; border:1px solid #ddd; margin-bottom:0.5rem; }
    .btn-group { display:flex !important; justify-content:center !important; gap:0.75rem; margin-top:1.5rem; align-items:center; }
    .btn-group .btn { min-width:140px; max-width:auto; flex:0 0 auto; padding:0.4rem 1rem; font-size:0.95rem; }
    #mensaje-dinamico-container { margin-bottom: 1rem; }
    .alert-dinamico { opacity:0; transition: opacity 0.5s ease-in-out; padding:0.6rem 1rem; margin-bottom:0.5rem; border-radius:0.5rem; font-weight:600; font-size:0.95rem; color:#fff; white-space: pre-line; }
    .alert-dinamico.error { background-color:#dc3545; }
    .alert-dinamico.success { background-color:#28a745; }
    .alert-dinamico.info { background-color:#007BFF; }
    @media(max-width:991px) { .examen-content { flex-direction:column; } .image-description-block { flex:1 1 100%; } }
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

    <h4>Datos del paciente</h4>
    <ul class="datos-paciente-flex">
        <li><strong>Nombre:</strong> {{ $orden->paciente->nombre ?? $orden->nombres ?? 'N/A' }}</li>
        <li><strong>Apellidos:</strong> {{ $orden->paciente->apellidos ?? $orden->apellidos ?? 'N/A' }}</li>
        <li><strong>Identidad:</strong> {{ $orden->paciente->identidad ?? $orden->identidad ?? 'N/A' }}</li>
        <li><strong>Género:</strong> {{ $orden->paciente->genero ?? $orden->genero ?? 'N/A' }}</li>
    </ul>

    <hr>

    <form action="{{ route('rayosx.storeAnalisis', $orden->id) }}" method="POST" enctype="multipart/form-data" id="form-analisis">
        @csrf
        <div class="mb-3">
            <label for="medico_analista_id" class="form-label"><strong>Médico analista:</strong></label>
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

        <h4>Exámenes realizados</h4>
@forelse ($orden->examenes as $examen)
    @php
        $bloquesImagenes = $examen->imagenes ?? collect();
    @endphp
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
            <a href="{{ route('rayosx.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left-circle"></i> Regresar</a>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save"></i> Guardar cambios</button>
        </div>
    </form>
</div>
<script>
function mostrarMensaje(mensaje, tipo='error') {
    const container = document.getElementById('mensaje-dinamico-container');
    const div = document.createElement('div');
    div.className = 'alert-dinamico ' + tipo;
    div.textContent = mensaje;
    container.appendChild(div);
    div.scrollIntoView({ behavior:'smooth', block:'center' });
    setTimeout(()=>{ div.style.opacity='1'; },50);
    setTimeout(()=>{ div.style.opacity='0'; setTimeout(()=>container.removeChild(div),500); },4000);
}

function previewImage(event, examenIdIndex) {
    const input = event.target;
    const preview = document.getElementById('preview_'+examenIdIndex);
    if(input.files && input.files[0]){
        const file = input.files[0];
        const ext = file.name.split('.').pop().toLowerCase();
        if(!['jpg','jpeg','png'].includes(ext)){
            mostrarMensaje('Solo se permiten imágenes JPG, JPEG o PNG.', 'error');
            input.value = '';
            preview.src='#'; preview.style.display='none';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e){ preview.src=e.target.result; preview.style.display='block'; }
        reader.readAsDataURL(file);
    } else { preview.src='#'; preview.style.display='none'; }
}

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

document.addEventListener('DOMContentLoaded', function(){
    const examenes=@json($orden->examenes->pluck('id'));
    examenes.forEach(examenId=>{
        const container=document.getElementById('examen-content-'+examenId);
        if(container){
            const bloquesExistentes = container.querySelectorAll('.image-description-block');
            if(bloquesExistentes.length===0){ 
                addImageBlock(examenId); 
            }
            if(bloquesExistentes.length >= 3){ 
                document.getElementById('btn-agregar-'+examenId).disabled=true; 
            }
        }
    });
});

document.getElementById('form-analisis').addEventListener('submit', function(event){
    const containerMensajes = document.getElementById('mensaje-dinamico-container');
    containerMensajes.innerHTML = '';
    let errores=false;

    const medicoAnalista = this.querySelector('#medico_analista_id');
    if(!medicoAnalista.value){ mostrarMensaje('El médico analista es obligatorio.','error'); errores=true; }

    this.querySelectorAll('.examen-card').forEach(card=>{
        const bloques = card.querySelectorAll('.image-description-block');
        bloques.forEach((bloque,j)=>{
            const fileInput = bloque.querySelector('input[type="file"]');
            const textarea = bloque.querySelector('textarea');
            const nombreExamen = card.querySelector('.examen-nombre').childNodes[0].textContent.trim();

            if(fileInput && fileInput.files.length>0){
                const ext = fileInput.files[0].name.split('.').pop().toLowerCase();
                if(!['jpg','jpeg','png'].includes(ext)){
                    mostrarMensaje(`Solo se permiten imágenes JPG, JPEG o PNG en "${nombreExamen}" bloque ${j+1}.`, 'error');
                    errores=true;
                }
            }

            if(!textarea.value.trim()){
                mostrarMensaje(`La descripción del bloque ${j+1} del examen "${nombreExamen}" es obligatoria.`, 'error');
                errores=true;
            }
        });
    });

    if(errores) event.preventDefault();
});
</script>

@endsection
