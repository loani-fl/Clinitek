<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Usuario;
 use Illuminate\Support\Facades\Auth;

class RolePermissionMiddleware
{
    /**
     * Maneja la autorización por rol y permiso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $roles
     * @param  string|null  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles = null, $permission = null)
    {
        

// Obtener usuario autenticado con Laravel
$user = Auth::user();
if (!$user) {
    return redirect()->route('login.form');
}


        // Validar roles (puede ser más de uno, separados por "|")
        if ($roles) {
            $rolesArray = explode('|', $roles);
            $hasRole = false;
            foreach ($rolesArray as $role) {
                if ($user->hasRole($role)) {
                    $hasRole = true;
                    break;
                }
            }

            if (!$hasRole) {
                abort(403, 'No tienes el rol requerido');
            }
        }

        // Validar permisos si se pasa alguno
        if ($permission && !$user->can($permission)) {
            abort(403, 'No tienes permiso para acceder a esta sección');
        }

        return $next($request);
    }
}
