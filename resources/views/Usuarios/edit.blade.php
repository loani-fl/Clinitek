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
</style>

<div class="custom-card">

    <h2 class="fw-bold text-center mb-4" style="color:#003b70;">Editar usuario</h2>

    <form id="formUsuario" action="{{ route('usuarios.update', $usuario->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <!-- DATOS PERSONALES -->
        <p class="section-title">Datos personales</p>

        <div class="row mb-4">

            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Nombre completo <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       id="name"
                       maxlength="50"
                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                       required
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $usuario->name) }}"
                       data-original="{{ $usuario->name }}">
                <div class="invalid-feedback">Solo letras y espacios.</div>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                <input type="email"
                       name="email"
                       id="email"
                       maxlength="50"
                       placeholder="ejemplo@correo.com"
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                       required
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $usuario->email) }}"
                       data-original="{{ $usuario->email }}">
                <div class="invalid-feedback" id="emailFeedback">Ingrese un correo válido.</div>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

        </div>

        <!-- CREDENCIALES -->
        <p class="section-title">Credenciales</p>

        <div class="row mb-4">

            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password"
                       name="password"
                       id="password"
                       minlength="8"
                       maxlength="20"
                       pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,20}"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Dejar en blanco para mantener la actual">
                <small class="text-muted">Dejar en blanco si no desea cambiar la contraseña</small>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">Confirmar contraseña:</label>
                <input type="password"
                       name="password_confirmation"
                       id="password_confirmation"
                       minlength="8"
                       maxlength="20"
                       class="form-control"
                       placeholder="Confirme la nueva contraseña">
                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
            </div>

        </div>

        <!-- BOTONES -->
        <div class="d-flex justify-content-center gap-3 mt-4">

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save"></i> Actualizar usuario
            </button>

            <button type="button" id="btnLimpiar" class="btn btn-warning px-4">
                <i class="bi bi-arrow-counterclockwise"></i> Restaurar
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-success px-4">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>

        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const restoreButton = document.getElementById('btnLimpiar');

    // Función para restaurar campos al valor original del usuario antes de cualquier actualización
    function restaurar() {
        const inputs = document.querySelectorAll('#formUsuario input');

        inputs.forEach(input => {
            if(input.dataset.original !== undefined) {
                input.value = input.dataset.original;
            } else if(input.type !== 'hidden') {
                input.value = '';
            }
            input.classList.remove('is-valid', 'is-invalid');
        });
    }

    restoreButton.addEventListener('click', restaurar);

    // Validación nombre
    const name = document.getElementById('name');
    name.addEventListener('keypress', function(e) {
        const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]$/;
        if (!regex.test(String.fromCharCode(e.charCode))) e.preventDefault();
    });

    // Validación email
    const email = document.getElementById('email');
    email.addEventListener('input', () => {
        const pattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
        email.classList.toggle('is-invalid', !pattern.test(email.value));
        email.classList.toggle('is-valid', pattern.test(email.value));
    });

    // Validación contraseñas
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');

    function validarPassword() {
        if(password.value === '' && passwordConfirm.value === '') {
            passwordConfirm.classList.remove('is-valid', 'is-invalid');
            return;
        }
        passwordConfirm.classList.toggle('is-valid', password.value === passwordConfirm.value);
        passwordConfirm.classList.toggle('is-invalid', password.value !== passwordConfirm.value);
    }

    password.addEventListener('input', validarPassword);
    passwordConfirm.addEventListener('input', validarPassword);

});
</script>

@endsection
