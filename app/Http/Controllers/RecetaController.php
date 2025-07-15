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
            'medicamentos' => 'required|array|min:1', 
            'medicamentos.*.nombre' => 'required|string',
            'medicamentos.*.indicacion' => 'required|string',
        ], [
            'detalles.required' => 'Detalles de prescripción es obligatorio.',
            'detalles.max' => 'Detalles no puede tener más de 500 caracteres.',
            'medicamentos.required' => 'Debes agregar al menos un medicamento.',
            'medicamentos.min' => 'Debes agregar al menos un medicamento.',
            'medicamentos.*.nombre.required' => 'El nombre del medicamento es obligatorio.',
            'medicamentos.*.indicacion.required' => 'Las indicaciones del medicamento son obligatorias.',
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
            $receta->medicamentos()->create([
                'nombre' => $med['nombre'],
                'indicacion' => $med['indicacion']
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