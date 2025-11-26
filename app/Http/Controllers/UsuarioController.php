<?php

namespace App\Http\Controllers;


use Spatie\Permission\Models\Role;
use App\Models\Usuario;
use App\Models\Empleado;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $rolId = $request->input('rol');
    
        $usuarios = Usuario::with('roles')
            ->when($query, function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->when($rolId, function($q) use ($rolId) {
                $q->whereHas('roles', function($qr) use ($rolId) {
                    $qr->where('id', $rolId);
                });
            })
            ->paginate(10)
            ->appends($request->all());
    
        $roles = Role::all();
    
        if ($request->ajax()) {
            return response()->json([
                'html' => view('Usuarios.partials.tabla', compact('usuarios'))->render(),
                'pagination' => view('Usuarios.partials.custom-pagination', compact('usuarios'))->render(),
                'total' => $usuarios->total(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastItem(),
            ]);
        }
    
        return view('Usuarios.index', compact('usuarios','roles'));
    }
    

    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all(); // Traemos los roles existentes
        return view('usuarios.create', compact('roles'));
    }
    
    public function store(Request $request)
{
    // Buscar persona (empleado o médico)
    $persona = Empleado::find($request->persona_id) ?? Medico::find($request->persona_id);
    if (!$persona) {
        return back()->withErrors(['persona_id' => 'No se encontró la persona seleccionada.'])->withInput();
    }

    $correo = $persona->correo ?? $persona->email ?? $request->correo_usuario;

    // Validar todo
    $request->merge(['correo_usuario' => $correo]); // reemplaza el input con el correo real
    $request->validate([
        'persona_id' => 'required|integer',
        'rol_id' => 'required|exists:roles,id',
        'correo_usuario' => 'required|email|unique:usuarios,email',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'persona_id.required' => 'Debes seleccionar un empleado o médico.',
        'rol_id.required' => 'Debes seleccionar un rol.',
        'correo_usuario.required' => 'El correo electrónico es obligatorio.',
        'correo_usuario.email' => 'El correo debe ser válido.',
        'correo_usuario.unique' => 'El correo electrónico ya está registrado.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
        'password.confirmed' => 'La confirmación de la contraseña no coincide.',
    ]);

    // Crear usuario
    $usuario = Usuario::create([
        'name' => $persona->nombre ?? $persona->nombres ?? $persona->name,
        'email' => $correo,
        'password' => Hash::make($request->password)
    ]);

    // Asignar rol
    $rol = Role::find($request->rol_id);
    $usuario->assignRole($rol);

    return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente con rol asignado.');
}

    

    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'password.min' => 'La contraseña debe tener mínimo 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

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

// Método AJAX para buscar empleados y médicos
public function searchPersonas(Request $request)
{
    $query = $request->q ?? '';
    $query = trim($query);

    if(strlen($query) < 1){
        return response()->json([]);
    }

    $query = strtolower($query);

    // Buscar empleados
    $empleados = \App\Models\Empleado::where(function($q) use ($query){
        $q->whereRaw('LOWER(nombres) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(apellidos) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(correo) LIKE ?', ["%{$query}%"]);
    })->get();

    // Buscar médicos
    $medicos = \App\Models\Medico::where(function($q) use ($query){
        $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(apellidos) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(correo) LIKE ?', ["%{$query}%"]);
    })->get();

    // Combinar resultados
    $personas = $empleados->map(fn($e) => [
        'id' => $e->id,
        'nombre' => $e->nombres,
        'apellido' => $e->apellidos,
        'correo' => $e->correo,
        'nombre_completo' => $e->nombres . ' ' . $e->apellidos,
    ])->merge(
        $medicos->map(fn($m) => [
            'id' => $m->id,
            'nombre' => $m->nombre,
            'apellido' => $m->apellidos,
            'correo' => $m->correo,
            'nombre_completo' => $m->nombre . ' ' . $m->apellidos,
        ])
    );

    // Filtrar coincidencias exactas de la query
    $personas = $personas->filter(fn($p) => 
        stripos($p['nombre'], $query) !== false ||
        stripos($p['apellido'], $query) !== false
    )->values();

    return response()->json($personas);
}

    // --------------------
    // Métodos de asignación de roles/permisos
    // --------------------
    public function asignarVista($id)
    {
        $user = Usuario::findOrFail($id);

        $roles = Role::all();
        $permisos = Permission::all();

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