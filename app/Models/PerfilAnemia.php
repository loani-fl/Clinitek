<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilAnemia extends Model
{
     protected $table = 'perfil_anemias';
     
    protected $fillable = [
        'diagnostico_id',
        'hierro_serico',
        'capacidad_fijacion_hierro',
        'transferrina',
        'ferritina',
        'vitamina_b12',
        'acido_folico',
        'eritropoyetina',
        'haptoglobina',
        'electroforesis_hemoglobina',
        'glucosa_6_fosfato',
        'fragilidad_osmotica_hematias',
    ];

    

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}

