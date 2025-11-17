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
    [class*="navbar"],
    [class*="nav-"],
    [id*="navbar"],
    [id*="nav"] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
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
        margin-bottom: 20px;
        position: relative;
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

    .btn-recovery:hover {
        transform: translateY(-3px);
        box-shadow: 
            0 12px 35px rgba(102, 126, 234, 0.6),
            0 0 0 1px rgba(255, 255, 255, 0.3);
    }

    .btn-recovery:active {
        transform: translateY(-1px);
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

    .alert-modern {
        background: rgba(220, 53, 69, 0.15);
        border: 1px solid rgba(220, 53, 69, 0.35);
        color: white;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 13px;
        font-weight: 500;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    .alert-modern ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert-modern li {
        margin-bottom: 5px;
    }

    .alert-modern li:last-child {
        margin-bottom: 0;
    }

    .alert-success-modern {
        background: rgba(72, 187, 120, 0.15);
        border: 1px solid rgba(72, 187, 120, 0.35);
        color: white;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 13px;
        font-weight: 500;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(72, 187, 120, 0.2);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
    }

    .alert-success-modern strong {
        margin-right: 8px;
        font-size: 18px;
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

        @if(session('success'))
            <div class="alert-success-modern">
                <strong>✓</strong> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-modern">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.reset') }}">
            @csrf

            <div class="form-group">
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
                    value="{{ old('usuario') }}"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Nueva contraseña
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control-modern" 
                    placeholder="••••••••"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    Confirmar contraseña
                </label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="form-control-modern" 
                    placeholder="••••••••"
                    required
                >
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
@endsection