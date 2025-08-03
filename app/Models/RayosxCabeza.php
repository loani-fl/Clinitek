<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxCabeza extends Model
{
    protected $fillable = [
        'rayosx_order_id',
        'craneo_ap',
        'craneo_lateral',
        'senos_paranasales',
        'huesos_nasales',
        'atm',
        'otros'
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }
}
