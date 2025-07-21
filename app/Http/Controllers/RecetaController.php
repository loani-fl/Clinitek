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
            'medicamentos.*.nombre' => ['required', 'string', 'max:55', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'medicamentos.*.indicacion' => 'required|string|max:255',
            'medicamentos.*.dosis' => ['required', 'string', 'max:25', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'medicamentos.*.detalles' => ['nullable', 'string', 'max:500', 'regex:/^[a-zA-Z0-9\s]+$/'],
        ], [
            'medicamentos.required' => 'Debes agregar al menos un medicamento.',
            'medicamentos.min' => 'Debes agregar al menos un medicamento.',
            'medicamentos.*.nombre.required' => 'El nombre del medicamento es obligatorio.',
            'medicamentos.*.nombre.max' => 'El nombre del medicamento no debe superar los 55 caracteres.',
            'medicamentos.*.nombre.regex' => 'Solo se admiten letras y números en el nombre del medicamento.',
            'medicamentos.*.indicacion.required' => 'Las indicaciones del medicamento son obligatorias.',
            'medicamentos.*.indicacion.max' => 'Las indicaciones no deben superar los 255 caracteres.',
            'medicamentos.*.dosis.required' => 'La dosis es obligatoria.',
            'medicamentos.*.dosis.max' => 'La dosis no debe superar los 25 caracteres.',
            'medicamentos.*.dosis.regex' => 'Solo se admiten letras y números en la dosis.',
            'medicamentos.*.detalles.max' => 'Los detalles no deben superar los 500 caracteres.',
            'medicamentos.*.detalles.regex' => 'Solo se admiten letras y números en los detalles.',
        ]);
    
        $receta = Receta::create([
            'paciente_id' => $pacienteId,
            'detalles' => $request->detalles,
        ]);
    
        foreach ($request->medicamentos as $med) {
            $medicamento = Medicamento::firstOrCreate([
                'nombre' => trim($med['nombre']),
            ]);
    
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
