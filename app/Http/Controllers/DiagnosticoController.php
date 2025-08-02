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
            'titulo' => [
                'required',
                'string',
                'max:60',
                'regex:/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚüÜ]*$/'
            ],
            'descripcion' => [
                'required',
                'string',
                'max:400',
                'regex:/^[a-zA-Z0-9\s%°#áéíóúÁÉÍÓÚñÑüÜ]*$/'
            ],
            'tratamiento' => [
                'required',
                'string',
                'max:400',
                'regex:/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚüÜ]*$/'
            ],
            'observaciones' => [
                'nullable',
                'string',
                'max:400',
                'regex:/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚüÜ]*$/'
            ],
        ], [
            'titulo.required' => 'El resumen es obligatorio.',
            'titulo.string' => 'El resumen debe ser un texto válido.',
            'titulo.regex' => 'El título solo puede contener letras, números, espacios, comas, puntos, punto y coma, dos puntos, guiones y letras con tilde o ñ.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'descripcion.regex' => 'La descripción solo puede contener letras, números, espacios, comas, puntos, punto y coma, dos puntos, guiones y letras con tilde o ñ.',

            'tratamiento.required' => 'El tratamiento es obligatorio.',
            'tratamiento.string' => 'El tratamiento debe ser un texto válido.',
            'tratamiento.regex' => 'El tratamiento solo puede contener letras, números, espacios, comas, puntos, punto y coma, dos puntos, guiones y letras con tilde o ñ.',

            'observaciones.string' => 'Las observaciones deben ser un texto válido.',
            'observaciones.regex' => 'Las observaciones solo pueden contener letras, números, espacios, comas, puntos, punto y coma, dos puntos, guiones y letras con tilde o ñ.',
        ]);

        // Agregamos el consulta_id al array validado para que se guarde
        $validatedData['consulta_id'] = $request->consulta_id;
        $validatedData['paciente_id'] = $request->paciente_id;

        $diagnostico = Diagnostico::create($validatedData);

        $consulta = Consulta::find($request->consulta_id);
        if ($consulta) {
            $consulta->estado = 'realizada';
            $consulta->save();
        }

        return redirect()->route('consultas.show', $diagnostico->id)
            ->with('success', 'Diagnóstico creado correctamente.');
    }


    public function show(Diagnostico $diagnostico)
    {
        $consultaId = $diagnostico->consulta_id;

        // Cargar relaciones necesarias
        $diagnostico->load('paciente', 'consulta');

        // Obtener el parámetro 'origen' desde la URL, por defecto 'consultas.index'
        $origen = request()->query('origen', 'consultas.index');

        // Si el origen es el expediente del paciente, también espera el ID del paciente para el link
        $pacienteId = request()->query('paciente_id', null);

        return view('diagnosticos.show', compact('diagnostico', 'consultaId', 'origen', 'pacienteId'));
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
            'max:60',
            'regex:/^[a-zA-Z0-9\s,.;:ñÑáéíóúÁÉÍÓÚüÜ-]*$/'
        ],
        'descripcion' => [
        'required',
        'string',
        'max:400',
        'regex:/^[a-zA-Z0-9\s%°#áéíóúÁÉÍÓÚñÑüÜ]*$/'
        ],
        'tratamiento' => [
            'required',
            'string',
            'max:400',
            'regex:/^[a-zA-Z0-9\s,.;:ñÑáéíóúÁÉÍÓÚüÜ-]*$/'
        ],
        'observaciones' => [
            'nullable',
            'string',
            'max:400',
            'regex:/^[a-zA-Z0-9\s,.;:ñÑáéíóúÁÉÍÓÚüÜ-]*$/'
        ],
    ], [
        'titulo.required' => 'El resumen es obligatorio.',
        'titulo.string' => 'El resumen debe ser un texto válido.',
        'titulo.regex' => 'El título solo puede contener letras, números, espacios, comas, puntos, punto y coma, dos puntos, guiones y letras con tilde o ñ.',

        'descripcion.required' => 'La descripción es obligatoria.',
        'descripcion.string' => 'La descripción debe ser un texto válido.',
        'descripcion.regex' => 'La descripción solo puede contener letras, números, espacios, comas, puntos, punto y coma, dos puntos, guiones y letras con tilde o ñ.',

        'tratamiento.required' => 'El tratamiento es obligatorio.',
        'tratamiento.string' => 'El tratamiento debe ser un texto válido.',
        'tratamiento.regex' => 'El tratamiento solo puede contener letras, números, espacios, comas, puntos, punto y coma, dos puntos, guiones y letras con tilde o ñ.',

        'observaciones.string' => 'Las observaciones deben ser un texto válido.',
        'observaciones.regex' => 'Las observaciones solo pueden contener letras, números, espacios, comas, puntos, punto y coma, dos puntos, guiones y letras con tilde o ñ.',
    ]);

    $request->session()->put('diagnostico_original', [
        'titulo' => $diagnostico->titulo,
        'descripcion' => $diagnostico->descripcion,
        'tratamiento' => $diagnostico->tratamiento,
        'observaciones' => $diagnostico->observaciones,
    ]);

    $diagnostico->update($validatedData);

    return redirect()->route('diagnosticos.show', $diagnostico->id)
        ->with('success', 'Diagnóstico actualizado correctamente.');
}

}
