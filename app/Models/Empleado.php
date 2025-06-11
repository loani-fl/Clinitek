<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Empleado extends Model
{
    use HasFactory;

    // Nueva tabla personalizada
    protected $table = 'listaempleados';

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
        'area',
        'turno_asignado',
        'estado',
        'salario',
        'observaciones',
      
    ];

    // Relación: un empleado pertenece a un puesto
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
