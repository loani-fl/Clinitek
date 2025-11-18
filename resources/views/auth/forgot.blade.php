@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<style>
    /* Ocultar TODOS los elementos del layout excepto el contenido */
    .header,
    header,
    nav,
    .navbar,
    footer,
    .fixed-top,
    .dropdown,
    .btn-outline-light,
    [class*="navbar"],
    [class*="nav-"],
    [class*="dropdown"],
    [id*="navbar"],
    [id*="nav"],
    [id*="dropdown"] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        width: 0 !important;
        overflow: hidden !important;
        position: absolute !important;
        pointer-events: none !important;
        z-index: -9999 !important;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
        height: 100vh !important;
        width: 100vw !important;
    }

    main {
        padding: 0 !important;
        margin: 0 !important;
        height: 100vh !important;
        width: 100vw !important;
        flex: none !important;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .recovery-wrapper {
        height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        background-image: url('{{ asset('images/inicio.avif') }}');
        background-size: cover;
        background-position: center right;
        background-repeat: no-repeat;
        background-attachment: fixed;
        position: fixed;
        top: 0;
        left: 0;
        overflow: hidden;
        padding: 40px 20px;
        z-index: 99999 !important;
    }

    .recovery-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 55%;
        height: 100%;
        background: linear-gradient(to right, 
            rgba(0, 0, 0, 0.75) 0%, 
            rgba(0, 0, 0, 0.55) 40%,
            rgba(0, 0, 0, 0.25) 70%,
            transparent 100%
        );
        z-index: 0;
    }

    .recovery-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(25px);
        border-radius: 24px;
        padding: 35px 35px;
        width: 100%;
        max-width: 420px;
        margin-left: 8%;
        box-shadow: 
            0 25px 70px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(255, 255, 255, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 100000 !important;
        animation: slideIn 0.6s ease-out;
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .recovery-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .recovery-icon {
        width: 75px;
        height: 75px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        box-shadow: 
            0 10px 30px rgba(102, 126, 234, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.3);
        animation: pulse 2s ease-in-out infinite;
        border: 2px solid rgba(255, 255, 255, 0.25);
    }

    @keyframes pulse {
        0%, 100% { 
            transform: scale(1);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        50% { 
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }
    }

    .recovery-icon svg {
        width: 38px;
        height: 38px;
        color: white;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
    }

    .recovery-title {
        color: white;
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 8px 0;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
    }

    .recovery-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 13px;
        font-weight: 400;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    }

    .form-group {
        margin-bottom: 24px;
        position: relative;
    }

    .form-group.has-error .form-control-modern {
        border-color: rgba(220, 53, 69, 0.6);
        background: rgba(220, 53, 69, 0.08);
    }

    .form-label {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        font-size: 13px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .form-label svg {
        width: 18px;
        height: 18px;
        margin-right: 10px;
        color: rgba(255, 255, 255, 0.9);
        filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.3));
    }

    /* Input con icono de ojo */
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-control-modern {
        width: 100%;
        padding: 13px 18px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        color: white;
        font-size: 14px;
        transition: all 0.3s ease;
        font-weight: 500;
        backdrop-filter: blur(10px);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-control-modern.with-icon {
        padding-right: 50px;
    }

    .form-control-modern::placeholder {
        color: rgba(255, 255, 255, 0.5);
        font-weight: 400;
    }

    .form-control-modern:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.22);
        border-color: rgba(255, 255, 255, 0.45);
        box-shadow: 
            0 0 0 4px rgba(102, 126, 234, 0.15),
            inset 0 2px 4px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* Botón del ojito */
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .toggle-password svg {
        width: 20px;
        height: 20px;
        color: rgba(255, 255, 255, 0.7);
        filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.3));
        transition: all 0.3s ease;
    }

    .toggle-password:hover svg {
        color: rgba(255, 255, 255, 0.95);
        transform: scale(1.1);
    }

    .toggle-password:active svg {
        transform: scale(0.95);
    }

    /* Mensajes de error estilo Google */
    .error-message {
        display: none;
        color: #ff6b6b;
        font-size: 12px;
        font-weight: 500;
        margin-top: 6px;
        padding-left: 2px;
        animation: fadeIn 0.3s ease;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    .error-message.show {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-recovery {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 
            0 8px 25px rgba(102, 126, 234, 0.45),
            0 0 0 1px rgba(255, 255, 255, 0.2);
        margin-top: 12px;
    }

    .btn-recovery:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 
            0 12px 35px rgba(102, 126, 234, 0.6),
            0 0 0 1px rgba(255, 255, 255, 0.3);
    }

    .btn-recovery:active {
        transform: translateY(-1px);
    }

    .btn-recovery:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .back-login {
        text-align: center;
        margin-top: 20px;
    }

    .back-login a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .back-login a:hover {
        color: white;
        text-decoration: underline;
        transform: translateY(-1px);
    }

    .back-login svg {
        margin-right: 6px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .recovery-wrapper {
            background-position: center;
            justify-content: center;
        }

        .recovery-wrapper::before {
            width: 100%;
            background: rgba(0, 0, 0, 0.75);
        }

        .recovery-card {
            margin-left: 0;
            padding: 40px 30px;
            max-width: 420px;
        }

        .recovery-title {
            font-size: 24px;
        }

        .recovery-icon {
            width: 70px;
            height: 70px;
        }

        .recovery-icon svg {
            width: 35px;
            height: 35px;
        }
    }

    @media (max-width: 480px) {
        .recovery-card {
            padding: 35px 25px;
        }

        .recovery-title {
            font-size: 22px;
        }

        .form-control-modern,
        .btn-recovery {
            padding: 14px 16px;
            font-size: 14px;
        }

        .form-control-modern.with-icon {
            padding-right: 50px;
        }
    }

    /* Decoración adicional */
    .recovery-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, 
            transparent,
            rgba(102, 126, 234, 0.8),
            rgba(118, 75, 162, 0.8),
            transparent
        );
        border-radius: 24px 24px 0 0;
    }

    .recovery-card::after {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.1), transparent 70%);
        pointer-events: none;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-20px, -20px) scale(1.1); }
    }
</style>

<div class="recovery-wrapper">
    <div class="recovery-card">
        <div class="recovery-header">
            <div class="recovery-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
            </div>
            <h3 class="recovery-title">Recuperar contraseña</h3>
            <p class="recovery-subtitle">Ingresa tu correo y nueva contraseña</p>
        </div>

        <form method="POST" action="{{ route('password.reset') }}" id="recoveryForm" autocomplete="off" novalidate>
            @csrf

            <div class="form-group" id="emailGroup">
                <label class="form-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    Correo electrónico
                </label>
                <input 
                    type="email" 
                    name="usuario" 
                    id="usuario" 
                    class="form-control-modern" 
                    placeholder="ejemplo@correo.com"
                    value="{{ request('email') ?? old('usuario') }}"
                    autocomplete="off"
                >
                <div class="error-message" id="emailError">
                    @error('usuario')
                        {{ $message }}
                    @else
                        Ingresa un correo electrónico válido
                    @enderror
                </div>
            </div>

            <div class="form-group" id="passwordGroup">
                <label class="form-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Nueva contraseña
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control-modern with-icon" 
                        placeholder="••••••••"
                        autocomplete="new-password"
                    >
                    <button type="button" class="toggle-password" id="togglePassword">
                        <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="eye-off-icon" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                <div class="error-message" id="passwordError">
                    @error('password')
                        {{ $message }}
                    @else
                        La contraseña debe tener al menos 4 caracteres
                    @enderror
                </div>
            </div>

            <div class="form-group" id="confirmGroup">
                <label class="form-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    Confirmar contraseña
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="form-control-modern with-icon" 
                        placeholder="••••••••"
                        autocomplete="new-password"
                    >
                    <button type="button" class="toggle-password" id="toggleConfirm">
                        <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="eye-off-icon" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                <div class="error-message" id="confirmError">Las contraseñas no coinciden</div>
            </div>

            <button type="submit" class="btn-recovery">
                Actualizar contraseña
            </button>

            <div class="back-login">
                <a href="{{ route('login.form') }}">
                    <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Volver al inicio de sesión
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('recoveryForm');
    const emailInput = document.getElementById('usuario');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmError = document.getElementById('confirmError');
    const emailGroup = document.getElementById('emailGroup');
    const passwordGroup = document.getElementById('passwordGroup');
    const confirmGroup = document.getElementById('confirmGroup');
    
    // Botones de toggle para contraseñas
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirm = document.getElementById('toggleConfirm');

    // Funcionalidad del ojito para la nueva contraseña
    togglePassword.addEventListener('click', function() {
        const eyeIcon = this.querySelector('.eye-icon');
        const eyeOffIcon = this.querySelector('.eye-off-icon');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIcon.style.display = 'none';
            eyeOffIcon.style.display = 'block';
        } else {
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
        }
    });

    // Funcionalidad del ojito para confirmar contraseña
    toggleConfirm.addEventListener('click', function() {
        const eyeIcon = this.querySelector('.eye-icon');
        const eyeOffIcon = this.querySelector('.eye-off-icon');
        const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmInput.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIcon.style.display = 'none';
            eyeOffIcon.style.display = 'block';
        } else {
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
        }
    });

    // Capturar el email de la URL si existe
    const urlParams = new URLSearchParams(window.location.search);
    const emailFromUrl = urlParams.get('email');
    
    if (emailFromUrl && !emailInput.value) {
        emailInput.value = emailFromUrl;
    }

    // Mostrar errores del servidor si existen
    @if($errors->has('usuario'))
        emailError.classList.add('show');
        emailGroup.classList.add('has-error');
    @endif

    @if($errors->has('password'))
        passwordError.classList.add('show');
        passwordGroup.classList.add('has-error');
    @endif

    // Validación en tiempo real para email
    emailInput.addEventListener('blur', validateEmail);
    emailInput.addEventListener('input', function() {
        if (emailError.classList.contains('show')) {
            validateEmail();
        }
    });

    // Validación en tiempo real para contraseña
    passwordInput.addEventListener('blur', validatePassword);
    passwordInput.addEventListener('input', function() {
        if (passwordError.classList.contains('show')) {
            validatePassword();
        }
        if (confirmInput.value !== '') {
            validateConfirm();
        }
    });

    // Validación en tiempo real para confirmar contraseña
    confirmInput.addEventListener('blur', validateConfirm);
    confirmInput.addEventListener('input', function() {
        if (confirmError.classList.contains('show')) {
            validateConfirm();
        }
    });

    // Validación al enviar el formulario
    form.addEventListener('submit', function(e) {
        const emailValid = validateEmail();
        const passwordValid = validatePassword();
        const confirmValid = validateConfirm();

        if (!emailValid || !passwordValid || !confirmValid) {
            e.preventDefault();
        }
    });

    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email === '') {
            emailError.textContent = 'El correo electrónico es obligatorio';
            emailError.classList.add('show');
            emailGroup.classList.add('has-error');
            return false;
        } else if (!emailRegex.test(email)) {
            emailError.textContent = 'Ingresa un correo electrónico válido';
            emailError.classList.add('show');
            emailGroup.classList.add('has-error');
            return false;
        } else {
            emailError.classList.remove('show');
            emailGroup.classList.remove('has-error');
            return true;
        }
    }

    function validatePassword() {
        const password = passwordInput.value;

        if (password === '') {
            passwordError.textContent = 'La contraseña es obligatoria';
            passwordError.classList.add('show');
            passwordGroup.classList.add('has-error');
            return false;
        } else if (password.length < 4) {
            passwordError.textContent = 'La contraseña debe tener al menos 4 caracteres';
            passwordError.classList.add('show');
            passwordGroup.classList.add('has-error');
            return false;
        } else {
            passwordError.classList.remove('show');
            passwordGroup.classList.remove('has-error');
            return true;
        }
    }

    function validateConfirm() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (confirm === '') {
            confirmError.textContent = 'Debes confirmar tu contraseña';
            confirmError.classList.add('show');
            confirmGroup.classList.add('has-error');
            return false;
        } else if (password !== confirm) {
            confirmError.textContent = 'Las contraseñas no coinciden';
            confirmError.classList.add('show');
            confirmGroup.classList.add('has-error');
            return false;
        } else {
            confirmError.classList.remove('show');
            confirmGroup.classList.remove('has-error');
            return true;
        }
    }
});
</script>
@endsection