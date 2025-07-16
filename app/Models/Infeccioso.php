<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infeccioso extends Model
{
     protected $table = 'infecciosos';
    protected $fillable = [
        'diagnostico_id',
          'hiv_1_y_2',
       'hepatitis_b',
      'hepatitis_c',
        'sifilis_vdrl_o_rpr',
        'citomegalovirus_cmv',
    ];

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}
