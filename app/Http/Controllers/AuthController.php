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
        // Validación con límites de caracteres
        $request->validate([
            'email' => [
                'required',
                'string',
                'max:70'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100'
            ],
        ], [
            'email.required' => 'El usuario o correo electrónico es obligatorio',
            'email.max' => 'El usuario o correo electrónico no puede exceder 70 caracteres',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.max' => 'La contraseña no puede exceder 100 caracteres'
        ]);

        $login = $request->input('email');

        // Detectar si es correo válido
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Es correo
            $usuario = Usuario::where('email', $login)->first();
        } else {
            // Es nombre de usuario
            $usuario = Usuario::where('name', $login)->first();
        }

        // Verificar si el usuario existe
        if (!$usuario) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'El correo o usuario no está registrado']);
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $usuario->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'La contraseña es incorrecta']);
        }

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