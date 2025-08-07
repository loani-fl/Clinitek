<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RayosxOrder extends Model
{
  protected $fillable = [
    'diagnostico_id',
    'paciente_id',
    'fecha',
    'edad',
    'identidad',
    'nombres',
    'apellidos',
    'medico_solicitante',
    'paciente_tipo',
    'estado',
      'medico_radiologo_id',
        'medico_analista_id',
];


    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }

    public function pacienteClinica()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function pacienteRayosX()
    {
        return $this->belongsTo(PacienteRayosX::class, 'paciente_id');
    }

    public function examenes()
    {
        return $this->hasMany(RayosxOrderExamen::class);
    }

       
    
    public function getAllExamenes()
    {
        // mantiene tu función existente (sin cambios)
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
    public function medicoAnalista()
    {
        return $this->belongsTo(Medico::class, 'medico_analista_id');
    }

    public function medicoRadiologo()
    {
        return $this->belongsTo(Medico::class, 'medico_radiologo_id');
    }

  

    
}
