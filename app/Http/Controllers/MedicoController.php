<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medico;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MedicoController extends Controller
{
    public function create()
    {
        return view('medicos.create');
    }

    public function store(Request $request)
    {
        $hoy = Carbon::today();
        $fecha18Anios = $hoy->copy()->subYears(18)->format('Y-m-d');
        $fechaMinIngreso = $hoy->copy()->subMonth()->format('Y-m-d');
        $fechaMaxIngreso = $hoy->copy()->addMonth()->format('Y-m-d');

        $request->validate([
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u'],
            'apellidos' => ['required', 'regex:/^[\pL\s]+$/u'],
            'numero_identidad' => [
                'required',
                'regex:/^(0[1-9]|1[0-8])(0[1-9]|1[0-9]|2[0-8])(19[4-9][0-9]|200[0-7])\d{5}$/',
                'unique:medicos,numero_identidad'
            ],
            'especialidad' => 'required|string',
            'telefono' => [
                'required',
                'numeric',
                'digits:8',
                'regex:/^[9238][0-9]{7}$/',
                'unique:medicos,telefono'
            ],
            'correo' => [
    'required',
    'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/',
    'unique:medicos,correo'
],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:' . $fecha18Anios],
            'fecha_ingreso' => ['required', 'date', 'after_or_equal:' . $fechaMinIngreso, 'before_or_equal:' . $fechaMaxIngreso],
            'genero' => 'required',
            'observaciones' => 'nullable|string',
            'direccion' => ['required', 'string', 'max:300'],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',

            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',

            'numero_identidad.required' => 'El número de identidad es obligatorio.',
            'numero_identidad.regex' => 'El número de identidad debe tener 13 dígitos: 2 dígitos de departamento (01-18), 2 de municipio (01-28), 4 del año de nacimiento (1940-2007), y 5 números más.',
            'numero_identidad.unique' => 'El número de identidad ya está registrado.',

            'especialidad.required' => 'La especialidad es obligatoria.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.numeric' => 'El teléfono debe ser un número válido.',
            'telefono.digits' => 'El teléfono debe tener 8 dígitos.',
            'telefono.regex' => 'El teléfono debe iniciar con 9, 2, 3 o 8.',
            'telefono.unique' => 'El teléfono ya está registrado.',

            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'Debe ingresar un correo válido.',
            'correo.unique' => 'El correo ya está registrado.',
            'correo.regex' => 'Debe ingresar un correo válido con @ y punto de dominio.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_nacimiento.before_or_equal' => 'Debes tener al menos 18 años.',

            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'fecha_ingreso.after_or_equal' => 'La fecha de ingreso no puede ser anterior a :date.',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser posterior a :date.',

            'genero.required' => 'El género es obligatorio.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no puede superar los 300 caracteres.',

            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.mimes' => 'La foto debe ser un archivo jpg, jpeg, png o gif.',
            'foto.max' => 'La foto no puede superar los 2MB.',
        ]);

        // Asignar salario según especialidad
        $salarios = [
            'Pediatría' => 25000,
            'Cardiología' => 30000,
            'Medicina General' => 18000,
            'Dermatología' => 22000,
            'Neurología' => 32000,
            'Ginecología' => 27000,
        ];

        $especialidad = $request->input('especialidad');
        $salario = $salarios[$especialidad] ?? 0;

        $datosMedico = $request->only([
            'nombre', 'apellidos', 'numero_identidad', 'especialidad', 'telefono',
            'correo', 'fecha_nacimiento', 'fecha_ingreso', 'genero', 'observaciones', 'direccion'
        ]);

        $datosMedico['salario'] = $salario;

        if ($request->hasFile('foto')) {
            $datosMedico['foto'] = $request->file('foto')->store('fotos_medicos', 'public');
        }

        Medico::create($datosMedico);

        return redirect()->route('medicos.index')->with('success', 'Médico registrado exitosamente');
    }

    public function index(Request $request)
    {
        $query = Medico::query();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('especialidad', 'like', "%{$buscar}%")
                  ->orWhere('numero_identidad', 'like', "%{$buscar}%")
                  ->orWhere('correo', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        if ($request->has('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        $medicos = $query->orderBy('nombre')->paginate(4)->appends($request->all());

        return view('medicos.index', compact('medicos'));
    }

    public function show($id)
    {
        $medico = Medico::findOrFail($id);
        return view('medicos.show', compact('medico'));
    }

    public function edit($id)
    {
        $medico = Medico::findOrFail($id);
        return view('medicos.edit', compact('medico'));
    }

    public function update(Request $request, $id)
    {
        $hoy = Carbon::today();
        $fecha18Anios = $hoy->copy()->subYears(18)->format('Y-m-d');
        $fechaMinIngreso = $hoy->copy()->subMonth()->format('Y-m-d');
        $fechaMaxIngreso = $hoy->copy()->addMonth()->format('Y-m-d');

        $request->validate([
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'apellidos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'numero_identidad' => [
                'required',
                'regex:/^(0[1-9]|1[0-8])(0[1-9]|1[0-9]|2[0-8])(19[4-9][0-9]|200[0-7])\d{5}$/',
                Rule::unique('medicos', 'numero_identidad')->ignore($id)
            ],
            'especialidad' => 'required|string|max:80',
            'telefono' => [
                'required',
                'numeric',
                'digits:8',
                'regex:/^[9238][0-9]{7}$/',
                Rule::unique('medicos', 'telefono')->ignore($id)
            ],
            'correo' => [
                'required',
                'email',
                'max:30',
                Rule::unique('medicos', 'correo')->ignore($id)
            ],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:' . $fecha18Anios],
            'fecha_ingreso' => ['required', 'date', 'after_or_equal:' . $fechaMinIngreso, 'before_or_equal:' . $fechaMaxIngreso],
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'observaciones' => 'nullable|string|max:100',
            'direccion' => ['required', 'string', 'max:300'],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'estado' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no debe superar los 50 caracteres.',

            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
            'apellidos.max' => 'Los apellidos no deben superar los 50 caracteres.',

            'numero_identidad.required' => 'El número de identidad es obligatorio.',
            'numero_identidad.regex' => 'El número de identidad debe tener 13 dígitos: 2 dígitos de departamento (01-18), 2 de municipio (01-28), 4 del año de nacimiento (1940-2007), y 5 números más.',
            'numero_identidad.unique' => 'Este número de identidad ya está registrado.',

            'especialidad.required' => 'La especialidad es obligatoria.',
            'especialidad.max' => 'La especialidad no debe superar los 80 caracteres.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'Debe tener 8 dígitos.',
            'telefono.regex' => 'El teléfono debe iniciar con 9, 2, 3 o 8.',
            'telefono.unique' => 'Este número ya está registrado.',

            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'Formato de correo inválido.',
            'correo.max' => 'El correo no debe superar los 30 caracteres.',
            'correo.unique' => 'Este correo ya está registrado.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before_or_equal' => 'Debes tener al menos 18 años.',

            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.after_or_equal' => 'No puede ser anterior a hace un mes.',
            'fecha_ingreso.before_or_equal' => 'No puede ser posterior a un mes después de hoy.',

            'genero.required' => 'El género es obligatorio.',
            'genero.in' => 'El género no es válido.',

            'observaciones.max' => 'Las observaciones no deben exceder 100 caracteres.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'No debe superar los 300 caracteres.',

            'foto.image' => 'Debe ser una imagen.',
            'foto.mimes' => 'Formatos válidos: jpg, jpeg, png, gif.',
            'foto.max' => 'No debe pesar más de 2MB.',
        ]);

        $medico = Medico::findOrFail($id);

        $salarios = [
            'Pediatría' => 27500,
            'Cardiología' => 15000,
            'Dermatología' => 14200,
            'Neurología' => 24800,
            'Psiquiatría' => 14700,
            'Radiología' => 16300,
        ];

        $especialidad = $request->input('especialidad');
        $salario = $salarios[$especialidad] ?? 0;

        // Actualizar foto
        if ($request->hasFile('foto')) {
            if ($medico->foto && Storage::disk('public')->exists($medico->foto)) {
                Storage::disk('public')->delete($medico->foto);
            }
            $medico->foto = $request->file('foto')->store('fotos_medicos', 'public');
        }

        $estado = $request->has('estado') ? (bool)$request->estado : false;

        $medico->update([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'numero_identidad' => $request->numero_identidad,
            'especialidad' => $especialidad,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'genero' => $request->genero,
            'observaciones' => $request->observaciones,
            'direccion' => $request->direccion,
            'salario' => $salario,
            'foto' => $medico->foto,
            'estado' => $estado,
        ]);

        return redirect()->route('medicos.index')->with('success', 'Médico actualizado exitosamente');
    }

    public function toggleEstado(Medico $medico)
    {
        $medico->estado = !$medico->estado;
        $medico->save();

        return redirect()->route('medicos.edit', $medico->id)
            ->with('success', 'Estado del médico actualizado correctamente.');
    }

    public function buscar(Request $request)
    {
        $termino = $request->input('buscar');

        $medicos = Medico::where('nombre', 'like', "%{$termino}%")
            ->orWhere('correo', 'like', "%{$termino}%")
            ->orWhere('especialidad', 'like', "%{$termino}%")
            ->get();

        return response()->json($medicos);
    }
}
