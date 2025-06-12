<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Models\Empleado;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('buscar');
        $tipo = $request->input('tipo', 'empleados'); // Por defecto empleados

        if ($tipo === 'medicos') {
            // Consultar médicos
            $empleados = Medico::when($busqueda, function ($query, $busqueda) {
                    return $query->where(function ($q) use ($busqueda) {
                        $q->where('nombre', 'like', "%$busqueda%")
                          ->orWhere('apellidos', 'like', "%$busqueda%");
                    });
                })
                ->orderBy('nombre')
                ->paginate(10)
                ->withQueryString();

        } else {
            // Por defecto empleados
            $empleados = Empleado::with('puesto')
                ->when($busqueda, function ($query, $busqueda) {
                    return $query->where(function ($q) use ($busqueda) {
                        $q->where('nombres', 'like', "%$busqueda%")
                          ->orWhere('apellidos', 'like', "%$busqueda%")
                          ->orWhereHas('puesto', function ($q2) use ($busqueda) {
                              $q2->where('nombre', 'like', "%$busqueda%");
                          });
                    });
                })
                ->orderBy('nombres')
                ->paginate(10)
                ->withQueryString();
        }

        return view('empleados.index', [
            'empleados' => $empleados,
            'busqueda' => $busqueda,
            'usuarioActual' => session('usuario_nombre'),
        ]);
    }

    public function create()
    {
        $puestos = Puesto::all();
        return view('empleados.create', [
            'puestos' => $puestos,
            'usuarioActual' => session('usuario_nombre')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres'           => 'required|string|max:255',
            'apellidos'         => 'required|string|max:255',
            'identidad'         => 'required|string|max:25|unique:empleados',
            'direccion'         => 'required|string|max:255',
            'telefono'          => 'required|string|max:15',
            'correo'            => 'required|email|unique:empleados',
            'fecha_ingreso'     => 'required|date',
            'fecha_nacimiento'  => 'required|date',
            'genero'            => 'required|in:Masculino,Femenino,Otro',
            'estado_civil'      => 'required|string|max:50',
            'puesto_id'         => 'required|exists:puestos,id',
            'salario'           => 'required|numeric|min:0',
            'observaciones'     => 'nullable|string',
        ]);

        Empleado::create($request->except('area'));

        return redirect()->route('empleados.index')->with('success', 'Empleado registrado exitosamente.');
    }

    public function show($id)
    {
        $empleado = Empleado::with('puesto')->findOrFail($id);
        return view('empleados.show', [
            'empleado' => $empleado,
            'usuarioActual' => session('usuario_nombre')
        ]);
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $puestos = Puesto::all();
        return view('empleados.edit', [
            'empleado' => $empleado,
            'puestos' => $puestos,
            'usuarioActual' => session('usuario_nombre')
        ]);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'nombres'       => 'required|string|max:255',
            'apellidos'     => 'required|string|max:255',
            'identidad'     => ['required', 'string', 'max:25', Rule::unique('empleados')->ignore($empleado->id)],
            'direccion'     => 'required|string|max:255',
            'correo'        => ['required', 'email', Rule::unique('empleados')->ignore($empleado->id)],
            'telefono'      => 'required|string|max:15',
            'estado_civil'  => 'required|string|max:50',
            'genero'        => 'required|in:Masculino,Femenino,Otro',
            'fecha_ingreso' => 'required|date',
            'salario'       => 'required|numeric|min:0',
            'puesto_id'     => 'required|exists:puestos,id',
        ]);

        $empleado->update($request->all());

        return redirect()->route('empleados.visualizacion')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return redirect()->route('empleados.visualizacion')->with('success', 'Empleado eliminado correctamente.');
    }

    public function visualizacion(Request $request)
    {
        if (!session('autenticado')) {
            return redirect()->route('login')->withErrors(['clave' => 'Debes iniciar sesión.']);
        }

        if (!in_array('ver_empleados', session('permisos', []))) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        $query = Empleado::with('puesto');

        if ($request->has('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('nombres', 'like', "%$buscar%")
                  ->orWhere('apellidos', 'like', "%$buscar%")
                  ->orWhere('identidad', 'like', "%$buscar%");
            });
        }

        if ($request->has('ordenar_por') && $request->has('direccion')) {
            $query->orderBy($request->ordenar_por, $request->direccion);
        }

        $empleados = $query->paginate(10);

        return view('empleados.visualizacion', [
            'empleados' => $empleados,
            'usuarioActual' => session('usuario_nombre')
        ]);
    }
}
