dame la vista completa @extends('layouts.app')

@section('content')

<style>
.custom-card {
    max-width: 1000px;
    background-color: rgba(255, 255, 255, 0.95);
    border: 1px solid #91cfff;
    border-radius: 0.5rem;
    position: relative;
    overflow: hidden;
    margin: 2rem auto;
    padding: 2rem;
    box-shadow: 0 0 15px rgba(0,123,255,0.25);
    z-index: 1;
    min-height: 650px;
}

.custom-card::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 800px;
    height: 800px;
    background-image: url('/images/logo2.jpg');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.15;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 0;
}

input.is-invalid, textarea.is-invalid, select.is-invalid {
    border-color: red;
}

.invalid-feedback {
    display: block;
}

.alert-custom {
    padding: 0.75rem;
    border-radius: 0.25rem;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.lista-resultados {
    position: absolute;
    z-index: 1000;
    background: white;
    border: 1px solid #ced4da;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    max-height: 280px;
    overflow-y: auto;
    width: 100%;
    margin-top: 0;
}

.resultado-item {
    padding: 0.875rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f1f3f5;
    transition: all 0.2s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.resultado-item:hover {
    background: linear-gradient(to right, #e3f2fd, #f8f9fa);
    border-left: 3px solid #007BFF;
    padding-left: calc(1rem - 3px);
}

.resultado-item:last-child {
    border-bottom: none;
    border-radius: 0 0 0.375rem 0.375rem;
}

.resultado-info {
    flex-grow: 1;
}

.resultado-nombre {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.resultado-identidad {
    font-size: 0.875rem;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.resultado-icono {
    color: #007BFF;
    font-size: 1.25rem;
}

.no-resultados {
    padding: 1.5rem;
    text-align: center;
    color: #6c757d;
    font-style: italic;
}

.form-control-lg {
    height: calc(3rem + 2px);
    font-size: 1rem;
    border: 2px solid #ced4da;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #007BFF;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
}

.form-control:focus {
    border-color: #007BFF;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
}
</style>

<div class="custom-card">
    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Registro de Emergencia</h2>
    </div>

    @php
        $docFieldsVisible = old('documentado', 1) == 1;
        $fechaActual = old('fecha', date('Y-m-d'));
        $horaActual = old('hora', date('H:i:s'));
    @endphp

    <form id="formEmergencia" action="{{ route('emergencias.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Radio Documentado / Indocumentado --}}
        <div class="mb-4 d-flex gap-3">
            <label>
                <input type="radio" name="documentado" value="1" {{ $docFieldsVisible ? 'checked' : '' }} onclick="toggleDocs()"> Documentado
            </label>
            <label>
                <input type="radio" name="documentado" value="0" {{ !$docFieldsVisible ? 'checked' : '' }} onclick="toggleDocs()"> Indocumentado
            </label>
        </div>

        {{-- Campos Documentado --}}
        <div class="docFields" style="display: {{ $docFieldsVisible ? 'block' : 'none' }};">
            
            <h5 class="mb-3 text-dark fw-bold">
                <i class="fas fa-user-circle"></i> Datos básicos y contacto
            </h5>
            
            {{-- Buscador de pacientes --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <label style="font-weight: 700; font-size: 0.95rem;">
                        <i class="fas fa-search"></i> BUSCAR PACIENTE
                    </label>
                    <div style="position: relative;">
                        <input 
                            type="text" 
                            id="buscarIdentidad" 
                            class="form-control" 
                            placeholder="Buscar por identidad..."
                            maxlength="13"
                            autocomplete="off"
                            style="padding-right: 40px; height: 38px; font-size: 0.9rem;">
                        <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                        <div id="listaResultados" class="lista-resultados" style="display: none;"></div>
                    </div>
                    <div id="mensajeBusqueda"></div>
                </div>
            </div>

            <div class="row mb-3">
                @foreach([
                    ['label'=>'Nombre(s)','name'=>'nombres','type'=>'text','size'=>'50'],
                    ['label'=>'Apellidos','name'=>'apellidos','type'=>'text','size'=>'50'],
                    ['label'=>'Identidad','name'=>'identidad','type'=>'text','size'=>'13','placeholder'=>'Ej: 0703200201564'],
                    ['label'=>'Fecha de Nacimiento','name'=>'fecha_nacimiento','type'=>'date']
                ] as $field)
                <div class="col-md-3">
                    <label>{{ $field['label'] }}: <span class="text-danger">*</span></label>
                    <input 
                        type="{{ $field['type'] }}" 
                        name="{{ $field['name'] }}" 
                        id="{{ $field['name'] }}"
                        maxlength="{{ $field['size'] ?? '' }}" 
                        placeholder="{{ $field['placeholder'] ?? '' }}"
                        class="form-control @error($field['name']) is-invalid @enderror" 
                        value="{{ old($field['name']) }}">
                    @error($field['name']) <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                @endforeach
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Teléfono: <span class="text-danger">*</span></label>
                    <input type="text" name="telefono" id="telefono" maxlength="8" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label>Tipo de Sangre (opcional):</label>
                    <select name="tipo_sangre" id="tipo_sangre" class="form-select @error('tipo_sangre') is-invalid @enderror">
                        <option value="">Seleccione...</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_sangre')==$tipo?'selected':'' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_sangre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label>Género: <span class="text-danger">*</span></label>
                    <select name="genero" id="genero" class="form-select @error('genero') is-invalid @enderror">
                        <option value="">Seleccione...</option>
                        <option value="Femenino" {{ old('genero')=='Femenino'?'selected':'' }}>Femenino</option>
                        <option value="Masculino" {{ old('genero')=='Masculino'?'selected':'' }}>Masculino</option>
                        <option value="Otro" {{ old('genero')=='Otro'?'selected':'' }}>Otro</option>
                    </select>
                    @error('genero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label>Dirección: <span class="text-danger">*</span></label>
                    <textarea name="direccion" id="direccion" rows="2" maxlength="300" class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion') }}</textarea>
                    @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

{{-- Campos Indocumentado con Foto, Fecha, Hora y Edad en la misma fila --}}
<div class="indocFields" style="display: {{ !$docFieldsVisible ? 'block' : 'none' }};">
    <div class="row mb-3 align-items-end"> {{-- align-items-end alinea los labels abajo --}}
        <div class="col-md-6">
            <label>Foto del paciente:</label>
            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-2">
            <label class="form-label">Fecha:</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $fechaActual }}">
        </div>

        <div class="col-md-2">
            <label class="form-label">Hora:</label>
            <input type="time" name="hora" id="hora" class="form-control" value="{{ $horaActual }}">
        </div>

        <div class="col-md-2">
            <label class="form-label">Edad <span class="text-danger">*</span></label>
            <input type="number" name="edad" id="edad" min="0" max="105"
                class="form-control @error('edad') is-invalid @enderror"
                value="{{ old('edad') }}">
            @error('edad') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>
    </div>
</div>




        {{-- Motivo de la emergencia --}}
        <h5 class="mt-4 mb-3 text-dark fw-bold">Motivo de la emergencia</h5>
        <div class="row mb-3">
            <div class="col-md-7">
                <textarea name="motivo" rows="2" maxlength="300" class="form-control @error('motivo') is-invalid @enderror">{{ old('motivo') }}</textarea>
                @error('motivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

   {{-- Signos vitales --}}
<h5 class="mt-4 mb-3 text-dark fw-bold">Signos Vitales</h5>
<div class="row mb-3">
    @foreach([
        ['label'=>'Presión Arterial','name'=>'pa','type'=>'text','placeholder'=>'Ej: 120/80',
         'note'=>'<strong>Ingrese presión arterial sistólica / diastólica</strong> (Ej: 120/80). Permite valores normales y elevados según emergencia)'],
        ['label'=>'Frecuencia Cardíaca','name'=>'fc','type'=>'number',
         'note'=>'<strong>Ingrese frecuencia cardíaca en reposo o emergencia</strong> (Ej: 60–100 lpm normales, cualquier valor en urgencia permitido)'],
        ['label'=>'Temperatura (°C)','name'=>'temp','type'=>'number','step'=>'0.1',
         'note'=>'<strong>Ingrese temperatura corporal</strong> (normal: 36–37.5 °C, cualquier valor permitido en emergencia)']
    ] as $vital)
    <div class="col-md-4">
        <label>{{ $vital['label'] }}:</label>
        <input 
            type="{{ $vital['type'] }}" 
            name="{{ $vital['name'] }}" 
            placeholder="{{ $vital['placeholder'] ?? '' }}"
            class="form-control @error($vital['name']) is-invalid @enderror" 
            value="{{ old($vital['name']) }}"
            @if(isset($vital['step'])) step="{{ $vital['step'] }}" @endif
            maxlength="{{ $vital['name']=='pa'?7:'' }}">
        @if(isset($vital['note'])) <small class="text-muted">{!! $vital['note'] !!}</small> @endif
        @error($vital['name']) <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @endforeach
</div>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">Registrar</button>
            <button type="button" id="btnLimpiar" class="btn btn-warning">Limpiar</button>
            <a href="{{ route('emergencias.index') }}" class="btn btn-success">Regresar</a>
        </div>
    </form>
</div>

<script>
function toggleDocs(){
    let doc = document.querySelector('input[name="documentado"][value="1"]').checked;
    document.querySelectorAll('.docFields').forEach(el => el.style.display = doc ? 'block' : 'none');
    document.querySelectorAll('.indocFields').forEach(el => el.style.display = doc ? 'none' : 'block');
}

// Limpiar formulario completo incluyendo validaciones y mensajes
document.getElementById('btnLimpiar').addEventListener('click', () => {
    const form = document.getElementById('formEmergencia');

    // Resetea todos los campos
    form.reset();

    // Quita clases de error
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    // Oculta mensajes de error
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

    // Limpiar buscador
    document.getElementById('buscarIdentidad').value = '';
    document.getElementById('listaResultados').style.display = 'none';
    document.getElementById('listaResultados').innerHTML = '';
    document.getElementById('mensajeBusqueda').innerHTML = '';

    // Limpiar campos de paciente
    limpiarCamposPaciente();

    // Mantener visibilidad de campos según radio
    toggleDocs();
});

// Función para limpiar campos de paciente
function limpiarCamposPaciente() {
    const campos = ['nombres','apellidos','identidad','fecha_nacimiento','telefono','tipo_sangre','genero','direccion'];
    campos.forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            if(el.tagName.toLowerCase() === 'select') el.selectedIndex = 0;
            else el.value = '';
        }
    });
}

// Variables para búsqueda de pacientes
let timeoutBusqueda = null;

document.getElementById('buscarIdentidad').addEventListener('input', function() {
    const identidad = this.value.trim();
    const listaDiv = document.getElementById('listaResultados');
    const mensajeDiv = document.getElementById('mensajeBusqueda');

    clearTimeout(timeoutBusqueda);

    if (!identidad) {
        listaDiv.style.display = 'none';
        listaDiv.innerHTML = '';
        mensajeDiv.innerHTML = '';
        limpiarCamposPaciente();
        return;
    }

    timeoutBusqueda = setTimeout(() => {
        buscarPacientes(identidad);
    }, 300);
});

function buscarPacientes(identidad) {
    const listaDiv = document.getElementById('listaResultados');
    const mensajeDiv = document.getElementById('mensajeBusqueda');

    const url = `{{ route('pacientes.buscar') }}?identidad=${encodeURIComponent(identidad)}`;

    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.pacientes.length > 0) {
            let html = '';
            data.pacientes.forEach(p => {
                html += `
                    <div class="resultado-item" onclick="seleccionarPaciente(${p.id})">
                        <div class="resultado-info">
                            <div class="resultado-nombre">${p.nombre} ${p.apellidos}</div>
                            <div class="resultado-identidad"><i class="fas fa-id-card"></i> ${p.identidad}</div>
                        </div>
                        <i class="fas fa-chevron-right resultado-icono"></i>
                    </div>`;
            });
            listaDiv.innerHTML = html;
            listaDiv.style.display = 'block';
            mensajeDiv.innerHTML = '';
        } else {
            listaDiv.innerHTML = '<div class="no-resultados"><i class="fas fa-user-slash"></i><br>No se encontraron pacientes</div>';
            listaDiv.style.display = 'block';
        }
    })
    .catch(() => {
        listaDiv.style.display = 'none';
        mensajeDiv.innerHTML = '<div class="alert alert-danger alert-custom mt-2">Error al buscar pacientes</div>';
    });
}

function seleccionarPaciente(id) {
    fetch(`{{ url('pacientes/datos') }}/${id}`)
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                document.getElementById('nombres').value = data.paciente.nombres || '';
                document.getElementById('apellidos').value = data.paciente.apellidos || '';
                document.getElementById('identidad').value = data.paciente.identidad || '';
                document.getElementById('fecha_nacimiento').value = data.paciente.fecha_nacimiento || '';
                document.getElementById('telefono').value = data.paciente.telefono || '';
                document.getElementById('direccion').value = data.paciente.direccion || '';
                document.getElementById('genero').value = data.paciente.genero || '';
                if(data.paciente.tipo_sangre) document.getElementById('tipo_sangre').value = data.paciente.tipo_sangre;

                document.getElementById('buscarIdentidad').value = data.paciente.identidad;
                document.getElementById('listaResultados').style.display = 'none';
                document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-success alert-custom mt-2"><i class="fas fa-check-circle"></i> Datos cargados correctamente</div>';

                setTimeout(() => { document.getElementById('mensajeBusqueda').innerHTML = ''; }, 2000);
            }
        })
        .catch(() => {
            document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-danger alert-custom mt-2">Error al cargar datos del paciente</div>';
        });
}

document.addEventListener('click', function(e) {
    const buscarInput = document.getElementById('buscarIdentidad');
    const listaDiv = document.getElementById('listaResultados');
    if(e.target !== buscarInput && !listaDiv.contains(e.target)) {
        listaDiv.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', toggleDocs);

// Permitir solo letras y espacios en nombres y apellidos
['nombres','apellidos'].forEach(id => {
    document.getElementById(id).addEventListener('keypress', function(e){
        const char = String.fromCharCode(e.which);
        if(!/[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/.test(char)) e.preventDefault();
    });
});

// Permitir solo números en teléfono e identidad
['telefono','identidad','fc'].forEach(id => {
    document.getElementById(id).addEventListener('keypress', function(e){
        const char = String.fromCharCode(e.which);
        if(!/[0-9]/.test(char)) e.preventDefault();
    });
});

// Permitir números y '/' en presión arterial
document.getElementById('pa').addEventListener('keypress', function(e){
    const char = String.fromCharCode(e.which);
    if(!/[0-9\/]/.test(char)) e.preventDefault();
});
// Presión arterial: agregar '/' automáticamente
document.getElementById('pa').addEventListener('input', function(e){
    let val = this.value.replace(/[^0-9]/g,''); // solo números
    if(val.length > 3){
        this.value = val.slice(0,3) + '/' + val.slice(3,5);
    } else {
        this.value = val;
    }
});

// Permitir cualquier número en FC y temperatura, sin bloquear (emergencia)
['fc','temp'].forEach(id => {
    document.getElementById(id).addEventListener('input', function(){
        this.value = this.value.replace(/[^0-9.]/g,'');
    });
});
</script>

@endsection
