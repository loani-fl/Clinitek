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
            'especialidad' => $request->especialidad,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'fecha_ingreso' => $request->fecha_ingreso,
            'genero' => $request->genero,
            'observaciones' => $request->observaciones,
        ]);

        // ✅ Redirigir a la lista de médicos (NO al formulario)
        return redirect()->route('medicos.index')->with('success', 'Médico registrado exitosamente');
    }

    /**
     * Mostrar lista de médicos
     */

    public function index(Request $request)
    {
        $query = Medico::query();

        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('especialidad', 'like', "%{$buscar}%");
        }

        $medicos = $query->paginate(10);
        $medicos->appends($request->only('buscar'));

        return view('medicos.index', compact('medicos'));
    }


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
        // Validar datos
        $request->validate([
            'nombre' => 'required|regex:/^[\pL\s\-]+$/u',
            'apellidos' => 'required|regex:/^[\pL\s\-]+$/u',
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
        ]);

        // Actualizar el médico
        $medico = Medico::findOrFail($id);
        $medico->update([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
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
    public function destroy($id)
    {
        $medico = Medico::findOrFail($id); // Busca el médico o lanza 404 si no existe
        $medico->delete(); // Elimina el médico

        return redirect()->route('medicos.index')
            ->with('success', 'Médico eliminado correctamente.');
    }

}
