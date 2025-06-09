<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de sesión</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            color: #1e40af;
            margin-bottom: 1.5rem;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #1d4ed8;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #2563eb;
        }
        .error {
            color: #dc2626;
            margin-top: 1rem;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Bienvenido</h2>
        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <input type="text" name="clave" placeholder="Clave de acceso" required>
            <button type="submit">Entrar</button>

            @if ($errors->has('clave'))
                <p class="error">{{ $errors->first('clave') }}</p>
            @endif
        </form>
    </div>
</body>
</html>
