<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrinaFluido extends Model
{
    protected $fillable = [
        'diagnostico_id',
        'examen_general_orina',
        'cultivo_orina',
        'orina_24_horas',
        'prueba_embarazo',
        'liquido_cefalorraquideo',
        'liquido_pleural',
        'liquido_peritoneal',
        'liquido_articular',
        'espermograma',
    ];


    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}
