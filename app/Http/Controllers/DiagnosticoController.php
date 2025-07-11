<?php

namespace App\Http\Controllers;
use App\Models\Diagnostico;
use App\Models\Paciente;
use App\Models\Consulta;
use Illuminate\Http\Request;

class DiagnosticoController extends Controller
{
    // Mostrar formulario para crear diagnóstico
    public function create($pacienteId, $consultaId)
    {
        $paciente = Paciente::findOrFail($pacienteId);
        $consulta = Consulta::with('medico')->findOrFail($consultaId);


        if ($consulta->estado !== 'realizada') {
            return redirect()->route('consultas.index')
                ->with('error', 'No se puede crear diagnóstico porque la consulta no está realizada.');
        }

        return view('diagnosticos.create', compact('paciente', 'consulta'));
    }
    public function store(Request $request)
    {
        // Validar datos
        $validatedData = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'consulta_id' => 'required|exists:consultas,id',
            'resumen' => 'required|string|max:255',
            'descripcion' => 'required|string|max:400',
            'tratamiento' => 'required|string|max:400',
            'observaciones' => 'nullable|string|max:400',  // nullable si no es obligatorio
        ], [
            'paciente_id.required' => 'El paciente es obligatorio.',
            'paciente_id.exists' => 'El paciente seleccionado no es válido.',

            'consulta_id.required' => 'La consulta es obligatoria.',
            'consulta_id.exists' => 'La consulta seleccionada no es válida.',


            'resumen.required' => 'El resumen es obligatorio.',
            'resumen.string' => 'El resumen debe ser un texto válido.',
            'resumen.max' => 'El resumen no puede tener más de 255 caracteres.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'descripcion.max' => 'La descripción no puede tener más de 400 caracteres.',

            'tratamiento.required' => 'El tratamiento es obligatorio.',
            'tratamiento.string' => 'El tratamiento debe ser un texto válido.',
            'tratamiento.max' => 'El tratamiento no puede tener más de 400 caracteres.',

            'observaciones.string' => 'Las observaciones deben ser un texto válido.',
            'observaciones.max' => 'Las observaciones no pueden tener más de 400 caracteres.',
        ]);


        // Crear diagnóstico
        $diagnostico = Diagnostico::create($validatedData);

        // Redirigir a la vista show del diagnóstico creado
        return redirect()->route('diagnosticos.show', $diagnostico->id)
            ->with('success', 'Diagnóstico creado correctamente.');

    }
    public function show(Diagnostico $diagnostico)
    {
        $diagnostico->load('paciente');
        return view('diagnosticos.show', compact('diagnostico'));
    }

    public function edit(Diagnostico $diagnostico)
    {
        // Cargar relaciones necesarias para mostrar en el formulario (paciente y consulta)
        $diagnostico->load('paciente', 'consulta.medico');

        return view('diagnosticos.edit', compact('diagnostico'));
    }

    public function update(Request $request, Diagnostico $diagnostico)
    {
        $validatedData = $request->validate([
            'resumen' => 'required|string|max:255',
            'descripcion' => 'required|string|max:400',
            'tratamiento' => 'required|string|max:400',
            'observaciones' => 'nullable|string|max:400',
        ], [
            'resumen.required' => 'El resumen es obligatorio.',
            'resumen.string' => 'El resumen debe ser un texto válido.',
            'resumen.max' => 'El resumen no puede tener más de 255 caracteres.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'descripcion.max' => 'La descripción no puede tener más de 400 caracteres.',

            'tratamiento.required' => 'El tratamiento es obligatorio.',
            'tratamiento.string' => 'El tratamiento debe ser un texto válido.',
            'tratamiento.max' => 'El tratamiento no puede tener más de 400 caracteres.',

            'observaciones.string' => 'Las observaciones deben ser un texto válido.',
            'observaciones.max' => 'Las observaciones no pueden tener más de 400 caracteres.',
        ]);

        $diagnostico->update($validatedData);

        return redirect()->route('diagnosticos.show', $diagnostico->id)
            ->with('success', 'Diagnóstico actualizado correctamente.');
    }

}
