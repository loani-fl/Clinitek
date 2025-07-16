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

        // Mapeo de campos especiales si usas abreviaturas
        $mapaCamposEspeciales = [
            'tsh' => 'hormona_estimulante_tiroides_tsh',
            'lh' => 'hormona_luteinizante_lh',
            'fsh' => 'hormona_foliculo_estimulante_fsh',
            'beta_hcg' => 'beta_hcg_embarazo',
        ];

        // Elimina registros antiguos para evitar duplicados
        foreach ($secciones as $modelo) {
            $modelo::where('diagnostico_id', $diagnosticoId)->delete();
        }

        $examenesSeleccionados = $request->input('examenes', []);

        foreach ($secciones as $nombreSeccion => $modeloClase) {
            $atributos = [];

            // Inicializa todos los campos como false
            $modeloInstancia = new $modeloClase;
            foreach ($modeloInstancia->getFillable() as $campo) {
                $atributos[$campo] = false;
            }

            // Marca true para los exámenes seleccionados que correspondan a esta sección
            foreach ($examenesSeleccionados as $examen) {
                // Normaliza el nombre del examen para buscar el campo correcto
                $examenSinSimbolos = preg_replace('/[()\/\-.:\']+/', '', strtolower($examen));
                $examenNormalizado = \Illuminate\Support\Str::snake($examenSinSimbolos);

                if (array_key_exists($examenNormalizado, $mapaCamposEspeciales)) {
                    $campo = $mapaCamposEspeciales[$examenNormalizado];
                } else {
                    $campo = $examenNormalizado;
                }

                // Busca coincidencias parciales si no existe el campo exacto
                if (!array_key_exists($campo, $atributos)) {
                    foreach ($atributos as $key => $valor) {
                        if (strpos($key, $campo) !== false) {
                            $campo = $key;
                            break;
                        }
                    }
                }

                // Si el campo pertenece a esta sección, marcar true
                if (array_key_exists($campo, $atributos)) {
                    $atributos[$campo] = true;
                }
            }

            // Si hay al menos un campo true, guarda el registro
            if (in_array(true, $atributos, true)) {
                $atributos['diagnostico_id'] = $diagnosticoId;
                $modeloClase::create($atributos);
            }
        }

        return redirect()->route('diagnosticos.show', $diagnosticoId)
            ->with('success', 'Orden de examen guardada correctamente.');
    }

    // Mostrar la orden de exámenes guardada para un diagnóstico
    public function show($diagnosticoId)
    {
        $diagnostico = Diagnostico::with('paciente')->findOrFail($diagnosticoId);

        $modelos = [
            'HEMATOLOGÍA' => new Hematologia(),
            'PERFIL DIABETES' => new PerfilDiabetes(),
            'PERFIL DE ANEMIA' => new PerfilAnemia(),
            'BIOQUÍMICOS' => new Bioquimico(),
            'MARCADORES TUMORALES' => new MarcadorTumoral(),
            'INMUNOLOGÍA Y AUTOINMUNIDAD' => new InmunologiaAutoinmunidad(),
            'ORINA Y FLUIDOS' => new OrinaFluido(),
            'HORMONAS' => new Hormona(),
            'INFECCIOSOS' => new Infeccioso(),
        ];

        $registros = [];

        foreach ($modelos as $seccion => $modelo) {
            $registro = $modelo->where('diagnostico_id', $diagnosticoId)->first();

            if ($registro) {
                $campos = collect($registro->getAttributes())
                    ->except(['id', 'diagnostico_id', 'created_at', 'updated_at']);

                if ($campos->contains(true)) {
                    $registros[$seccion] = $registro;
                }
            }
        }

        return view('examenes.show', compact('diagnostico', 'registros'));
    }
}
