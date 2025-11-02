<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlPrenatal extends Model
{
    use HasFactory;

    protected $table = 'controles_prenatales';

    protected $fillable = [
        'paciente_id',
        'fecha_ultima_menstruacion',
        'fecha_probable_parto',
        'semanas_gestacion',
        'numero_gestaciones',
        'numero_partos',
        'numero_abortos',
        'numero_hijos_vivos',
        'tipo_partos_anteriores',
        'complicaciones_previas',
        'enfermedades_cronicas',
        'alergias',
        'cirugias_previas',
        'medicamentos_actuales',
        'habitos',
        'antecedentes_familiares',
        'fecha_control',
        'presion_arterial',
        'frecuencia_cardiaca_materna',
        'temperatura',
        'peso_actual',
        'altura_uterina',
        'latidos_fetales',
        'movimientos_fetales',
        'edema',
        'presentacion_fetal',
        'resultados_examenes',
        'observaciones',
        'suplementos',
        'vacunas_aplicadas',
        'indicaciones_medicas',
        'fecha_proxima_cita',
    ];

    protected $casts = [
        'fecha_ultima_menstruacion' => 'date',
        'fecha_probable_parto' => 'date',
        'fecha_control' => 'date',
        'fecha_proxima_cita' => 'date',
        'peso_actual' => 'decimal:2',
        'altura_uterina' => 'decimal:1',
        'temperatura' => 'decimal:1',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}