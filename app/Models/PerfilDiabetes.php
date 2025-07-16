<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilDiabetes extends Model
{
     protected $table = 'perfil_diabetes';
    protected $fillable = [
        'diagnostico_id',
        'peptido_c',
        'indice_peptidico',
        'insulina',
        'homa_ir',
        'homa_ir_post_prandial',
        'fructosamina',
        'hemoglobina_glicosilada',
    ];


    
    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }
}