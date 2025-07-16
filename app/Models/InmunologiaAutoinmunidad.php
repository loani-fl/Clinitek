<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InmunologiaAutoinmunidad extends Model
{
    protected $table = 'inmunologia_autoinmunidad';

    protected $fillable = [
        'diagnostico_id',
        'iga',
        'igg',
        'igm',
        'ige',
        'complemento_c3',
        'complemento_c4',
        'vitamina_d',
        'ac_antinucleares',
    ];

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}

