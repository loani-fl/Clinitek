<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCustom
{
    public function handle(Request $request, Closure $next)
    {
        // Si NO hay sesión → mandar al login
        if (!session()->has('usuario_id')) {
            return redirect()->route('login.form');
        }

        return $next($request);
    }
}
