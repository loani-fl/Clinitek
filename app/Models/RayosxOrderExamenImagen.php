<?php

// app/Models/RayosxOrderExamenImagen.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxOrderExamenImagen extends Model
{

    protected $table = 'rayosx_order_examen_imagenes';
    
    protected $fillable = ['rayosx_order_examen_id', 'imagen_ruta'];

    public function examen()
    {
        return $this->belongsTo(RayosxOrderExamen::class, 'rayosx_order_examen_id');
    }
}
