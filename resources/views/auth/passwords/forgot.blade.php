@extends('layouts.app')

@section('content')
<style>
    /* Ocultar TODOS los elementos del layout */
    .header, header, nav, .navbar, footer, .fixed-top, .dropdown, .btn-outline-light,
    [class*="navbar"], [class*="nav-"], [class*="dropdown"],
    [id*="navbar"], [id*="nav"], [id*="dropdown"] {
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
        min-height: 100vh;
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
        overflow-y: auto;
        overflow-x: hidden;
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
        padding: 28px 30px;
        width: 100%;
        max-width: 420px;
        margin: 0 0 0 8%;
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
        margin-bottom: 20px;
    }

    .login-icon {
        width: 65px;
        height: 65px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
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
        width: 32px;
        height: 32px;
        color: white;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
    }

    .login-title {
        color: white;
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 6px 0;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
    }

    .login-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 13px;
        font-weight: 400;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    }

    .form-group {
        margin-bottom: 16px;
        position: relative;
    }

    .form-group.has-error .form-control-modern {
        border-color: rgba(220, 53, 69, 0.6);
        background: rgba(220, 53, 69, 0.08);
    }

    .form-label {
        color: rgba(255, 255, 255, 0.95);
        font-weight: 600;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        font-size: 12px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .form-label svg {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        color: rgba(255, 255, 255, 0.9);
        filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.3));
    }

    .form-control-modern {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        color: white;
        font-size: 13px;
        transition: all 0.3s ease;
        font-weight: 500;
        backdrop-filter: blur(10px);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
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

    .error-message {
        display: none;
        color: #ff6b6b;
        font-size: 11px;
        font-weight: 500;
        margin-top: 5px;
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
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow:
            0 8px 25px rgba(102, 126, 234, 0.45),
            0 0 0 1px rgba(255, 255, 255, 0.2);
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
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

    .btn-login svg {
        width: 16px;
        height: 16px;
        margin-right: 7px;
    }

    .back-to-login {
        text-align: center;
        margin-top: 16px;
    }

    .back-to-login a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .back-to-login a:hover {
        color: white;
        text-decoration: underline;
        transform: translateY(-1px);
    }

    .back-to-login svg {
        margin-right: 6px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .login-wrapper {
            background-position: center;
            justify-content: center;
            padding: 30px 20px;
        }

        .login-wrapper::before {
            width: 100%;
            background: rgba(0, 0, 0, 0.75);
        }

        .login-card {
            margin: 0;
            padding: 30px 25px;
            max-width: 380px;
        }

        .login-title {
            font-size: 22px;
        }

        .login-icon {
            width: 60px;
            height: 60px;
        }

        .login-icon svg {
            width: 30px;
            height: 30px;
        }
    }

    @media (max-width: 480px) {
        .login-card {
            padding: 28px 22px;
            max-width: 350px;
        }

        .login-title {
            font-size: 20px;
        }

        .form-control-modern,
        .btn-login {
            padding: 11px 14px;
            font-size: 13px;
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
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h3 class="login-title">Actualizar Contraseña</h3>
            <p class="login-subtitle">Ingresa tus datos para actualizar tu contraseña</p>
        </div>

        <form action="{{ route('password.email') }}" method="POST" id="forgotForm" autocomplete="off">
            @csrf

            <!-- Campo de nombre de usuario -->
            <div class="form-group @error('username') has-error @enderror" id="usernameGroup">
                <label class="form-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Usuario
                </label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="form-control-modern"
                    value="{{ old('username') }}"
                    autocomplete="off"
                    required
                >
                <div class="error-message @error('username') show @enderror" id="usernameError">
                    @error('username')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <!-- Campo de correo del .env -->
            <div class="form-group @error('email') has-error @enderror" id="emailGroup">
                <label class="form-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    Correo de actualización
                </label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control-modern"
                    value="{{ old('email', $email ?? '') }}"
                    autocomplete="off"
                    required
                >
                <div class="error-message @error('email') show @enderror" id="emailError">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                Enviar enlace
            </button>

            <div class="back-to-login">
                <a href="{{ route('login.form') }}">
                    <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Volver al login
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forgotForm = document.getElementById('forgotForm');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const usernameError = document.getElementById('usernameError');
    const emailError = document.getElementById('emailError');
    const usernameGroup = document.getElementById('usernameGroup');
    const emailGroup = document.getElementById('emailGroup');

    // Limpiar errores cuando el usuario escribe
    usernameInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            usernameError.classList.remove('show');
            usernameError.textContent = '';
            usernameGroup.classList.remove('has-error');
        }
    });

    emailInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            emailError.classList.remove('show');
            emailError.textContent = '';
            emailGroup.classList.remove('has-error');
        }
    });

    // Validación al enviar
    forgotForm.addEventListener('submit', function(e) {
        let isValid = true;

        const usernameValue = usernameInput.value.trim();
        if (usernameValue === '') {
            e.preventDefault();
            usernameError.textContent = 'El usuario es obligatorio.';
            usernameError.classList.add('show');
            usernameGroup.classList.add('has-error');
            isValid = false;
        }

        const emailValue = emailInput.value.trim();
        if (emailValue === '') {
            e.preventDefault();
            emailError.textContent = 'El correo electrónico es obligatorio.';
            emailError.classList.add('show');
            emailGroup.classList.add('has-error');
            isValid = false;
        } else {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailValue)) {
                e.preventDefault();
                emailError.textContent = 'Ingresa un correo electrónico válido.';
                emailError.classList.add('show');
                emailGroup.classList.add('has-error');
                isValid = false;
            }
        }

        if (!isValid) {
            if (usernameValue === '') {
                usernameInput.focus();
            } else if (emailValue === '') {
                emailInput.focus();
            }
        }
    });

    @if($errors->has('username'))
        usernameError.textContent = `{{ $errors->first('username') }}`;
        usernameError.classList.add('show');
        usernameGroup.classList.add('has-error');
    @endif

    @if($errors->has('email'))
        emailError.textContent = `{{ $errors->first('email') }}`;
        emailError.classList.add('show');
        emailGroup.classList.add('has-error');
    @endif
});
</script>
@endsection