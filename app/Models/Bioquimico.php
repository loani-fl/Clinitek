<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bioquimico extends Model
{
     protected $table = 'bioquimicos';
    protected $fillable = [
     'diagnostico_id',
        'urea',
     'bun',
       'creatinina',
      'acido_urico',
     'glucosa',
     'glucosa_post_prandial_2h',
      'c_tolencia_glucosa_2h',
      'c_tolencia_glucosa_4h',
     'bilirrubina_total_y_fracciones',
    'proteinas_totales',
    'albumina_globulina',
    'electroforesis_proteinas',
        'cistatina_c_creatinina_tfg',
    'diabetes_gestacional',
    ];

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}
