<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puesto;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EmpleadosController extends Controller
{
    public function create()
    {
        $puestos = Puesto::all();
        return view('empleado.create', compact('puestos'));
    }

    public function store(Request $request)
    {
        $hoy = Carbon::today();
        $hace18 = $hoy->copy()->subYears(18);
        $hace65 = $hoy->copy()->subYears(65);
        $anio = now()->year;
        $fechaLimite = now()->subYears(18);
        $departamentosValidos = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18'];

        $rules = [
            'nombres' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'
            ],
            'apellidos' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'
            ],
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
            'correo' => [
                'required',
                'string',
                'max:30',
                'unique:listaempleados,correo',
                'regex:/^[a-zA-Z]+@[a-zA-Z]+(\.[a-zA-Z]+){1,2}$/'
            ],
            'fecha_ingreso' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($anio) {
                    $fecha = Carbon::parse($value);
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
                function ($attribute, $value, $fail) use ($hace18, $hace65) {
                    $fecha = Carbon::parse($value);
                    if ($fecha->gt($hace18)) {
                        $fail('Debes tener al menos 18 años.');
                    }
                    if ($fecha->lt($hace65)) {
                        $fail('No debes tener más de 65 años.');
                    }
                },
            ],
            'direccion' => [
                'required',
                'string',
                'max:200',
                'regex:/^[\pL\pN\s.,;#\/\-\(\)áéíóúÁÉÍÓÚñÑ]+$/u',
            ],
            'observaciones' => [
                'required',
                'string',
                'max:350',
                'regex:/^[\pL\pN\s.,;áéíóúÁÉÍÓÚñÑ]+$/u',
            ],
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'estado_civil' => 'nullable|in:Soltero,Casado,Divorciado,Viudo',
            'puesto_id' => 'required|exists:puestos,id',
            'salario' => 'required|numeric|between:0,99999.99',
            'area' => 'required|string|max:50',
            'turno_asignado' => 'required|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'nombres.regex' => 'Nombres solo debe contener letras y espacios.',
            'apellidos.required' => 'Los Apellidos son obligatorios.',
            'apellidos.regex' => 'Apellidos solo debe contener letras y espacios.',
            'identidad.required' => 'La identidad es obligatoria.',
            'telefono.required' => 'El Teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono debe comenzar con 2, 3, 8 o 9 y contener exactamente 8 dígitos numéricos.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'El correo debe ser un correo válido.',
            'correo.unique' => 'El correo ya está en uso.',
            'fecha_nacimiento.before_or_equal' => 'El empleado debe tener al menos 18 años.',
            'direccion.required' => 'La Dirección es obligatoria.',
            'observaciones.required' => 'Las Observaciones son obligatorias.',
            'turno_asignado.required' => 'El turno asignado es obligatorio.',
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
            'area' => 'Área',
            'turno_asignado' => 'Turno asignado',
        ];

        // 1. Validar los datos
        $validated = $request->validate($rules, $messages, $attributes);

        // 2. Buscar el puesto y asignar el área al arreglo validado
        $puesto = Puesto::findOrFail($validated['puesto_id']);
        $validated['area'] = $puesto->area;

        Log::info('Se validó correctamente. Intentando crear empleado...', $validated);

        // 3. Manejar foto si existe
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/fotos', $nombreArchivo);
            $validated['foto'] = $nombreArchivo;
        }

        $validated['estado'] = 'activo';

        // 4. Crear el empleado
        $empleado = Empleado::create($validated);

        // 5. Redireccionar con mensaje
        if ($empleado) {
            return redirect()->route('empleado.create')->with('success', 'Empleado registrado correctamente.');
        } else {
            return back()->withErrors('No se pudo registrar el empleado.');
        }
    }

    public function index()
    {
        $empleados = Empleado::with('puesto')->paginate(10);
        return view('empleado.index', compact('empleados'));
    }

    public function update(Request $request, string $id)
    {
        $empleado = Empleado::findOrFail($id);

        $anio = now()->year;
        $hace18 = now()->subYears(18)->toDateString();
        $fechaLimite = now()->subYears(18);
        $departamentosValidos = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18'];

        $rules = [
            'nombres' => ['required', 'string', 'max:50', 'regex:/^[\pL\s]+$/u'],
            'apellidos' => ['required', 'string', 'max:50', 'regex:/^[\pL\s]+$/u'],
            'identidad' => [
                'required',
                'digits:13',
                'unique:listaempleados,identidad,' . $empleado->id,
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
                'unique:listaempleados,telefono,' . $empleado->id,
            ],
            'correo' => [
                'required',
                'string',
                'max:30',
                'email',
                'unique:listaempleados,correo,' . $empleado->id,
            ],
            'fecha_ingreso' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($anio) {
                    $fecha = Carbon::parse($value);
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
            'direccion' => ['required', 'string', 'max:200'],
            'genero' => ['required', 'in:Masculino,Femenino,Otro'],
            'estado_civil' => ['nullable', 'in:Soltero,Casado,Divorciado,Viudo'],
            'puesto_id' => ['required', 'exists:puestos,id'],
            'salario' => ['required', 'numeric', 'between:0,99999.99'],
            'observaciones' => ['nullable', 'string', 'max:350'],
            'turno_asignado' => ['required', 'in:Mañana,Tarde,Noche'],
            'estado' => ['required', 'in:Activo,Inactivo'],
            'area' => 'required|string|max:50',
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

            'direccion.required' => 'La Dirección es obligatoria.',
            'direccion.max' => 'La Dirección no puede tener más de 200 caracteres.',

            'observaciones.max' => 'Las Observaciones no pueden tener más de 350 caracteres.',

            'turno_asignado.required' => 'El Turno asignado es obligatorio.',
            'turno_asignado.in' => 'El Turno asignado no es válido.',

            'estado.required' => 'El Estado es obligatorio.',
            'estado.in' => 'El Estado no es válido.',
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
            'turno_asignado' => 'Turno asignado',
            'estado' => 'Estado',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        $puesto = Puesto::findOrFail($validated['puesto_id']);
        $validated['area'] = $puesto->area;

        $empleado->update($validated);

        return redirect()->route('empleado.index')->with('success', 'Empleado actualizado correctamente.');
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $puestos = Puesto::all();
        return view('empleado.edit', compact('empleado', 'puestos'));
    }

    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleado.show', compact('empleado'));
    }
}

