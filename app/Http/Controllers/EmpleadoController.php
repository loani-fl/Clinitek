<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Models\Empleado;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    public function index()
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
        $turnos = ['mañana', 'tarde', 'noche'];
        return view('empleado.create', compact('puestos', 'turnos'));
    }

    public function store(Request $request)
    {
        $anio = now()->year;
        $hoy = now()->toDateString();
        $hace18 = now()->subYears(18)->toDateString();
        $fechaLimite = now()->subYears(18);
        $departamentosValidos = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18'];

        $rules = [
            'nombres' => ['required', 'string', 'max:50', 'regex:/^[\pL\s]+$/u'],
            'apellidos' => ['required', 'string', 'max:50', 'regex:/^[\pL\s]+$/u'],
            'identidad' => [
                'required',
                'digits:13',
                'unique:listaempleados,identidad',
                function ($attribute, $value, $fail) use ($departamentosValidos) {
                    $codigoDepartamento = substr($value, 0, 2);
                    if (!in_array($codigoDepartamento, $departamentosValidos)) {
                        $fail('El código del departamento en la identidad no es válido.');
                    }
                }
            ],
            'telefono' => [
                'required',
                'digits:8',
                'regex:/^[2389][0-9]{7}$/',
                'unique:listaempleados,telefono'
            ],
            'correo' => ['required', 'string', 'max:30', 'email', 'unique:listaempleados,correo'],
            'fecha_ingreso' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $fecha = Carbon::parse($value);
                    $anio = now()->year;
                    $min = Carbon::createFromDate($anio, 5, 1);
                    $max = Carbon::createFromDate($anio, 8, 31);
                    if ($fecha->lt($min) || $fecha->gt($max)) {
                        $fail("La fecha de ingreso debe estar entre mayo y agosto de $anio.");
                    }
                }
            ],
            'fecha_nacimiento' => [
                'required',
                'date',
                "before_or_equal:$hace18",
                function ($attribute, $value, $fail) use ($fechaLimite) {
                    $fecha = Carbon::parse($value);
                    if ($fecha->greaterThan($fechaLimite)) {
                        $fail('Usted no es mayor de edad.');
                    }
                }
            ],
            'direccion' => 'required|string|max:30',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'estado_civil' => 'nullable|in:Soltero,Casado,Divorciado,Viudo',
            'puesto_id' => 'required|exists:puestos,id',
            'salario' => 'required|numeric|between:0,99999.99',
            'observaciones' => 'nullable|string|max:40',
            'turno_asignado' => 'required|in:mañana,tarde,noche',
        ];

        $validated = $request->validate($rules);

        $puesto = Puesto::findOrFail($validated['puesto_id']);
        $validated['area'] = $puesto->area;

        Empleado::create($validated);

        return redirect()->route('empleados.index')->with('success', 'Empleado registrado correctamente.');
    }

    public function show(string $id)
    {
        $empleado = Empleado::with('puesto')->findOrFail($id);
        return view('empleado.show', compact('empleado'));
    }

    public function edit(string $id)
    {
        $empleado = Empleado::with('puesto')->findOrFail($id);
        $puestos = Puesto::all();
        $turnos = ['mañana', 'tarde', 'noche'];
        return view('empleado.edit', compact('empleado', 'puestos', 'turnos'));
    }

    public function update(Request $request, string $id)
    {
        $empleado = Empleado::findOrFail($id);
    
        $rules = [
            'nombres' => ['required', 'string', 'max:50'],
            'apellidos' => ['required', 'string', 'max:50'],
            'identidad' => ['required', 'string', 'max:20'],
            'telefono' => ['required', 'digits:8', 'regex:/^[2389][0-9]{7}$/'],
            'correo' => ['required', 'string', 'max:30', 'email'],
            'direccion' => ['required', 'string', 'max:30'],
            'genero' => ['required', 'in:Masculino,Femenino,Otro'],
            'estado_civil' => ['nullable', 'in:Soltero,Casado,Divorciado,Viudo'],
            'puesto_id' => ['required', 'exists:puestos,id'],
            'salario' => ['required', 'numeric', 'between:0,99999.99'],
            'observaciones' => ['nullable', 'string', 'max:40'],
            'turno_asignado' => ['required', 'in:mañana,tarde,noche'],
            'fecha_ingreso' => ['required', 'date'],
            'fecha_nacimiento' => ['required', 'date'],
            'estado' => ['required', 'in:Activo,Inactivo'],
        ];
    
        $validated = $request->validate($rules);
    
        // Obtener área del puesto seleccionado
        $puesto = Puesto::findOrFail($validated['puesto_id']);
        $validated['area'] = $puesto->area;
    
        $empleado->update($validated);
    
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }
    

    public function destroy(string $id)
    {
        //
    }
}
