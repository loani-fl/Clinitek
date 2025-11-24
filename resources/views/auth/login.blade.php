@extends('layouts.app')

@section('content')
<style>
    /* Ocultar TODOS los elementos del layout excepto el contenido de login */
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

    .login-wrapper {
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
    }

    .login-wrapper::before {
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

    .login-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(25px);
        border-radius: 24px;
        padding: 35px 35px;
        width: 100%;
        max-width: 400px;
        margin-left: 8%;
        box-shadow:
            0 25px 70px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(255, 255, 255, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 1;
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

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-icon {
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

    .login-icon svg {
        width: 38px;
        height: 38px;
        color: white;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
    }

    .login-title {
        color: white;
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
    }

    .login-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 14px;
        font-weight: 400;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    }

    /* Mensaje de éxito */
    .success-message {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        color: rgba(255, 255, 255, 0.95);
        font-size: 13px;
        font-weight: 500;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease;
        display: flex;
        align-items: center;
    }

    .success-message svg {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        flex-shrink: 0;
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

    .btn-login {
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

    .btn-login:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow:
            0 12px 35px rgba(102, 126, 234, 0.6),
            0 0 0 1px rgba(255, 255, 255, 0.3);
    }

    .btn-login:active {
        transform: translateY(-1px);
    }

    .btn-login:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .forgot-password {
        text-align: center;
        margin-top: 20px;
    }

    .forgot-password a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .forgot-password a:hover {
        color: white;
        text-decoration: underline;
        transform: translateY(-1px);
    }

    .forgot-password svg {
        margin-right: 6px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .login-wrapper {
            background-position: center;
            justify-content: center;
        }

        .login-wrapper::before {
            width: 100%;
            background: rgba(0, 0, 0, 0.75);
        }

        .login-card {
            margin-left: 0;
            padding: 40px 30px;
            max-width: 420px;
        }

        .login-title {
            font-size: 28px;
        }

        .login-icon {
            width: 80px;
            height: 80px;
        }

        .login-icon svg {
            width: 40px;
            height: 40px;
        }
    }

    @media (max-width: 480px) {
        .login-card {
            padding: 35px 25px;
        }

        .login-title {
            font-size: 24px;
        }

        .form-control-modern,
        .btn-login {
            padding: 14px 16px;
            font-size: 14px;
        }

        .form-control-modern.with-icon {
            padding-right: 50px;
        }
    }

    /* Decoración adicional */
    .login-card::before {
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
        border-radius: 28px 28px 0 0;
    }

    .login-card::after {
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

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <h3 class="login-title">Bienvenido</h3>
            <p class="login-subtitle">Ingresa tus credenciales para continuar</p>
        </div>

        {{-- Mensaje de error de permisos --}}
        @if(session('error'))
            <div id="alert-permission" class="alert alert-warning alert-dismissible fade show text-center" role="alert" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 90%; max-width: 400px; box-shadow: 0 0 15px rgba(0,0,0,0.2); border-radius: 0.5rem;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>¡Alerta!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <script>
                // Desaparece automáticamente después de 3 segundos
                setTimeout(function() {
                    let alertEl = document.getElementById('alert-permission');
                    if (alertEl) {
                        alertEl.classList.remove('show');
                        alertEl.classList.add('hide');
                        setTimeout(() => alertEl.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif

        {{-- Mensaje de éxito después de restablecer contraseña --}}
        @if(session('success'))
        <div class="success-message">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" id="loginForm" autocomplete="off" novalidate>
            @csrf

            <div class="form-group" id="emailGroup">
                <label class="form-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                   El usuario o correo electrónico es obligatorio
                </label>
               <input
    type="text"
    name="email"
    id="email"
    class="form-control-modern"
    placeholder="Usuario o correo"
    value="{{ request('email') ?? old('email') }}"
    autocomplete="off"
     maxlength="70"
>

                <div class="error-message" id="emailError">
                    @error('email')
                        {{ $message }}
                  @else
    Ingresa un correo o usuario válido
@enderror

                </div>
            </div>

            <div class="form-group" id="passwordGroup">
                <label class="form-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Contraseña
                </label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control-modern with-icon"
                        placeholder="••••••••"
                        autocomplete="new-password"
                         maxlength="100"
                    >
                    <button type="button" class="toggle-password" id="togglePassword">
                        <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg id="eyeOffIcon" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                <div class="error-message" id="passwordError">
                    @error('password')
                        {{ $message }}
                    @else
                        La contraseña es obligatoria
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                Ingresar
            </button>

            <div class="forgot-password">
                <a href="javascript:void(0)" onclick="goToForgotPassword()">
                    <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function goToForgotPassword() {
    const email = document.getElementById('email').value.trim();
    const forgotUrl = "{{ route('password.forgot') }}";

    if (email) {
        window.location.href = forgotUrl + '?email=' + encodeURIComponent(email);
    } else {
        window.location.href = forgotUrl;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const emailGroup = document.getElementById('emailGroup');
    const passwordGroup = document.getElementById('passwordGroup');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');

    // Funcionalidad del ojito para mostrar/ocultar contraseña
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Cambiar icono
        if (type === 'text') {
            eyeIcon.style.display = 'none';
            eyeOffIcon.style.display = 'block';
        } else {
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
        }
    });

    // Mostrar errores del servidor si existen
    @if($errors->has('email'))
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
    });

    // Validación al enviar el formulario
    form.addEventListener('submit', function(e) {
        const emailValid = validateEmail();
        const passwordValid = validatePassword();

        if (!emailValid || !passwordValid) {
            e.preventDefault();
        }
    });
function validateEmail() {
    const email = emailInput.value.trim();

    if (email === '') {
        emailError.textContent = 'El usuario o correo electrónico es obligatorio';
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
        } else {
            passwordError.classList.remove('show');
            passwordGroup.classList.remove('has-error');
            return true;
        }
    }
});
</script>
@endsection
