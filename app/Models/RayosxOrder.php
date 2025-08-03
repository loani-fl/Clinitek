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

    public function getAllExamenes()
{
    $examenes = [];

    $secciones = [
        'cabeza' => 'RayosxCabeza',
        'torax' => 'RayosxTorax',
        'abdomen' => 'RayosxAbdomen',
        'extremidadSuperior' => 'RayosxExtremidadSuperior',
        'extremidadInferior' => 'RayosxExtremidadInferior',
        'columnaPelvis' => 'RayosxColumnaPelvis',
        'estudiosEspeciales' => 'RayosxEstudiosEspeciales',
    ];

    foreach ($secciones as $relacion => $modelo) {
        if ($this->$relacion) {
            foreach ($this->$relacion->getAttributes() as $campo => $valor) {
                if (in_array($campo, ['id', 'rayosx_order_id', 'created_at', 'updated_at'])) {
                    continue;
                }
                if ($valor) {
                    $examenes[] = ucwords(str_replace('_', ' ', $campo));
                }
            }
        }
    }

    return $examenes;
}

public function examenes()
{
    return $this->hasMany(RayosxOrderExamen::class);
}


}



