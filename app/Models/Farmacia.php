<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmacia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ubicacion',
        'telefono',
        'horario',
        'descripcion',
        'descuento',
        'pagina_web',
        'foto',
    ];
}
