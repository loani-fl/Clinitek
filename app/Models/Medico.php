<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $table = 'medicos';

    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'especialidad',
        'correo',
        'numero_identidad',
        'salario',     
        'fecha_nacimiento',
        'fecha_ingreso',
        'genero',
        'observaciones',
        'direccion',    
        'foto',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function getNombreCompletoAttribute()
{
    return $this->nombre . ' ' . $this->apellido;
}
}
