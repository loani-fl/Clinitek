<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Paciente;

class Consulta extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'genero',
        'fecha',
        'hora',
        'especialidad',
        'medico_id', 
        'motivo',
        'sintomas',
        'estado',
          
    ];

    public function paciente()
{
    return $this->belongsTo(Paciente::class);
}

public function medico()
{
    return $this->belongsTo(Medico::class);
}
    public function examenes()
{
    return $this->hasMany(\App\Models\Examen::class);
}

}
