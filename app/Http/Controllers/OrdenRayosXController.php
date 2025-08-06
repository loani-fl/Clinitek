<?php

namespace App\Http\Controllers;

use App\Models\RayosxOrder;
use App\Models\RayosxOrderExamen;
use Illuminate\Http\Request;
use App\Models\Diagnostico;

class OrdenRayosXController extends Controller
{
   public function create()
{
    // Obtener los IDs de diagnósticos que ya tienen ordenes de rayos X
    $diagnosticosConOrdenIds = RayosxOrder::pluck('diagnostico_id')->toArray();

    // Obtener diagnósticos con paciente y consulta realizada, que no tengan orden de rayos x
    $diagnosticos = Diagnostico::with(['paciente', 'consulta' => function($query) {
        $query->where('estado', 'realizada');
    }])
    ->whereNotIn('id', $diagnosticosConOrdenIds)
    ->get();

    return view('rayosX.create', compact('diagnosticos'));
}


    public function store(Request $request)
    {
        $request->validate([
    'diagnostico_id' => 'required|exists:diagnosticos,id',
    'examenes' => 'required|array|min:1|max:5',
], [
    'diagnostico_id.required' => 'Debe seleccionar un diagnóstico antes de guardar.',
    'diagnostico_id.exists' => 'El diagnóstico seleccionado no es válido.',
    'examenes.required' => 'Debe seleccionar al menos un examen de Rayos X.',
    'examenes.min' => 'Debe seleccionar al menos un examen de Rayos X.',
    'examenes.max' => 'No puede seleccionar más de 5 exámenes de Rayos X.',
]);


        // Evitar duplicados por diagnóstico
        if (RayosxOrder::where('diagnostico_id', $request->diagnostico_id)->exists()) {
            return back()->with('error', 'Ya existe una orden de Rayos X para este diagnóstico.');
        }

        $orden = RayosxOrder::create([
            'diagnostico_id' => $request->diagnostico_id,
            'fecha' => now(),
             'estado' => 'Pendiente', // 👈 Siempre pendiente al crear
        ]);

        foreach (array_keys($request->examenes) as $examenKey) {
            $orden->examenes()->create(['examen' => $examenKey]);
        }

        return redirect()->route('rayosx.show', $orden->id)->with('success', 'Orden creada correctamente.');
    }

    public function index()
    {
        $ordenes = RayosxOrder::with(['diagnostico.paciente'])->latest()->paginate(10);
        return view('rayosX.index', compact('ordenes'));
    }
   public function show($id)
    {
        $orden = RayosxOrder::with(['examenes', 'diagnostico.paciente'])->findOrFail($id);

        // Array con claves de examenes seleccionados en esta orden
        $examenesSeleccionados = $orden->examenes->pluck('examen')->toArray();

        $diagnosticos = Diagnostico::with('paciente')->get();

        return view('rayosX.show', compact('orden', 'examenesSeleccionados', 'diagnosticos'));
    }
    public function marcarRealizado(RayosxOrder $orden)
{
    $orden->estado = 'Realizado';
    $orden->save();

    return redirect()->route('rayosx.index')->with('success', 'Orden marcada como realizada.');
}

}
