<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
    public function handle(Request $request, Closure $next, $permission = null)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.form');
        }

        // Administrador global
        if ($user->hasRole('administrador')) {
            return $next($request);
        }

        // Validar permisos
        if ($permission) {
            $permissionsArray = explode('|', $permission);
            $hasPermission = false;

            foreach ($permissionsArray as $perm) {
                if ($user->can($perm)) {
                    $hasPermission = true;
                    break;
                }
            }

            if (!$hasPermission) {
                // Redirigir a login con mensaje
                return redirect()->route('login.form')
                    ->with('error', 'No tienes permiso para acceder a esta sección');
            }
        }

        return $next($request);
    }
}
