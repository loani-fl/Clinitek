<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCustom
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica si el usuario tiene sesión
        if (!$request->session()->has('usuario_id')) {
            return redirect()->route('login.form'); // Redirige a login si no está logueado
        }

        return $next($request);
    }
}
