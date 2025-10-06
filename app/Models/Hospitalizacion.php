<?php

namespace App\Models;
use HasFactory;

use Illuminate\Database\Eloquent\Model;

class Hospitalizacion extends Model
{

    protected $table = 'hospitalizaciones'; // <- así Eloquent usará el nombre correcto

    protected $fillable = [
        'paciente_id',
        'fecha_ingreso',
        'motivo',
        'acompanante',
        'hospital',
        'medico_id',
        'clinica',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    
public function emergencia()
{
    return $this->belongsTo(Emergencia::class);
}

public function medico()
{
    return $this->belongsTo(Medico::class);
}
}
