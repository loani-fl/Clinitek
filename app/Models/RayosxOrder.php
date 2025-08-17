<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RayosxOrder extends Model
{
    use HasFactory;

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
        'total_precio',
    ];

    // Relación con diagnóstico
    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class);
    }

    // Relación con paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    // Relación con exámenes (muchos a muchos)
    public function examenes()
    {
        return $this->belongsToMany(Examen::class, 'rayosx_order_examen', 'rayosx_order_id', 'examen_id')
        ->withPivot('precio'); // si en la pivot tienes precio
    }

    // Relación con médicos
    public function medicoAnalista()
    {
        return $this->belongsTo(Medico::class, 'medico_analista_id');
    }

    public function medicoRadiologo()
    {
        return $this->belongsTo(Medico::class, 'medico_radiologo_id');
    }

    // Función para obtener todos los exámenes de las secciones (opcional)
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
}
