<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'descripcion',
        'cantidad',
        'unidad',
        'fecha_ingreso',
        'fecha_vencimiento',
        'precio_unitario',
        'proveedor',
        'observaciones',
    ];
}
