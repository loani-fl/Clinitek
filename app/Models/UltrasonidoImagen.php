<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UltrasonidoImagen extends Model
{
    protected $fillable = [
        'ultrasonido_id',
        'ruta',
        'descripcion',
    ];

    public function ultrasonido()
    {
        return $this->belongsTo(Ultrasonido::class);
    }
}

