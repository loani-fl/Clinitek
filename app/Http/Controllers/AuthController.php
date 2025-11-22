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
    // Validación
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6'
    ], [
        'email.required' => 'El correo electrónico es obligatorio',
        'email.email' => 'Ingresa un correo electrónico válido',
        'password.required' => 'La contraseña es obligatoria',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres'
    ]);

    $usuario = Usuario::where('email', $request->email)->first();

    if (!$usuario || !Hash::check($request->password, $usuario->password)) {
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Correo o contraseña incorrectos']);
    }

    // Loguear usuario con auth de Laravel
    Auth::login($usuario);

    // Guardar el ID en la sesión para el middleware
    session(['usuario_id' => $usuario->id]);

    // Redirigir según rol
  // Redirigir según rol
if ($usuario->hasRole('administrador')) {
    return redirect()->route('usuarios.index'); // ya existe
} elseif ($usuario->hasRole('medico')) {
    return redirect()->route('medicos.index'); // ya existe
} elseif ($usuario->hasRole('usuario')) {
    return redirect()->route('inicio'); // usuarios normales
} elseif ($usuario->hasRole('empleado')) {
    return redirect()->route('empleados.index'); // ruta para empleados
} else {
    // Si el usuario tiene algún otro rol no previsto, lo llevamos a inicio o ruta por defecto
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
