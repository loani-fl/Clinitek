<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medico;

class MedicoController extends Controller
{
    /**
     * Mostrar formulario para crear nuevo médico
     */
    public function create()
    {
        return view('medicos.create');
    }

    /**
     * Guardar nuevo médico en base de datos
     */
    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u',
            'apellidos' => 'required|regex:/^[\pL\s\-]+$/u',
            'numero_identidad' => 'required|digits:13|numeric|unique:medicos,numero_identidad',
            'especialidad' => 'required|string',
            'telefono' => 'required|numeric|unique:medicos,telefono',
            'correo' => 'required|email|unique:medicos,correo',
            'fecha_nacimiento' => 'required|date',
            'fecha_ingreso' => 'required|date',
            'genero' => 'required',
            'observaciones' => 'nullable|string',
        ], [
            'telefono.unique' => 'Este número de teléfono ya está registrado por otro médico.',
            'correo.unique' => 'Este correo electrónico ya está registrado por otro médico.',
            'numero_identidad.required' => 'Por favor ingrese el número de identidad.',
            'numero_identidad.digits' => 'El número de identidad debe contener exactamente 13 números.',
            'numero_identidad.numeric' => 'El número de identidad debe contener solo números.',
            'numero_identidad.unique' => 'Este número de identidad ya está registrado por otro médico.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras, espacios y guiones.',
            'especialidad.required' => 'La especialidad es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.numeric' => 'El teléfono debe contener solo números.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe tener un formato válido.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'genero.required' => 'El género es obligatorio.',
        ]);

        // Crear y guardar el médico
        Medico::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'numero_identidad' => $request->numero_identidad,
            'especialidad' => $request->especialidad,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'genero' => $request->genero,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('medicos.index')->with('success', 'Médico registrado exitosamente');
    }

    /**
     * Mostrar lista de médicos
     */
    public function index()
    {
        $medicos = Medico::all();
        return view('medicos.index', compact('medicos'));
    }

    /**
     * Mostrar detalle de un médico
     */
    public function show($id)
    {
        $medico = Medico::findOrFail($id);
        return view('medicos.show', compact('medico'));
    }

    /**
     * Mostrar formulario para editar médico existente
     */
    public function edit($id)
    {
        $medico = Medico::findOrFail($id);
        return view('medicos.edit', compact('medico'));
    }

    /**
     * Actualizar médico existente
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u',
            'apellidos' => 'required|regex:/^[\pL\s\-]+$/u',
            'numero_identidad' => 'required|digits:13|numeric|unique:medicos,numero_identidad,' . $id,
            'especialidad' => 'required|string',
            'telefono' => 'required|numeric|unique:medicos,telefono,' . $id,
            'correo' => 'required|email|unique:medicos,correo,' . $id,
            'fecha_nacimiento' => 'required|date',
            'fecha_ingreso' => 'required|date',
            'genero' => 'required',
            'observaciones' => 'nullable|string',
        ], [
            'telefono.unique' => 'Este número de teléfono ya está registrado por otro médico.',
            'correo.unique' => 'Este correo electrónico ya está registrado por otro médico.',
            'numero_identidad.required' => 'Por favor ingrese el número de identidad.',
            'numero_identidad.digits' => 'El número de identidad debe contener exactamente 13 números.',
            'numero_identidad.numeric' => 'El número de identidad debe contener solo números.',
            'numero_identidad.unique' => 'Este número de identidad ya está registrado por otro médico.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios y guiones.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras, espacios y guiones.',
            'especialidad.required' => 'La especialidad es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.numeric' => 'El teléfono debe contener solo números.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe tener un formato válido.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'genero.required' => 'El género es obligatorio.',
        ]);

        $medico = Medico::findOrFail($id);
        $medico->update([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'numero_identidad' => $request->numero_identidad,
            'especialidad' => $request->especialidad,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'genero' => $request->genero,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('medicos.index')->with('success', 'Médico actualizado exitosamente');
    }
}
