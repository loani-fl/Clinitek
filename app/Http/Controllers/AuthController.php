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
            'max:70'   // máximo 100 caracteres
        ],
        'password' => [
            'required',
            'string',
            'min:8',    // mínimo 8 caracteres
            'max:100'   // máximo 128 caracteres
        ],
    ], [
            'email.required' => 'El correo o usuario es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres'
        ]);

        $login = $request->input('email'); // Puede ser correo o nombre

        // Detectar si es correo válido
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Es correo
            $usuario = Usuario::where('email', $login)->first();
        } else {
            // Es nombre de usuario
            $usuario = Usuario::where('name', $login)->first();
        }

        // Verificar usuario y contraseña
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Correo/usuario o contraseña incorrectos']);
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
