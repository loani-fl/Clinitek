<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RayosxOrderExamen extends Model
{
 protected $table = 'rayosx_order_examenes';

    protected $fillable = [
        'rayosx_order_id',
        'examen',
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }
}
