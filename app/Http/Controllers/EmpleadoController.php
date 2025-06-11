<?php

namespace App\Http\Controllers;
use App\Models\Puesto;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    
    public function create()
    {
        $puestos = Puesto::all();
        return view('empleados.create', compact('puestos'));
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
            'unique:empleados,identidad',
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
    'unique:empleados,telefono'
],


        'correo' => [
            'required',
            'string',
            'max:30',
            'email',
            'unique:empleados,correo',
        ],

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
    },
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
    ];

    $messages = [
        'required' => ':attribute es obligatorio.',
        'max' => ':attribute no puede tener más de :max caracteres.',
        'digits' => ':attribute debe tener exactamente :digits dígitos.',
        'email' => ':attribute debe ser un correo electrónico válido.',
        'unique' => ':attribute ya está registrado.',
        'date' => ':attribute debe ser una fecha válida.',
        'before_or_equal' => ':attribute no es válido.',
        'in' => 'El valor seleccionado para :attribute no es válido.',
        'exists' => 'El valor seleccionado para :attribute es inválido.',
        'numeric' => ':attribute debe ser un número.',
        'between' => ':attribute debe ser un número válido con hasta 2 decimales.',

        'nombres.required' => 'Los Nombres son obligatorios.',
        'nombres.max' => 'Nombres no debe tener más de 50 caracteres.',
        'nombres.regex' => 'Nombres solo debe contener letras y espacios.',

        'apellidos.required' => 'Los Apellidos son obligatorios.',
        'apellidos.max' => 'Apellidos no debe tener más de 50 caracteres.',
        'apellidos.regex' => 'Apellidos solo debe contener letras y espacios.',

        'identidad.required' => 'La identidad es obligatoria.',
        'identidad.digits' => 'La identidad debe contener exactamente 13 dígitos.',

        'telefono.required' => 'El Teléfono es obligatorio.',
        'telefono.digits' => 'El teléfono debe contener exactamente 8 dígitos numéricos.',
        'telefono.regex' => 'El teléfono debe comenzar con 2, 3, 8 o 9 y contener exactamente 8 dígitos numéricos.',

        'correo.required' => 'El correo es obligatorio.',
        'correo.max' => 'El correo no debe tener más de 30 caracteres.',
        'correo.email' => 'El correo debe ser un correo válido.',
        'correo.unique' => 'El correo ya está en uso.',

        'fecha_nacimiento.before_or_equal' => 'El empleado debe tener al menos 18 años.',
        'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser en el futuro.',
    ];

    $attributes = [
        'nombres' => 'Nombres',
        'apellidos' => 'Apellidos',
        'identidad' => 'Identidad',
        'telefono' => 'Teléfono',
        'direccion' => 'Dirección',
        'correo' => 'Correo electrónico',
        'fecha_ingreso' => 'Fecha de ingreso',
        'fecha_nacimiento' => 'Fecha de nacimiento',
        'genero' => 'Género',
        'estado_civil' => 'Estado civil',
        'puesto_id' => 'Puesto',
        'salario' => 'Sueldo',
        'observaciones' => 'Observaciones',
    ];

    $validated = $request->validate($rules, $messages, $attributes);

    Empleado::create($validated);

    return redirect()->back()
        ->withInput()
        ->with('success', 'Empleado registrado correctamente.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
