<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Pago;
use App\Models\RayosxOrder;
use App\Models\RayosxOrderExamen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
{
    $consulta_id = $request->query('consulta_id');
    $rayosx_id = $request->query('rayosx_id');

    $paciente = null;
    $servicio = '';
    $cantidad = 0;
    $consulta = null;
    $rayosx = null;
    

    $preciosPorEspecialidad = [
        "Cardiología" => 900.00,
        "Pediatría" => 500.00,
        "Dermatología" => 900.00,
        "Medicina General" => 800.00,
        "Psiquiatría" => 500.00,
        "Neurología" => 1000.00,
        "Radiología" => 700.00,
    ];

    if ($consulta_id) {
        $consulta = Consulta::with('paciente', 'medico')->find($consulta_id);

        if ($consulta && $consulta->paciente) {
            $paciente = $consulta->paciente;
            $servicio = 'Consulta médica';

            $especialidad = $consulta->medico->especialidad ?? null;

            if ($especialidad && isset($preciosPorEspecialidad[$especialidad])) {
                $cantidad = $preciosPorEspecialidad[$especialidad];
            } else {
                $cantidad = $consulta->precio ?? 0; // fallback si no encuentra especialidad
            }
        }
        elseif ($rayosx_id) {
            $rayosx = RayosxOrderExamen::with('orden')->find($rayosx_id);
        
            dd([
                'rayosx' => $rayosx,
                'orden' => $rayosx ? $rayosx->orden : null,
                'paciente' => $rayosx && $rayosx->orden ? $rayosx->orden->paciente : null,
            ]);
        }
        
        
        
    
        if ($rayosx && $rayosx->paciente) {
            $paciente = $rayosx->paciente;
            $servicio = 'Exámenes rayos X'; // 🔹 Cambiado para que se muestre así
    
            // Si el precio ya está en la tabla RayosxOrderExamen, lo usamos directo
            $cantidad = $rayosx->precio_total ?? $rayosx->precio ?? 0;
        }
    }
    
  

    return view('pago.create', compact(
        'consulta',
        'paciente',
        'rayosx',
        'servicio',
        'cantidad'
    ));
}




public function store(Request $request)
{
    $messages = [
        'metodo_pago.required' => 'El método de pago es obligatorio.',
        'metodo_pago.in' => 'El método de pago seleccionado no es válido.',

        'nombre_titular.required_if' => 'El nombre del titular es obligatorio.',
        'nombre_titular.max' => 'El nombre del titular no puede tener más de 50 caracteres.',

        'numero_tarjeta.required_if' => 'El número de tarjeta es obligatorio.',
        'numero_tarjeta.max' => 'El número de tarjeta no puede tener más de 19 caracteres.',

        'fecha_expiracion.required_if' => 'La fecha de expiración es obligatoria.',

        'cvv.required_if' => 'El código CVV es obligatorio.',
        'cvv.max' => 'El código CVV no puede tener más de 4 caracteres.',
    ];

    $rules = [
        'metodo_pago' => 'required|in:tarjeta,efectivo',
        'nombre_titular' => 'required_if:metodo_pago,tarjeta|max:50',
        'numero_tarjeta' => 'required_if:metodo_pago,tarjeta|max:19',
        'fecha_expiracion' => 'required_if:metodo_pago,tarjeta',
        'cvv' => 'required_if:metodo_pago,tarjeta|max:4',
        'cantidad' => 'nullable|string',
        'servicio_tarjeta' => 'nullable|string',
        'servicio_efectivo' => 'nullable|string',
        'rayosx_id' => 'nullable|integer|exists:rayosx_order_examens,id',
    ];

    $request->validate($rules, $messages);

    $pago = new Pago();
    $pago->consulta_id = $request->consulta_id ?? null;
    $pago->rayosx_order_examen_id = $request->rayosx_id ?? null;
    $pago->fecha = Carbon::now();
    $pago->metodo_pago = $request->metodo_pago;

    // ASIGNAR ESTADO SEGÚN MÉTODO
    if ($request->metodo_pago === 'tarjeta') {
        $pago->estado_pago = 'pagada'; // Pago con tarjeta = pagada
        $pago->servicio = $request->servicio_tarjeta;
        $pago->descripcion = $request->descripcion_servicio ?? null;
        $pago->cantidad = $request->cantidad ?? $request->cantidad_tarjeta;

        $pago->nombre_titular = $request->nombre_titular;
        $pago->numero_tarjeta = $request->numero_tarjeta;
        $pago->fecha_expiracion = $request->fecha_expiracion;
        $pago->cvv = $request->cvv;
    } else {
        $pago->estado_pago = 'pendiente'; // Pago en efectivo = pendiente
        $pago->servicio = $request->servicio_efectivo;
        $pago->descripcion = $request->descripcion_servicio ?? null;
        $pago->cantidad = $request->cantidad ?? $request->cantidad_efectivo;
    }

    $pago->save();

    if ($request->metodo_pago === 'efectivo' && $pago->consulta_id) {
        $consulta = Consulta::find($pago->consulta_id);
        if ($consulta) {
            $consulta->cuenta = $pago->cantidad;
            $consulta->save();
        }
    }

    return redirect()->route('pago.show', ['pago' => $pago->id]);
}


     
     

    
public function show(Pago $pago)
{
    $paciente = null;

    if ($pago->consulta) {
        $paciente = $pago->consulta->paciente;
    } elseif ($pago->rayosx_order_examen_id) {
        // Asumiendo que el modelo Pago tiene relación con RayosxOrderExamen
        $rayosx = $pago->rayosxOrderExamen; 
        if ($rayosx) {
            $paciente = $rayosx->paciente;
        }
    }

    return view('pago.show', compact('pago', 'paciente'));
}



    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        //
    }
}