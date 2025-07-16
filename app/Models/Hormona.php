<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hormona extends Model
{
    use HasFactory;

    protected $fillable = [
        'diagnostico_id',
        'hormona_luteinizante_lh',
        'hormona_foliculo_estimulante_fsh',
        'cortisol',
        'prolactina',
        'testosterona',
        'estradiol',
        'progesterona',
        'beta_hcg_embarazo',
    ];

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}
