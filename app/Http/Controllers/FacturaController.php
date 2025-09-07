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
        'fecha' => 'required|date',
        'hora' => 'required',
        'motivo' => 'required|string|max:250',
        'sintomas' => 'required|string|max:250',
        'total_pagar' => 'required|numeric|min:0'
    ]);

    try {
        // PASO 1: Convertir hora a formato 24h si no es inmediata
        $horaFormato24 = null;
        if ($request->hora !== 'inmediata') {
            $horaFormato24 = \Carbon\Carbon::createFromFormat('g:i A', $request->hora)->format('H:i:s');
            
            // Verificar disponibilidad de horario
            $existe = \App\Models\Consulta::where('medico_id', $request->medico_id)
                ->where('fecha', $request->fecha)
                ->where('hora', $horaFormato24)
                ->exists();

            if ($existe) {
                return back()->withErrors(['hora' => 'El médico ya tiene una consulta registrada en esa fecha y hora.'])
                            ->withInput();
            }
        }

        $medico = \App\Models\Medico::findOrFail($request->medico_id);

        // PASO 2: Crear y guardar la consulta PRIMERO
        $consulta = \App\Models\Consulta::create([
            'paciente_id' => $request->paciente_id,
            'fecha' => $request->fecha,
            'hora' => $horaFormato24,
            'medico_id' => $request->medico_id,
            'especialidad' => $medico->especialidad,
            'motivo' => $request->motivo,
            'sintomas' => $request->sintomas,
            'total_pagar' => $request->total_pagar,
            'estado' => 'pendiente',
        ]);

        // PASO 3: Crear el pago asociado
        $pago = \App\Models\Pago::create([
            'paciente_id' => $request->paciente_id,
            'metodo_pago' => 'pendiente',
            'fecha' => now()->format('Y-m-d'),
            'origen' => 'consulta',
            'referencia_id' => $consulta->id,
            'servicio' => 'Consulta médica',
            'monto' => $request->total_pagar,
        ]);

        // PASO 4: Crear la factura usando la consulta guardada
        $paciente = \App\Models\Paciente::findOrFail($request->paciente_id);
        $factura = Factura::crearDesdeConsulta($consulta, $paciente, $medico);

        return redirect()->route('factura.show', $factura->id)
            ->with('success', 'Consulta registrada y factura generada exitosamente');

    } catch (\Exception $e) {
        return back()->withInput()
                    ->withErrors(['error' => 'Error al procesar la consulta: ' . $e->getMessage()]);
    }
}

    /**
     * Generar factura desde orden de rayos X
     */
    public function generarDesdeRayosX(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha' => 'required|date',
            'examenes' => 'required|array|min:1|max:7',
            'examenes.*' => 'string'
        ]);
    
        try {
            // PASO 1: Validar exámenes seleccionados
            $examenes_validos = array_keys(Factura::getExamenesPrecios());
            $examenes_seleccionados = array_intersect($request->examenes, $examenes_validos);
    
            if (empty($examenes_seleccionados)) {
                return back()->withInput()
                            ->withErrors(['examenes' => 'Debe seleccionar al menos un examen válido']);
            }
    
            // PASO 2: Crear y guardar la orden de rayos X PRIMERO
            $orden = new \App\Models\RayosxOrder();
            $orden->paciente_id = $request->paciente_id;
            $orden->fecha = $request->fecha;
            
            // Calcular total usando los precios del modelo Factura
            $precios = Factura::getExamenesPrecios();
            $total = 0;
            foreach ($examenes_seleccionados as $examen) {
                $total += $precios[$examen]['precio'] ?? 0;
            }
            $orden->total_precio = $total;
            $orden->estado = 'pendiente'; // Agregar estado inicial
            $orden->save();
    
            // PASO 3: Guardar los exámenes asociados a la orden
            foreach ($examenes_seleccionados as $codigoExamen) {
                $orden->examenes()->create([
                    'examen_codigo' => $codigoExamen,
                ]);
            }
    
            // PASO 4: Crear el pago asociado
            $pago = \App\Models\Pago::create([
                'paciente_id' => $request->paciente_id,
                'metodo_pago' => 'pendiente',
                'fecha' => now()->format('Y-m-d'),
                'origen' => 'rayos_x',
                'referencia_id' => $orden->id,
                'servicio' => 'Rayos X',
                'monto' => $total,
            ]);
    
            // PASO 5: Crear la factura usando la orden guardada
            $paciente = \App\Models\Paciente::findOrFail($request->paciente_id);
            $factura = Factura::crearDesdeRayosX($orden, $paciente, $examenes_seleccionados);
    
            return redirect()->route('factura.show', $factura->id)
                ->with('success', 'Orden de Rayos X creada y factura generada exitosamente');
    
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Error al crear la orden: ' . $e->getMessage()]);
        }
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