<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Examen;

class ExamenController extends Controller
{
    public function create($pacienteId, $consultaId)
    {
        $paciente = Paciente::findOrFail($pacienteId);
        $consulta = Consulta::with('medico')->findOrFail($consultaId);

        return view('examenes.create', compact('paciente', 'consulta'));
    }

    public function store(Request $request, $pacienteId, $consultaId)
    {
        $consulta = Consulta::with('paciente', 'medico', 'examens')->findOrFail($consultaId);

        // Validar que la consulta esté realizada
        if ($consulta->estado !== 'realizada') {
            return back()->withErrors([
                'estado' => 'Debe finalizar la consulta antes de ordenar exámenes.'
            ]);
        }

        // Validar que se haya seleccionado al menos un examen
        $request->validate([
            'examenes' => 'required|array|min:1|max:10'
        ], [
            'examenes.required' => 'Debe seleccionar al menos un examen.',
            'examenes.array' => 'Debe seleccionar al menos un examen.',
            'examenes.min' => 'Debe seleccionar al menos un examen.'
        ]);

        // Aquí pones este código:
        $examenesSeleccionados = $request->input('examenes', []);

        foreach ($examenesSeleccionados as $examen) {
            Examen::create([
                'paciente_id' => $pacienteId,
                'consulta_id' => $consultaId,
                'nombre' => $examen,
            ]);
        }

        return view('examenes.show', [
            'examenes' => collect($examenesSeleccionados)->map(function($nombre) {
                return (object) ['nombre' => $nombre];
            }),
            'consulta_id' => $consultaId,
            'paciente_id' => $pacienteId,
        ]);
    }

    public function show($pacienteId, $consultaId)
    {
        $consulta = Consulta::with('paciente', 'medico')->findOrFail($consultaId);
        $paciente = $consulta->paciente;
        $medico = $consulta->medico;
        $examenes = Examen::where('paciente_id', $pacienteId)
                          ->where('consulta_id', $consultaId)
                          ->get();

        $edad = \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age;

        return view('examenes.show', compact('consulta', 'paciente', 'medico', 'examenes', 'edad'));
    }

    // Helper para lista de exámenes disponibles
    private function getExamenesDisponibles()
    {
        return [
            'Hemograma Completo', 'Glucosa', 'Urea', 'Creatinina',
            'Ácido Úrico', 'Tolerancia a la Glucosa', 'TSH',
            'Prolactina', 'Testosterona', 'Estradiol', 'CA125',
            'CA19-9', 'Beta HCG', 'Cultivo de Orina', 'Examen de Orina'
        ];
    }
}
