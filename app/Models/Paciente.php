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
        'identidad',             // agregado
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'correo',
        'tipo_sangre',
        'padecimientos',
        'medicamentos',
        'historial_clinico',
        'alergias',
        'historial_quirurgico',
    ];

    // Opcional: si quieres, puedes definir casts o fechas
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];
}
