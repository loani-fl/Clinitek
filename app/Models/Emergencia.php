<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentado',
        'nombres',
        'apellidos',
        'identidad',
        'edad',
        'sexo',
        'direccion',
        'telefono',
        'codigo_temporal',
        'foto',
        'fecha_hora',
        'motivo',
        'pa',
        'fc',
        'temp',
    ];

    protected $casts = [
        'documentado' => 'boolean', // <-- hace que documentado sea booleano
    ];
}

