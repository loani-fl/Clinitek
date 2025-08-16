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
             'ABDOMEN' => [
                'abdomen_simple',
                'abdomen_agudo',
                'abdomen_erecto',
                'abdomen_decubito',
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
       
        'imagenes.*.*' => 'nullable|mimes:jpg,jpeg,png|max:5120', // Validación de imágenes
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
        'imagenes.*.image' => 'Cada archivo debe ser una imagen.',
        'imagenes.*.mimes' => 'Solo se permiten imágenes jpg, jpeg o png.',
        'imagenes.*.max' => 'Cada imagen no debe superar los 2MB.',
    ]);

    $orden = new RayosxOrder();
    $orden->paciente_id = $request->paciente_id;
    $orden->fecha = $request->fecha;

    // Calcular total de precios de exámenes
    $examenesSeleccionados = $request->examenes;
    $totalPrecio = \App\Models\Examen::whereIn('codigo', $examenesSeleccionados)->sum('precio');
    $orden->total_precio = $totalPrecio;
    $orden->save();

    // Guardar relación de exámenes
    foreach ($examenesSeleccionados as $codigoExamen) {
        $examenRegistro = $orden->examenes()->create([
            'examen_codigo' => $codigoExamen,
        ]);

        // Guardar imágenes asociadas a este examen, si se suben
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $file) {
                $nombre = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/rayosx_examenes', $nombre);

                \App\Models\RayosxOrderExamenImagen::create([
                    'rayosx_order_examen_id' => $examenRegistro->id,
                    'ruta' => 'rayosx_examenes/' . $nombre,
                    'descripcion' => null,
                ]);
            }
        }
    }

    return redirect()->route('rayosx.index')->with('success', 'Orden de rayos X creada correctamente.');
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
            'imagenes' => 'required|array',
            'imagenes.*.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'medico_analista_id.required' => 'Debe seleccionar un médico analista válido.',
            'medico_analista_id.exists' => 'El médico analista no existe.',
            'descripciones.required' => 'Debe agregar descripciones para los exámenes.',
            'imagenes.required' => 'Debe subir al menos una imagen para cada examen.',
            'imagenes.*.*.image' => 'Solo se permiten archivos de imagen.',
            'imagenes.*.*.max' => 'Cada imagen no debe superar los 5MB.',
        ]);
    
        // Validar mínimo 1 y máximo 3 imágenes por examen
        foreach ($request->imagenes as $examenId => $imagenesArray) {
            $count = is_array($imagenesArray) ? count($imagenesArray) : 0;
            if ($count < 1) {
                return back()->withErrors(['imagenes' => "El examen con ID '{$examenId}' debe tener al menos 1 imagen."])->withInput();
            }
            if ($count > 3) {
                return back()->withErrors(['imagenes' => "El examen con ID '{$examenId}' no puede tener más de 3 imágenes."])->withInput();
            }
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
                    // Generar nombre único para cada imagen
                    $nombre = time() . '_' . uniqid() . '_' . $imagen->getClientOriginalName();
    
                    // Guardar en storage/app/public/rayosx_examenes
                    $imagen->storeAs('public/rayosx_examenes', $nombre);
    
                    // Crear registro en la BD
                    RayosxOrderExamenImagen::create([
                        'rayosx_order_examen_id' => $examen->id,
                        'ruta' => 'rayosx_examenes/' . $nombre,
                    ]);
                }
            }
        }
    
        return redirect()->route('rayosx.index', $orden->id)
            ->with('success', 'Análisis guardado correctamente y estado actualizado a realizado.');
    }
    
// Mostrar lista paginada de órdenes con paciente

public function index(Request $request)
{
    try {
        $query = $request->input('search', '');

        $ordenesQuery = RayosXOrder::with('paciente')->orderBy('fecha', 'desc');

        // Filtro por búsqueda de paciente (ya existente)
        if ($query) {
            $ordenesQuery->whereHas('paciente', function ($q) use ($query) {
                $q->where('nombre', 'like', "%$query%")
                  ->orWhere('apellidos', 'like', "%$query%");
            });
        }

        // >>> NUEVOS FILTROS: fecha y estado <<<
        $fechaInicio = $request->input('fecha_desde');
        $fechaFin = $request->input('fecha_hasta');
        $estadoFiltro = $request->input('estado');

        if ($fechaInicio) {
            $ordenesQuery->where('fecha', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $ordenesQuery->where('fecha', '<=', $fechaFin);
        }
        if ($estadoFiltro) {
            $ordenesQuery->where('estado', $estadoFiltro);
        }

        // Obtener resultados
        if ($query) {
            $ordenes = $ordenesQuery->get();
            $isSearch = true;
        } else {
            $ordenes = $ordenesQuery->paginate(5);
            $isSearch = false;
        }

        $all = RayosXOrder::count();

        // Respuesta AJAX
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

public function analisis(RayosxOrder $orden)
{
    // Cargar relación de exámenes con imágenes y paciente
    $orden->load(['examenes.imagenes', 'paciente']);

    // Obtener médicos radiológos para asignación o referencia
    $medicosRadiologos = \App\Models\Medico::where('especialidad', 'Radiología')->get();

    // Nombres legibles para los códigos de exámenes
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


}
