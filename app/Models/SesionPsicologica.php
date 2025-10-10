<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionPsicologica extends Model
{
    use HasFactory;


    protected $table = 'sesiones_psicologicas';

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'motivo_consulta',
        'tipo_examen',
        'resultado',
        'observaciones',
        'archivo_resultado',
    ];

    // Relación con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    // Relación con Médico
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }
}
