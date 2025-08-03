<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxAbdomen extends Model
{
    protected $fillable = [
        'rayosx_order_id',
        'simple',
        'serie_obstructiva',
        'otros'
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }
}
