<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id', 
        'medico_id',
        'numero_factura',
        'paciente_nombre',
        'paciente_identidad',
        'medico_nombre',
        'especialidad',
        'tipo',
        'metodo_pago',
        'descripcion',
        'total',
        'fecha',
        'hora'
    ];

    protected $casts = [
        'descripcion' => 'array',
        'fecha' => 'date',
        'hora' => 'datetime:H:i:s',
        'total' => 'decimal:2'
    ];

    // AGREGAR ESTAS RELACIONES
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($factura) {
            // Solo generar número de factura si no existe
            if (!$factura->numero_factura) {
                $factura->numero_factura = 'FACT-' . str_pad(static::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
            
            // Solo configurar fecha y hora si no existen
            if (!$factura->fecha || !$factura->hora) {
                $honduras_time = Carbon::now('America/Tegucigalpa');
                $factura->fecha = $factura->fecha ?? $honduras_time->format('Y-m-d');
                $factura->hora = $factura->hora ?? $honduras_time->format('H:i:s');
            }
        });
    }

    /**
     * Generar factura desde consulta
     */
    public static function crearDesdeConsulta($consulta, $paciente, $medico)
    {
        return static::create([
            'paciente_id' => $paciente->id,
            'medico_id' => $medico->id, // AGREGAR ESTA LÍNEA
            'paciente_nombre' => $paciente->nombre_completo ?? ($paciente->nombre . ' ' . $paciente->apellido),
            'paciente_identidad' => $paciente->identidad,
            'medico_nombre' => $medico->nombre_completo ?? ($medico->nombre . ' ' . $medico->apellido),
            'especialidad' => $medico->especialidad,
            'tipo' => 'consulta',
            'metodo_pago' => 'efectivo',
            'descripcion' => [
                [
                    'servicio' => 'Consulta médica',
                    'precio' => $consulta->total ?? static::getPrecioEspecialidad($medico->especialidad)
                ]
            ],
            'total' => $consulta->total ?? static::getPrecioEspecialidad($medico->especialidad)
        ]);
    }

    /**
     * Generar factura desde orden de rayos X
     */
    public static function crearDesdeRayosX($orden, $paciente, $examenes_seleccionados)
    {
        $examenes_precios = static::getExamenesPrecios();
        $descripcion = [];
        $total = 0;

        foreach ($examenes_seleccionados as $examen) {
            if (isset($examenes_precios[$examen])) {
                $descripcion[] = [
                    'servicio' => $examenes_precios[$examen]['nombre'],
                    'precio' => $examenes_precios[$examen]['precio']
                ];
                $total += $examenes_precios[$examen]['precio'];
            }
        }

        return static::create([
            'paciente_id' => $paciente->id,
            // Para rayos X no siempre hay médico, así que dejamos medico_id como null
            'paciente_nombre' => $paciente->nombre_completo ?? ($paciente->nombre . ' ' . $paciente->apellido),
            'paciente_identidad' => $paciente->identidad,
            'tipo' => 'rayos_x',
            'metodo_pago' => 'efectivo',
            'descripcion' => $descripcion,
            'total' => $total
        ]);
    }

    // ... resto de métodos igual
    public static function getPrecioEspecialidad($especialidad)
    {
        $precios = [
            'Cardiología' => 900.00,
            'Pediatría' => 500.00,
            'Dermatología' => 900.00,
            'Medicina General' => 800.00,
            'Psiquiatría' => 500.00,
            'Neurología' => 1000.00,
            'Radiología' => 700.00
        ];

        return $precios[$especialidad] ?? 800.00;
    }

    public static function getExamenesPrecios()
    {
        $examenes_nombres = [
            'craneo_anterior_posterior' => 'Cráneo Anterior Posterior',
            'craneo_lateral' => 'Cráneo Lateral',
            'waters' => 'Waters',
            'waters_lateral' => 'Waters Lateral',
            'conductos_auditivos' => 'Conductos Auditivos',
            'cavum' => 'Cavum',
            'senos_paranasales' => 'Senos Paranasales',
            'silla_turca' => 'Silla Turca',
            'huesos_nasales' => 'Huesos Nasales',
            'atm_tm' => 'ATM - TM',
            'mastoides' => 'Mastoides',
            'mandibula' => 'Mandíbula',
            'torax_posteroanterior_pa' => 'Tórax PA',
            'torax_anteroposterior_ap' => 'Tórax AP',
            'torax_lateral' => 'Tórax Lateral',
            'torax_oblicuo' => 'Tórax Oblicuo',
            'torax_superior' => 'Tórax Superior',
            'torax_inferior' => 'Tórax Inferior',
            'costillas_superiores' => 'Costillas Superiores',
            'costillas_inferiores' => 'Costillas Inferiores',
            'esternon_frontal' => 'Esternón Frontal',
            'esternon_lateral' => 'Esternón Lateral',
            'abdomen_simple' => 'Abdomen Simple',
            'abdomen_agudo' => 'Abdomen Agudo',
            'abdomen_erecto' => 'Abdomen Ereto',
            'abdomen_decubito' => 'Abdomen Decúbito',
            'clavicula_izquierda' => 'Clavícula Izquierda',
            'clavicula_derecha' => 'Clavícula Derecha',
            'hombro_anterior' => 'Hombro Anterior',
            'hombro_lateral' => 'Hombro Lateral',
            'humero_proximal' => 'Húmero Próximal',
            'humero_distal' => 'Húmero Distal',
            'codo_anterior' => 'Codo Anterior',
            'codo_lateral' => 'Codo Lateral',
            'antebrazo' => 'Antebrazo',
            'muneca' => 'Muñeca',
            'mano' => 'Mano',
            'cadera_izquierda' => 'Cadera Izquierda',
            'cadera_derecha' => 'Cadera Derecha',
            'femur_proximal' => 'Fémur Próximal',
            'femur_distal' => 'Fémur Distal',
            'rodilla_anterior' => 'Rodilla Anterior',
            'rodilla_lateral' => 'Rodilla Lateral',
            'tibia' => 'Tibia',
            'pie' => 'Pie',
            'calcaneo' => 'Calcáneo',
            'columna_cervical_lateral' => 'Cervical Lateral',
            'columna_cervical_anteroposterior' => 'Cervical Anteroposterior',
            'columna_dorsal_lateral' => 'Dorsal Lateral',
            'columna_dorsal_anteroposterior' => 'Dorsal Anteroposterior',
            'columna_lumbar_lateral' => 'Lumbar Lateral',
            'columna_lumbar_anteroposterior' => 'Lumbar Anteroposterior',
            'sacro_coxis' => 'Sacro Coxis',
            'pelvis_anterior_posterior' => 'Pelvis Anterior Posterior',
            'pelvis_oblicua' => 'Pelvis Oblicua',
            'escoliosis' => 'Escoliosis',
            'arteriograma_simple' => 'Arteriograma Simple',
            'arteriograma_contraste' => 'Arteriograma con Contraste',
            'histerosalpingograma_simple' => 'Histerosalpingograma Simple',
            'histerosalpingograma_contraste' => 'Histerosalpingograma con Contraste',
            'colecistograma_simple' => 'Colecistograma Simple',
            'colecistograma_contraste' => 'Colecistograma con Contraste',
            'fistulograma_simple' => 'Fistulograma Simple',
            'fistulograma_contraste' => 'Fistulograma con Contraste',
            'artrograma_simple' => 'Artrograma Simple',
            'artrograma_contraste' => 'Artrograma con Contraste',
        ];

        $precios_examenes = [
            'craneo_anterior_posterior' => 1500.00,
            'craneo_lateral' => 1200.00,
            'waters' => 500.00,
            'waters_lateral' => 500.00,
            'conductos_auditivos' =>550.00,
            'cavum' => 600.00,
            'senos_paranasales' => 800.00,
            'silla_turca' => 600.00,
            'huesos_nasales' => 700.00,
            'atm_tm' => 900.00,
            'mastoides' => 1000.00,
            'mandibula' => 1000.00,
            'torax_posteroanterior_pa' => 900.00,
            'torax_anteroposterior_ap' => 1000.00,
            'torax_lateral' => 1100.00,
            'torax_oblicuo' => 1100.00,
            'torax_superior' => 1100.00,
            'torax_inferior' => 1200.00,
            'costillas_superiores' => 1000.00,
            'costillas_inferiores' => 1100.00,
            'esternon_frontal' => 1100.00,
            'esternon_lateral' => 1000.00,
            'abdomen_simple' => 500.00,
            'abdomen_agudo' => 500.00,
            'abdomen_erecto' => 600.00,
            'abdomen_decubito' => 500.00,
            'clavicula_izquierda' => 600.00,
            'clavicula_derecha' => 600.00,
            'hombro_anterior' =>1000.00,
            'hombro_lateral' => 1000.00,
            'humero_proximal' => 1000.00,
            'humero_distal' => 1000.00,
            'codo_anterior' => 800.00,
            'codo_lateral' => 800.00,
            'antebrazo' => 500.00,
            'muneca' => 600.00,
            'mano' => 600.00,
            'cadera_izquierda' => 1000.00,
            'cadera_derecha' => 1000.00,
            'femur_proximal' => 900.00,
            'femur_distal' => 900.00,
            'rodilla_anterior' => 600.00,
            'rodilla_lateral' => 600.00,
            'tibia' => 600.00,
            'pie' => 700.00,
            'calcaneo' => 700.00,
            'columna_cervical_lateral' => 1000.00,
            'columna_cervical_anteroposterior' => 1000.00,
            'columna_dorsal_lateral' => 1000.00,
            'columna_dorsal_anteroposterior' => 1000.00,
            'columna_lumbar_lateral' => 900.00,
            'columna_lumbar_anteroposterior' => 1000.00,
            'sacro_coxis' => 800.00,
            'pelvis_anterior_posterior' => 900.00,
            'pelvis_oblicua' => 1100.00,
            'escoliosis' => 1000.00,
            'arteriograma_simple' => 800.00,
            'arteriograma_contraste' => 900.00,
            'histerosalpingograma_simple' => 900.00,
            'histerosalpingograma_contraste' => 1400.00,
            'colecistograma_simple' => 1000.00,
            'colecistograma_contraste' => 1100.00,
            'fistulograma_simple' => 700.00,
            'fistulograma_contraste' => 800.00,
            'artrograma_simple' => 900.00,
            'artrograma_contraste' => 1200.00,
        ];

        $examenes_con_precios = [];
        foreach ($examenes_nombres as $codigo => $nombre) {
            $examenes_con_precios[$codigo] = [
                'nombre' => $nombre,
                'precio' => $precios_examenes[$codigo] ?? 500.00
            ];
        }

        return $examenes_con_precios;
    }

    public static function getExamenesNombres()
    {
        $examenes_precios = static::getExamenesPrecios();
        $nombres = [];
        
        foreach ($examenes_precios as $codigo => $data) {
            $nombres[$codigo] = $data['nombre'];
        }
        
        return $nombres;
    }
}