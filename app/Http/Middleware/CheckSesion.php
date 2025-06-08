<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSesion
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('autenticado')) {
            return redirect()->route('login')->withErrors(['clave' => 'Debes iniciar sesión.']);
        }

        return $next($request);
    }
}
