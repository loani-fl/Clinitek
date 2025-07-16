<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcadorTumoral extends Model
{
     protected $table = 'marcadores_tumorales'; 
    protected $fillable = [
        'diagnostico_id',
        'af_proteina',
        'ac_embrionario',
        'ca125',
        'he4',
        'indice_roma',
        'ca15_3',
        'ca19_9',
        'ca72_4',
        'cyfra_21_1',
        'beta_2_microglobulina',
        'enolasa_neuroespecifica',
        'antigeno_prostatico_psa',
        'psa_libre',
    ];

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}

