<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;

class ConsultahoraController extends Controller
{
    public function horasOcupadas(Request $request)
    {
        $medico_id = $request->query('medico_id');
        $fecha = $request->query('fecha');

        if (!$medico_id || !$fecha) {
            return response()->json([], 400);
        }

        // Obtener las horas ocupadas (hora en formato 24h HH:mm:ss)
        $horasOcupadas = Consulta::where('medico_id', $medico_id)
            ->where('fecha', $fecha)
            ->pluck('hora')  // Asumiendo que el campo 'hora' está guardado en formato 24h "HH:mm:ss"
            ->toArray();

        return response()->json($horasOcupadas);
    }
}


