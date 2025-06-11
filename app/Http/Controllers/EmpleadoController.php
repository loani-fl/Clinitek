<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('buscar');

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

        return view('empleados.index', [
            'empleados' => $empleados,
            'busqueda' => $busqueda,
            'usuarioActual' => session('usuario_nombre')
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
    $validatedData = $request->validate([
        'nombres'           => 'required|string|max:255',
        'apellidos'         => 'required|string|max:255',
        'identidad'         => 'required|string|max:25|unique:listaempleados',
        'direccion'         => 'required|string|max:255',
        'telefono'          => 'required|string|max:15',
        'correo'            => 'required|email|unique:listaempleados',
        'fecha_ingreso'     => 'required|date',
        'fecha_nacimiento'  => 'required|date',
        'genero'            => 'required|in:Masculino,Femenino,Otro',
        'estado_civil'      => 'required|string|max:50',
        'puesto_id'         => 'required|exists:puestos,id',
        'salario'           => 'required|numeric|min:0',
        'area'              => 'nullable|string|max:100',
        'observaciones'     => 'nullable|string',
        'turno_asignado'    => 'required|string|max:50',
    ]);

    Empleado::create($validatedData); // <-- Esta es la línea correcta

    return redirect()->route('empleados.index')->with('success', 'Empleado registrado exitosamente.');
}


    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado correctamente.');
    }
}
