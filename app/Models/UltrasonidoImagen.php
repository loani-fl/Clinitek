<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UltrasonidoImagen extends Model
{
    protected $fillable = [
        'ultrasonido_id',
        'ruta',
        'descripcion',
        'tipo_examen',
    ];

    public function ultrasonido()
    {
        return $this->belongsTo(Ultrasonido::class);
    }
}

