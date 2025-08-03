<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RayosxOrder;
use Illuminate\Support\Facades\Validator;

class OrdenRayosXController extends Controller
{
    public function create()
    {
        // Supongo que cargas los diagnósticos para el select
        $diagnosticos = \App\Models\Diagnostico::with('paciente', 'consulta.medico')->get();

        return view('rayosx.create', compact('diagnosticos'));
    }

    public function store(Request $request)
    {
        // Validar que haya diagnóstico y al menos 1 examen, max 5 exámenes
        $rules = [
            'diagnostico_id' => 'required|exists:diagnosticos,id',
            'examenes' => 'required|array|min:1|max:5',
        ];

        $messages = [
            'diagnostico_id.required' => 'Debe seleccionar un diagnóstico.',
            'diagnostico_id.exists' => 'El diagnóstico seleccionado no es válido.',
            'examenes.required' => 'Debe seleccionar al menos un examen de Rayos X.',
            'examenes.min' => 'Debe seleccionar al menos un examen de Rayos X.',
            'examenes.max' => 'No puede seleccionar más de 5 exámenes de Rayos X.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Guardar la orden
        $orden = new RayosxOrder();
        $orden->diagnostico_id = $request->diagnostico_id;

        // Guardar exámenes seleccionados en JSON o como mejor convenga
        $examenesSeleccionados = array_keys($request->examenes); // los keys de los exámenes
        $orden->examenes = json_encode($examenesSeleccionados);

        $orden->save();

        return redirect()->route('rayosx.create')->with('success', 'Orden de Rayos X creada correctamente.');
    }
}

