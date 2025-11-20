<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
{
    $roles = Role::with('permissions', 'users')->get();

    return view('roles.index', compact('roles'));
}


    public function create()
    {
        // Obtener todos los permisos
        $permissions = Permission::all();

        // Agrupar permisos por secciones
        $usuarios = $permissions->filter(fn($p) => str_starts_with($p->name, 'usuarios.'));
        $ultrasonidos = $permissions->filter(fn($p) => str_starts_with($p->name, 'ultrasonidos.'));
        $rayosX = $permissions->filter(fn($p) => str_starts_with($p->name, 'rayosX.'));
        $recetas = $permissions->filter(fn($p) => str_starts_with($p->name, 'recetas.'));
        $consultas = $permissions->filter(fn($p) => str_starts_with($p->name, 'consultas.'));
        $controlPrenatal = $permissions->filter(fn($p) => str_starts_with($p->name, 'controlPrenatal.'));
        $dashboard = $permissions->filter(fn($p) => str_starts_with($p->name, 'dashboard.'));
        $diagnosticos = $permissions->filter(fn($p) => str_starts_with($p->name, 'diagnosticos.'));
        $emergencias = $permissions->filter(fn($p) => str_starts_with($p->name, 'emergencias.'));
        $empleado = $permissions->filter(fn($p) => str_starts_with($p->name, 'empleado.'));
        $examenes = $permissions->filter(fn($p) => str_starts_with($p->name, 'examenes.'));
        $factura = $permissions->filter(fn($p) => str_starts_with($p->name, 'factura.'));
        $farmacias = $permissions->filter(fn($p) => str_starts_with($p->name, 'farmacias.'));
        $hospitalizacion = $permissions->filter(fn($p) => str_starts_with($p->name, 'hospitalizacion.'));
        $inventario = $permissions->filter(fn($p) => str_starts_with($p->name, 'inventario.'));
        $medicos = $permissions->filter(fn($p) => str_starts_with($p->name, 'medicos.'));
        $pacientes = $permissions->filter(fn($p) => str_starts_with($p->name, 'pacientes.'));
        $puestos = $permissions->filter(fn($p) => str_starts_with($p->name, 'puestos.'));
        $sesiones = $permissions->filter(fn($p) => str_starts_with($p->name, 'sesiones.'));

        return view('roles.create', compact(
            'permissions',
            'usuarios',
            'ultrasonidos',
            'rayosX',
            'recetas',
            'consultas',
            'controlPrenatal',
            'dashboard',
            'diagnosticos',
            'emergencias',
            'empleado',
            'examenes',
            'factura',
            'farmacias',
            'hospitalizacion',
            'inventario',
            'medicos',
            'pacientes',
            'puestos',
            'sesiones'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role)
    {
        // Obtener todos los permisos
        $permissions = Permission::all();
        
        // Obtener los permisos actuales del rol
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        // Agrupar permisos por secciones
        $usuarios = $permissions->filter(fn($p) => str_starts_with($p->name, 'usuarios.'));
        $ultrasonidos = $permissions->filter(fn($p) => str_starts_with($p->name, 'ultrasonidos.'));
        $rayosX = $permissions->filter(fn($p) => str_starts_with($p->name, 'rayosX.'));
        $recetas = $permissions->filter(fn($p) => str_starts_with($p->name, 'recetas.'));
        $consultas = $permissions->filter(fn($p) => str_starts_with($p->name, 'consultas.'));
        $controlPrenatal = $permissions->filter(fn($p) => str_starts_with($p->name, 'controlPrenatal.'));
        $dashboard = $permissions->filter(fn($p) => str_starts_with($p->name, 'dashboard.'));
        $diagnosticos = $permissions->filter(fn($p) => str_starts_with($p->name, 'diagnosticos.'));
        $emergencias = $permissions->filter(fn($p) => str_starts_with($p->name, 'emergencias.'));
        $empleado = $permissions->filter(fn($p) => str_starts_with($p->name, 'empleado.'));
        $examenes = $permissions->filter(fn($p) => str_starts_with($p->name, 'examenes.'));
        $factura = $permissions->filter(fn($p) => str_starts_with($p->name, 'factura.'));
        $farmacias = $permissions->filter(fn($p) => str_starts_with($p->name, 'farmacias.'));
        $hospitalizacion = $permissions->filter(fn($p) => str_starts_with($p->name, 'hospitalizacion.'));
        $inventario = $permissions->filter(fn($p) => str_starts_with($p->name, 'inventario.'));
        $medicos = $permissions->filter(fn($p) => str_starts_with($p->name, 'medicos.'));
        $pacientes = $permissions->filter(fn($p) => str_starts_with($p->name, 'pacientes.'));
        $puestos = $permissions->filter(fn($p) => str_starts_with($p->name, 'puestos.'));
        $sesiones = $permissions->filter(fn($p) => str_starts_with($p->name, 'sesiones.'));

        return view('roles.edit', compact(
            'role',
            'permissions',
            'rolePermissions',
            'usuarios',
            'ultrasonidos',
            'rayosX',
            'recetas',
            'consultas',
            'controlPrenatal',
            'dashboard',
            'diagnosticos',
            'emergencias',
            'empleado',
            'examenes',
            'factura',
            'farmacias',
            'hospitalizacion',
            'inventario',
            'medicos',
            'pacientes',
            'puestos',
            'sesiones'
        ));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}