<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RayosxOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'fecha',
        'edad',
        'identidad',
        'nombres',
        'apellidos',
        'datos_clinicos',
        'estado',
        'total_precio',
    ];

   
 public function paciente()
{
    return $this->belongsTo(Paciente::class);
}



    public function examenes()
    {
        return $this->hasMany(RayosxOrderExamen::class);
    }
  public function medicoAnalista()
    {
        return $this->belongsTo(Medico::class, 'medico_analista_id');
    }
    
}
