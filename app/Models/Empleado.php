<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'nombres',
        'apellidos',
        'identidad',
        'telefono',
        'correo',
        'fecha_ingreso',
        'fecha_nacimiento',
        'genero',
        'estado_civil',
        'direccion',
        'puesto_id',
        'area',
        'salario',
        'turno_asignado',
        'observaciones',
        'estado',
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class);
    }
}
