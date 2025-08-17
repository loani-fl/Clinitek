<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Consulta;
use App\Models\RayosxOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Crear pago desde consulta y mostrar factura
     */
    public function createFromConsulta($consulta_id)
    {
        $consulta = Consulta::with(['paciente', 'medico'])->findOrFail($consulta_id);

            $precios = [
                'Cardiología' => 900,
                'Pediatría'   => 500,
                'Radiologia'  => 700,
                'Psiquiatría'  => 500,
                'Radiologia'  => 700,
                'Dermatología'  => 900,
                'Neurología'  => 1000,
                
            ];

            $cantidad = $precios[$consulta->especialidad] ?? 0;

            // Crear el pago directamente
            $pago = Pago::create([
                'paciente_id'   => $consulta->paciente->id,   // debe existir
                'medico_id'     => $consulta->medico->id ?? null, // si no hay médico, queda null
                'servicio'      => 'Consulta médica',
                'descripcion'   => null,
                'cantidad'      => $cantidad, // viene de especialidad
                'metodo_pago'   => 'efectivo',
                'fecha'         => Carbon::now(),
                'origen'        => 'consulta',
                'referencia_id' => $consulta->id,
            ]);

        

        return redirect()->route('pagos.show', $pago->id)
            ->with('success', 'Pago registrado exitosamente.');
    }

    /**
     * Crear pago desde rayos X y mostrar factura
     */
    public function createFromRayosx($rayosx_id)
    {
        $orden = RayosxOrder::with(['paciente', 'examenes'])->findOrFail($rayosx_id);
    
        // Lista de precios
        $preciosExamenes = [
            'craneo_anterior_posterior' => 120.00,
            'craneo_lateral' => 110.00,
            'waters' => 100.00,
            'waters_lateral' => 100.00,
            'conductos_auditivos' => 80.00,
            'cavum' => 90.00,
            'senos_paranasales' => 85.00,
            'silla_turca' => 95.00,
            'huesos_nasales' => 75.00,
            'atm_tm' => 90.00,
            'mastoides' => 88.00,
            'mandibula' => 85.00,
            'torax_posteroanterior_pa' => 150.00,
            'torax_anteroposterior_ap' => 150.00,
            'torax_lateral' => 140.00,
            'torax_oblicuo' => 130.00,
            'torax_superior' => 120.00,
            'torax_inferior' => 120.00,
            'costillas_superiores' => 110.00,
            'costillas_inferiores' => 110.00,
            'esternon_frontal' => 100.00,
            'esternon_lateral' => 100.00,
            'abdomen_simple' => 130.00,
            'abdomen_agudo' => 150.00,
            'abdomen_erecto' => 140.00,
            'abdomen_decubito' => 140.00,
            'clavicula_izquierda' => 90.00,
            'clavicula_derecha' => 90.00,
            'hombro_anterior' => 100.00,
            'hombro_lateral' => 100.00,
            'humero_proximal' => 110.00,
            'humero_distal' => 110.00,
            'codo_anterior' => 90.00,
            'codo_lateral' => 90.00,
            'antebrazo' => 80.00,
            'muneca' => 80.00,
            'mano' => 80.00,
            'cadera_izquierda' => 120.00,
            'cadera_derecha' => 120.00,
            'femur_proximal' => 130.00,
            'femur_distal' => 130.00,
            'rodilla_anterior' => 110.00,
            'rodilla_lateral' => 110.00,
            'tibia' => 100.00,
            'pie' => 90.00,
            'calcaneo' => 90.00,
            'columna_cervical_lateral' => 100.00,
            'columna_cervical_anteroposterior' => 100.00,
            'columna_dorsal_lateral' => 110.00,
            'columna_dorsal_anteroposterior' => 110.00,
            'columna_lumbar_lateral' => 110.00,
            'columna_lumbar_anteroposterior' => 110.00,
            'sacro_coxis' => 100.00,
            'pelvis_anterior_posterior' => 120.00,
            'pelvis_oblicua' => 120.00,
            'escoliosis' => 100.00,
            'arteriograma_simple' => 250.00,
            'arteriograma_contraste' => 300.00,
            'histerosalpingograma_simple' => 230.00,
            'histerosalpingograma_contraste' => 280.00,
            'colecistograma_simple' => 220.00,
            'colecistograma_contraste' => 270.00,
            'fistulograma_simple' => 210.00,
            'fistulograma_contraste' => 260.00,
            'artrograma_simple' => 200.00,
            'artrograma_contraste' => 250.00,
        ];
    
        // Calcular total en base a los exámenes seleccionados
        $total = 0;
        foreach ($orden->examenes as $examen) {
            if (isset($preciosExamenes[$examen->codigo])) {
                $total += $preciosExamenes[$examen->codigo];
            }
        }
    
        // Crear descripción con los nombres de los exámenes
        $descripcion = $orden->examenes->pluck('nombre')->implode(', ');
    
        // Crear el pago
        $pago = Pago::create([
            'paciente_id'    => $orden->paciente->id,
            'medico_id'      => null,
            'servicio'       => 'Rayos X',
            'descripcion'    => $descripcion,
            'cantidad'       => $total, // usamos el calculado
            'metodo_pago'    => 'efectivo',
            'fecha'          => Carbon::now(),
            'origen'         => 'rayosx',
            'referencia_id'  => $orden->id,
        ]);
    
        return redirect()->route('pago.show', $pago->id)
            ->with('success', 'Pago registrado exitosamente.');
    }
    

    /**
     * Mostrar factura
     */
    public function show($id)
    {
        $pago = Pago::with(['paciente', 'medico'])->findOrFail($id);

    $paciente = $pago->paciente;
    $medico   = $pago->medico;

    $examenesConPrecio = collect();

    if ($pago->origen === 'rayosx') {
        $orden = RayosxOrder::with('examenes')->find($pago->referencia_id);
    
            // Lista de precios de los exámenes
            $preciosExamenes = [
                'craneo_anterior_posterior' => 120.00,
                'craneo_lateral' => 110.00,
                'waters' => 100.00,
                'waters_lateral' => 100.00,
                'conductos_auditivos' => 80.00,
                'cavum' => 90.00,
                'senos_paranasales' => 85.00,
                'silla_turca' => 95.00,
                'huesos_nasales' => 75.00,
                'atm_tm' => 90.00,
                'mastoides' => 88.00,
                'mandibula' => 85.00,
                'torax_posteroanterior_pa' => 150.00,
                'torax_anteroposterior_ap' => 150.00,
                'torax_lateral' => 140.00,
                'torax_oblicuo' => 130.00,
                'torax_superior' => 120.00,
                'torax_inferior' => 120.00,
                'costillas_superiores' => 110.00,
                'costillas_inferiores' => 110.00,
                'esternon_frontal' => 100.00,
                'esternon_lateral' => 100.00,
                'abdomen_simple' => 130.00,
                'abdomen_agudo' => 150.00,
                'abdomen_erecto' => 140.00,
                'abdomen_decubito' => 140.00,
                'clavicula_izquierda' => 90.00,
                'clavicula_derecha' => 90.00,
                'hombro_anterior' => 100.00,
                'hombro_lateral' => 100.00,
                'humero_proximal' => 110.00,
                'humero_distal' => 110.00,
                'codo_anterior' => 90.00,
                'codo_lateral' => 90.00,
                'antebrazo' => 80.00,
                'muneca' => 80.00,
                'mano' => 80.00,
                'cadera_izquierda' => 120.00,
                'cadera_derecha' => 120.00,
                'femur_proximal' => 130.00,
                'femur_distal' => 130.00,
                'rodilla_anterior' => 110.00,
                'rodilla_lateral' => 110.00,
                'tibia' => 100.00,
                'pie' => 90.00,
                'calcaneo' => 90.00,
                'columna_cervical_lateral' => 100.00,
                'columna_cervical_anteroposterior' => 100.00,
                'columna_dorsal_lateral' => 110.00,
                'columna_dorsal_anteroposterior' => 110.00,
                'columna_lumbar_lateral' => 110.00,
                'columna_lumbar_anteroposterior' => 110.00,
                'sacro_coxis' => 100.00,
                'pelvis_anterior_posterior' => 120.00,
                'pelvis_oblicua' => 120.00,
                'escoliosis' => 100.00,
                'arteriograma_simple' => 250.00,
                'arteriograma_contraste' => 300.00,
                'histerosalpingograma_simple' => 230.00,
                'histerosalpingograma_contraste' => 280.00,
                'colecistograma_simple' => 220.00,
                'colecistograma_contraste' => 270.00,
                'fistulograma_simple' => 210.00,
                'fistulograma_contraste' => 260.00,
                'artrograma_simple' => 200.00,
                'artrograma_contraste' => 250.00,
            ];
    
            if ($orden) {
                $examenesConPrecio = $orden->examenes->map(function($examen) use ($preciosExamenes) {
                    return [
                        'descripcion' => $examen->nombre,
                        'precio' => $preciosExamenes[$examen->codigo] ?? 0,
                    ];
                });
            }
        }
    
        return view('pago.show', [
            'pago' => $pago,
            'paciente' => $paciente,
            'medico' => $medico,
            'origen' => $pago->origen,
            'examenesConPrecio' => $examenesConPrecio,
        ]);
    }
    

}
