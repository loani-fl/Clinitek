<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Diagnostico;
use App\Models\Hematologia;
use App\Models\PerfilDiabetes;
use App\Models\PerfilAnemia;
use App\Models\Bioquimico;
use App\Models\MarcadorTumoral;
use App\Models\InmunologiaAutoinmunidad;
use App\Models\OrinaFluido;
use App\Models\Hormona;
use App\Models\Examen;
use App\Models\Infeccioso;

class ExamenController extends Controller
{
    // Mostrar formulario para crear orden de exámenes
    public function create($pacienteId, $consultaId)
    {
        $paciente = Paciente::findOrFail($pacienteId);
        $consulta = Consulta::with('medico', 'diagnostico')->findOrFail($consultaId);

        if (!$consulta->diagnostico) {
            return redirect()->back()->withErrors('La consulta no tiene diagnóstico asociado.');
        }

        // Obtener exámenes seleccionados del request anterior (si existe)
        $examenesSeleccionados = old('examenes', []);

        return view('examenes.create', compact('paciente', 'consulta', 'examenesSeleccionados'));
    }

    // Guardar orden de exámenes
    public function store(Request $request, $pacienteId, $diagnosticoId)
    {
        $diagnostico = Diagnostico::findOrFail($diagnosticoId);

        $secciones = [
            'HEMATOLOGÍA' => Hematologia::class,
            'PERFIL DIABETES' => PerfilDiabetes::class,
            'PERFIL DE ANEMIA' => PerfilAnemia::class,
            'BIOQUÍMICOS' => Bioquimico::class,
            'MARCADORES TUMORALES' => MarcadorTumoral::class,
            'INMUNOLOGÍA Y AUTOINMUNIDAD' => InmunologiaAutoinmunidad::class,
            'ORINA Y FLUIDOS' => OrinaFluido::class,
            'HORMONAS' => Hormona::class,
            'INFECCIOSOS' => Infeccioso::class,
        ];

        $mapaCamposEspeciales = [
            'tsh' => 'hormona_estimulante_tiroides_tsh',
            'lh' => 'hormona_luteinizante_lh',
            'fsh' => 'hormona_foliculo_estimulante_fsh',
            'beta_hcg' => 'beta_hcg_embarazo',
        ];

        foreach ($secciones as $modelo) {
            $modelo::where('diagnostico_id', $diagnosticoId)->delete();
        }

        Examen::where('paciente_id', $pacienteId)
            ->where('consulta_id', $diagnostico->consulta_id)
            ->delete();

        $examenesSeleccionados = $request->input('examenes', []);

        foreach ($secciones as $nombreSeccion => $modeloClase) {
            $atributos = [];

            $modeloInstancia = new $modeloClase;
            foreach ($modeloInstancia->getFillable() as $campo) {
                $atributos[$campo] = false;
            }

            foreach ($examenesSeleccionados as $examen) {
                $examenSinSimbolos = preg_replace('/[()\/\-.:\']+/', '', strtolower($examen));
                $examenNormalizado = \Illuminate\Support\Str::snake($examenSinSimbolos);

                if (array_key_exists($examenNormalizado, $mapaCamposEspeciales)) {
                    $campo = $mapaCamposEspeciales[$examenNormalizado];
                } else {
                    $campo = $examenNormalizado;
                }

                if (!array_key_exists($campo, $atributos)) {
                    foreach ($atributos as $key => $valor) {
                        if (strpos($key, $campo) !== false) {
                            $campo = $key;
                            break;
                        }
                    }
                }

                if (array_key_exists($campo, $atributos)) {
                    $atributos[$campo] = true;
                }
            }

            if (in_array(true, $atributos, true)) {
                $atributos['diagnostico_id'] = $diagnosticoId;
                $modeloClase::create($atributos);
            }
        }

        foreach ($examenesSeleccionados as $nombreExamen) {
            Examen::create([
                'paciente_id' => $pacienteId,
                'consulta_id' => $diagnostico->consulta_id,
                'nombre' => $nombreExamen,
            ]);
        }

        return redirect()->route('consultas.show', $diagnostico->consulta_id)
            ->with('success', 'Orden de examen guardada correctamente.');
    }

    // Mostrar la orden de exámenes guardada para un diagnóstico
    public function show($diagnostico_id)
    {
        $diagnostico = Diagnostico::with('consulta.paciente', 'consulta.medico')->findOrFail($diagnostico_id);

        $consulta = $diagnostico->consulta;
        $paciente = $consulta->paciente;

        // Exámenes seleccionados en la orden guardada (normalizados)
        $examenesSeleccionados = Examen::where('consulta_id', $consulta->id)
            ->pluck('nombre')
            ->map(function($nombre) {
                $nombreSinSimbolos = preg_replace('/[()\/.:\'\-]+/', '', strtolower($nombre));
                return \Illuminate\Support\Str::snake($nombreSinSimbolos);
            })
            ->toArray();

        // Definición completa de secciones y sus exámenes
        $todosExamenes = [
            'HEMATOLOGÍA' => [
                'hemograma_completo', 'frotis_en_sangre_periferica', 'reticulocitos', 'eritrosedimentacion',
                'grupo_sanguineo', 'p_coombs_directa', 'p_coombs_indirecta', 'plasmodium_gota_gruesa',
                'plasmodium_anticuerpos'
            ],
            'HORMONAS' => [
                'hormona_luteinizante_lh', 'hormona_foliculo_estimulante_fsh', 'cortisol', 'prolactina',
                'testosterona', 'estradiol', 'progesterona', 'beta_hcg_embarazo'
            ],
            'ORINA Y FLUIDOS' => [
                'examen_general_orina', 'cultivo_orina', 'orina_24_horas', 'prueba_embarazo',
                'liquido_cefalorraquideo', 'liquido_pleural', 'liquido_peritoneal', 'liquido_articular',
                'espermograma'
            ],
            'BIOQUÍMICOS' => [
                'urea', 'bun', 'creatinina', 'acido_urico', 'glucosa', 'glucosa_post_prandial_2h',
                'c_tolencia_glucosa_2h', 'c_tolencia_glucosa_4h', 'bilirrubina_total_y_fracciones',
                'proteinas_totales', 'albumina_globulina', 'electroforesis_proteinas',
                'cistatina_c_creatinina_tfg', 'diabetes_gestacional'
            ],
            'MARCADORES TUMORALES' => [
                'af_proteina', 'ac_embrionario', 'ca125', 'he4', 'indice_roma', 'ca15_3', 'ca19_9',
                'ca72_4', 'cyfra_21_1', 'beta_2_microglobulina', 'enolasa_neuroespecifica',
                'antigeno_prostatico_psa', 'psa_libre'
            ],
            'PERFIL DE ANEMIA' => [
                'hierro_serico', 'capacidad_fijacion_hierro', 'transferrina', 'ferritina', 'vitamina_b12',
                'acido_folico', 'eritropoyetina', 'haptoglobina', 'electroforesis_hemoglobina',
                'glucosa_6_fosfato', 'fragilidad_osmotica_hematias'
            ],
            'PERFIL DIABETES' => [
                'peptido_c', 'indice_peptidico', 'insulina', 'homa_ir', 'homa_ir_post_prandial',
                'fructosamina', 'hemoglobina_glicosilada'
            ],
            'INMUNOLOGÍA Y AUTOINMUNIDAD' => [
                'iga', 'igg', 'igm', 'ige', 'complemento_c3', 'complemento_c4', 'vitamina_d',
                'ac_antinucleares'
            ],
            'INFECCIOSOS' => [
                'hiv_1_y_2', 'hepatitis_b', 'hepatitis_c', 'sifilis_vdrl_o_rpr', 'citomegalovirus_cmv'
            ],
        ];

        // Convertir cada examen a array con nombre y si está seleccionado
        $secciones = [];
        foreach ($todosExamenes as $seccion => $examenes) {
            $listaExamenes = [];
            foreach ($examenes as $examen) {
                $examenSinSimbolos = preg_replace('/[()\/.:\'\-]+/', '', strtolower($examen));
                $examenNormalizado = \Illuminate\Support\Str::snake($examenSinSimbolos);
                $listaExamenes[] = [
                    'nombre' => $examen,
                    'seleccionado' => in_array($examenNormalizado, $examenesSeleccionados),
                ];
            }
            $secciones[$seccion] = $listaExamenes;
        }

        // También pasamos $examenesSeleccionados para la vista
        return view('examenes.show', compact('paciente', 'consulta', 'secciones', 'examenesSeleccionados'));
    }
}
