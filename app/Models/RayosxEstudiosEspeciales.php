<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxEstudiosEspeciales extends Model
{
    protected $fillable = [
        'rayosx_order_id',
        'urografia',
        'histerosalpingografia',
        'tránsito_intestinal',
        'colon_por_enema',
        'otros'
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }
}
