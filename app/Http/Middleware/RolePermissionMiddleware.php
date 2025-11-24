<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RolePermissionMiddleware
{
    /**
     * Maneja la autorización por rol y permiso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permissions = null)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.form');
        }

        // Rol administrador pasa siempre
        if ($user->hasRole('administrador')) {
            return $next($request);
        }

        // Si se especifican permisos, revisarlos
        if ($permissions) {
            $permissionsArray = explode('|', $permissions);
            $hasPermission = false;

            // Obtener todos los permisos del usuario
            $userPermissions = $user->getAllPermissions()->pluck('name');

            foreach ($permissionsArray as $perm) {
                foreach ($userPermissions as $userPerm) {
                    // Str::is soporta * como wildcard
                    if (Str::is($perm, $userPerm)) {
                        $hasPermission = true;
                        break 2; // salir de ambos foreach
                    }
                }
            }

            if (!$hasPermission) {
                // Redirigir al login con mensaje de error
                return redirect()->route('login.form')
                    ->with('error', 'No tienes permiso para acceder a esta sección');
            }
        }

        return $next($request);
    }
}
