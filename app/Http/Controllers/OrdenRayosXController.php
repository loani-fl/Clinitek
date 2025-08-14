<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\RayosxOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\RayosxOrderExamenImagen;
use App\Models\Medico;

class OrdenRayosXController extends Controller
{
    // Mostrar formulario para crear nueva orden (sin variable $orden)
    public function create()
    {
        $pacientes = Paciente::orderBy('nombre')->get();

        // Definir secciones con claves para los exámenes
        $secciones = [ 
            'CABEZA' => [
                'craneo_anterior_posterior',
                'craneo_lateral',
                'waters',
                'waters_lateral',
                'conductos_auditivos',
                'cavum',
                'senos_paranasales',
                'silla_turca',
                'huesos_nasales',
                'atm_tm',
                'mastoides',
                'mandibula',
            ],
            'TÓRAX' => [
                'torax_posteroanterior_pa',
                'torax_anteroposterior_ap',
                'torax_lateral',
                'torax_oblicuo',
                'torax_superior',
                'torax_inferior',
                'costillas_superiores',
                'costillas_inferiores',
                'esternon_frontal',
                'esternon_lateral',
            ],
            'EXTREMIDAD SUPERIOR' => [
                'clavicula_izquierda',
                'clavicula_derecha',
                'hombro_anterior',
                'hombro_lateral',
                'humero_proximal',
                'humero_distal',
                'codo_anterior',
                'codo_lateral',
                'antebrazo',
                'muneca',
                'mano',
            ],
            'ABDOMEN' => [
                'abdomen_simple',
                'abdomen_agudo',
                'abdomen_erecto',
                'abdomen_decubito',
            ],
            'EXTREMIDAD INFERIOR' => [
                'cadera_izquierda',
                'cadera_derecha',
                'femur_proximal',
                'femur_distal',
                'rodilla_anterior',
                'rodilla_lateral',
                'tibia',
                'pie',
                'calcaneo',
            ],
            'COLUMNA Y PELVIS' => [
                'columna_cervical_lateral',
                'columna_cervical_anteroposterior',
                'columna_dorsal_lateral',
                'columna_dorsal_anteroposterior',
                'columna_lumbar_lateral',
                'columna_lumbar_anteroposterior',
                'sacro_coxis',
                'pelvis_anterior_posterior',
                'pelvis_oblicua',
                'escoliosis',
            ],
            'ESTUDIOS ESPECIALES' => [
                'arteriograma_simple',
                'arteriograma_contraste',
                'histerosalpingograma_simple',
                'histerosalpingograma_contraste',
                'colecistograma_simple',
                'colecistograma_contraste',
                'fistulograma_simple',
                'fistulograma_contraste',
                'artrograma_simple',
                'artrograma_contraste',
            ],
        ];

        // Nombres legibles de exámenes
        $examenes = [
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

        // Precios de los exámenes
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

        // Construir estructura completa para la vista
        $secciones_completas = [];
        foreach ($secciones as $categoria => $clavesExamenes) {
            $secciones_completas[$categoria] = [];
            foreach ($clavesExamenes as $clave) {
                $secciones_completas[$categoria][$clave] = [
                    'nombre' => $examenes[$clave] ?? $clave,
                    'precio' => $preciosExamenes[$clave] ?? 0,
                ];
            }
        }

        return view('rayosx.create', [
            'pacientes' => $pacientes,
            'secciones' => $secciones_completas,
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'paciente_id' => ['required', 'exists:pacientes,id'],
        'fecha' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:2025-10-31'],
        'examenes' => ['required', 'array', 'min:1', 'max:10'],
        'examenes.*' => ['string'],
    ], [
        'paciente_id.required' => 'Debe seleccionar un paciente.',
        'paciente_id.exists' => 'El paciente seleccionado no existe.',
        'fecha.required' => 'La fecha es obligatoria.',
        'fecha.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
        'fecha.before_or_equal' => 'La fecha no puede ser posterior al 31 de octubre de 2025.',
        'examenes.required' => 'Debe seleccionar al menos un examen.',
        'examenes.array' => 'Los exámenes deben enviarse en formato de arreglo.',
        'examenes.min' => 'Debe seleccionar al menos un examen.',
        'examenes.max' => 'No puede seleccionar más de 10 exámenes.',
    ]);

    $orden = new RayosxOrder();
    $orden->paciente_id = $request->paciente_id;
    $orden->fecha = $request->fecha;

    // Aquí calculamos el total de los precios de los exámenes seleccionados:
    $examenesSeleccionados = $request->examenes;

    // Suponiendo que tienes un modelo Examen con 'codigo' y 'precio'
    $totalPrecio = \App\Models\Examen::whereIn('codigo', $examenesSeleccionados)->sum('precio');

    $orden->total_precio = $totalPrecio; // Guardamos el total calculado

    $orden->save();

    // Guardar relación exámenes, si tienes una relación como examen_codigo o similar
    foreach ($examenesSeleccionados as $codigoExamen) {
        $orden->examenes()->create([
            'examen_codigo' => $codigoExamen,
        ]);
    }

    return redirect()->route('rayosx.index')->with('success', 'Orden de rayos X creada correctamente.');
}


    
    // Vista para analizar (editar) una orden existente
    public function analisis(RayosxOrder $orden)
    {
        // Cargar relación de exámenes con imágenes y paciente para mostrar todo junto
        $orden->load(['examenes.imagenes', 'paciente']);

        // Obtener médicos radiológos para asignación o referencia
        $medicosRadiologos = Medico::where('especialidad', 'Radiología')->get();

        // Nombres legibles para los códigos de exámenes (puedes ampliar según necesites)
        $examenesNombres = [
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

        return view('rayosx.analisis', compact('orden', 'medicosRadiologos', 'examenesNombres'));
    }

    // Mostrar detalles de una orden
    public function show($id)
    {
        $orden = RayosxOrder::with(['paciente', 'examenes.imagenes'])->findOrFail($id);

        $examenesNombres = [
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

        return view('rayosx.show', compact('orden', 'examenesNombres'));
    }
public function guardarAnalisis(Request $request, RayosxOrder $orden)
{
    $request->validate([
        'medico_analista_id' => 'required|exists:medicos,id',
        'descripciones' => 'required|array',
        'descripciones.*' => 'nullable|string',
        'imagenes' => 'nullable|array',
        'imagenes.*.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
    ], [
        'medico_analista_id.required' => 'Debe seleccionar un médico analista válido.',
        'medico_analista_id.exists' => 'El médico analista no existe.',
        'descripciones.required' => 'Debe agregar descripciones para los exámenes.',
        'imagenes.*.*.image' => 'Solo se permiten archivos de imagen.',
        'imagenes.*.*.max' => 'Cada imagen no debe superar los 5MB.',
    ]);

    // Validar mínimo 1 y máximo 3 imágenes nuevas por examen
    if ($request->has('imagenes')) {
        foreach ($request->imagenes as $examenId => $imagenesArray) {
            $count = is_array($imagenesArray) ? count($imagenesArray) : 0;
            if ($count < 1) {
                return back()->withErrors(['imagenes' => "El examen con ID '{$examenId}' debe tener al menos 1 imagen nueva."])->withInput();
            }
            if ($count > 3) {
                return back()->withErrors(['imagenes' => "El examen con ID '{$examenId}' no puede tener más de 3 imágenes nuevas."])->withInput();
            }
        }
    } else {
        return back()->withErrors(['imagenes' => "Debe subir al menos una imagen para cada examen."])->withInput();
    }

    // Guardar médico analista y estado
    $orden->medico_analista_id = $request->medico_analista_id;
    $orden->estado = 'realizado';
    $orden->save();

    // Guardar descripciones e imágenes
    foreach ($request->descripciones as $examenId => $descripcion) {
        $examen = $orden->examenes()->where('id', $examenId)->first();
        if (!$examen) {
            continue;
        }

        $examen->descripcion = $descripcion;
        $examen->save();

        if ($request->hasFile("imagenes.$examenId")) {
            foreach ($request->file("imagenes.$examenId") as $imagen) {
                $ruta = $imagen->store('rayosx/imagenes', 'public');

                RayosxOrderExamenImagen::create([
                    'rayosx_order_examen_id' => $examen->id,
                 'imagen_ruta' => $ruta,

                ]);
            }
        }
    }

    return redirect()->route('rayosx.show', $orden->id)
        ->with('success', 'Análisis guardado correctamente y estado actualizado a realizado.');
}

// Mostrar lista paginada de órdenes con paciente
 
public function index(Request $request)
{
    try {
        $query = $request->input('search', '');

        $ordenesQuery = RayosXOrder::with('paciente')->orderBy('fecha', 'desc');

        if ($query) {
            $ordenesQuery->whereHas('paciente', function ($q) use ($query) {
                $q->where('nombre', 'like', "%$query%")
                  ->orWhere('apellidos', 'like', "%$query%");
            });
        }

        if ($query) {
            $ordenes = $ordenesQuery->get();
            $isSearch = true;
        } else {
            $ordenes = $ordenesQuery->paginate(1);
            $isSearch = false;
        }

        $all = RayosXOrder::count();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('rayosx.partials.tabla', compact('ordenes', 'isSearch'))->render(),
                'pagination' => $isSearch ? '' : $ordenes->links('pagination::bootstrap-5')->render(),
                'total' => $ordenes->count(),
                'all' => $all,
            ]);
        }

        return view('rayosx.index', compact('ordenes', 'isSearch', 'all'));
    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
        abort(500, $e->getMessage());
    }
}

}
