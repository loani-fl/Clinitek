<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Empleado extends Model
{
    protected $table = 'listaempleados';

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
        'estado'
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class);
    }

    // Accesor para obtener el nombre completo del empleado
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    // Accesor para obtener la edad del empleado
    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nacimiento)->age;
    }
}
