<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PacienteController extends Controller
{
    public function create()
    {
        return view('pacientes.create');
    }

    public function createConConsulta($paciente_id, $consulta_id)
    {
        $consulta = Consulta::findOrFail($consulta_id);

        if ($consulta->estado !== 'realizada') {
            return redirect()->back()->with('error', 'Antes debe realizarse un diagnóstico para poder crear la orden de examen.');
        }

        return view('pacientes.create', compact('paciente_id', 'consulta_id'));
    }

    private function validarAnioIdentidad($identidad)
    {
        $anio = (int)substr($identidad, 4, 4);
        $anioActual = (int)date('Y');
        return ($anio >= 1930 && $anio <= $anioActual);
    }

    public function store(Request $request)
{
    $anioActual = date('Y');

    $request->validate([
        'nombre' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
        'apellidos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
        'identidad' => [
            'required',
            'digits:13',
            'regex:/^(0[1-9]|1[0-8])(0[1-9]|1[0-9]|2[0-8])(\d{4})\d{5}$/',
            'unique:pacientes,identidad'
        ],
        'fecha_nacimiento' => [
            'required',
            'date',
            'before_or_equal:' . Carbon::now()->subYears(21)->format('Y-m-d'),
            'after_or_equal:' . Carbon::now()->subYears(60)->format('Y-m-d'),
        ],
        'telefono' => ['required', 'digits:8', 'regex:/^[2389][0-9]{7}$/', 'unique:pacientes,telefono'],
        'direccion' => ['required', 'string', 'max:300'],
        'correo' => [
            'required',
            'email',
            'max:50',
            'unique:pacientes,correo',
            'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
        ],
        'tipo_sangre' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
        'genero' => ['required', 'in:Femenino,Masculino,Otro'],
        'padecimientos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
        'medicamentos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
        'historial_clinico' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
        'alergias' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
        'historial_quirurgico' => ['nullable', 'regex:/^[\pL\s]*$/u', 'max:200'],
    ], [
        // ✅ Aquí van TUS mensajes en español para que NO salgan en inglés
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'nombre.max' => 'El nombre no puede superar los 50 caracteres.',

        'apellidos.required' => 'Los apellidos son obligatorios.',
        'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
        'apellidos.max' => 'Los apellidos no pueden superar los 50 caracteres.',

        'identidad.required' => 'La identidad es obligatoria.',
        'identidad.digits' => 'La identidad debe tener exactamente 13 dígitos.',
        'identidad.regex' => 'El formato de la identidad no es válido.',
        'identidad.unique' => 'Esta identidad ya está registrada.',

        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        'fecha_nacimiento.date' => 'Debe ser una fecha válida.',
        'fecha_nacimiento.before_or_equal' => 'El paciente debe tener al menos 21 años.',
        'fecha_nacimiento.after_or_equal' => 'El paciente no puede tener más de 60 años.',

        'telefono.required' => 'El teléfono es obligatorio.',
        'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
        'telefono.regex' => 'El teléfono debe iniciar con 2, 3, 8 o 9.',
        'telefono.unique' => 'Este teléfono ya está registrado.',

        'direccion.required' => 'La dirección es obligatoria.',
        'direccion.max' => 'La dirección no puede superar los 300 caracteres.',

        'correo.required' => 'El correo electrónico es obligatorio.',
        'correo.email' => 'Debe ser un correo válido.',
        'correo.regex' => 'El formato del correo electrónico no es válido.',
        'correo.unique' => 'Este correo ya está registrado.',
        'correo.max' => 'El correo no puede superar los 50 caracteres.',

        'tipo_sangre.in' => 'El tipo de sangre debe ser uno válido (A+, A-, B+, B-, AB+, AB-, O+, O-).',

        'genero.required' => 'El género es obligatorio.',
        'genero.in' => 'El género debe ser Femenino, Masculino u Otro.',

        'padecimientos.required' => 'Los padecimientos son obligatorios.',
        'padecimientos.regex' => 'Los padecimientos solo pueden contener letras y espacios.',
        'padecimientos.max' => 'Los padecimientos no pueden superar los 200 caracteres.',

        'medicamentos.required' => 'Los medicamentos son obligatorios.',
        'medicamentos.regex' => 'Los medicamentos solo pueden contener letras y espacios.',
        'medicamentos.max' => 'Los medicamentos no pueden superar los 200 caracteres.',

        'historial_clinico.required' => 'El historial clínico es obligatorio.',
        'historial_clinico.regex' => 'El historial clínico solo puede contener letras y espacios.',
        'historial_clinico.max' => 'El historial clínico no puede superar los 200 caracteres.',

        'alergias.required' => 'Las alergias son obligatorias.',
        'alergias.regex' => 'Las alergias solo pueden contener letras y espacios.',
        'alergias.max' => 'Las alergias no pueden superar los 200 caracteres.',

        'historial_quirurgico.regex' => 'El historial quirúrgico solo puede contener letras y espacios.',
        'historial_quirurgico.max' => 'El historial quirúrgico no puede superar los 200 caracteres.',
    ]);

    // ✅ Mantienes tu validación adicional
    if (!$this->validarAnioIdentidad($request->identidad)) {
        return redirect()->back()
            ->withErrors(['identidad' => "El año en la identidad debe estar entre 1930 y $anioActual."])
            ->withInput();
    }

    Paciente::create($request->all());

    return redirect()->route('pacientes.index')->with('success', 'Paciente registrado exitosamente.');
}


    public function index(Request $request)
    {
        $query = $request->input('search', '');
        $pacientesQuery = Paciente::query();

        if ($query) {
            $pacientesQuery->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%$query%")
                  ->orWhere('apellidos', 'like', "%$query%")
                  ->orWhere('identidad', 'like', "%$query%");
            });
            $pacientes = $pacientesQuery->get();
        } else {
            $pacientes = $pacientesQuery->paginate(2);
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('pacientes.partials.tabla', compact('pacientes'))->render(),
                'pagination' => ($pacientes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    ? view('pagination::bootstrap-5', ['paginator' => $pacientes])->render()
                    : '',
                'total' => $pacientes->count(),
                'all' => Paciente::count(),
            ]);
        }

        return view('pacientes.index', compact('pacientes'));
    }

    public function show($id)
    {
        $paciente = Paciente::with(['consultas.recetas', 'diagnostico'])->findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $anioActual = date('Y');

        $request->validate([
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'apellidos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'genero' => ['required', 'in:Femenino,Masculino,Otro'],
            'identidad' => [
                'required',
                'digits:13',
                'regex:/^(0[1-9]|1[0-8])(0[1-9]|1[0-9]|2[0-8])(\d{4})\d{5}$/',
                Rule::unique('pacientes', 'identidad')->ignore($paciente->id)
            ],
            'fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(21)->format('Y-m-d'),
                'after_or_equal:' . Carbon::now()->subYears(60)->format('Y-m-d'),
            ],
            'telefono' => [
                'required',
                'digits:8',
                'regex:/^[2389][0-9]{7}$/',
                Rule::unique('pacientes', 'telefono')->ignore($paciente->id),
            ],
            'direccion' => ['required', 'string', 'max:300'],
            'correo' => [
                'required',
                'email',
                'max:50',
                Rule::unique('pacientes', 'correo')->ignore($paciente->id),
                'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
            ],
            'tipo_sangre' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'padecimientos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'medicamentos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'historial_clinico' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'alergias' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'historial_quirurgico' => ['nullable', 'regex:/^[\pL\s]*$/u', 'max:200'],
        ]);

        if (!$this->validarAnioIdentidad($request->identidad)) {
            return redirect()->back()
                ->withErrors(['identidad' => "El año en la identidad debe estar entre 1930 y $anioActual."])
                ->withInput();
        }

        $paciente->update($request->only([
            'nombre',
            'apellidos',
            'identidad',
            'fecha_nacimiento',
            'telefono',
            'direccion',
            'correo',
            'tipo_sangre',
            'genero',
            'padecimientos',
            'medicamentos',
            'historial_clinico',
            'alergias',
            'historial_quirurgico',
        ]));

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    public function showRecetas($pacienteId)
    {
        $paciente = Paciente::with('recetas')->findOrFail($pacienteId);
        return view('recetas.show', compact('paciente'));
    }
}
