@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background-color: #e8f4fc;
    margin: 0; padding: 0; min-height: 100vh; position: relative;
}

/* Wrapper compacto */
.content-wrapper {
    margin-top: 60px; max-width: 1000px; background-color: #fff;
    margin-left: auto; margin-right: auto; border-radius: 1.5rem;
    padding: 1rem 2rem; position: relative; overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

/* Logo translúcido */
.content-wrapper::before {
    content: "";
    position: absolute; top: 50%; left: 50%;
    width: 2200px; height: 2200px;
    background-image: url('{{ asset('images/logo2.jpg') }}');
    background-size: contain; background-repeat: no-repeat;
    background-position: center; opacity: 0.1;
    transform: translate(-50%, -50%);
    pointer-events: none; z-index: 0;
}

h2, h5 { color: #003366; font-weight: 700; margin-bottom: 1rem; text-align: center; }
h5 { font-size: 1.2rem; }

/* Datos del paciente */
.patient-block {
    background-color: #f0f7ff;
    padding: 1rem 1.2rem;
    border-radius: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.patient-data-row { display: flex; flex-wrap: wrap; gap: 0.8rem; }
.patient-data-field { display: flex; align-items: center; gap: 0.4rem; min-width: 180px; flex: 1; }
.patient-data-field strong { min-width: 100px; font-weight: normal; color: rgb(3,12,22); font-size: 0.95rem; }
.underline-field-solid { border-bottom: 1px solid #333; min-height: 1.2rem; padding: 0 4px; user-select: none; flex:1; font-size:0.85rem; }

/* Lista autocompletado */
#listaPacientes {
    z-index: 1150 !important;
    position: absolute; width: 100%; max-height: 200px;
    overflow-y: auto; background: white; border: 1px solid #ccc; border-radius: 0 0 0.4rem 0.4rem; display: none;
}

/* Botones */
button, a.btn { font-size: 0.95rem; padding: 0.4rem 0.5rem; font-weight: normal; border-radius: 0.4rem; margin-left:0.5rem; line-height:1.2; }
button.btn-warning { color:#212529; background-color:#ffc107; border-color:#ffc107; }
button.btn-warning:hover { background-color:#e0a800; border-color:#d39e00; }
button.btn-success { background-color:#198754; border-color:#198754; color:white; }
button.btn-success:hover { background-color:#157347; border-color:#146c43; }
a.btn-primary { background-color:#0d6efd; border-color:#0d6efd; color:white; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; }
a.btn-primary:hover { background-color:#0b5ed7; border-color:#0a58ca; color:white; }

/* Mensajes flash */
.mensaje-flash { min-width:280px; max-width:600px; text-align:center; border-radius:8px; padding:8px 16px; margin:5px auto; font-weight: normal; opacity:0; transform:translateY(-20px); transition: opacity 0.4s, transform 0.4s; box-shadow:0 4px 12px rgba(0,0,0,0.25); font-size:0.9rem; }
.mensaje-flash.mostrar { opacity:1; transform:translateY(0); }
.mensaje-flash.error { background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
.mensaje-flash.exito { background-color:#d4edda; color:#155724; border:1px solid #c3e6cb; }

/* Secciones exámenes */
/* Exámenes */
.examen-block {
    background-color: #f0f7ff; /* fondo azul clarito para toda la sección */
    padding: 1rem 1rem;
    border-radius: 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.secciones-container { display:grid; grid-template-columns:repeat(3,1fr); gap:1.3rem 0.1rem; margin-top:0.3rem; }

.section-title {
    font-size: 1.1rem;        /* tamaño similar al de paciente */
    font-weight: 700;          /* negrita */
    color: #000000;            /* color negro */
    background-color: #f0f7ff; /* fondo azul clarito */
    padding: 0.4rem 0.6rem;
    border-radius: 0.6rem;
    text-align: center;
    margin-bottom: 0.7rem;
}


.examenes-grid {
    display: flex;
    flex-direction: column;
    gap: 0.35rem; /* espacio uniforme entre cada checkbox */
}

/* Nombres de los exámenes */
.examenes-grid label {
    display: flex;
    align-items: center;   /* asegura que checkbox y texto estén alineados verticalmente */
    font-size: 0.85rem;
    font-weight: normal;    /* sin negrita */
    color: #0f0f0f;         /* color negro */
    cursor: pointer;
}

.examenes-grid input[type="checkbox"] {
    margin-right: 0.5rem;  /* espacio entre checkbox y nombre */
    vertical-align: middle;
}

/* Línea azul y total */
.linea-azul { height:3px; background-color:#007BFF; width:100%; border-radius:2px; margin:0.5rem 0 0.8rem 0; }
#totalPrecio { font-size:1.1rem; }

/* Controles superiores */
.top-controls { display:flex; flex-wrap:nowrap; gap:0.4rem; justify-content:center; align-items:flex-end; margin-bottom:1rem; }
.top-controls > div { flex:0 0 auto; }
.top-controls label { font-size:0.9rem; margin-bottom:0.2rem; font-weight:normal; }
#buscarPaciente { width:260px; font-size:0.9rem; padding:0.4rem 0.6rem; }
#fecha { width:120px; font-size:0.9rem; padding:0.4rem 0.6rem; }
a.btn-primary { width:200px; }

@media (max-width:767.98px) {
    .top-controls { flex-direction:column; align-items:center; }
    #buscarPaciente, #fecha, a.btn-primary { width:90%; }
}
</style>

<div class="content-wrapper">
    <div class="row align-items-center mb-3">
        <div class="col-md-3 text-center">
            <img src="{{ asset('images/logo2.jpg') }}" alt="Logo Clinitek" style="height:50px; width:auto;">
            <div style="font-size:0.9rem; font-weight:700; color:#555;">Laboratorio ultrasonidos Honduras</div>
        </div>
        <div class="col-md-9 text-center" style="transform: translateX(30%);">
            <h4 class="mb-0" style="font-size:1.1rem; font-weight:700; color:#333; line-height:1.3;">Crear orden de ultrasonido</h4>
        </div>
    </div>

    <div class="linea-azul"></div>

    <div id="mensajes-container" style="text-align:center; margin:5px 0;"></div>

    @if ($errors->any())
    <div class="alert alert-danger mb-3" style="padding:0.5rem;">
        <ul class="mb-0" style="font-size:0.9rem;">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('ultrasonidos.store') }}" method="POST" id="formOrden" novalidate>
        @csrf

        {{-- Controles superiores --}}
        <div class="top-controls">
            <div style="position:relative;">
                <label for="buscarPaciente" class="form-label">Buscar paciente <span class="text-danger">*</span></label>
                <input type="text" id="buscarPaciente" class="form-control" placeholder="Buscar por nombre..." autocomplete="off"
                    value="{{ old('paciente_nombre', $pacientes->firstWhere('id', old('paciente_id')) ? $pacientes->firstWhere('id', old('paciente_id'))->nombre.' '.$pacientes->firstWhere('id', old('paciente_id'))->apellidos : '') }}"
                    required>
                <input type="hidden" name="paciente_id" id="paciente_id" value="{{ old('paciente_id') }}">
                <ul id="listaPacientes" class="list-group">
                    @foreach ($pacientes as $paciente)
                        <li class="list-group-item list-group-item-action paciente-item" 
                            data-id="{{ $paciente->id }}" 
                            data-nombre="{{ $paciente->nombre }}" 
                            data-apellidos="{{ $paciente->apellidos ?? '' }}" 
                            data-identidad="{{ $paciente->identidad ?? '' }}" 
                            data-genero="{{ $paciente->genero ?? '' }}" 
                            style="cursor:pointer; font-size:0.9rem; padding:0.4rem 0.6rem;">
                            {{ $paciente->nombre }} {{ $paciente->apellidos ?? '' }}
                        </li>
                    @endforeach
                </ul>
                <div id="mensajeNoEncontrado" style="font-size:0.85rem; color:#000; margin-top:0.3rem; display:none;">Paciente no encontrado.</div>
            </div>

            <div>
                <label for="fecha" class="form-label">Fecha <span class="text-danger"></span></label>
                <input type="date" name="fecha" id="fecha"
                value="{{ date('Y-m-d') }}" 
                readonly
                class="form-control" required>

            </div>

            <div>
                <label class="d-block">&nbsp;</label>
                <a href="{{ route('pacientes.create',['returnUrl'=>route('ultrasonidos.create')]) }}" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Registrar paciente
                </a>
            </div>
        </div>

        {{-- Datos del paciente --}}
        <div id="datosPaciente" class="patient-block" style="display:{{ old('paciente_id')?'block':'none' }};">
            <h5>Datos del paciente</h5>
            <div class="patient-data-row">
                <div class="patient-data-field"><strong>Nombres:</strong>
                    <div id="dp-nombres" class="underline-field-solid">
                        {{ old('paciente_id') && $pacientes->firstWhere('id', old('paciente_id')) ? $pacientes->firstWhere('id', old('paciente_id'))->nombre : '' }}
                    </div>
                </div>
                <div class="patient-data-field"><strong>Apellidos:</strong>
                    <div id="dp-apellidos" class="underline-field-solid">
                        {{ old('paciente_id') && $pacientes->firstWhere('id', old('paciente_id')) ? $pacientes->firstWhere('id', old('paciente_id'))->apellidos : '' }}
                    </div>
                </div>
            </div>
            <div class="patient-data-row">
                <div class="patient-data-field"><strong>Identidad:</strong>
                    <div id="dp-identidad" class="underline-field-solid">
                        {{ old('paciente_id') && $pacientes->firstWhere('id', old('paciente_id')) ? $pacientes->firstWhere('id', old('paciente_id'))->identidad : '' }}
                    </div>
                </div>
                <div class="patient-data-field"><strong>Género:</strong>
                    <div id="dp-genero" class="underline-field-solid">
                        {{ old('paciente_id') && $pacientes->firstWhere('id', old('paciente_id')) ? $pacientes->firstWhere('id', old('paciente_id'))->genero : '' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Exámenes --}}
        <div class="examen-block">
            <h5>Exámenes disponibles</h5>
            <div class="secciones-container">
                @foreach($secciones as $categoria => $examenes)
                    <div class="seccion">
                        <div class="section-title">{{ $categoria }}</div>
                        <div class="examenes-grid">
                            @foreach($examenes as $clave => $datos)
                                <label>
                                    <input type="checkbox" class="examen-checkbox" name="examenes[]" value="{{ $clave }}" data-precio="{{ $datos['precio'] }}" 
                                           {{ (is_array(old('examenes')) && in_array($clave,old('examenes')))?'checked':'' }}>
                                    {{ $datos['nombre'] }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-2 text-end fw-bold" style="font-size:1rem;">
            Total a pagar: L<span id="totalPrecio">0.00</span>
        </div>

        <div class="d-flex justify-content-center gap-2 mb-3">
            <button type="submit" class="btn btn-primary px-3 py-1"><i class="bi bi-save"></i> Registrar y pagar</button>
            <button type="button" id="btnLimpiar" class="btn btn-warning px-3 py-1"><i class="bi bi-trash"></i> Limpiar</button>
            <a href="{{ route('ultrasonidos.index') }}" class="btn btn-success px-3 py-1"><i class="bi bi-arrow-left"></i> Regresar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() { 
    const inputBuscar = document.getElementById('buscarPaciente');
    const lista = document.getElementById('listaPacientes');
    const inputHiddenId = document.getElementById('paciente_id'); 
    const dpNombres = document.getElementById('dp-nombres'); 
    const dpApellidos = document.getElementById('dp-apellidos');
    const dpIdentidad = document.getElementById('dp-identidad'); 
    const dpGenero = document.getElementById('dp-genero');
    const fechaInput = document.getElementById('fecha'); 
    const hoy = new Date(); 
    const dd = String(hoy.getDate()).padStart(2,'0'); 
    const mm = String(hoy.getMonth()+1).padStart(2,'0'); 
    const yyyy = hoy.getFullYear(); 
    fechaInput.setAttribute('max', `${yyyy}-${mm}-${dd}`); 
    fechaInput.value = fechaInput.value || `${yyyy}-${mm}-${dd}`; 

    const mensajesContainer = document.getElementById('mensajes-container');

    function showMensaje(texto, tipo='error') { 
        const div = document.createElement('div'); 
        div.textContent = texto; 
        div.className = `mensaje-flash ${tipo} mostrar`; 
        mensajesContainer.appendChild(div); 
        setTimeout(()=>{div.style.opacity=0; setTimeout(()=>{div.remove();},400);},4000); 
    } 

    // Autocompletado paciente
    inputBuscar.addEventListener('input', ()=> {
        const filtro = inputBuscar.value.toLowerCase();
        let visibleCount=0;
        Array.from(lista.children).forEach(li=>{
            const texto = li.textContent.toLowerCase();
            if(texto.includes(filtro) && filtro!==''){ 
                li.style.display='block'; visibleCount++; 
            } else { 
                li.style.display='none'; 
            }
        });
        lista.style.display = visibleCount>0 ? 'block' : 'none';
        inputHiddenId.value='';
        dpNombres.textContent='';
        dpApellidos.textContent='';
        dpIdentidad.textContent='';
        dpGenero.textContent='';
        document.getElementById('datosPaciente').style.display='none';
    });

    lista.querySelectorAll('.paciente-item').forEach(item=>{
        item.addEventListener('click', ()=>{
            inputBuscar.value = item.textContent.trim();
            inputHiddenId.value = item.dataset.id;
            lista.style.display='none';
            dpNombres.textContent = item.dataset.nombre || '';
            dpApellidos.textContent = item.dataset.apellidos || '';
            dpIdentidad.textContent = item.dataset.identidad || '';
            dpGenero.textContent = item.dataset.genero || '';
            document.getElementById('datosPaciente').style.display='block';
        });
    });

    document.addEventListener('click', e=>{
        if(!inputBuscar.contains(e.target) && !lista.contains(e.target)) lista.style.display='none';
    });

    // Exámenes y total
    const checkboxes = document.querySelectorAll('.examen-checkbox'); 
    const totalSpan = document.getElementById('totalPrecio'); 
    const MAX_EXAMENES = 7; 

    function actualizarTotal(){
        let total=0, seleccionados=0;
        checkboxes.forEach(cb=>{
            if(cb.checked){ 
                total += parseFloat(cb.dataset.precio); 
                seleccionados++; 
            }
        });
        totalSpan.textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        checkboxes.forEach(cb=>{
            cb.disabled = seleccionados >= MAX_EXAMENES && !cb.checked;
        });
        return seleccionados;
    }

    checkboxes.forEach(cb=>{
        cb.addEventListener('change', ()=>{ actualizarTotal(); });
    });
    actualizarTotal();

    // Validaciones form
    const form = document.getElementById('formOrden'); 
    form.addEventListener('submit', e=>{
        if(!inputHiddenId.value){ 
            e.preventDefault(); 
            showMensaje('Seleccione un paciente.','error'); 
            return; 
        }
        if(document.querySelectorAll('.examen-checkbox:checked').length===0){ 
            e.preventDefault(); 
            showMensaje('Debe seleccionar al menos un examen.','error'); 
            return; 
        }
    });

    document.getElementById('btnLimpiar').addEventListener('click', ()=>{
        form.reset();
        inputBuscar.value='';
        inputHiddenId.value='';
        dpNombres.textContent='';
        dpApellidos.textContent='';
        dpIdentidad.textContent='';
        dpGenero.textContent='';
        totalSpan.textContent='0.00';
        checkboxes.forEach(cb=>cb.disabled=false);
        document.getElementById('datosPaciente').style.display='none';
    });
});
</script>
@endsection
