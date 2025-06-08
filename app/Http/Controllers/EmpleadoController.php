<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    // Mostrar la lista de empleados con búsqueda y paginación
    public function index(Request $request)
    {
        $busqueda = $request->input('buscar');

        $empleados = Empleado::when($busqueda, function ($query, $busqueda) {
            return $query->where('nombre', 'like', "%$busqueda%")
                         ->orWhere('puesto', 'like', "%$busqueda%");
        })->orderBy('nombre')->paginate(10);

        return view('empleados.index', compact('empleados', 'busqueda'));
    }

    // Mostrar formulario para crear nuevo empleado
    public function create()
    {
        $puestos = Puesto::all();
        return view('empleados.create', compact('puestos'));
    }

    // Guardar un nuevo empleado
    public function store(Request $request)
    {
        $request->validate([
            'nombres'           => 'required|string|max:255',
            'apellidos'         => 'required|string|max:255',
            'identidad'         => 'required|unique:empleados,identidad',
            'direccion'         => 'required|string',
            'telefono'          => 'required|string|max:15',
            'correo'            => 'required|email|unique:empleados,correo',
            'fecha_ingreso'     => 'required|date',
            'fecha_nacimiento'  => 'required|date',
            'genero'            => 'required',
            'estado_civil'      => 'required',
            'puesto_id'         => 'required|exists:puestos,id',
            'salario'           => 'required|numeric|min:0',
            'observaciones'     => 'nullable|string',
        ]);

        Empleado::create($request->all());

        return redirect()->route('empleados.create')->with('success', 'Empleado registrado exitosamente.');
    }

    // Mostrar detalles de un empleado
    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.show', compact('empleado'));
    }

    // Mostrar formulario para editar empleado
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $puestos = Puesto::all();
        return view('empleados.edit', compact('empleado', 'puestos'));
    }

    // Actualizar los datos del empleado
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'nombres'           => 'required|string|max:255',
            'apellidos'         => 'required|string|max:255',
            'identidad'         => 'required|unique:empleados,identidad,' . $empleado->id,
            'direccion'         => 'required|string',
            'telefono'          => 'required|string|max:15',
            'correo'            => 'required|email|unique:empleados,correo,' . $empleado->id,
            'fecha_ingreso'     => 'required|date',
            'fecha_nacimiento'  => 'required|date',
            'genero'            => 'required',
            'estado_civil'      => 'required',
            'puesto_id'         => 'required|exists:puestos,id',
            'salario'           => 'required|numeric|min:0',
            'observaciones'     => 'nullable|string',
        ]);

        $empleado->update($request->all());

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    // Eliminar un empleado
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}

