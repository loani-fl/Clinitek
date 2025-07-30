<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxOrder extends Model
{
    protected $fillable = [
        'diagnostico_id', 'fecha', 'edad', 'identidad',
        'nombres', 'apellidos', 'medico_solicitante'
    ];

    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }

    public function cabeza()
    {
        return $this->hasOne(RayosxCabeza::class);
    }

    public function torax()
    {
        return $this->hasOne(RayosxTorax::class);
    }

    public function abdomen()
    {
        return $this->hasOne(RayosxAbdomen::class);
    }

    public function extremidadSuperior()
    {
        return $this->hasOne(RayosxExtremidadSuperior::class);
    }

    public function extremidadInferior()
    {
        return $this->hasOne(RayosxExtremidadInferior::class);
    }

    public function columnaPelvis()
    {
        return $this->hasOne(RayosxColumnaPelvis::class);
    }

    public function estudiosEspeciales()
    {
        return $this->hasOne(RayosxEstudiosEspeciales::class);
    }
}



