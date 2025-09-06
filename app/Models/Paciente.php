<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'nombre',
        'apellidos',
        'identidad',
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'correo',
        'tipo_sangre',
        'genero',
        'padecimientos',
        'medicamentos',
        'historial_clinico',
        'alergias',
        'historial_quirurgico',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function diagnostico()
    {
        return $this->hasOne(Diagnostico::class);
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function recetas()
    {
        return $this->hasManyThrough(Receta::class, Consulta::class);
    }

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class);
    }

    public function ordenesRayosX()
    {
        return $this->hasMany(RayosxOrder::class);
    }

    // AGREGAR: Relación con facturas
    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    // CORREGIR: Usar 'apellidos' en lugar de 'apellido'
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }

    // AGREGAR: Accessor para compatibilidad con 'apellido' singular
    public function getApellidoAttribute()
    {
        return $this->apellidos;
    }
}