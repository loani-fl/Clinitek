<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Mostrar login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        // Validación mejorada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingresa un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres'
        ]);

        // Buscar usuario
        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'El correo no está registrado']);
        }

        if (!Hash::check($request->password, $usuario->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'Contraseña incorrecta']);
        }

        // Crear sesión
        session([
            'usuario_id' => $usuario->id,
            'usuario_name' => $usuario->name,
            'usuario_rol' => $usuario->rol
        ]);

        return redirect()->route('inicio');
    }

    // Cerrar sesión
   // Cerrar sesión
public function logout()
{
    session()->flush();
    return redirect()->route('login.form'); // ✅ Cambiar de 'login' a 'login.form'
}
}