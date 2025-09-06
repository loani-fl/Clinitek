<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Consulta;
use App\Models\OrdenRayosX;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FacturaController extends Controller
{
    public function index()
{
    $facturas = Factura::orderBy('created_at', 'desc')->paginate(10);
    return view('factura.index', compact('facturas'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('factura.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required',
            'tipo' => 'required|in:consulta,rayos_x',
            'medico_id' => 'nullable|required_if:tipo,consulta',
            'examenes' => 'nullable|required_if:tipo,rayos_x|array',
            'metodo_pago' => 'sometimes|in:efectivo,tarjeta,transferencia'
        ]);

        $paciente = Paciente::findOrFail($request->paciente_id);
        
        if ($request->tipo === 'consulta') {
            $medico = Medico::findOrFail($request->medico_id);
            $factura = Factura::crearDesdeConsulta(
                (object)['total' => Factura::getPrecioEspecialidad($medico->especialidad)],
                $paciente,
                $medico
            );
        } else {
            $factura = Factura::crearDesdeRayosX(
                null,
                $paciente,
                $request->examenes
            );
        }

        if ($request->metodo_pago) {
            $factura->update(['metodo_pago' => $request->metodo_pago]);
        }

        return redirect()->route('factura.show', $factura->id)
            ->with('success', 'Factura generada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $factura = Factura::with(['paciente', 'medico'])->find($id);
        
        if (!$factura) {
            return redirect()->back()->with('error', 'Factura no encontrada');
        }
        
        return view('factura.show', compact('factura'));
    }

    /**
     * Generar factura desde consulta
     */
    public function generarDesdeConsulta(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'consulta_id' => 'nullable|exists:consultas,id'
        ]);

        $paciente = Paciente::findOrFail($request->paciente_id);
        $medico = Medico::findOrFail($request->medico_id);
        
        // Si existe consulta previa, usar sus datos
        $consulta = $request->consulta_id ? 
            Consulta::findOrFail($request->consulta_id) : 
            (object)['total' => Factura::getPrecioEspecialidad($medico->especialidad)];

        $factura = Factura::crearDesdeConsulta($consulta, $paciente, $medico);

        return redirect()->route('factura.show', $factura->id)
            ->with('success', 'Factura de consulta generada exitosamente');
    }

    /**
     * Generar factura desde orden de rayos X
     */
    public function generarDesdeRayosX(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'examenes' => 'required|array|min:1',
            'examenes.*' => 'string'
        ]);

        $paciente = Paciente::findOrFail($request->paciente_id);
        $examenes_validos = array_keys(Factura::getExamenesPrecios());
        $examenes_seleccionados = array_intersect($request->examenes, $examenes_validos);

        if (empty($examenes_seleccionados)) {
            return back()->withErrors(['examenes' => 'Debe seleccionar al menos un examen válido']);
        }

        $factura = Factura::crearDesdeRayosX(null, $paciente, $examenes_seleccionados);

        return redirect()->route('factura.show', $factura->id)
            ->with('success', 'Factura de rayos X generada exitosamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factura $factura)
    {
        return view('factura.edit', compact('factura'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Factura $factura)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia'
        ]);

        $factura->update($request->only(['metodo_pago']));

        return redirect()->route('factura.show', $factura->id)
            ->with('success', 'Factura actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factura $factura)
    {
        $factura->delete();
        
        return redirect()->route('factura.index')
            ->with('success', 'Factura eliminada exitosamente');
    }

    /**
     * Imprimir factura
     */
    public function imprimir(Factura $factura)
    {
        return view('factura.imprimir', compact('factura'));
    }

    /**
 * Crear factura desde formulario de rayos X
 */
/**
 * Crear factura desde formulario de rayos X
 */
    public function rayosX(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha' => 'required|date',
            'examenes' => 'required|array|min:1|max:7',
            'examenes.*' => 'string'
        ]);

        try {
            $paciente = Paciente::findOrFail($request->paciente_id);
            
            // Usar el método existente de tu modelo Factura
            $examenes_validos = array_keys(Factura::getExamenesPrecios());
            $examenes_seleccionados = array_intersect($request->examenes, $examenes_validos);

            if (empty($examenes_seleccionados)) {
                return back()->withInput()
                            ->withErrors(['examenes' => 'Debe seleccionar al menos un examen válido']);
            }

            // Crear la factura usando el método existente
            $factura = Factura::crearDesdeRayosX(null, $paciente, $examenes_seleccionados);
            
            // Si necesitas actualizar la fecha específica
            if ($request->fecha !== now()->format('Y-m-d')) {
                $factura->update(['fecha' => $request->fecha]);
            }

            return redirect()->route('factura.show', $factura->id)
                            ->with('success', 'Orden de Rayos X creada exitosamente');

        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Error al crear la orden: ' . $e->getMessage()]);
        }
    }
}