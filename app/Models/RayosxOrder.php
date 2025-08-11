<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RayosxOrder extends Model
{
    use HasFactory;

    protected $table = 'rayosx_order_examenes';

    protected $fillable = [
        'rayosx_order_id',
        'examen',
        'examen_codigo',
        'descripcion',
        'imagen_path',
    ];

    public function orden()
    {
        return $this->belongsTo(RayosxOrder::class, 'rayosx_order_id');
    }

    public function imagenes()
    {
        return $this->hasMany(RayosxExamenImagen::class, 'rayosx_order_examen_id');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
