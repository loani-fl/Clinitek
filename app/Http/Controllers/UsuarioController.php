<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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
        $roles = Role::all(); 
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Buscar empleado o médico
        $persona = Empleado::find($request->persona_id) ?? Medico::find($request->persona_id);

        if (!$persona) {
            return back()->withErrors(['persona_id' => 'No se encontró la persona seleccionada.'])->withInput();
        }

        $correo = $persona->correo ?? $persona->email ?? $request->correo_usuario;

        // Agregar el correo real antes de validar
        $request->merge(['correo_usuario' => $correo]);

        // Validación
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

    
   // Búsqueda de empleados y médicos
public function searchPersonas(Request $request)
{
    $query = trim(strtolower($request->q ?? ''));

    // Si la búsqueda está vacía, devolver vacío
    if(strlen($query) < 1){
        return response()->json([]);
    }

    /* =======================
       BUSCAR EMPLEADOS
    ======================== */
    $empleados = Empleado::where(function($q) use ($query){
        $q->whereRaw('LOWER(nombres) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(apellidos) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(correo) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(identidad) LIKE ?', ["%{$query}%"]);
    })->get();

    /* =======================
       BUSCAR MÉDICOS
    ======================== */
    $medicos = Medico::where(function($q) use ($query){
        $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(apellidos) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(correo) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(numero_identidad) LIKE ?', ["%{$query}%"])
          ->orWhereRaw('LOWER(especialidad) LIKE ?', ["%{$query}%"]);
    })->get();

    /* =======================
       UNIR RESULTADOS
    ======================== */
    $personas = collect();

    // FORMATO EMPLEADOS
    foreach($empleados as $e){
        $personas->push([
            'id' => $e->id,
            'tipo' => 'empleado',
            'nombre' => $e->nombres,
            'apellido' => $e->apellidos,
            'correo' => $e->correo,
            'telefono' => $e->telefono,
            'identidad' => $e->identidad,
            'genero' => $e->genero,
            'direccion' => $e->direccion ?? null,
            'fecha_nacimiento' => $e->fecha_nacimiento ?? null,
            'fecha_ingreso' => $e->fecha_ingreso ?? null,
            'nombre_completo' => $e->nombres . ' ' . $e->apellidos,
        ]);
    }

    // FORMATO MÉDICOS
    foreach($medicos as $m){
        $personas->push([
            'id' => $m->id,
            'tipo' => 'medico',
            'nombre' => $m->nombre,
            'apellido' => $m->apellidos,
            'correo' => $m->correo,
            'telefono' => $m->telefono,
            'identidad' => $m->numero_identidad,
            'genero' => $m->genero,
            'direccion' => $m->direccion,
            'especialidad' => $m->especialidad,
            'fecha_nacimiento' => $m->fecha_nacimiento,
            'fecha_ingreso' => $m->fecha_ingreso,
            'salario' => $m->salario,
            'observaciones' => $m->observaciones,
            'nombre_completo' => $m->nombre . ' ' . $m->apellidos,
        ]);
    }

    return response()->json($personas->values());
}


    // ----------------------------------------
    // Roles y permisos
    // ----------------------------------------
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
        $user->syncRoles($request->input('role'));

        return redirect()->route('usuarios.index')->with('success', 'Rol actualizado correctamente.');
    }
}
