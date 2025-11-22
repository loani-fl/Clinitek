@extends('layouts.app')

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

.icon-valid {
    color: green;
    font-weight: bold;
}

.icon-invalid {
    color: red;
    font-weight: bold;
}
</style>

<div class="custom-card">

    <h2 class="fw-bold text-center mb-4" style="color:#003b70;">Crear usuario</h2>

    <form id="formUsuario" action="{{ route('usuarios.store') }}" method="POST" novalidate>
        @csrf

        <!-- DATOS PERSONALES -->
        <p class="section-title">Datos personales</p>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       id="name"
                       maxlength="50"
                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                       required
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                <input type="email"
                       name="email"
                       id="email"
                       maxlength="50"
                       placeholder="ejemplo@correo.com"
                       required
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <!-- CONTRASEÑA -->
        <p class="section-title">Credenciales</p>

        <div class="row mb-4">

            {{-- Contraseña --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Contraseña <span class="text-danger">*</span></label>

                <div class="input-group">
                    <input type="password"
                           name="password"
                           id="password"
                           minlength="8"
                           maxlength="20"
                           required
                           class="form-control @error('password') is-invalid @enderror">

                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                </div>

                <!-- Lista de requisitos -->
                <ul id="passwordRules" class="mt-2" style="font-size:14px; list-style:none; padding-left:0;">
                    <li id="rule-length" class="text-danger">
                        <span class="icon-invalid">✖</span> Mínimo 8 caracteres
                    </li>
                    <li id="rule-uppercase" class="text-danger">
                        <span class="icon-invalid">✖</span> Al menos una letra mayúscula
                    </li>
                    <li id="rule-number" class="text-danger">
                        <span class="icon-invalid">✖</span> Al menos un número
                    </li>
                    <li id="rule-special" class="text-danger">
                        <span class="icon-invalid">✖</span> Al menos un carácter especial (!.@#$%^&*)
                    </li>
                </ul>

                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Confirmar contraseña --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                <input type="password"
                       name="password_confirmation"
                       id="password_confirmation"
                       minlength="8"
                       maxlength="20"
                       required
                       class="form-control">
                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
            </div>

        </div>

        <!-- BOTONES -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" id="btnCrear" class="btn btn-primary px-4">
                <i class="bi bi-plus-circle"></i> Crear usuario
            </button>

            <button type="button" id="btnLimpiar" class="btn btn-warning px-4">
                <i class="bi bi-trash"></i> Limpiar
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-success px-4">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');

    const reglas = {
        length: false,
        uppercase: false,
        number: false,
        special: false
    };

    // Limpiar formulario
    document.getElementById('btnLimpiar').addEventListener('click', () => {
        document.getElementById('formUsuario').reset();
        document.querySelectorAll('.is-valid, .is-invalid')
                .forEach(el => el.classList.remove('is-valid', 'is-invalid'));
        resetReglas();
    });

    function resetReglas() {
        Object.keys(reglas).forEach(r => reglas[r] = false);
        ['rule-length','rule-uppercase','rule-number','rule-special'].forEach(id => {
            const li = document.getElementById(id);
            li.classList.remove('text-success');
            li.classList.add('text-danger');
            li.querySelector('span').textContent = '✖';
            li.querySelector('span').classList.add('icon-invalid');
            li.querySelector('span').classList.remove('icon-valid');
        });
    }

    // Validación de contraseña
    password.addEventListener('input', () => {
        const value = password.value;

        reglas.length   = value.length >= 8;
        reglas.uppercase = /[A-Z]/.test(value);
        reglas.number    = /[0-9]/.test(value);
        reglas.special   = /[!@#$%^&*]/.test(value);

        actualizarRegla('rule-length', reglas.length);
        actualizarRegla('rule-uppercase', reglas.uppercase);
        actualizarRegla('rule-number', reglas.number);
        actualizarRegla('rule-special', reglas.special);

        validarConfirmacion();
    });

    function actualizarRegla(id, estado) {
        const li = document.getElementById(id);
        const icon = li.querySelector('span');

        if (estado) {
            li.classList.remove('text-danger');
            li.classList.add('text-success');
            icon.textContent = "✔";
            icon.classList.remove('icon-invalid');
            icon.classList.add('icon-valid');
        } else {
            li.classList.add('text-danger');
            li.classList.remove('text-success');
            icon.textContent = "✖";
            icon.classList.add('icon-invalid');
            icon.classList.remove('icon-valid');
        }
    }

    // Confirmación de contraseña
    confirmation.addEventListener('input', validarConfirmacion);

    function validarConfirmacion() {
        if (!confirmation.value) {
            confirmation.classList.remove('is-valid', 'is-invalid');
            return;
        }
        if (confirmation.value === password.value) {
            confirmation.classList.add('is-valid');
            confirmation.classList.remove('is-invalid');
        } else {
            confirmation.classList.add('is-invalid');
            confirmation.classList.remove('is-valid');
        }
    }

    // Mostrar/ocultar contraseña
    document.getElementById('togglePassword').addEventListener('click', () => {
        const type = password.type === "password" ? "text" : "password";
        password.type = type;
        confirmation.type = type;

        const icon = document.querySelector('#togglePassword i');
        icon.classList.toggle('bi-eye-fill');
        icon.classList.toggle('bi-eye-slash-fill');
    });

});
</script>

@endsection
