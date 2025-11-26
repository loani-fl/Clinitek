@extends('layouts.app')

@section('title', 'Registrar Usuario')

@section('content')

<style>
.custom-card {
    max-width: 900px;
    background-color: rgba(255, 255, 255, 0.97);
    border: 1px solid #91cfff;
    border-radius: 0.8rem;
    padding: 2rem 2.5rem;
    box-shadow: 0 0 15px rgba(0,123,255,0.25);
    margin: 2.5rem auto;
    position: relative;
}

.custom-card::before {
    content: "";
    position: absolute;
    top: 50%; left: 50%;
    width: 700px; height: 700px;
    background-image: url('/images/logo2.jpg');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.15;
    transform: translate(-50%, -50%);
    pointer-events: none;
}

.section-title {
    font-weight: 600;
    font-size: 20px;
    color: #003b70;
    margin: 2rem 0 1rem 0;
    padding-bottom: 6px;
    border-bottom: 3px solid #007BFF;
    width: 100%;
}

#personaSeleccionada {
    background-color: #f1f9ff;
    border: 1px solid #91cfff;
    border-radius: 0.5rem;
    padding: 1rem;
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    margin-top: 0.5rem;
}

#personaSeleccionada div { flex: 1 1 200px; }

#searchPersona {
    max-width: 400px;
    width: 100%;
}

#personaSeleccionada p { margin: 0.2rem 0; font-size: 15px; }

.icon-valid { color: green; font-weight: bold; }
.icon-invalid { color: red; font-weight: bold; }

#results { max-height: 120px; overflow-y: auto; z-index: 1000; }

.form-select, .form-control {
    background-color: #f1f9ff;
    border: 1px solid #91cfff;
    border-radius: 0.5rem;
    padding: 0.6rem 1rem;
    font-size: 15px;
    color: #003b70;
    transition: all 0.3s ease;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
}

.form-select:focus, .form-control:focus {
    border-color: #007BFF;
    box-shadow: 0 0 5px rgba(0,123,255,0.3);
    outline: none;
}
</style>

<div class="custom-card">
    <h2 class="fw-bold text-center mb-4" style="color:#003b70;">Crear usuario</h2>

    <form id="formUsuario" action="{{ route('usuarios.store') }}" method="POST" novalidate>
        @csrf

        <!-- DATOS PERSONALES -->
<p class="section-title">Datos personales</p>
<div class="row mb-4">
    <div class="col-md-12 mb-3 position-relative">
        <label class="form-label">Buscar persona <span class="text-danger">*</span></label>
        <input type="text" id="searchPersona" class="form-control" placeholder="Escribe el nombre o apellido..." 
               value="{{ old('nombre') ? old('nombre').' '.old('apellido') : '' }}">
        <div id="results" class="list-group position-absolute w-100"></div>

        <input type="hidden" name="persona_id" id="persona_id" value="{{ old('persona_id') }}">
        <input type="hidden" name="nombre" id="nombre" value="{{ old('nombre') }}">
        <input type="hidden" name="apellido" id="apellido" value="{{ old('apellido') }}">
        <input type="hidden" name="correo" id="correo" value="{{ old('correo') }}">

        <!-- NUEVOS CAMPOS -->
        <input type="hidden" name="telefono" id="telefono" value="{{ old('telefono') }}">
        <input type="hidden" name="identidad" id="identidad" value="{{ old('identidad') }}">
        <input type="hidden" name="genero" id="genero" value="{{ old('genero') }}">

        @error('persona_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror

        <div id="personaSeleccionada" style="display: {{ old('persona_id') ? 'flex' : 'none' }};">
            <div><p><strong>Nombre:</strong> <span id="displayNombre">{{ old('nombre') }}</span></p></div>
            <div><p><strong>Apellido:</strong> <span id="displayApellido">{{ old('apellido') }}</span></p></div>
            <div><p><strong>Correo:</strong> <span id="displayCorreo">{{ old('correo') }}</span></p></div>

            <!-- NUEVOS DATOS -->
            <div><p><strong>Teléfono:</strong> <span id="displayTelefono">{{ old('telefono') }}</span></p></div>
            <div><p><strong>Identidad:</strong> <span id="displayIdentidad">{{ old('identidad') }}</span></p></div>
            <div><p><strong>Género:</strong> <span id="displayGenero">{{ old('genero') }}</span></p></div>
        </div>
    </div>
</div>


        <!-- DATOS DE INICIO DE SESIÓN Y ROL -->
        <p class="section-title">Credenciales de inicio de sesión</p>
        <div class="row mb-4">

            <!-- Usuario -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Usuario <span class="text-danger">*</span></label>
                <input type="text" 
                       name="usuario" 
                       id="usuario" 
                       class="form-control" 
                       placeholder="Nombre de usuario"
                       maxlength="50"
                       required>
                <div class="invalid-feedback">Debe ingresar un usuario.</div>
                <div class="valid-feedback">Usuario válido</div>
            </div>

            <!-- Correo -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Correo <span class="text-danger">*</span></label>
                <input type="email" 
                       name="correo_usuario" 
                       id="correo_usuario" 
                       class="form-control @error('correo_usuario') is-invalid @enderror" 
                       placeholder="ejemplo: usuario@gmail.com"
                       maxlength="50"
                       value="{{ old('correo_usuario') }}"
                       required>
                <div class="invalid-feedback">
                    @error('correo_usuario') {{ $message }} @else Debe ingresar un correo válido. @enderror
                </div>
                <div class="valid-feedback">Correo válido</div>
            </div>

            <!-- Rol -->
            <div class="col-md-4 mb-3" id="divRol">
                <label class="form-label">Asignar rol <span class="text-danger">*</span></label>
                <select name="rol_id" id="rol_id" class="form-select" required>
                    <option value="">-- Seleccione un rol --</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                    @endforeach
                </select>
                @error('rol_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Contraseña -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" name="password" id="password" minlength="8" maxlength="20" required class="form-control">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                </div>
                <ul id="passwordRules" class="mt-2" style="font-size:14px; list-style:none; padding-left:0;">
                    <li id="rule-length" class="text-danger"><span class="icon-invalid">✖</span> Mínimo 8 caracteres</li>
                    <li id="rule-uppercase" class="text-danger"><span class="icon-invalid">✖</span> Al menos una letra mayúscula</li>
                    <li id="rule-number" class="text-danger"><span class="icon-invalid">✖</span> Al menos un número</li>
                    <li id="rule-special" class="text-danger"><span class="icon-invalid">✖</span> Al menos un carácter especial (!.@#$%^&*)</li>
                </ul>
            </div>

            <!-- Confirmación contraseña -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" maxlength="20" required class="form-control">
                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
            </div>

        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus-circle"></i> Crear usuario</button>
            <button type="button" id="btnLimpiar" class="btn btn-warning px-4"><i class="bi bi-trash"></i> Limpiar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-success px-4"><i class="bi bi-arrow-left"></i> Regresar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');
    const reglas = { length:false, uppercase:false, number:false, special:false };

    const searchInput = document.getElementById('searchPersona');
    const resultsDiv = document.getElementById('results');
    const personaIdInput = document.getElementById('persona_id');
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const correoInput = document.getElementById('correo');
    const telefonoInput = document.getElementById('telefono');
    const identidadInput = document.getElementById('identidad');
    const generoInput = document.getElementById('genero');

    const displayTelefono = document.getElementById('displayTelefono');
    const displayIdentidad = document.getElementById('displayIdentidad');
    const displayGenero = document.getElementById('displayGenero');
    const displayDiv = document.getElementById('personaSeleccionada');
    const displayNombre = document.getElementById('displayNombre');
    const displayApellido = document.getElementById('displayApellido');
    const displayCorreo = document.getElementById('displayCorreo');
    const form = document.getElementById('formUsuario');

    const usuarioInput = document.getElementById('usuario');
    const correoUsuarioInput = document.getElementById('correo_usuario');
    const btnLimpiar = document.getElementById('btnLimpiar');

    // Validación usuario en tiempo real
    usuarioInput.addEventListener('input', () => {
        if(usuarioInput.value.trim() === "") {
            usuarioInput.classList.remove('is-valid','is-invalid');
        } else {
            usuarioInput.classList.add('is-valid');
            usuarioInput.classList.remove('is-invalid');
        }
    });

    // Validación correo en tiempo real
    correoUsuarioInput.addEventListener('input', () => {
        const regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if(correoUsuarioInput.value.trim() === "") {
            correoUsuarioInput.classList.remove('is-valid','is-invalid');
        } else if(regexCorreo.test(correoUsuarioInput.value.trim())) {
            correoUsuarioInput.classList.add('is-valid');
            correoUsuarioInput.classList.remove('is-invalid');
        } else {
            correoUsuarioInput.classList.add('is-invalid');
            correoUsuarioInput.classList.remove('is-valid');
        }
    });

    // Validación contraseña
    password.addEventListener('input', () => {
        const value = password.value;
        reglas.length = value.length >= 8;
        reglas.uppercase = /[A-Z]/.test(value);
        reglas.number = /[0-9]/.test(value);
        reglas.special = /[!@#$%^&*]/.test(value);

        ['rule-length','rule-uppercase','rule-number','rule-special'].forEach(id => {
            const li = document.getElementById(id);
            const icon = li.querySelector('span');
            const estado = reglas[id.replace('rule-','')];
            if (estado) { li.classList.replace('text-danger','text-success'); icon.textContent="✔"; }
            else { li.classList.replace('text-success','text-danger'); icon.textContent="✖"; }
        });
        validarConfirmacion();
    });

    function validarConfirmacion() {
        if (!confirmation.value) { confirmation.classList.remove('is-valid','is-invalid'); return; }
        confirmation.classList.toggle('is-valid', confirmation.value === password.value);
        confirmation.classList.toggle('is-invalid', confirmation.value !== password.value);
    }
    confirmation.addEventListener('input', validarConfirmacion);

    document.getElementById('togglePassword').addEventListener('click', () => {
        const type = password.type === "password" ? "text" : "password";
        password.type = type; confirmation.type = type;
        const icon = document.querySelector('#togglePassword i');
        icon.classList.toggle('bi-eye-fill'); icon.classList.toggle('bi-eye-slash-fill');
    });

    // Validación al enviar
    form.addEventListener('submit', (e)=>{
        let valid = true;

        if(!usuarioInput.value.trim()) { usuarioInput.classList.add('is-invalid'); valid = false; }
        else { usuarioInput.classList.remove('is-invalid'); usuarioInput.classList.add('is-valid'); }

        const regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if(!correoUsuarioInput.value.trim() || !regexCorreo.test(correoUsuarioInput.value.trim())){
            correoUsuarioInput.classList.add('is-invalid'); valid = false;
        } else { correoUsuarioInput.classList.remove('is-invalid'); correoUsuarioInput.classList.add('is-valid'); }

        if(!personaIdInput.value){
            valid = false;
            searchInput.classList.add('is-invalid');
            if(!document.getElementById('errorPersona')){
                const divError = document.createElement('div');
                divError.id = 'errorPersona';
                divError.classList.add('invalid-feedback');
                divError.textContent = 'Debe buscar y seleccionar una persona de la lista.';
                searchInput.parentNode.appendChild(divError);
            }
        } else {
            searchInput.classList.remove('is-invalid');
            const err = document.getElementById('errorPersona'); if(err) err.remove();
        }

        const rolSelect = document.getElementById('rol_id');
        if(!rolSelect.value){
            valid = false;
            rolSelect.classList.add('is-invalid');
            if(!document.getElementById('errorRol')){
                const divError = document.createElement('div');
                divError.id = 'errorRol';
                divError.classList.add('invalid-feedback');
                divError.textContent = 'Debe seleccionar un rol.';
                rolSelect.parentNode.appendChild(divError);
            }
        } else {
            rolSelect.classList.remove('is-invalid');
            const errRol = document.getElementById('errorRol'); if(errRol) errRol.remove();
        }

        if(!valid) e.preventDefault();
    });

    // Limpiar TODO
    btnLimpiar.addEventListener('click', () => {
        form.reset();
        searchInput.value = '';
        personaIdInput.value = '';
        nombreInput.value = '';
        apellidoInput.value = '';
        correoInput.value = '';
        displayDiv.style.display = 'none';
        displayNombre.textContent = '';
        displayApellido.textContent = '';
        displayCorreo.textContent = '';
        resultsDiv.innerHTML = '';
        form.querySelectorAll('input, select, textarea').forEach(el => el.classList.remove('is-valid','is-invalid'));
        ['rule-length','rule-uppercase','rule-number','rule-special'].forEach(id => {
            const li = document.getElementById(id);
            li.classList.replace('text-success','text-danger');
            li.querySelector('span').textContent = "✖";
        });
        const errPersona = document.getElementById('errorPersona'); if(errPersona) errPersona.remove();
        const errRol = document.getElementById('errorRol'); if(errRol) errRol.remove();
    });

    // Autocomplete persona
    searchInput.addEventListener('input', function() {
        searchInput.classList.remove('is-invalid');
        const err = document.getElementById('errorPersona'); if(err) err.remove();

        const query = this.value.trim();
        if(query.length < 1){ resultsDiv.innerHTML=''; return; }

        fetch(`/personas/search?q=${encodeURIComponent(query)}`)
            .then(resp=>resp.json())
            .then(data=>{
                resultsDiv.innerHTML='';
                const filtrados = data.filter(p =>
                    p.nombre.toLowerCase().includes(query.toLowerCase()) ||
                    p.apellido.toLowerCase().includes(query.toLowerCase())
                );
                if(filtrados.length>0){
                    filtrados.forEach(p=>{
                        const item=document.createElement('a');
                        item.href="#"; item.classList.add('list-group-item','list-group-item-action');
                        item.textContent=p.nombre_completo;
                        item.addEventListener('click', e=>{
                            e.preventDefault();
                            personaIdInput.value=p.id;
                            nombreInput.value=p.nombre; apellidoInput.value=p.apellido; correoInput.value=p.correo;
                            displayNombre.textContent=p.nombre; displayApellido.textContent=p.apellido; displayCorreo.textContent=p.correo;
                            telefonoInput.value = p.telefono;
                            identidadInput.value = p.identidad;
                            generoInput.value = p.genero;

                            displayTelefono.textContent = p.telefono;
                            displayIdentidad.textContent = p.identidad;
                            displayGenero.textContent = p.genero;
                            displayDiv.style.display='flex';
                            searchInput.value=p.nombre_completo; resultsDiv.innerHTML='';
                        });
                        resultsDiv.appendChild(item);
                    });
                } else {
                    const noItem=document.createElement('div'); noItem.classList.add('list-group-item'); noItem.textContent="No se encontraron resultados";
                    resultsDiv.appendChild(noItem);
                }
            });
    });

    document.addEventListener('click', e=>{ if(!resultsDiv.contains(e.target) && e.target!==searchInput) resultsDiv.innerHTML=''; });
});
</script>

@endsection
