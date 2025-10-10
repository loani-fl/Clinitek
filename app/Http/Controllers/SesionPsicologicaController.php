<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesionPsicologica;
use App\Models\Paciente;   // <<--- IMPORTANTE
use App\Models\Medico;     // <<--- si tienes modelo Medico separado

class SesionPsicologicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sesiones = SesionPsicologica::with(['paciente', 'medico'])
            ->orderBy('fecha', 'desc')
            ->paginate(10); // Paginación
        return view('sesiones.index', compact('sesiones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pacientes = Paciente::all();
        $medicos = Medico::all();
        return view('sesiones.create', compact('pacientes', 'medicos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',

            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'motivo_consulta' => 'required|string',
            'tipo_examen' => 'required|string|max:100',
            'resultado' => 'required|string',
            'observaciones' => 'nullable|string',
            'archivo_resultado' => 'nullable|file|max:5120|mimes:pdf,jpeg,jpg,png',
        ]);

        $rutaArchivo = null;
        if ($request->hasFile('archivo_resultado')) {
            $rutaArchivo = $request->file('archivo_resultado')->store('psicologia', 'public');
        }

        SesionPsicologica::create([
            'paciente_id' => $request->paciente_id,
            'medico_id' =>$request->medico_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'motivo_consulta' => $request->motivo_consulta,
            'tipo_examen' => $request->tipo_examen,
            'resultado' => $request->resultado,
            'observaciones' => $request->observaciones,
            'archivo_resultado' => $rutaArchivo,
        ]);

        return redirect()->route('sesiones.index')
            ->with('success', 'Sesión psicológica registrada correctamente ');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sesion = SesionPsicologica::with(['paciente', 'medico'])->findOrFail($id);
        return view('sesiones.show', compact('sesion'));
    }



    /**
     * Show the form for editing the specified resource.
     */

}
