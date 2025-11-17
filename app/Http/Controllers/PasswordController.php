<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    // Mostrar formulario de recuperación
    public function showForgot()
    {
        return view('auth.forgot');
    }

    // Procesar cambio de contraseña
    public function reset(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|confirmed|min:4'
        ], [
            'usuario.required' => 'El usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.'
        ]);

       
        $usuario = Usuario::where('email', $request->usuario)->first();


        if (!$usuario) {
            return back()->withErrors(['usuario' => 'El usuario no existe']);
        }

        $usuario->update([
            'password' => Hash::make($request->password)
        ]);

       return redirect()->route('login.form')->with('success', 'Contraseña actualizada correctamente.');

    }
}
