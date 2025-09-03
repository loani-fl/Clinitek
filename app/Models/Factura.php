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
            // ... resto de exámenes igual
        ];

        $precios_examenes = [
            'craneo_anterior_posterior' => 1500.00,
            'craneo_lateral' => 1200.00,
            'waters' => 500.00,
            'waters_lateral' => 500.00,
            // ... resto de precios igual
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