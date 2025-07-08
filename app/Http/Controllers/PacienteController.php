<?php

namespace App\Http\Controllers;

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
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no puede exceder 50 caracteres.',

            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
            'apellidos.max' => 'Los apellidos no pueden exceder 50 caracteres.',

            'identidad.required' => 'La identidad es obligatoria.',
            'identidad.digits' => 'La identidad debe contener solo números.',
            'identidad.regex' => 'La identidad debe comenzar con un código válido (01-18), seguido de (01-28), seguido de un año de 4 dígitos, y respetar la estructura.',
            'identidad.unique' => 'Esta identidad ya está registrada.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha debe ser válida.',
            'fecha_nacimiento.before_or_equal' => 'La persona debe tener al menos 21 años.',
            'fecha_nacimiento.after_or_equal' => 'La persona no puede tener más de 60 años.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener 8 dígitos.',
            'telefono.regex' => 'El teléfono debe comenzar con 2, 3, 8 o 9.',
            'telefono.unique' => 'Este número de teléfono ya está registrado.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no puede exceder 300 caracteres.',

            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo debe contener un "@" y un punto "." en el dominio.',
            'correo.max' => 'El correo no puede exceder 50 caracteres.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'correo.regex' => 'El correo debe contener un "@" y un punto "." en el dominio.',

            'tipo_sangre.in' => 'Seleccione un tipo de sangre válido.',

            'genero.required' => 'El género es obligatorio.',
            'genero.in' => 'Debe seleccionar una opción válida de género.',

            'padecimientos.required' => 'Los padecimientos son obligatorios.',
            'padecimientos.regex' => 'Solo se permiten letras y espacios.',
            'padecimientos.max' => 'No puede exceder 200 caracteres.',

            'medicamentos.required' => 'Los medicamentos son obligatorios.',
            'medicamentos.regex' => 'Solo se permiten letras y espacios.',
            'medicamentos.max' => 'No puede exceder 200 caracteres.',

            'historial_clinico.required' => 'El historial clínico es obligatorio.',
            'historial_clinico.regex' => 'Solo se permiten letras y espacios.',
            'historial_clinico.max' => 'No puede exceder 200 caracteres.',

            'alergias.required' => 'Las alergias son obligatorias.',
            'alergias.regex' => 'Solo se permiten letras y espacios.',
            'alergias.max' => 'No puede exceder 200 caracteres.',

            'historial_quirurgico.regex' => 'Solo se permiten letras y espacios.',
            'historial_quirurgico.max' => 'No puede exceder 200 caracteres.',
        ]);

        $anioNacimiento = (int)substr($request->identidad, 4, 4);
        if ($anioNacimiento < 1930 || $anioNacimiento > (int)$anioActual) {
            return redirect()->back()
                ->withErrors(['identidad' => "El año en la identidad debe estar entre 1930 y $anioActual."])
                ->withInput();
        }

        Paciente::create($request->all());

        return redirect()->route('pacientes.index')->with('success', 'Paciente registrado exitosamente.');
    }

    public function index()
    {
        $pacientes = Paciente::paginate(6);
        return view('pacientes.index', compact('pacientes'));
    }

    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
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
            'genero' => ['nullable', 'in:Femenino,Masculino,Otro'],
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
                'nullable',
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
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no puede exceder 50 caracteres.',

            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
            'apellidos.max' => 'Los apellidos no pueden exceder 50 caracteres.',

            'identidad.required' => 'La identidad es obligatoria.',
            'identidad.digits' => 'La identidad debe contener solo números.',
            'identidad.regex' => 'La identidad debe comenzar con un código válido (01-18), seguido de (01-28), seguido de un año de 4 dígitos, y respetar la estructura.',
            'identidad.unique' => 'Esta identidad ya está registrada para otro paciente.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha debe ser válida.',
            'fecha_nacimiento.before_or_equal' => 'La persona debe tener al menos 21 años.',
            'fecha_nacimiento.after_or_equal' => 'La persona no puede tener más de 60 años.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener 8 dígitos.',
            'telefono.regex' => 'El teléfono debe comenzar con 2, 3, 8 o 9.',
            'telefono.unique' => 'Este número de teléfono ya está registrado.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no puede exceder 300 caracteres.',

            'correo.email' => 'Debe ingresar un correo válido.',
            'correo.max' => 'El correo no puede exceder 50 caracteres.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'correo.regex' => 'El correo debe contener un "@" y un punto "." en el dominio.',

            'tipo_sangre.in' => 'Seleccione un tipo de sangre válido.',

            'padecimientos.required' => 'Los padecimientos son obligatorios.',
            'padecimientos.regex' => 'Solo se permiten letras y espacios.',
            'padecimientos.max' => 'No puede exceder 200 caracteres.',

            'medicamentos.required' => 'Los medicamentos son obligatorios.',
            'medicamentos.regex' => 'Solo se permiten letras y espacios.',
            'medicamentos.max' => 'No puede exceder 200 caracteres.',

            'historial_clinico.required' => 'El historial clínico es obligatorio.',
            'historial_clinico.regex' => 'Solo se permiten letras y espacios.',
            'historial_clinico.max' => 'No puede exceder 200 caracteres.',

            'alergias.required' => 'Las alergias son obligatorias.',
            'alergias.regex' => 'Solo se permiten letras y espacios.',
            'alergias.max' => 'No puede exceder 200 caracteres.',

            'historial_quirurgico.regex' => 'Solo se permiten letras y espacios.',
            'historial_quirurgico.max' => 'No puede exceder 200 caracteres.',
        ]);

        $anioNacimiento = (int)substr($request->identidad, 4, 4);
        if ($anioNacimiento < 1930 || $anioNacimiento > (int)$anioActual) {
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
}
