<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paciente;
use App\Models\Medico;

class Consulta extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'fecha',
        'hora',
        'especialidad',
        'medico_id',
        'motivo',
        'sintomas',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }
}

