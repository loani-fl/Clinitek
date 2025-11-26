@extends('layouts.app')

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

    .login-wrapper {
        height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #667eea 100%);
        position: fixed;
        top: 0;
        left: 0;
        overflow: hidden;
        padding: 40px 20px;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(25px);
        border-radius: 24px;
        padding: 40px 35px;
        width: 100%;
        max-width: 450px;
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
            transform: translateY(-40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-header {
        text-align: center;
        margin-bottom: 25px;
    }

    .login-icon {
        width: 85px;
        height: 85px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
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
        width: 42px;
        height: 42px;
        color: white;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3));
    }

    .login-title {
        color: white;
        font-size: 30px;
        font-weight: 700;
        margin: 0 0 10px 0;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
    }

    .login-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 14px;
        font-weight: 400;
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    }

    .email-display {
        background: rgba(255, 255, 255, 0.1);
        padding: 12px 18px;
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.95);
        font-size: 14px;
        margin-bottom: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
        font-weight: 500;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .username-display {
        background: rgba(102, 126, 234, 0.15);
        padding: 12px 18px;
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.95);
        font-size: 13px;
        margin-bottom: 25px;
        border: 1px solid rgba(102, 126, 234, 0.3);
        text-align: center;
        font-weight: 500;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .username-display strong {
        color: rgba(255, 255, 255, 1);
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 20px;
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
        display: block;
        font-size: 13px;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-control-modern {
        width: 100%;
        padding: 14px 18px;
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
        background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow:
            0 8px 25px rgba(0, 212, 255, 0.45),
            0 0 0 1px rgba(255, 255, 255, 0.2);
        margin-top: 15px;
    }

    .btn-login:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow:
            0 12px 35px rgba(0, 212, 255, 0.6),
            0 0 0 1px rgba(255, 255, 255, 0.3);
    }

    .btn-login:active {
        transform: translateY(-1px);
    }

    .btn-login:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .back-to-login {
        text-align: center;
        margin-top: 20px;
    }

    .back-to-login a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 13px;
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .login-card {
            padding: 40px 30px;
            max-width: 420px;
        }
    }

    @media (max-width: 480px) {
        .login-card {
            padding: 35px 25px;
        }

        .login-title {
            font-size: 26px;
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
</style>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h3 class="login-title">Restablecer contraseña</h3>
            <p class="login-subtitle">Introduce tu nueva contraseña</p>
        </div>

        <div class="email-display">
            {{ $email }}
        </div>

        @if(isset($username) && $username)
        <div class="username-display">
            <strong>Tu nombre de usuario es:</strong> {{ $username }}
        </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" id="resetForm" autocomplete="off">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="username" value="{{ $username }}">

            <div class="form-group @error('password') has-error @enderror" id="passwordGroup">
                <label class="form-label">Nueva contraseña</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control-modern with-icon"
                       
                        autocomplete="new-password"
                        required
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
                <div class="error-message @error('password') show @enderror" id="passwordError">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="form-group @error('password_confirmation') has-error @enderror" id="confirmGroup">
                <label class="form-label">Confirmar contraseña</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control-modern with-icon"
                       
                        autocomplete="new-password"
                        required
                    >
                    <button type="button" class="toggle-password" id="toggleConfirm">
                        <svg id="eyeIcon2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg id="eyeOffIcon2" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                <div class="error-message @error('password_confirmation') show @enderror" id="confirmError">
                    @error('password_confirmation')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                Restablecer
            </button>

            <div class="back-to-login">
                <a href="{{ route('login.form') }}">Volver al login</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.getElementById('resetForm');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const passwordError = document.getElementById('passwordError');
    const confirmError = document.getElementById('confirmError');
    const passwordGroup = document.getElementById('passwordGroup');
    const confirmGroup = document.getElementById('confirmGroup');
    const submitBtn = document.getElementById('submitBtn');

    // Toggle password visibility - Contraseña
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');

    togglePassword.addEventListener('click', function() {
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

    // Toggle password visibility - Confirmar
    const toggleConfirm = document.getElementById('toggleConfirm');
    const eyeIcon2 = document.getElementById('eyeIcon2');
    const eyeOffIcon2 = document.getElementById('eyeOffIcon2');

    toggleConfirm.addEventListener('click', function() {
        const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmInput.setAttribute('type', type);
        
        if (type === 'text') {
            eyeIcon2.style.display = 'none';
            eyeOffIcon2.style.display = 'block';
        } else {
            eyeIcon2.style.display = 'block';
            eyeOffIcon2.style.display = 'none';
        }
    });

    // Limpiar errores cuando el usuario empieza a escribir
    passwordInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            passwordError.classList.remove('show');
            passwordError.textContent = '';
            passwordGroup.classList.remove('has-error');
        }
    });

    confirmInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            confirmError.classList.remove('show');
            confirmError.textContent = '';
            confirmGroup.classList.remove('has-error');
        }
    });

    // Validación al enviar el formulario
    resetForm.addEventListener('submit', function(e) {
        let isValid = true;

        const passwordValue = passwordInput.value.trim();
        if (passwordValue === '') {
            e.preventDefault();
            passwordError.textContent = 'La contraseña es obligatoria.';
            passwordError.classList.add('show');
            passwordGroup.classList.add('has-error');
            isValid = false;
        } else if (passwordValue.length < 6) {
            e.preventDefault();
            passwordError.textContent = 'La contraseña debe tener al menos 6 caracteres.';
            passwordError.classList.add('show');
            passwordGroup.classList.add('has-error');
            isValid = false;
        } else {
            passwordError.classList.remove('show');
            passwordError.textContent = '';
            passwordGroup.classList.remove('has-error');
        }

        const confirmValue = confirmInput.value.trim();
        if (confirmValue === '') {
            e.preventDefault();
            confirmError.textContent = 'Debes confirmar tu contraseña.';
            confirmError.classList.add('show');
            confirmGroup.classList.add('has-error');
            isValid = false;
        } else if (passwordValue !== confirmValue) {
            e.preventDefault();
            confirmError.textContent = 'Las contraseñas no coinciden.';
            confirmError.classList.add('show');
            confirmGroup.classList.add('has-error');
            isValid = false;
        } else {
            confirmError.classList.remove('show');
            confirmError.textContent = '';
            confirmGroup.classList.remove('has-error');
        }

        if (!isValid) {
            if (passwordValue === '' || passwordValue.length < 6) {
                passwordInput.focus();
            } else if (confirmValue === '' || passwordValue !== confirmValue) {
                confirmInput.focus();
            }
        }
    });

    // Mostrar errores del servidor si existen
    @if($errors->has('password'))
        passwordError.textContent = `{{ $errors->first('password') }}`;
        passwordError.classList.add('show');
        passwordGroup.classList.add('has-error');
    @endif

    @if($errors->has('password_confirmation'))
        confirmError.textContent = `{{ $errors->first('password_confirmation') }}`;
        confirmError.classList.add('show');
        confirmGroup.classList.add('has-error');
    @endif
});
</script>
@endsection