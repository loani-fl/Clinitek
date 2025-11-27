@extends('layouts.app')

@section('title', 'Editar Usuario')

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

.datos-persona-readonly {
    background-color: #f1f9ff;
    border: 1px solid #91cfff;
    border-radius: 0.5rem;
    padding: 1rem;
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.datos-persona-readonly div { flex: 1 1 200px; }
.datos-persona-readonly p { margin: 0.2rem 0; font-size: 15px; }

.icon-valid { color: green; font-weight: bold; }
.icon-invalid { color: red; font-weight: bold; }

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

.badge-info {
    background-color: #17a2b8;
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 0.3rem;
    font-size: 13px;
}
</style>

<div class="custom-card">
    <h2 class="fw-bold text-center mb-4" style="color:#003b70;">Editar usuario</h2>

    <form id="formUsuario" action="{{ route('usuarios.update', $usuario->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <!-- DATOS PERSONALES (SOLO LECTURA) -->
        <p class="section-title">Datos personales <span class="badge-info">Solo lectura</span></p>
        <div class="row mb-4">
            <div class="col-md-12 mb-3">
                <div class="datos-persona-readonly">
                    <div><p><strong>Nombre:</strong> {{ $usuario->name }}</p></div>
                    <div><p><strong>Correo:</strong> {{ $usuario->email }}</p></div>
                </div>
            </div>
        </div>

        <!-- DATOS DE INICIO DE SESIÓN Y ROL -->
        <p class="section-title">Credenciales de inicio de sesión</p>
        <div class="row mb-4">

            <!-- Usuario (Solo lectura) -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" 
                       class="form-control" 
                       value="{{ $usuario->name }}"
                       disabled
                       style="background-color: #e9ecef;">
            </div>

            <!-- Correo (Solo lectura) -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Correo</label>
                <input type="email" 
                       class="form-control" 
                       value="{{ $usuario->email }}"
                       disabled
                       style="background-color: #e9ecef;">
            </div>

            <!-- Rol -->
            <div class="col-md-4 mb-3" id="divRol">
                <label class="form-label">Asignar rol <span class="text-danger">*</span></label>
                <select name="rol_id" id="rol_id" class="form-select" required>
                    <option value="">-- Seleccione un rol --</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->id }}" {{ $usuario->roles->contains($rol->id) ? 'selected' : '' }}>
                            {{ $rol->name }}
                        </option>
                    @endforeach
                </select>
                @error('rol_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Nueva Contraseña (Opcional) -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Nueva contraseña (dejar en blanco para mantener la actual)</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" minlength="8" maxlength="20" class="form-control">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                </div>
                <ul id="passwordRules" class="mt-2" style="font-size:14px; list-style:none; padding-left:0; display:none;">
                    <li id="rule-length" class="text-danger"><span class="icon-invalid">✖</span> Mínimo 8 caracteres</li>
                    <li id="rule-uppercase" class="text-danger"><span class="icon-invalid">✖</span> Al menos una letra mayúscula</li>
                    <li id="rule-number" class="text-danger"><span class="icon-invalid">✖</span> Al menos un número</li>
                    <li id="rule-special" class="text-danger"><span class="icon-invalid">✖</span> Al menos un carácter especial (!.@#$%^&*)</li>
                </ul>
            </div>

            <!-- Confirmación contraseña -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" maxlength="20" class="form-control">
                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
            </div>

        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Actualizar usuario</button>
            <button type="button" id="btnLimpiar" class="btn btn-warning px-4"><i class="bi bi-arrow-clockwise"></i> Restablecer</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-success px-4"><i class="bi bi-arrow-left"></i> Regresar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');
    const passwordRules = document.getElementById('passwordRules');
    const reglas = { length:false, uppercase:false, number:false, special:false };
    const form = document.getElementById('formUsuario');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const rolSelect = document.getElementById('rol_id');

    // Guardar valores originales
    const originalRol = rolSelect.value;

    // Validación contraseña (solo si se está escribiendo)
    password.addEventListener('input', () => {
        if (password.value.length > 0) {
            passwordRules.style.display = 'block';
            
            const value = password.value;
            reglas.length = value.length >= 8;
            reglas.uppercase = /[A-Z]/.test(value);
            reglas.number = /[0-9]/.test(value);
            reglas.special = /[!@#$%^&*]/.test(value);

            ['rule-length','rule-uppercase','rule-number','rule-special'].forEach(id => {
                const li = document.getElementById(id);
                const icon = li.querySelector('span');
                const estado = reglas[id.replace('rule-','')];
                if (estado) { 
                    li.classList.remove('text-danger');
                    li.classList.add('text-success');
                    icon.textContent="✔"; 
                    icon.classList.remove('icon-invalid');
                    icon.classList.add('icon-valid');
                } else { 
                    li.classList.remove('text-success');
                    li.classList.add('text-danger');
                    icon.textContent="✖"; 
                    icon.classList.remove('icon-valid');
                    icon.classList.add('icon-invalid');
                }
            });
        } else {
            passwordRules.style.display = 'none';
            confirmation.value = '';
            confirmation.classList.remove('is-valid','is-invalid');
        }
        validarConfirmacion();
    });

    function validarConfirmacion() {
        if (!confirmation.value || !password.value) { 
            confirmation.classList.remove('is-valid','is-invalid'); 
            return; 
        }
        confirmation.classList.toggle('is-valid', confirmation.value === password.value);
        confirmation.classList.toggle('is-invalid', confirmation.value !== password.value);
    }
    confirmation.addEventListener('input', validarConfirmacion);

    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', () => {
        const type = password.type === "password" ? "text" : "password";
        password.type = type; 
        confirmation.type = type;
        const icon = document.querySelector('#togglePassword i');
        icon.classList.toggle('bi-eye-fill'); 
        icon.classList.toggle('bi-eye-slash-fill');
    });

    // Validación al enviar
    form.addEventListener('submit', (e) => {
        let valid = true;

        // Validar rol
        if(!rolSelect.value){
            valid = false;
            rolSelect.classList.add('is-invalid');
            if(!document.getElementById('errorRol')){
                const divError = document.createElement('div');
                divError.id = 'errorRol';
                divError.classList.add('invalid-feedback');
                divError.style.display = 'block';
                divError.textContent = 'Debe seleccionar un rol.';
                rolSelect.parentNode.appendChild(divError);
            }
        } else {
            rolSelect.classList.remove('is-invalid');
            const errRol = document.getElementById('errorRol'); 
            if(errRol) errRol.remove();
        }

        // Validar contraseña solo si se está cambiando
        if (password.value.length > 0) {
            // Verificar que cumpla todas las reglas
            const todasCumplen = Object.values(reglas).every(v => v === true);
            if (!todasCumplen) {
                valid = false;
                password.classList.add('is-invalid');
            } else {
                password.classList.remove('is-invalid');
                password.classList.add('is-valid');
            }

            // Verificar confirmación
            if (password.value !== confirmation.value) {
                valid = false;
                confirmation.classList.add('is-invalid');
            } else {
                confirmation.classList.remove('is-invalid');
                confirmation.classList.add('is-valid');
            }
        }

        if(!valid) {
            e.preventDefault();
            // Scroll al primer error
            const firstError = form.querySelector('.is-invalid');
            if(firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Botón Restablecer
    btnLimpiar.addEventListener('click', () => {
        // Limpiar contraseñas
        password.value = '';
        confirmation.value = '';
        
        // Restaurar rol original
        rolSelect.value = originalRol;
        
        // Ocultar reglas de contraseña
        passwordRules.style.display = 'none';
        
        // Limpiar validaciones
        form.querySelectorAll('input, select').forEach(el => {
            el.classList.remove('is-valid','is-invalid');
        });
        
        // Resetear reglas visuales
        ['rule-length','rule-uppercase','rule-number','rule-special'].forEach(id => {
            const li = document.getElementById(id);
            li.classList.remove('text-success');
            li.classList.add('text-danger');
            const icon = li.querySelector('span');
            icon.textContent = "✖";
            icon.classList.remove('icon-valid');
            icon.classList.add('icon-invalid');
        });
        
        // Eliminar mensajes de error
        const errRol = document.getElementById('errorRol'); 
        if(errRol) errRol.remove();
    });
});
</script>

@endsection