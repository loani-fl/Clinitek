<?php

namespace App\Http\Controllers;

use App\Models\Diagnostico;
use App\Models\Paciente;
use App\Models\Consulta;
use Illuminate\Http\Request;

class DiagnosticoController extends Controller
{
    public function create($pacienteId, $consultaId)
    {
        $paciente = Paciente::findOrFail($pacienteId);
        $consulta = Consulta::with('medico')->findOrFail($consultaId);

        return view('diagnosticos.create', compact('paciente', 'consulta'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'consulta_id' => 'required|exists:consultas,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:400',
            'tratamiento' => 'required|string|max:400',
            'observaciones' => 'nullable|string|max:400',
        ], [
            'paciente_id.required' => 'El paciente es obligatorio.',
            'paciente_id.exists' => 'El paciente seleccionado no es válido.',
            'consulta_id.required' => 'La consulta es obligatoria.',
            'consulta_id.exists' => 'La consulta seleccionada no es válida.',
            'titulo.required' => 'El resumen es obligatorio.',
            'titulo.string' => 'El resumen debe ser un texto válido.',
            'titulo.max' => 'El resumen no puede tener más de 255 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'descripcion.max' => 'La descripción no puede tener más de 400 caracteres.',
            'tratamiento.required' => 'El tratamiento es obligatorio.',
            'tratamiento.string' => 'El tratamiento debe ser un texto válido.',
            'tratamiento.max' => 'El tratamiento no puede tener más de 400 caracteres.',
            'observaciones.string' => 'Las observaciones deben ser un texto válido.',
            'observaciones.max' => 'Las observaciones no pueden tener más de 400 caracteres.',
        ]);

        $diagnostico = Diagnostico::create($validatedData);

        $consulta = Consulta::find($request->consulta_id);
        if ($consulta) {
            $consulta->estado = 'realizada';
            $consulta->save();
        }

        return redirect()->route('diagnosticos.show', $diagnostico->id)
            ->with('success', 'Diagnóstico creado correctamente.');
    }

    public function show(Diagnostico $diagnostico)
    {
        $consultaId = $diagnostico->consulta_id; // obtienes el id directo
        $diagnostico->load('paciente');
        return view('diagnosticos.show', compact('diagnostico', 'consultaId'));
    }


    public function edit(Diagnostico $diagnostico)
    {
        $diagnostico->load('paciente', 'consulta.medico');
        return view('diagnosticos.edit', compact('diagnostico'));
    }

    public function update(Request $request, Diagnostico $diagnostico)

    {
        $validatedData = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s,.;]+$/'
            ],
            'descripcion' => [
                'required',
                'string',
                'max:400',
                'regex:/^[a-zA-Z0-9\s,.;]+$/'
            ],
            'tratamiento' => [
                'required',
                'string',
                'max:400',
                'regex:/^[a-zA-Z0-9\s,.;]+$/'
            ],
            'observaciones' => [
                'nullable',
                'string',
                'max:400',
                'regex:/^[a-zA-Z0-9\s,.;]*$/'
            ],
        ], [
            'titulo.required' => 'El resumen es obligatorio.',
            'titulo.string' => 'El resumen debe ser un texto válido.',
            'titulo.max' => 'El resumen no puede tener más de 255 caracteres.',
            'titulo.regex' => 'El resumen solo puede contener letras, números, espacios, comas, puntos y punto y coma.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'descripcion.max' => 'La descripción no puede tener más de 400 caracteres.',
            'descripcion.regex' => 'La descripción solo puede contener letras, números, espacios, comas, puntos y punto y coma.',

            'tratamiento.required' => 'El tratamiento es obligatorio.',
            'tratamiento.string' => 'El tratamiento debe ser un texto válido.',
            'tratamiento.max' => 'El tratamiento no puede tener más de 400 caracteres.',
            'tratamiento.regex' => 'El tratamiento solo puede contener letras, números, espacios, comas, puntos y punto y coma.',

            'observaciones.string' => 'Las observaciones deben ser un texto válido.',
            'observaciones.max' => 'Las observaciones no pueden tener más de 400 caracteres.',
            'observaciones.regex' => 'Las observaciones solo pueden contener letras, números, espacios, comas, puntos y punto y coma.',
        ]);

        $request->session()->put('diagnostico_original', [
            'titulo' => $diagnostico->resumen,
            'descripcion' => $diagnostico->descripcion,
            'tratamiento' => $diagnostico->tratamiento,
            'observaciones' => $diagnostico->observaciones,
        ]);

        $diagnostico->update($validatedData);


        return redirect()->route('diagnosticos.show', $diagnostico->id)
            ->with('success', 'Diagnóstico actualizado correctamente.');
    }
    public function index()
{
    $diagnosticos = Diagnostico::with('paciente', 'consulta.medico')->get();
    return view('diagnosticos.index', compact('diagnosticos'));
}

}
