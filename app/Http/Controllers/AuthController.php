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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario
        $usuario = Usuario::where('email', $request->email)->first();

       if (!$usuario) {
    return back()->withErrors(['email' => 'El correo no está registrado']);
}

if (!Hash::check($request->password, $usuario->password)) {
    return back()->withErrors(['password' => 'Contraseña incorrecta']);
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
    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
