<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Consulta;
use App\Models\Medicamento;
use App\Models\Paciente;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    public function create(Consulta $consulta)
    {
        $consulta->load(['medico', 'paciente']);
        return view('recetas.create', compact('consulta'));
    }


    
    public function store(Request $request, $pacienteId)
{
    $request->validate([
       'detalles' => 'nullable|string|max:500',
        'medicamentos' => 'required|array|min:1',
        'medicamentos.*.nombre' => 'required|string',
        'medicamentos.*.indicacion' => 'required|string',
        'medicamentos.*.dosis' => 'required|string',
        'medicamentos.*.detalles' => 'required|string|max:500',
    

    ], [
        'detalles.required' => 'Detalles de prescripción es obligatorio.',
        'detalles.max' => 'Detalles no puede tener más de 500 caracteres.',
        'medicamentos.required' => 'Debes agregar al menos un medicamento.',
        'medicamentos.min' => 'Debes agregar al menos un medicamento.',
        'medicamentos.*.nombre.required' => 'El nombre del medicamento es obligatorio.',
        'medicamentos.*.indicacion.required' => 'Las indicaciones del medicamento son obligatorias.',
        'medicamentos.*.dosis.required' => 'La dosis es obligatoria.',
        'medicamentos.*.detalles.required' => 'Los detalles de prescripción del medicamento son obligatorios.',
    ]);

    $consulta = Consulta::where('paciente_id', $pacienteId)->latest('fecha')->first();

    if (!$consulta) {
        return redirect()->back()->withErrors([
            'consulta' => 'Este paciente no tiene ninguna consulta registrada.'
        ]);
    }

    $receta = Receta::create([
        'consulta_id' => $consulta->id,
        'paciente_id' => $pacienteId,
        'detalles' => $request->detalles,
    ]);

    foreach ($request->medicamentos as $med) {
        $medicamento = Medicamento::firstOrCreate([
            'nombre' => trim($med['nombre']),
        ]);

        $yaExiste = $receta->medicamentos()->where('medicamento_id', $medicamento->id)->exists();
        if ($yaExiste) {
            continue;
        }

        $receta->medicamentos()->attach($medicamento->id, [
            'indicaciones' => $med['indicacion'],
            'dosis' => $med['dosis'],
            'detalles' => $med['detalles'],
        ]);
    }

    return redirect()->route('consultas.show', $consulta->id)
        ->with('success', 'Receta creada correctamente.');
}

    

    public function show($id)
    {
        $paciente = Paciente::with(['consultas.receta', 'diagnostico'])->findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    }
}