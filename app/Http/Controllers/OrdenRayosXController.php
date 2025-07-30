<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\RayosxOrder;
use Illuminate\Http\Request;

class OrdenRayosXController extends Controller
{
public function create()
{
    $diagnosticos = Diagnostico::with(['paciente', 'consulta' => function($query) {
        // Carga solo la consulta con estado 'realizada'
        $query->where('estado', 'realizada');
    }])
    ->whereHas('consulta', function ($query) {
        // Solo diagnósticos que tienen consulta realizada
        $query->where('estado', 'realizada');
    })
    ->get();

    return view('rayosx.create', compact('diagnosticos'));
}




    public function store(Request $request)
    {
        $request->validate([
            'diagnostico_id' => 'required|exists:diagnosticos,id|unique:rayosx_orders,diagnostico_id',
            'fecha' => 'required|date',
            'datos_clinicos' => 'nullable|string',
        ]);

        RayosxOrder::create([
            'diagnostico_id' => $request->diagnostico_id,
            'fecha' => $request->fecha,
            'datos_clinicos' => $request->datos_clinicos,
        ]);

        return redirect()->route('rayosx.create')->with('success', 'Orden de Rayos X creada correctamente.');
    }
}
