<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Consulta;
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
            'detalles' => 'required|string|max:500',
        ], [
            'detalles.required' => 'Detalles de prescripción es obligatorio.',
            'detalles.max' => 'Detalles no puede tener más de 500 caracteres.',
        ]);

        // Buscar la consulta más reciente del paciente
        $consulta = Consulta::where('paciente_id', $pacienteId)->latest('fecha')->first();

        if (!$consulta) {
            return redirect()->back()->withErrors('Este paciente no tiene ninguna consulta registrada.');
        }

        Receta::create([
            'consulta_id' => $consulta->id,
            'paciente_id' => $pacienteId,
            'detalles' => $request->detalles,
        ]);

        // Redirigir usando el ID correcto de la consulta, no el del paciente
        return redirect()->route('consultas.show', $consulta->id)
            ->with('success', 'Receta creada correctamente.');
    }

    public function show($id)
    {
        $paciente = Paciente::with(['consultas.receta', 'diagnostico'])->findOrFail($id);
        return view('pacientes.show', compact('paciente'));
    }
}
