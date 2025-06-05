<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    // Mostrar la lista de empleados
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
        return view('empleados.create');
    }

    // Guardar un nuevo empleado
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:empleados,correo',
            'telefono' => 'required|string|max:20',
        ]);

        Empleado::create($request->all());

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
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
        return view('empleados.edit', compact('empleado'));
    }

    // Actualizar los datos del empleado
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:empleados,correo,' . $empleado->id,
            'telefono' => 'required|string|max:20',
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

