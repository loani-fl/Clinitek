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
</style>

<div class="custom-card">
    <div class="mb-4 text-center" style="border-bottom: 3px solid #007BFF;">
        <h2 class="fw-bold text-black mb-0">Crear usuario</h2>
    </div>

    <form id="formUsuario" action="{{ route('usuarios.store') }}" method="POST" novalidate>
        @csrf

        <!-- Fila 1: Nombre, Correo y Rol -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="name" class="form-label">Nombre: <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       id="name"
                       maxlength="50"
                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
                       title="Solo letras y espacios"
                       required
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}">
                <div class="invalid-feedback">Solo letras y espacios.</div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="email" class="form-label">Correo: <span class="text-danger">*</span></label>
                <input type="email"
                       name="email"
                       id="email"
                       maxlength="50"
                       placeholder="ejemplo@correo.com"
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                       title="Debe ser un correo válido, por ejemplo: ejemplo@correo.com"
                       required
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}">
                <div class="invalid-feedback" id="emailFeedback">Debe ser un correo válido, por ejemplo: ejemplo@correo.com</div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="rol" class="form-label">Rol: <span class="text-danger">*</span></label>
                <select name="rol" id="rol" required class="form-select @error('rol') is-invalid @enderror">
                    <option value="">Seleccione...</option>
                    <option value="admin" {{ old('rol')=='admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="empleado" {{ old('rol')=='empleado' ? 'selected' : '' }}>Empleado</option>
                    <option value="medico" {{ old('rol')=='medico' ? 'selected' : '' }}>Médico</option>
                </select>
                @error('rol')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Fila 2: Contraseña y Confirmar Contraseña -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label">Contraseña: <span class="text-danger">*</span></label>
                <input type="password"
                       name="password"
                       id="password"
                       minlength="8"
                       maxlength="20"
                       pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,20}"
                       title="Debe tener entre 8 y 20 caracteres, incluyendo letras y números"
                       required
                       class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirmar contraseña: <span class="text-danger">*</span></label>
                <input type="password"
                       name="password_confirmation"
                       id="password_confirmation"
                       minlength="8"
                       maxlength="20"
                       pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,20}"
                       title="Debe coincidir con la contraseña ingresada"
                       required
                       class="form-control">
                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
            </div>
        </div>

        <!-- Botones centrados -->
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Crear usuario
            </button>
            <button type="button" id="btnLimpiar" class="btn btn-warning">
                <i class="bi bi-trash"></i> Limpiar
            </button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-success">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Limpiar formulario y validaciones completamente
document.getElementById('btnLimpiar').addEventListener('click', () => {
    const form = document.getElementById('formUsuario');

    // Limpiar valores de inputs y select
    form.querySelectorAll('input, select, textarea').forEach(el => {
        if (el.tagName.toLowerCase() === 'select') {
            el.selectedIndex = 0;
        } else {
            el.value = '';
        }
        el.classList.remove('is-invalid', 'is-valid');
    });
});


    // Evitar números y caracteres especiales en nombre
    document.getElementById('name').addEventListener('keypress', function(e) {
        const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]$/;
        const key = String.fromCharCode(e.charCode);
        if (!regex.test(key)) {
            e.preventDefault();
        }
    });

    // Validación de correo en tiempo real
    const email = document.getElementById('email');
    email.addEventListener('input', () => {
        const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
        if(email.value === '' || emailPattern.test(email.value)) {
            email.classList.remove('is-invalid');
            email.classList.add('is-valid');
        } else {
            email.classList.remove('is-valid');
            email.classList.add('is-invalid');
        }
    });

    // Validación de contraseña en tiempo real
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');

    function validarPassword() {
        if (passwordConfirm.value === '') {
            passwordConfirm.classList.remove('is-valid', 'is-invalid');
            return;
        }
        if (password.value === passwordConfirm.value) {
            passwordConfirm.classList.remove('is-invalid');
            passwordConfirm.classList.add('is-valid');
        } else {
            passwordConfirm.classList.remove('is-valid');
            passwordConfirm.classList.add('is-invalid');
        }
    }

    password.addEventListener('input', validarPassword);
    passwordConfirm.addEventListener('input', validarPassword);

});
</script>

@endsection
