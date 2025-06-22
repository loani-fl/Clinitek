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
        'foto',
       
    ];
    protected $casts = [
        'estado' => 'boolean', // Para manejar como booleano y evitar problemas con 0/1 o '0'/'1'
    ];
}