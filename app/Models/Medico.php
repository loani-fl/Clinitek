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
        'numero_identidad',
        'especialidad',
        'telefono',
        'correo',
        'fecha_nacimiento',
        'fecha_ingreso',
        'genero',
        'observaciones',
    ];
}