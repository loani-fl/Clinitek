<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Mostrar login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $login = $request->input('email');
        $password = $request->input('password');

        // Validar campos vacíos
        $errors = [];
        
        if (empty($login)) {
            $errors['email'] = 'El usuario o correo electrónico es obligatorio.';
        }
        
        if (empty($password)) {
            $errors['password'] = 'La contraseña es obligatoria.';
        }
        
        // Si hay errores de campos vacíos, retornar ambos
        if (!empty($errors)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors($errors);
        }

        // Validar longitud mínima de contraseña
        if (strlen($password) < 6) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'La contraseña debe tener al menos 6 caracteres.']);
        }

        // Detectar si es correo válido
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Es correo
            $usuario = Usuario::where('email', $login)->first();
        } else {
            // Es nombre de usuario
            $usuario = Usuario::where('name', $login)->first();
        }

        // VALIDACIÓN ESPECÍFICA: Verificar qué está mal exactamente
        
        // Caso 1: El usuario/correo NO existe
        if (!$usuario) {
            // Como no sabemos si la contraseña es correcta (porque el usuario no existe),
            // mostramos error en ambos campos para no dar pistas de seguridad
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'El correo o usuario no está registrado o es incorrecto.',
                    'password' => 'La crontraseña es incorrecta.'
                ]);
        }

        // Caso 2: El usuario/correo SÍ existe pero la contraseña es incorrecta
        if (!Hash::check($password, $usuario->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'password' => 'La contraseña es incorrecta.'
                ]);
        }

        // Si llegamos aquí, todo está correcto
        // Loguear usuario
        Auth::login($usuario);
        session(['usuario_id' => $usuario->id]);

        // Redirigir según rol
        if ($usuario->hasRole('administrador')) {
            return redirect()->route('usuarios.index');
        } elseif ($usuario->hasRole('medico')) {
            return redirect()->route('medicos.index');
        } elseif ($usuario->hasRole('usuario')) {
            return redirect()->route('inicio');
        } elseif ($usuario->hasRole('empleado')) {
            return redirect()->route('empleados.index');
        } else {
            return redirect()->route('inicio');
        }
    }

    // Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form');
    }
}