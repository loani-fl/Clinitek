<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'servicio',
        'descripcion',
        'cantidad',
        'metodo_pago',
        'fecha',
        'nombre_titular',
        'numero_tarjeta',
        'fecha_expiracion',
        'origen', // 'consulta' o 'rayosx'
        'referencia_id' // id de la consulta o rayosx asociado
    ];

    // Relación con paciente
    public function paciente()
    {
        return $this->belongsTo(\App\Models\Paciente::class);
    }
    
    public function medico()
    {
        return $this->belongsTo(\App\Models\Medico::class);
    }

            
        public function consulta()
        {
            return $this->belongsTo(\App\Models\Consulta::class, 'referencia_id');
        }

    
}