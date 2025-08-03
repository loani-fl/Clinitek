<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxExtremidadSuperior extends Model
{
    protected $fillable = [
        'rayosx_order_id',
        'hombro',
        'clavicula',
        'brazo',
        'codo',
        'antebrazo',
        'muñeca',
        'mano_dedos',
        'otros'
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }
}
