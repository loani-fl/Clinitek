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
        'especialidad',
        'telefono',
        'correo',
        'salario',
        'identidad',
        'fecha_nacimiento',
        'fecha_ingreso',
        'genero',
        'observaciones',
        'foto',
    ];
}
