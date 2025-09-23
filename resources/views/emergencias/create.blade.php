quiero que los signos vitales tengan mensajes emergentes asi como los demas campos @extends('layouts.app')

@section('content')
<style>
    body { background-color: #e8f4fc; margin: 0; padding: 0; }
    .content-wrapper { margin-top: 60px; max-width: 900px; margin-left:auto; margin-right:auto; padding:1rem; position: relative; }
    .custom-card::before {
        content: "";
        position: absolute;
        top: 50%; left: 50%;
        width: 800px; height: 800px;
        background-image: url('{{ asset("images/logo2.jpg") }}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.15;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }
    .custom-card { background-color:#fff; border-radius:1.5rem; padding:2rem; overflow:hidden; position: relative; z-index:1; }
    .card-header { background-color: transparent; border-bottom: 3px solid #007BFF; text-align:center; margin-bottom:1rem; padding:0.5rem 0; }
    .card-header h2 { font-size:2rem; font-weight:bold; color:#000; margin:0; }
    label { font-size:0.85rem; font-weight:600; color:#003366; }
    input, select, textarea { font-size:0.85rem !important; }
    .row { display: flex; flex-wrap: wrap; margin-left: -0.5rem; margin-right: -0.5rem; }
    .row > div { padding-left: 0.5rem; padding-right: 0.5rem; margin-bottom: 1rem; }
    .col-4th { flex: 0 0 25%; max-width: 25%; }
    .col-half { flex: 0 0 50%; max-width: 50%; }
    .align-items-end { align-items: flex-end; }
    .btn { font-size:0.9rem; }
    .custom-radio { display:flex; align-items:center; gap:0.5rem; font-size:1rem; font-weight:600; color:#003366; cursor:pointer; }
    .custom-radio input[type="radio"] { appearance:none; -webkit-appearance:none; width:18px; height:18px; border:2px solid #003366; border-radius:4px; position: relative; cursor:pointer; }
    .custom-radio input[type="radio"]:checked::after { content:"✖"; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); font-size:14px; color:#003366; }
    .radio-group { display:flex; gap:2rem; margin-bottom:1.5rem; }
    .text-danger { font-size:0.75rem; margin-top:0.25rem; display:block; }
    /* Campos indocumentado alineados */
    #indocFields { display: none; }
    #indocFields .col-4th { flex: 0 0 33.33%; max-width: 33.33%; }


    /* Campos indocumentado alineados */
#indocFields { display: none; }

/* Foto más grande y ocupa más espacio */
#indocFields .col-4th:first-child { flex: 0 0 50%; max-width: 50%; }

/* Fecha y hora más pequeños */
#indocFields .col-4th:nth-child(2),
#indocFields .col-4th:nth-child(3) { 
    flex: 0 0 25%; 
    max-width: 25%; 
}

#indocFields label { 
    font-size: 0.75rem; /* Más pequeño para fecha y hora */
}

#indocFields .col-4th:first-child label { 
    font-size: 0.85rem; /* Mantener un poco más grande para foto */
}
</style>

<div class="content-wrapper">
    <div class="card custom-card">
        <div class="card-header">
            <h2>Registro de Emergencias</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('emergencias.store') }}" method="POST" enctype="multipart/form-data" id="emergenciaForm">
            @csrf

            <div class="radio-group">
                <label class="custom-radio">
                    <input type="radio" name="documentado" value="1" {{ old('documentado', '1') == '1' ? 'checked' : '' }} onclick="toggleDocs()"><span>Documentado</span>
                </label>
                <label class="custom-radio">
                    <input type="radio" name="documentado" value="0" {{ old('documentado') == '0' ? 'checked' : '' }} onclick="toggleDocs()"><span>Indocumentado</span>
                </label>
            </div>

            <!-- Campos Documentado -->
            <div id="docFields">
                <div class="row mb-3">
                    <div class="col-4th">
                        <label for="nombres">Nombres:</label>
                        <input type="text" id="nombres" name="nombres" class="form-control @error('nombres') is-invalid @enderror" maxlength="40" value="{{ old('nombres') }}">
                        @error('nombres') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-4th">
                        <label for="apellidos">Apellidos:</label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror" maxlength="40" value="{{ old('apellidos') }}">
                        @error('apellidos') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-4th">
                        <label for="identidad">Número de identidad:</label>
                        <input type="text" id="identidad" name="identidad" class="form-control @error('identidad') is-invalid @enderror" maxlength="13" value="{{ old('identidad') }}">
                        @error('identidad') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-4th">
                        <label>Edad:</label>
                        <input type="number" id="edad" name="edad" class="form-control @error('edad') is-invalid @enderror" readonly value="{{ old('edad') }}">
                        @error('edad') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-4th">
                        <label>Sexo:</label>
                        <select name="sexo" class="form-control @error('sexo') is-invalid @enderror">
                            <option value="">-- Selecciona --</option>
                            <option value="M" {{ old('sexo')=='M' ? 'selected':'' }}>Masculino</option>
                            <option value="F" {{ old('sexo')=='F' ? 'selected':'' }}>Femenino</option>
                        </select>
                        @error('sexo') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-4th">
                        <label>Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" class="form-control @error('telefono') is-invalid @enderror" maxlength="8" value="{{ old('telefono') }}">
                        @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-4th">
                        <label>Fecha:</label>
                        <input type="text" name="fecha" class="form-control" 
                               value="{{ isset($emergencia) ? \Carbon\Carbon::parse($emergencia->fecha)->format('d/m/Y') : now()->format('d/m/Y') }}" 
                               readonly>
                    </div>
                    <div class="col-4th">
                        <label>Hora:</label>
                        <input type="text" name="hora" class="form-control" 
                               value="{{ isset($emergencia) ? \Carbon\Carbon::parse($emergencia->hora)->format('h:i A') : now()->format('h:i A') }}" 
                               readonly>
                    </div>
                </div>
            </div>

            <!-- Campos Indocumentado -->
            <div class="row mb-3" id="indocFields">
               <div class="col-4th">
    <label>Foto del paciente (jpg, jpeg, png):</label>
    <input type="file" name="foto" accept=".jpg,.jpeg,.png" 
           class="form-control @error('foto') is-invalid @enderror" capture="camera">
    @error('foto') <span class="text-danger">{{ $message }}</span> @enderror
</div>

                <div class="col-4th">
                    <label>Fecha:</label>
                    <input type="text" name="fecha_indoc" class="form-control" value="{{ now()->format('d/m/Y') }}" readonly>
                </div>
                <div class="col-4th">
                    <label>Hora:</label>
                    <input type="text" name="hora_indoc" class="form-control" value="{{ now()->format('h:i A') }}" readonly>
                </div>
            </div>

            <!-- Otros campos -->
            <div class="row mb-3">
                <div class="col-half">
                    <label>Motivo de la emergencia:</label>
                    <textarea name="motivo" id="motivo" class="form-control @error('motivo') is-invalid @enderror" maxlength="60">{{ old('motivo') }}</textarea>
                    @error('motivo') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-half">
                    <label>Dirección:</label>
                    <textarea name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror" maxlength="70">{{ old('direccion') }}</textarea>
                    @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <h4 class="mt-4">Signos Vitales</h4>
            <div class="row mb-3">
               <div class="col-4th">
                    <label for="pa">Presión Arterial:</label>
                    <input type="text" id="pa" name="pa" class="form-control @error('pa') is-invalid @enderror" placeholder="Ej: 120/80 mmHg" maxlength="7" value="{{ old('pa') }}">
                    @error('pa') <span class="text-danger">{{ $message }}</span> @enderror
               </div>
               <div class="col-4th">
                    <label for="fc">Frecuencia Cardíaca:</label>
                    <input type="number" id="fc" name="fc" class="form-control @error('fc') is-invalid @enderror" value="{{ old('fc') }}" min="30" max="200" placeholder="30-200" oninput="if(this.value.length>3)this.value=this.value.slice(0,3)">
                    @error('fc') <span class="text-danger">{{ $message }}</span> @enderror
               </div>
               <div class="col-4th">
                    <label for="temp">Temperatura (°C):</label>
                    <input type="number" step="0.1" id="temp" name="temp" class="form-control @error('temp') is-invalid @enderror" value="{{ old('temp') }}" min="30" max="45" placeholder="30-45" oninput="if(this.value>45)this.value=45; if(this.value<30)this.value=30;">
                    @error('temp') <span class="text-danger">{{ $message }}</span> @enderror
               </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4 w-100">
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Guardar</button>
                <button type="button" class="btn btn-warning" id="btnLimpiar"><i class="bi bi-trash"></i> Limpiar</button>
                <a href="{{ route('emergencias.index') }}" class="btn btn-success"><i class="bi bi-arrow-left"></i> Volver</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDocs() {
    let doc = document.querySelector('input[name="documentado"][value="1"]').checked;
    document.getElementById("docFields").style.display = doc ? "flex" : "none";
    document.getElementById("indocFields").style.display = doc ? "none" : "flex";
}

// Ejecutar al cargar para respetar old()
window.addEventListener('DOMContentLoaded', () => toggleDocs());

// Botón Limpiar sin cambiar radio
document.getElementById('btnLimpiar').addEventListener('click', function(){
    const form = document.getElementById('emergenciaForm');
    const seleccionado = document.querySelector('input[name="documentado"]:checked').value;

    // Solo limpiar los campos de entrada del formulario (inputs y textarea), sin tocar fecha/hora de indoc
    form.querySelectorAll('input, textarea, select').forEach(input => {
        const name = input.name;
        // Si es fecha o hora de indocumentado, no limpiar
        if(name === 'fecha_indoc' || name === 'hora_indoc') return;
        // Para otros campos de texto/numero/select/textarea
        if(input.type === 'file') input.value = '';
        else if(input.type !== 'radio' && input.type !== 'checkbox') input.value = '';
        else if(input.tagName.toLowerCase() === 'select') input.selectedIndex = 0;
    });

    // Limpiar errores y clases de validación
    form.querySelectorAll('.text-danger').forEach(e => e.innerHTML = '');
    form.querySelectorAll('.is-invalid, .is-valid').forEach(e => e.classList.remove('is-invalid','is-valid'));

    // Restaurar radio seleccionado
    document.querySelector(`input[name="documentado"][value="${seleccionado}"]`).checked = true;

    toggleDocs();
});


// Validaciones en tiempo real
document.getElementById('nombres').addEventListener('keypress', e => {
    if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]$/.test(e.key)) e.preventDefault();
});
document.getElementById('apellidos').addEventListener('keypress', e => {
    if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]$/.test(e.key)) e.preventDefault();
});
document.getElementById('telefono').addEventListener('keypress', e => {
    if (!/^\d$/.test(e.key)) e.preventDefault();
});

// Identidad solo números y cálculo de edad
const identidad = document.getElementById('identidad');
identidad.addEventListener('input', e => {
    identidad.value = identidad.value.replace(/\D/g, '');
    if (identidad.value.length >= 8) {
        const year = parseInt(identidad.value.substring(4,8));
        const edad = new Date().getFullYear() - year;
        document.getElementById('edad').value = (edad >= 0 && edad <= 95) ? edad : '';
    } else {
        document.getElementById('edad').value = '';
    }
});

document.getElementById('motivo').addEventListener('keypress', e => {
    if (!/^[\p{L}\d\s\.,]$/u.test(e.key)) e.preventDefault();
});
document.getElementById('direccion').addEventListener('keypress', e => {
    if (!/^[\p{L}\d\s\.,#\/\-\(\)]$/u.test(e.key)) e.preventDefault();
});
</script>
@endsection
