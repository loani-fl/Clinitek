<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxTorax extends Model
{
    protected $fillable = [
        'rayosx_order_id',
        'pa',
        'lateral',
        'ap_suplino',
        'costillas',
        'otros'
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }
}
