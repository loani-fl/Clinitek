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
        'estado',
    ];

    // Relación con Paciente (única)
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    // Relación con Médico
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    // Relación con Exámenes (varios)
    public function examens()
    {
        return $this->hasMany(\App\Models\Examen::class);
    }

    // Relación con Diagnóstico (uno)
    public function diagnostico()
    {
        return $this->hasOne(Diagnostico::class);
    }

    // Relación con Recetas (varias)
    public function recetas()
    {
        return $this->hasMany(Receta::class);
    }
}
