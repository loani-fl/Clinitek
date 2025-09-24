<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospitalizacion extends Model
{
    protected $fillable = [
        'paciente_id',
        'fecha_ingreso',
        'motivo',
        'medico_responsable',
        'estado'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
