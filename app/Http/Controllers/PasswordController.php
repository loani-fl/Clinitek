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
            'usuario' => 'required|string|max:70',  // Cambiado para aceptar correo o usuario
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'max:100',
                'regex:/[a-zA-Z]/',      // Al menos una letra
                'regex:/[0-9]/',         // Al menos un número
                'regex:/[@#$%&*!]/'      // Al menos un símbolo
            ]
        ], [
            'usuario.required' => 'El usuario o correo electrónico es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.max' => 'La contraseña no puede tener más de 100 caracteres',
            'password.regex' => 'La contraseña debe contener letras, números y símbolos (@#$%&*!)'
        ]);

        $login = $request->input('usuario');

        // Detectar si es correo válido o nombre de usuario (igual que en login)
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Es correo
            $usuario = Usuario::where('email', $login)->first();
        } else {
            // Es nombre de usuario
            $usuario = Usuario::where('name', $login)->first();
        }

        if (!$usuario) {
            return back()
                ->withInput($request->only('usuario'))
                ->withErrors(['usuario' => 'El correo/usuario no está registrado']);
        }

        // Actualizar contraseña
        $usuario->update([
            'password' => Hash::make($request->password)
        ]);

        // Redirigir al login con el email/usuario como parámetro
        return redirect()->route('login.form', ['email' => $request->usuario])
            ->with('success', 'Contraseña actualizada correctamente');
    }
}