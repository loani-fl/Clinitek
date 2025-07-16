<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hematologia extends Model
{
     protected $table = 'hematologia';
    protected $fillable = [
        'diagnostico_id',
        'hemograma_completo',
        'frotis_en_sangre_periferica',
        'reticulocitos',
        'eritrosedimentacion',
        'grupo_sanguineo',
        'p_coombs_directa',
        'p_coombs_indirecta',
        'plasmodium_gota_gruesa',
        'plasmodium_anticuerpos',
    ];

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}
