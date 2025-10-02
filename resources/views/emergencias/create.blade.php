@extends('layouts.app')

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
    min-height: 600px;
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
            
            {{-- Buscador de pacientes en una sola fila --}}
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

        {{-- Campos Indocumentado --}}
        <div class="indocFields" style="display: {{ !$docFieldsVisible ? 'block' : 'none' }};">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Foto del paciente:</label>
                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
                    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Motivo de la emergencia --}}
        <h5 class="mt-4 mb-3 text-dark fw-bold">Motivo de la emergencia</h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <textarea name="motivo" rows="2" maxlength="300" class="form-control @error('motivo') is-invalid @enderror">{{ old('motivo') }}</textarea>
                @error('motivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Signos vitales --}}
        <h5 class="mt-4 mb-3 text-dark fw-bold">Signos Vitales</h5>
        <div class="row mb-3">
            @foreach([
                ['label'=>'Presión Arterial','name'=>'pa','type'=>'text','placeholder'=>'Ej: 120/80'],
                ['label'=>'Frecuencia Cardíaca','name'=>'fc','type'=>'number'],
                ['label'=>'Temperatura (°C)','name'=>'temp','type'=>'number','step'=>'0.1']
            ] as $vital)
            <div class="col-md-4">
                <label>{{ $vital['label'] }}:</label>
                <input 
                    type="{{ $vital['type'] }}" 
                    name="{{ $vital['name'] }}" 
                    placeholder="{{ $vital['placeholder'] ?? '' }}"
                    step="{{ $vital['step'] ?? '' }}"
                    class="form-control @error($vital['name']) is-invalid @enderror" 
                    value="{{ old($vital['name']) }}">
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

// Limpiar formulario
document.getElementById('btnLimpiar').addEventListener('click', () => {
    const form = document.getElementById('formEmergencia');
    form.querySelectorAll('input, textarea, select').forEach(el => {
        if(el.type === 'radio') return;
        if(el.tagName.toLowerCase() === 'select') el.selectedIndex = 0;
        else el.value = '';
    });
    document.getElementById('mensajeBusqueda').innerHTML = '';
});

// Variable para controlar el timeout del debounce
let timeoutBusqueda = null;

// Buscar paciente automáticamente mientras escribe
document.getElementById('buscarIdentidad').addEventListener('input', function() {
    const identidad = this.value.trim();
    const listaDiv = document.getElementById('listaResultados');
    const mensajeDiv = document.getElementById('mensajeBusqueda');
    
    // Limpiar timeout anterior
    clearTimeout(timeoutBusqueda);
    
    // Si está vacío, ocultar lista y limpiar campos
    if (!identidad) {
        listaDiv.style.display = 'none';
        listaDiv.innerHTML = '';
        mensajeDiv.innerHTML = '';
        limpiarCamposPaciente();
        return;
    }
    
    // Esperar 300ms después de que el usuario deje de escribir
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
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.pacientes && data.pacientes.length > 0) {
                // Mostrar lista de resultados con mejor diseño
                let html = '';
                data.pacientes.forEach(paciente => {
                    html += `
                        <div class="resultado-item" onclick="seleccionarPaciente(${paciente.id})">
                            <div class="resultado-info">
                                <div class="resultado-nombre">${paciente.nombre} ${paciente.apellidos}</div>
                                <div class="resultado-identidad">
                                    <i class="fas fa-id-card"></i>
                                    <span>${paciente.identidad}</span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right resultado-icono"></i>
                        </div>
                    `;
                });
                listaDiv.innerHTML = html;
                listaDiv.style.display = 'block';
                mensajeDiv.innerHTML = '';
            } else {
                // No hay resultados
                listaDiv.innerHTML = '<div class="no-resultados"><i class="fas fa-user-slash"></i><br>No se encontraron pacientes</div>';
                listaDiv.style.display = 'block';
                mensajeDiv.innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            listaDiv.style.display = 'none';
            mensajeDiv.innerHTML = '<div class="alert alert-danger alert-custom mt-2">Error al buscar pacientes</div>';
        });
}

function seleccionarPaciente(pacienteId) {
    const url = `{{ url('pacientes/datos') }}/${pacienteId}`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Llenar campos con datos del paciente
                document.getElementById('nombres').value = data.paciente.nombres || '';
                document.getElementById('apellidos').value = data.paciente.apellidos || '';
                document.getElementById('identidad').value = data.paciente.identidad || '';
                document.getElementById('fecha_nacimiento').value = data.paciente.fecha_nacimiento || '';
                document.getElementById('telefono').value = data.paciente.telefono || '';
                document.getElementById('genero').value = data.paciente.genero || '';
                document.getElementById('direccion').value = data.paciente.direccion || '';
                
                if (data.paciente.tipo_sangre) {
                    document.getElementById('tipo_sangre').value = data.paciente.tipo_sangre;
                }
                
                // Actualizar campo de búsqueda
                document.getElementById('buscarIdentidad').value = data.paciente.identidad;
                
                // Ocultar lista
                document.getElementById('listaResultados').style.display = 'none';
                document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-success alert-custom mt-2"><i class="fas fa-check-circle"></i> Datos cargados correctamente</div>';
                
                // Ocultar mensaje después de 2 segundos
                setTimeout(() => {
                    document.getElementById('mensajeBusqueda').innerHTML = '';
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('mensajeBusqueda').innerHTML = '<div class="alert alert-danger alert-custom mt-2">Error al cargar datos del paciente</div>';
        });
}

// Cerrar lista al hacer clic fuera
document.addEventListener('click', function(e) {
    const buscarInput = document.getElementById('buscarIdentidad');
    const listaDiv = document.getElementById('listaResultados');
    
    if (e.target !== buscarInput && !listaDiv.contains(e.target)) {
        listaDiv.style.display = 'none';
    }
});

function limpiarCamposPaciente() {
    document.getElementById('nombres').value = '';
    document.getElementById('apellidos').value = '';
    document.getElementById('identidad').value = '';
    document.getElementById('fecha_nacimiento').value = '';
    document.getElementById('telefono').value = '';
    document.getElementById('tipo_sangre').selectedIndex = 0;
    document.getElementById('genero').selectedIndex = 0;
    document.getElementById('direccion').value = '';
}
</script>

@endsection