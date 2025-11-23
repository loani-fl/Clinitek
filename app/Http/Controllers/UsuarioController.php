<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::orderBy('id', 'desc');

        // Filtro por búsqueda (nombre o email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $usuarios = $query->paginate(2)->withQueryString();

        if ($request->ajax()) {
            $html = view('Usuarios.partials.tabla', compact('usuarios'))->render();
            $pagination = view('Usuarios.partials.custom-pagination', compact('usuarios'))->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'total' => $usuarios->count(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastItem(),
                'total' => $usuarios->total(),
            ]);
        }

        return view('Usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener minimo 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        Usuario::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Usuario $usuario)
    {
        // Crear una copia de los valores originales para restaurar
        $usuarioOriginal = $usuario->replicate();

        return view('usuarios.edit', compact('usuario', 'usuarioOriginal'));
    }


    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.min' => 'La contraseña debe tener minimo 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }




    public function asignarVista($id)
    {
        $user = Usuario::findOrFail($id);

        // Obtener roles y permisos
        $roles = Role::all();
        $permisos = Permission::all();

        // AGRUPAR PERMISOS POR SECCIONES
        $usuarios = $permisos->filter(fn($p) => str_starts_with($p->name, 'usuarios.'));
        $pacientes = $permisos->filter(fn($p) => str_starts_with($p->name, 'pacientes.'));
        $medicos = $permisos->filter(fn($p) => str_starts_with($p->name, 'medicos.'));
        $empleado = $permisos->filter(fn($p) => str_starts_with($p->name, 'empleado.'));
        $consultas = $permisos->filter(fn($p) => str_starts_with($p->name, 'consultas.'));
        $controlPrenatal = $permisos->filter(fn($p) => str_starts_with($p->name, 'controlPrenatal.'));
        $recetas = $permisos->filter(fn($p) => str_starts_with($p->name, 'recetas.'));
        $rayosX = $permisos->filter(fn($p) => str_starts_with($p->name, 'rayosX.'));
        $ultrasonidos = $permisos->filter(fn($p) => str_starts_with($p->name, 'ultrasonidos.'));
        $inventario = $permisos->filter(fn($p) => str_starts_with($p->name, 'inventario.'));
        $farmacias = $permisos->filter(fn($p) => str_starts_with($p->name, 'farmacias.'));
        $hospitalizacion = $permisos->filter(fn($p) => str_starts_with($p->name, 'hospitalizacion.'));
        $diagnosticos = $permisos->filter(fn($p) => str_starts_with($p->name, 'diagnosticos.'));
        $emergencias = $permisos->filter(fn($p) => str_starts_with($p->name, 'emergencias.'));
        $puestos = $permisos->filter(fn($p) => str_starts_with($p->name, 'puestos.'));
        $examenes = $permisos->filter(fn($p) => str_starts_with($p->name, 'examenes.'));
        $sesiones = $permisos->filter(fn($p) => str_starts_with($p->name, 'sesiones.'));
        $dashboard = $permisos->filter(fn($p) => str_starts_with($p->name, 'dashboard.'));

        return view('usuarios.asignar', compact(
            'user',
            'roles',
            'permisos',
            'usuarios',
            'pacientes',
            'medicos',
            'empleado',
            'consultas',
            'controlPrenatal',
            'recetas',
            'rayosX',
            'ultrasonidos',
            'inventario',
            'farmacias',
            'hospitalizacion',
            'diagnosticos',
            'emergencias',
            'puestos',
            'examenes',
            'sesiones',
            'dashboard'
        ));
    }

    public function asignarUpdate(Request $request, $id)
    {
        $user = Usuario::findOrFail($id);
        $user->syncRoles($request->input('role')); // un solo rol

        return redirect()->route('usuarios.index')->with('success', 'Rol actualizado correctamente.');
    }

}
