<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxExtremidadInferior extends Model
{
    protected $fillable = [
        'rayosx_order_id',
        'pelvis',
        'cadera',
        'femur',
        'rodilla',
        'pierna',
        'tobillo',
        'pie_dedos',
        'otros'
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }
}
