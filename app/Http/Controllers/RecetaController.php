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
            'medicamentos.*.detalles' => 'nullable|string|max:500',
        ], [
            'medicamentos.required' => 'Debes agregar al menos un medicamento.',
            'medicamentos.min' => 'Debes agregar al menos un medicamento.',
            'medicamentos.*.nombre.required' => 'El nombre del medicamento es obligatorio.',
            'medicamentos.*.indicacion.required' => 'Las indicaciones del medicamento son obligatorias.',
            'medicamentos.*.dosis.required' => 'La dosis es obligatoria.',
        ]);

        // Buscar la última consulta del paciente
        $consulta = Consulta::where('paciente_id', $pacienteId)->latest('fecha')->first();

        if (!$consulta) {
            return redirect()->back()->withErrors([
                'consulta' => 'Este paciente no tiene ninguna consulta registrada.'
            ]);
        }

        // Crear la receta
        $receta = Receta::create([
            'consulta_id' => $consulta->id,
            'paciente_id' => $pacienteId,
            'detalles' => $request->detalles,
        ]);

        // Guardar medicamentos asociados
        foreach ($request->medicamentos as $med) {
            $medicamento = Medicamento::firstOrCreate([
                'nombre' => trim($med['nombre']),
            ]);

            // Evitar duplicados en la misma receta
            if (!$receta->medicamentos()->where('medicamento_id', $medicamento->id)->exists()) {
                $receta->medicamentos()->attach($medicamento->id, [
                    'indicaciones' => $med['indicacion'],
                    'dosis' => $med['dosis'],
                    'detalles' => $med['detalles'] ?? null,
                ]);
            }
        }

        return redirect()->route('recetas.show', $pacienteId)
            ->with('success', 'Receta creada correctamente.');
    }




    public function show($pacienteId)
    {
        $paciente = Paciente::with(['consultas.recetas'])->findOrFail($pacienteId);

        // Obtener todas las recetas del paciente sin paginación
        $recetas = Receta::whereHas('consulta', function ($query) use ($pacienteId) {
            $query->where('paciente_id', $pacienteId);
        })->with('medicamentos')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('recetas.show', compact('paciente', 'recetas'));
    }

}
