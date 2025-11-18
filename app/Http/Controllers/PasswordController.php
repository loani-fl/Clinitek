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
            'usuario' => 'required|email',
            'password' => 'required|confirmed|min:4'
        ], [
            'usuario.required' => 'El correo electrónico es obligatorio',
            'usuario.email' => 'Ingresa un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres'
        ]);

        // Buscar usuario por email
        $usuario = Usuario::where('email', $request->usuario)->first();

        if (!$usuario) {
            return back()
                ->withInput($request->only('usuario'))
                ->withErrors(['usuario' => 'El correo no está registrado']);
        }

        // Actualizar contraseña
        $usuario->update([
            'password' => Hash::make($request->password)
        ]);

        // Redirigir al login con el email como parámetro
        return redirect()->route('login.form', ['email' => $request->usuario])
            ->with('success', 'Contraseña actualizada correctamente');
    }
}