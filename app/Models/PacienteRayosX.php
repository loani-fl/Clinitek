<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PacienteRayosX extends Model
{
  protected $table = 'pacientes_rayosxs';



    protected $fillable = [
        'nombre',
        'apellidos',
        'identidad',
        'fecha_orden',
        'fecha_nacimiento',
        'edad',
        'telefono'
    ];
}

