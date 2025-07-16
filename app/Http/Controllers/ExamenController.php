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

        return view('examenes.create', compact('paciente', 'consulta'));
    }

    // Guardar orden de exámenes
    public function store(Request $request, $pacienteId, $diagnosticoId)
{
    // Validar que el diagnóstico exista
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

    // Elimina registros antiguos para evitar duplicados en tablas específicas
    foreach ($secciones as $modelo) {
        $modelo::where('diagnostico_id', $diagnosticoId)->delete();
    }

    // Elimina exámenes anteriores para este paciente y consulta en tabla 'examenes'
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

    // Guardar en tabla 'examenes' para mostrar listado en la vista show
    foreach ($examenesSeleccionados as $nombreExamen) {
        Examen::create([
            'paciente_id' => $pacienteId,
            'consulta_id' => $diagnostico->consulta_id,
            'nombre' => $nombreExamen,
        ]);
    }

    return redirect()->route('examenes.show', $diagnosticoId)
        ->with('success', 'Orden de examen guardada correctamente.');
}


    // Mostrar la orden de exámenes guardada para un diagnóstico
public function show($diagnosticoId)
{
    $diagnostico = Diagnostico::with('paciente', 'consulta.medico')->findOrFail($diagnosticoId);

    $paciente = $diagnostico->paciente;
    $consulta = $diagnostico->consulta;

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

    $datosSecciones = [];

    foreach ($secciones as $nombreSeccion => $modeloClase) {
        $registro = $modeloClase::where('diagnostico_id', $diagnosticoId)->first();


        if ($registro) {
    $campos = collect($registro->getAttributes())
        ->except(['id', 'diagnostico_id', 'created_at', 'updated_at'])
        ->toArray();
    $datosSecciones[$nombreSeccion] = $campos;
}
// Si no hay registro, no hagas nada

    }

    return view('examenes.show', compact('diagnostico', 'paciente', 'consulta', 'datosSecciones'));
}



}
