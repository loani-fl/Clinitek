<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'metodo_pago',
        'nombre_titular',
        'numero_tarjeta',
        'fecha_expiracion',
        'cvv',
        'cantidad',
        'fecha',
        'consulta_id',
    ];

    public function consulta()
    {
        return $this->belongsTo(\App\Models\Consulta::class, 'consulta_id');
    }
}
