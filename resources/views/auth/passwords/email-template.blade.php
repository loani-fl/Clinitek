<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Grupo Centinela</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f0f2f5;
            padding: 0;
            margin: 0;
            line-height: 1.6;
        }
        
        .email-wrapper {
            width: 100%;
            background-color: #f0f2f5;
            padding: 40px 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .email-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #667eea 100%);
            padding: 50px 40px;
            text-align: center;
            position: relative;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="%23ffffff"/></svg>') no-repeat bottom;
            background-size: 100% 80px;
            opacity: 0.15;
        }
        
        .lock-icon {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }
        
        .lock-icon svg {
            width: 45px;
            height: 45px;
            color: white;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }
        
        .email-header h1 {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }
        
        .email-body {
            padding: 45px 40px;
            color: #333333;
        }.greeting {
font-size: 18px;
font-weight: 600;
color: #1a1a1a;
margin-bottom: 20px;
}.message {
        font-size: 15px;
        color: #555555;
        line-height: 1.8;
        margin-bottom: 18px;
    }
    
    .highlight {
        background: linear-gradient(135deg, #667eea15, #764ba215);
        padding: 18px 22px;
        border-radius: 10px;
        border-left: 4px solid #667eea;
        margin: 25px 0;
        font-size: 14px;
        color: #444444;
        line-height: 1.7;
    }
    
    .btn-container {
        text-align: center;
        margin: 35px 0;
    }
    
    .btn-reset {
        display: inline-block;
        padding: 16px 40px;
        background: linear-gradient(135deg, #00d4ff 0%, #0099cc 100%);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 15px;
        box-shadow: 0 8px 20px rgba(0, 212, 255, 0.35);
        transition: all 0.3s ease;
        letter-spacing: 0.3px;
    }
    
    .btn-reset:hover {
        box-shadow: 0 12px 30px rgba(0, 212, 255, 0.5);
        transform: translateY(-2px);
    }
    
    .expiry-notice {
        background: #fff8e6;
        border: 1px solid #ffd966;
        border-radius: 8px;
        padding: 14px 18px;
        margin: 25px 0;
        font-size: 13px;
        color: #856404;
        display: flex;
        align-items: center;
    }
    
    .expiry-notice::before {
        content: '⏱️';
        font-size: 18px;
        margin-right: 10px;
    }
    
    .security-note {
        background: #f0f9ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 14px 18px;
        margin: 25px 0;
        font-size: 13px;
        color: #1e40af;
        display: flex;
        align-items: center;
    }
    
    .security-note::before {
        content: '🔒';
        font-size: 18px;
        margin-right: 10px;
    }
    
    .signature {
        margin-top: 35px;
        font-size: 15px;
        color: #555555;
    }
    
    .signature strong {
        color: #1a1a1a;
        font-weight: 600;
    }
    
    .email-footer {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 35px 40px;
        text-align: center;
        border-top: 1px solid #dee2e6;
    }
    
    .company-name {
        font-size: 16px;
        font-weight: 700;
        color: #495057;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }
    
    .copyright {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 20px;
    }
    
    .manual-link-section {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 18px;
        margin-top: 20px;
    }
    
    .manual-link-title {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .manual-link {
        font-size: 12px;
        color: #0066cc;
        word-break: break-all;
        line-height: 1.6;
        text-decoration: none;
    }
    
    .social-icons {
        margin-top: 25px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    
    .social-icons a {
        width: 36px;
        height: 36px;
        background: white;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .social-icons a:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #dee2e6, transparent);
        margin: 30px 0;
    }
    
    /* Responsive */
    @media only screen and (max-width: 600px) {
        .email-header {
            padding: 40px 25px;
        }
        
        .email-header h1 {
            font-size: 26px;
        }
        
        .lock-icon {
            width: 75px;
            height: 75px;
        }
        
        .lock-icon svg {
            width: 38px;
            height: 38px;
        }
        
        .email-body {
            padding: 35px 25px;
        }
        
        .btn-reset {
            padding: 14px 35px;
            font-size: 14px;
        }
        
        .email-footer {
            padding: 30px 25px;
        }
        
        .manual-link {
            font-size: 11px;
        }
    }
</style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <div class="lock-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <h1>🔐 Recuperar Contraseña</h1>
            </div>
        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Hola,</p>
            
            <p class="message">
                Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>Clinica electronica</strong>.
            </p>
            
            <div class="highlight">
                <strong>📌 Importante:</strong> Si no solicitaste este cambio, puedes ignorar este correo de forma segura. Tu contraseña actual permanecerá sin cambios.
            </div>
            
            <p class="message">
                Para crear una nueva contraseña, haz clic en el siguiente botón:
            </p>
            
            <div class="btn-container">
                <a href="{{ url('password/reset', $token) }}?email={{ urlencode($email) }}&username={{ urlencode($username) }}" class="btn-reset">
                    Restablecer mi Contraseña
                </a>
            </div>
            
            <div class="expiry-notice">
                <span>Este enlace expirará en <strong>24 horas</strong> por seguridad.</span>
            </div>
            
            <div class="security-note">
                <span>Por tu seguridad, nunca compartas este correo con nadie.</span>
            </div>
            
            <div class="divider"></div>
            
            <div class="signature">
                <p>Saludos cordiales,</p>
                <p><strong>Tu clinica electronica clinitek</strong></p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="company-name">Clinica electronica</div>
            <div class="copyright">© {{ date('Y') }} Grupo Centinela. Todos los derechos reservados.</div>
            
            <div class="manual-link-section">
                <div class="manual-link-title">Si el botón no funciona, copia y pega este enlace:</div>
                <a href="{{ url('password/reset', $token) }}?email={{ urlencode($email) }}&username={{ urlencode($username) }}" class="manual-link">
                    {{ url('password/reset', $token) }}?email={{ urlencode($email) }}&username={{ urlencode($username) }}
                </a>
            </div>
            
            <div class="social-icons">
                <a href="#" title="Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="#" title="Twitter">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#1DA1F2">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </a>
                <a href="#" title="Instagram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="url(#instagram-gradient)">
                        <defs>
                            <linearGradient id="instagram-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#f09433;stop-opacity:1" />
                                <stop offset="25%" style="stop-color:#e6683c;stop-opacity:1" />
                                <stop offset="50%" style="stop-color:#dc2743;stop-opacity:1" />
                                <stop offset="75%" style="stop-color:#cc2366;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#bc1888;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
