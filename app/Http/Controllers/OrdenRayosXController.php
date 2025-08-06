<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\RayosxOrder;
use App\Models\RayosxOrderExamen;
use Illuminate\Http\Request;
use App\Models\PacienteRayosX; // <- agregar

class OrdenRayosXController extends Controller
{
    /**
     * Mostrar formulario de creación.
     */
public function create(Request $request)
{
    // Pacientes de la clínica
    $pacientesClinica = Paciente::orderBy('nombre')->get();

    // Pacientes creados solo para Rayos X
    $pacientesRayosX = PacienteRayosX::orderBy('nombre')->get();

    // Pasamos ambas colecciones a la vista
    // (la vista las unirá en el select)
    return view('rayosX.create', [
        'pacientesClinica' => $pacientesClinica,
        'pacientesRayosX' => $pacientesRayosX,
        // opcional: mantener selección enviada por query (ej: seleccion=rayosx-12)
        'seleccion' => $request->query('seleccion')
    ]);
}


    /**
     * Guardar nueva orden de Rayos X.
     */
    public function store(Request $request)
{
    
    $request->validate([
        'seleccion' => 'required',
        'fecha' => 'required|date',
      
        'examenes' => 'required|array|min:1|max:10',
    ], [
        'seleccion.required' => 'Debe seleccionar un diagnóstico o paciente.',
        'fecha.required' => 'La fecha es obligatoria.',
      
        'examenes.required' => 'Debe seleccionar al menos un examen.',
    ]);

    $diagnostico_id = null;
    $paciente_id = null;
    $paciente_tipo = null; // 'clinica' o 'rayosx'

    if (str_starts_with($request->seleccion, 'diagnostico-')) {
        $diagnostico_id = (int) str_replace('diagnostico-', '', $request->seleccion);
    } elseif (str_starts_with($request->seleccion, 'clinica-')) {
        $paciente_id = (int) str_replace('clinica-', '', $request->seleccion);
        $paciente_tipo = 'clinica';
    } elseif (str_starts_with($request->seleccion, 'rayosx-')) {
        $paciente_id = (int) str_replace('rayosx-', '', $request->seleccion);
        $paciente_tipo = 'rayosx';
    }

    // Validar existencia según tipo
    if ($diagnostico_id && !Diagnostico::find($diagnostico_id)) {
        return back()->with('error', 'El diagnóstico seleccionado no existe.');
    }
    if ($paciente_tipo === 'clinica' && !Paciente::find($paciente_id)) {
        return back()->with('error', 'El paciente de la clínica seleccionado no existe.');
    }
    if ($paciente_tipo === 'rayosx' && !PacienteRayosX::find($paciente_id)) {
        return back()->with('error', 'El paciente para Rayos X seleccionado no existe.');
    }

    // Si quieres guardar en la orden datos del paciente (identidad/edad/nombres)
    // puedes obtener los datos según el tipo:
    $identidad = null;
    $edad = null;
    $nombres = null;
    $apellidos = null;

    if ($paciente_tipo === 'clinica') {
        $p = Paciente::find($paciente_id);
        $identidad = $p->identidad ?? null;
        $edad = $p->edad ?? null;
        $nombres = $p->nombre ?? null;
        $apellidos = $p->apellidos ?? null;
    } elseif ($paciente_tipo === 'rayosx') {
        $p = PacienteRayosX::find($paciente_id);
        $identidad = $p->identidad ?? null;
        $edad = $p->edad ?? null;
        $nombres = $p->nombre ?? null;
        $apellidos = $p->apellidos ?? null;
    } else {
        // si es diagnóstico y la UI espera rellenar manualmente, usa request values
        $identidad = $request->identidad;
        $edad = $request->edad;
        $nombres = $request->nombres;
        $apellidos = $request->apellidos;
    }

    // Crear la orden
    $orden = RayosxOrder::create([
        'diagnostico_id' => $diagnostico_id,
        // guardamos paciente_id numérico (si quieres distinguir tipos, añade columna paciente_tipo)
        'paciente_id' => $paciente_id,
        'fecha' => $request->fecha,
        'edad' => $edad,
        'identidad' => $identidad,
        'nombres' => $nombres,
        'apellidos' => $apellidos,
      
        // si necesitas saber el tipo del paciente, añade 'paciente_tipo' => $paciente_tipo
    ]);

    foreach ($request->examenes as $examen) {
        RayosxOrderExamen::create([
            'rayosx_order_id' => $orden->id,
            'examen' => $examen,
        ]);
    }

    return redirect()->route('rayosx.index')->with('success', 'Orden de Rayos X creada correctamente.');
}

    /**
     * Listar órdenes.
     */
    public function index()
    {
        $ordenes = RayosxOrder::with(['diagnostico.paciente', 'paciente'])
            ->latest()
            ->paginate(10);

        return view('rayosX.index', compact('ordenes'));
    }

    /**
     * Mostrar una orden.
     */
    public function show($id)
    {
        $orden = RayosxOrder::with(['examenes', 'diagnostico.paciente', 'paciente'])
            ->findOrFail($id);

        return view('rayosX.show', compact('orden'));
    }
}
