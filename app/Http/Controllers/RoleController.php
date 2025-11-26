<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Usuario;
use Illuminate\Support\Facades\Artisan;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda');

        // Iniciar la consulta excluyendo el rol "administrador"
        $query = Role::with('permissions', 'users')
            ->where('name', '!=', 'administrador');

        // Si hay búsqueda, filtrar por nombre
        if ($busqueda) {
            $query->where('name', 'like', '%' . $busqueda . '%');
        }

        // Aplicar paginación
        $roles = $query->paginate(5);

        // Si es una petición AJAX, retornar JSON
        if ($request->ajax()) {
            $html = '';

            if ($roles->count() > 0) {
                foreach ($roles as $index => $role) {
                    $numero = ($roles->currentPage() - 1) * $roles->perPage() + $index + 1;
                    $editUrl = route('roles.edit', $role->id);
                    $deleteUrl = route('roles.destroy', $role->id);
                    $csrf = csrf_token();

                    $deleteButton = '';
                    if ($role->users->count() == 0) {
                        $deleteButton = '
                            <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="' . $csrf . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'¿Estás seguro de eliminar este rol?\');" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        ';
                    } else {
                        $deleteButton = '
                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="No se puede eliminar: tiene usuarios asignados">
                                <i class="bi bi-trash"></i>
                            </button>
                        ';
                    }

                    $html .= '
                        <tr>
                            <td>' . $numero . '</td>
                            <td>' . htmlspecialchars($role->name) . '</td>
                            <td>
                                <span class="badge bg-success">
                                    ' . $role->permissions->count() . ' permisos
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    ' . $role->users->count() . ' usuarios
                                </span>
                            </td>
                            <td>
                                <a href="' . $editUrl . '" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                ' . $deleteButton . '
                            </td>
                        </tr>
                    ';
                }
            } else {
                $html = '
                    <tr>
                        <td colspan="5" class="text-center fst-italic text-muted">No hay roles registrados</td>
                    </tr>
                ';
            }

            return response()->json([
                'html' => $html,
                'pagination' => $roles->links()->render(),
                'total' => $roles->total()
            ]);
        }

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
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\pL\s]+$/u', // solo letras y espacios
                'unique:roles,name',
            ],
            'permissions' => 'required|array',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede tener más de 50 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios, sin números ni caracteres especiales.',
            'name.unique' => 'El nombre ya existe.',
            'permissions.required' => 'Debe seleccionar al menos un permiso.',
        ]);

        // Crear el rol
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        // Sincronizar permisos seleccionados
        $role->syncPermissions($request->permissions ?? []);

        // Limpiar cache de permisos
        Artisan::call('permission:cache-reset');

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }





    public function edit($id)
    {
        $role = Role::findOrFail($id);

        // Obtener todos los permisos
        $permissions = Permission::all();

        // Agrupar permisos por secciones (igual que en create)
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

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\pL\s]+$/u',
                'unique:roles,name,' . $role->id,
            ],
            'permissions' => 'required|array',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede tener más de 50 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios, sin números ni caracteres especiales.',
            'name.unique' => 'El nombre ya existe.',
            'permissions.required' => 'Debe seleccionar al menos un permiso.',
        ]);

        $role->name = $request->name;
        $role->save();

        // Sincronizar permisos
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }



public function destroy(Role $role)
    {
        // Verificar si el rol tiene usuarios asignados
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
