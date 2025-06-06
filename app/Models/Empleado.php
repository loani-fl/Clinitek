<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres',
        'apellidos',
        'identidad',
        'direccion',
        'telefono',
        'correo',
        'fecha_ingreso',
        'fecha_nacimiento',
        'genero',
        'estado_civil',
        'puesto_id',
        'salario',
        'observaciones',
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class);
    }
}
