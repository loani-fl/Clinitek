<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Ultrasonido;
use App\Models\UltrasonidoHigado;
use App\Models\UltrasonidoVesicula;
use App\Models\UltrasonidoBazo;
use App\Models\UltrasonidoVejiga;
use App\Models\UltrasonidoOvarico;
use App\Models\UltrasonidoUtero;
use App\Models\UltrasonidoTiroides;
use App\Models\Medico;
use App\Models\UltrasonidoImagen;

class UltrasonidoOrderController extends Controller
{
    public function create()
    {
        $pacientes = Paciente::all();

        // Todos los ultrasonidos como elementos individuales
        $secciones = [
            'Hígado' => ['higado' => ['nombre' => 'Ultrasonido Hígado', 'precio' => 150]],
            'Vesícula' => ['vesicula' => ['nombre' => 'Ultrasonido Vesícula', 'precio' => 120]],
            'Bazo' => ['bazo' => ['nombre' => 'Ultrasonido Bazo', 'precio' => 100]],
            'Vejiga' => ['vejiga' => ['nombre' => 'Ultrasonido Vejiga', 'precio' => 90]],
            'Ovarios' => ['ovarico' => ['nombre' => 'Ultrasonido Ovarios', 'precio' => 130]],
            'Útero' => ['utero' => ['nombre' => 'Ultrasonido Útero', 'precio' => 130]],
            'Tiroides' => ['tiroides' => ['nombre' => 'Ultrasonido Tiroides', 'precio' => 140]],
            // Aquí agregamos los 20 ultrasonidos adicionales como elementos individuales
            'Pelvico Transabdominal' => ['pelvico_transabdominal' => ['nombre' => 'Ultrasonido pélvico transabdominal', 'precio' => 150]],
            'Transvaginal' => ['transvaginal' => ['nombre' => 'Ultrasonido transvaginal', 'precio' => 150]],
            'Sonohisterografía' => ['sonohisterografia' => ['nombre' => 'Sonohisterografía', 'precio' => 160]],
            'Doppler Ginecológico' => ['doppler_ginecologico' => ['nombre' => 'Ultrasonido Doppler ginecológico', 'precio' => 170]],
            'Obstétrico Temprano' => ['obstetrico_temprano' => ['nombre' => 'Ultrasonido obstétrico temprano', 'precio' => 140]],
            'Morfológico' => ['morfologico' => ['nombre' => 'Ultrasonido morfológico', 'precio' => 200]],
            'Crecimiento Fetal' => ['crecimiento_fetal' => ['nombre' => 'Ultrasonido de crecimiento fetal', 'precio' => 180]],
            'Bienestar Fetal' => ['bienestar_fetal' => ['nombre' => 'Ultrasonido de bienestar fetal', 'precio' => 175]],
            'Ultrasonido 3D' => ['ultrasonido_3d' => ['nombre' => 'Ultrasonido 3D', 'precio' => 220]],
            'Ultrasonido 4D' => ['ultrasonido_4d' => ['nombre' => 'Ultrasonido 4D', 'precio' => 250]],
            'Ovario y Útero' => ['ovario_utero' => ['nombre' => 'Ultrasonido de ovario y útero', 'precio' => 150]],
            'Control DIU' => ['control_diu' => ['nombre' => 'Ultrasonido para control de DIU', 'precio' => 130]],
            'Detección Endometriosis' => ['deteccion_endometriosis' => ['nombre' => 'Ultrasonido para detección de endometriosis', 'precio' => 180]],
            'Mamario' => ['mamario' => ['nombre' => 'Ultrasonido mamario', 'precio' => 140]],
            'Tiroides Adicional' => ['tiroides_adicional' => ['nombre' => 'Ultrasonido de tiroides', 'precio' => 120]],
            'Pelvis con Contraste' => ['pelvis_con_contraste' => ['nombre' => 'Ultrasonido de pelvis con contraste', 'precio' => 210]],
            'Folicular' => ['folicular' => ['nombre' => 'Ultrasonido folicular', 'precio' => 130]],
            'Placenta' => ['placenta' => ['nombre' => 'Ultrasonido de placenta', 'precio' => 160]],
            'Transrectal' => ['transrectal' => ['nombre' => 'Ultrasonido transrectal', 'precio' => 180]],
            'Mama 3D' => ['mama_3d' => ['nombre' => 'Ultrasonido de mama 3D', 'precio' => 200]],
        ];

        return view('ultrasonidos.create', compact('pacientes', 'secciones'));
    }

    public function store(Request $request)
    {
        $validKeys = array_merge(
            ['higado','vesicula','bazo','vejiga','ovarico','utero','tiroides'],
            [
                'pelvico_transabdominal','transvaginal','sonohisterografia','doppler_ginecologico',
                'obstetrico_temprano','morfologico','crecimiento_fetal','bienestar_fetal',
                'ultrasonido_3d','ultrasonido_4d','ovario_utero','control_diu',
                'deteccion_endometriosis','mamario','tiroides_adicional','pelvis_con_contraste',
                'folicular','placenta','transrectal','mama_3d'
            ]
        );

        $precios = [
            'higado' => 150,'vesicula' => 120,'bazo' => 100,'vejiga' => 90,'ovarico' => 130,
            'utero' => 130,'tiroides' => 140,'pelvico_transabdominal' => 150,'transvaginal' => 150,
            'sonohisterografia' => 160,'doppler_ginecologico' => 170,'obstetrico_temprano' => 140,
            'morfologico' => 200,'crecimiento_fetal' => 180,'bienestar_fetal' => 175,'ultrasonido_3d' => 220,
            'ultrasonido_4d' => 250,'ovario_utero' => 150,'control_diu' => 130,'deteccion_endometriosis' => 180,
            'mamario' => 140,'tiroides_adicional' => 120,'pelvis_con_contraste' => 210,'folicular' => 130,
            'placenta' => 160,'transrectal' => 180,'mama_3d' => 200
        ];

        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha' => 'required|date|after_or_equal:today',
            'examenes' => 'required|array|min:1|max:7',
            'examenes.*' => 'in:' . implode(',', $validKeys),
        ]);

        $total = array_sum(array_map(fn($ex) => $precios[$ex], $request->examenes));

        $ultrasonido = Ultrasonido::create([
            'paciente_id' => $request->paciente_id,
            'fecha' => $request->fecha,
            'total' => $total,
            'examenes' => $request->examenes,
        ]);

        foreach ($request->examenes as $examen) {
            // Solo modelos para los 7 iniciales
            $model = match($examen) {
                'higado' => UltrasonidoHigado::class,
                'vesicula' => UltrasonidoVesicula::class,
                'bazo' => UltrasonidoBazo::class,
                'vejiga' => UltrasonidoVejiga::class,
                'ovarico' => UltrasonidoOvarico::class,
                'utero' => UltrasonidoUtero::class,
                'tiroides' => UltrasonidoTiroides::class,
                default => null
            };
            if ($model) {
                $model::create(['ultrasonido_id' => $ultrasonido->id]);
            }
        }

        return redirect()->route('ultrasonidos.index')->with('success', 'Orden de ultrasonido creada correctamente.');
    }
    public function index(Request $request)
    {
        $query = Ultrasonido::with('paciente');

        // Filtros
        if ($request->search) {
            $query->whereHas('paciente', fn($q) => $q->where('nombre', 'like', "%{$request->search}%")
                                                  ->orWhere('apellidos', 'like', "%{$request->search}%"));
        }

        if ($request->fecha_desde) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->fecha_hasta) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        $ordenes = $query->orderBy('fecha', 'desc')->paginate(2);

        // ✅ Respuesta AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('ultrasonidos.partials', compact('ordenes'))->render(),
                'pagination' => $ordenes->links()->toHtml(),
                'total' => $ordenes->count(),
                'all' => $ordenes->total()
            ]);
        }
        return view('ultrasonidos.index', compact('ordenes'));
    }

// En UltrasonidoOrderController.php

public function show($id)
{
    $orden = Ultrasonido::with(['paciente', 'medico', 'imagenes'])->findOrFail($id);
    
    // 1. Mapa de claves a nombres legibles
    $mapaNombres = [
        'higado' => 'Ultrasonido Hígado',
        'vesicula' => 'Ultrasonido Vesícula',
        'bazo' => 'Ultrasonido Bazo',
        'vejiga' => 'Ultrasonido Vejiga',
        'ovarico' => 'Ultrasonido Ovarios',
        'utero' => 'Ultrasonido Útero',
        'tiroides' => 'Ultrasonido Tiroides',
    ];

    // 2. Claves ordenadas para la vista
    $examenesKeys = $orden->examenes ?? [];

    // 3. Agrupamos las imágenes por la columna 'tipo_examen'
    $imagenesAgrupadas = $orden->imagenes->groupBy('tipo_examen');

    // 4. Pasamos toda la información necesaria a la vista
    return view('ultrasonidos.show', compact(
        'orden', 
        'mapaNombres', 
        'examenesKeys', 
        'imagenesAgrupadas'
    ));
}




public function analisis($id)
{
    $orden = Ultrasonido::with([
        'paciente',
        'imagenes',
        'medico'
    ])->findOrFail($id);

    // Solo mostrar médicos de Ginecología
    $medicos = Medico::where('especialidad', 'Ginecología')->get();

    // Mapear claves a nombres legibles
    $mapaNombres = [
        'higado' => 'Ultrasonido Hígado',
        'vesicula' => 'Ultrasonido Vesícula',
        'bazo' => 'Ultrasonido Bazo',
        'vejiga' => 'Ultrasonido Vejiga',
        'ovarico' => 'Ultrasonido Ovarios',
        'utero' => 'Ultrasonido Útero',
        'tiroides' => 'Ultrasonido Tiroides',
    ];

    $examenesSeleccionados = collect($orden->examenes ?? [])
        ->map(fn($ex) => $mapaNombres[$ex] ?? $ex);

    return view('ultrasonidos.analisis', compact('orden', 'medicos', 'examenesSeleccionados'));
}


// En app/Http/Controllers/UltrasonidoOrderController.php

public function guardarAnalisis(Request $request, $id)
{
    // ... (Tu código de validación es correcto, lo omito por brevedad) ...
    $request->validate([
        'medico_id' => 'required|exists:medicos,id',
        'imagenes' => 'required|array',
        'imagenes.*' => 'required|array',
        'imagenes.*.*' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        'descripciones' => 'required|array',
        'descripciones.*' => 'required|array',
        'descripciones.*.*' => 'required|string|max:200',
    ], [
        'medico_id.required' => 'Debe seleccionar un médico responsable.',
        // ... (El resto de tus mensajes de error) ...
    ]);

    $orden = Ultrasonido::findOrFail($id);
    $orden->medico_id = $request->medico_id;
    $orden->estado = 'completado';
    $orden->save();

    // 1. OBTENER LAS CLAVES DE EXÁMENES DE LA ORDEN
    // Esto debería ser un array como ['higado', 'vesicula', 'bazo']
    $examenesKeys = $orden->examenes ?? []; 

    // Procesar imágenes por examen
    if ($request->has('imagenes')) {
        // $examenIndex es el índice (0, 1, 2...) del grupo de imágenes en el formulario
        foreach ($request->file('imagenes') as $examenIndex => $imagenesExamen) {
            
            // 2. Usar el índice para obtener la CLAVE del examen (ej: 'higado')
            if (!isset($examenesKeys[$examenIndex])) {
                // Esto podría pasar si el formulario tiene más grupos de campos que exámenes guardados
                continue; 
            }
            $examenKey = $examenesKeys[$examenIndex]; // <-- ¡CLAVE!

            foreach ($imagenesExamen as $index => $imagen) {
                $ruta = $imagen->store('ultrasonidos', 'public');

                // Obtener la descripción correspondiente
                $descripcion = $request->descripciones[$examenIndex][$index] ?? '';

                UltrasonidoImagen::create([
                    'ultrasonido_id' => $orden->id,
                    'tipo_examen' => $examenKey, // <-- ¡LÍNEA MODIFICADA PARA GUARDAR EL TIPO!
                    'ruta' => $ruta,
                    'descripcion' => $descripcion,
                ]);
            }
        }
    }

    return redirect()->route('ultrasonidos.index')
        ->with('success', 'Análisis de ultrasonido guardado correctamente.');
}


}
