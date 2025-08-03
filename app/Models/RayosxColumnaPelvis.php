<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxColumnaPelvis extends Model
{
    protected $fillable = [
        'rayosx_order_id',
        'columna_cervical',
        'columna_dorsal',
        'columna_lumbar',
        'sacra_coccigea',
        'pelvis',
        'otros'
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }
}
