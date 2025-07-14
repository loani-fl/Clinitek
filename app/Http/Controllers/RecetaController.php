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

    public function store(Request $request, Consulta $consulta)
    {
        $request->validate([
            'detalles' => 'required|string|max:500',
        ], [
            'detalles.required' => 'Detalles de prescripción es obligatorio.',
            'detalles.max' => 'Detalles no puede tener más de 500 caracteres.',
        ]);

        Receta::create([
            'consulta_id' => $consulta->id,
            'detalles' => $request->detalles,
        ]);

        return redirect()->route('consultas.show', $consulta->id)
            ->with('success', 'Receta creada correctamente.');
    }

    public function show($pacienteId)
    {
        $paciente = Paciente::with(['recetas.medico'])->findOrFail($pacienteId);
        return view('recetas.show', compact('paciente'));
    }
}
