<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'metodo_pago',
        'nombre_titular',
        'numero_tarjeta',
        'fecha_expiracion',
        'cvv',
        'cantidad',
        'fecha',
        'consulta_id',
        'estado_pago',
        'servicio',
        'descripcion',
        'rayosx_order_id',
    ];

    protected $dates = ['fecha']; // <- agregar para que Laravel entienda que es fecha Carbon

    public function consulta()
    {
        return $this->belongsTo(\App\Models\Consulta::class, 'consulta_id');
    }

    public function rayosxOrder()
{
    return $this->belongsTo(\App\Models\RayosxOrder::class, 'rayosx_order_id');
}
public function rayosxOrderExamen()
{
    return $this->belongsTo(RayosxOrderExamen::class, 'rayosx_order_examen_id');
}



  

public function getFechaLocalAttribute()
{
    if (!$this->fecha) {
        return null;
    }

    // Si ya es instancia de Carbon, úsalo, si no, conviértelo.
    if ($this->fecha instanceof Carbon) {
        $fechaCarbon = $this->fecha;
    } else {
        try {
            $fechaCarbon = Carbon::parse($this->fecha);
        } catch (\Exception $e) {
            // Si falla el parseo, retorna null para evitar error
            return null;
        }
    }

    return $fechaCarbon->timezone('America/Tegucigalpa');
}

}
