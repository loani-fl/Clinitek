<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RayosxExamenImagen extends Model
{
    use HasFactory;

    protected $table = 'rayosx_examen_imagenes';

    protected $fillable = [
        'rayosx_order_examen_id',
        'ruta',
        'descripcion',
    ];

    public function examen()
    {
        return $this->belongsTo(RayosxOrderExamen::class, 'rayosx_order_examen_id');
    }
}
