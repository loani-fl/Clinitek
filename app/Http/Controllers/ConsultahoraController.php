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

    $horas = Consulta::where('medico_id', $medico_id)
            ->where('fecha', $fecha)
            ->whereNotNull('hora')  // Excluir valores nulos
            ->pluck('hora');

    return response()->json($horas);
}



}

