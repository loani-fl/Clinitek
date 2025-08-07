<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Pago;
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

    if ($consulta_id) {
        // Buscar la consulta con su paciente relacionado
        $consulta = Consulta::with('paciente')->find($consulta_id);

        if ($consulta && $consulta->paciente) {
            $paciente = $consulta->paciente;
        } else {
            $paciente = null;
        }
    } else {
        $consulta = null;
        $paciente = null;
    }

    return view('pago.create', compact('consulta', 'paciente'));
}


    


    /**
     * Store a newly created resource in storage.
     */

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
     
             'cantidad.required' => 'La cantidad es obligatoria.',
             'cantidad.numeric' => 'La cantidad debe ser un número válido.',
             'cantidad.min' => 'La cantidad debe ser mayor que cero.',
             'cantidad.max' => 'La cantidad no puede superar L. 9999.99.',
             'cantidad.min' => 'La cantidad debe ser mayor o igual a L. 1000.00.',
     
             'servicio.required' => 'El campo servicio es obligatorio.',
         ];
     
         // Validación base que siempre se aplica
         $rules = [
             'metodo_pago' => 'required|in:tarjeta,efectivo',
             'nombre_titular' => 'required_if:metodo_pago,tarjeta|max:50',
             'numero_tarjeta' => 'required_if:metodo_pago,tarjeta|max:19',
             'fecha_expiracion' => 'required_if:metodo_pago,tarjeta',
             'cvv' => 'required_if:metodo_pago,tarjeta|max:4',
         ];
     
         // Validación condicional para servicio y cantidad según método de pago
         if ($request->metodo_pago === 'tarjeta') {
             $rules['servicio'] = 'required|string';
             $rules['cantidad'] = ['required', 'numeric', 'min:1000.00', 'max:9999.99'];
         } elseif ($request->metodo_pago === 'efectivo') {
             $rules['servicio'] = 'required|string';
             $rules['cantidad'] = ['required', 'numeric', 'min:1000.00', 'max:9999.99'];
         }
     
         $request->validate($rules, $messages);
     
         $pago = new Pago();
     
         $pago->fecha = Carbon::now();
     
         $pago->metodo_pago = $request->metodo_pago;
         $pago->servicio = $request->servicio;
         $pago->descripcion = $request->descripcion_servicio;
         $pago->cantidad = $request->cantidad;
     
         if ($request->metodo_pago === 'tarjeta') {
             $pago->nombre_titular = $request->nombre_titular;
             $pago->numero_tarjeta = $request->numero_tarjeta;
             $pago->fecha_expiracion = $request->fecha_expiracion;
             $pago->cvv = $request->cvv;
         }
     
         $pago->consulta_id = $request->consulta_id;
     
         $pago->save();
     
         // ** Aquí agrego la actualización de cuenta solo si es pago en efectivo **
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