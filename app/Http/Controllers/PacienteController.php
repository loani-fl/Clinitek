<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'apellidos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'identidad' => ['required', 'regex:/^(0[1-9]|1[0-8])[0-9]{11}$/', 'size:13'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:today'],
            'telefono' => ['required', 'digits:8', 'regex:/^[2389][0-9]{7}$/'],
            'direccion' => ['required', 'string', 'max:300'],
            'correo' => ['required', 'email', 'max:50', 'unique:pacientes,correo'],
            'tipo_sangre' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'genero' => ['required', 'in:Femenino,Masculino,Otro'],
            'padecimientos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'medicamentos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'historial_clinico' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'alergias' => ['required', 'regex:/^[\pL\s]+$/u', 'max:200'],
            'historial_quirurgico' => ['nullable', 'regex:/^[\pL\s]*$/u', 'max:200'],
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Debe ingresar un correo válido que contenga @ y un dominio (ejemplo.com).',
            'correo.max' => 'El correo no puede exceder 50 caracteres.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',

            'genero.required' => 'El género es obligatorio.',
            'genero.in' => 'Debe seleccionar una opción válida de género.',

            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.max' => 'El nombre no puede exceder 50 caracteres.',

            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios.',
            'apellidos.max' => 'Los apellidos no pueden exceder 50 caracteres.',

            'identidad.required' => 'La identidad es obligatoria.',
            'identidad.regex' => 'La identidad debe comenzar con un valor válido (01-18) y contener 13 dígitos.',
            'identidad.size' => 'La identidad debe tener exactamente 13 caracteres.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha debe ser válida.',
            'fecha_nacimiento.before_or_equal' => 'La fecha no puede ser futura.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener 8 dígitos.',
            'telefono.regex' => 'El teléfono debe comenzar con 2, 3, 8 o 9.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no puede exceder 300 caracteres.',

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

        Paciente::create($request->all());
        

        return redirect()->route('pacientes.index')->with('success', 'Paciente registrado exitosamente.');
    }

public function index() {
    $pacientes = Paciente::paginate(10);
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
        $request->validate([
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'apellidos' => ['required', 'regex:/^[\pL\s]+$/u', 'max:50'],
            'genero' => ['nullable', 'in:Masculino,Femenino,Otro'],
            'identidad' => ['required', 'digits:13', 'regex:/^(0[1-9]|1[0-8])[0-9]{11}$/'],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:today'],
            'telefono' => [
                'required',
                'digits:8',
                'regex:/^[0-9]{8}$/',
                \Illuminate\Validation\Rule::unique('pacientes', 'telefono')->ignore($paciente->id),
            ],
            'direccion' => ['required', 'string', 'max:300'],
            'correo' => [
                'nullable',
                'email',
                'max:50',
                \Illuminate\Validation\Rule::unique('pacientes', 'correo')->ignore($paciente->id),
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
            'identidad.digits' => 'La identidad debe tener exactamente 13 dígitos.',
            'identidad.regex' => 'La identidad debe comenzar entre 01 y 18 y contener solo números.',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha debe ser válida.',
            'fecha_nacimiento.before_or_equal' => 'La fecha no puede ser futura.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.digits' => 'El teléfono debe tener 8 dígitos.',
            'telefono.regex' => 'El teléfono debe comenzar con 2, 3, 8 o 9.',
            'telefono.unique'   => 'Este número de teléfono ya existe.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.max' => 'La dirección no puede exceder 300 caracteres.',

            'correo.email' => 'Debe ingresar un correo válido.',
            'correo.max' => 'El correo no puede exceder 50 caracteres.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',

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
