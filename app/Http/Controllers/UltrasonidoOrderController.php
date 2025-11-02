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

        $secciones = [
            'Hígado' => ['higado' => ['nombre' => 'Ultrasonido Hígado', 'precio' => 150]],
            'Vesícula' => ['vesicula' => ['nombre' => 'Ultrasonido Vesícula', 'precio' => 120]],
            'Bazo' => ['bazo' => ['nombre' => 'Ultrasonido Bazo', 'precio' => 100]],
            'Vejiga' => ['vejiga' => ['nombre' => 'Ultrasonido Vejiga', 'precio' => 90]],
            'Ovarios' => ['ovarico' => ['nombre' => 'Ultrasonido Ovarios', 'precio' => 130]],
            'Útero' => ['utero' => ['nombre' => 'Ultrasonido Útero', 'precio' => 130]],
            'Tiroides' => ['tiroides' => ['nombre' => 'Ultrasonido Tiroides', 'precio' => 140]],
        ];

        return view('ultrasonidos.create', compact('pacientes', 'secciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha' => 'required|date|before_or_equal:today',
            'examenes' => 'required|array|min:1|max:7',
            'examenes.*' => 'in:higado,vesicula,bazo,vejiga,ovarico,utero,tiroides',
        ]);

        $precios = [
            'higado' => 150,
            'vesicula' => 120,
            'bazo' => 100,
            'vejiga' => 90,
            'ovarico' => 130,
            'utero' => 130,
            'tiroides' => 140,
        ];

        $total = array_sum(array_map(fn($ex) => $precios[$ex], $request->examenes));

       $ultrasonido = Ultrasonido::create([
    'paciente_id' => $request->paciente_id,
    'fecha' => $request->fecha,
    'total' => $total,
    'examenes' => $request->examenes, // ✅ Guarda los exámenes seleccionados
]);


        foreach ($request->examenes as $examen) {
            $model = match($examen) {
                'higado' => UltrasonidoHigado::class,
                'vesicula' => UltrasonidoVesicula::class,
                'bazo' => UltrasonidoBazo::class,
                'vejiga' => UltrasonidoVejiga::class,
                'ovarico' => UltrasonidoOvarico::class,
                'utero' => UltrasonidoUtero::class,
                'tiroides' => UltrasonidoTiroides::class,
            };
            $model::create(['ultrasonido_id' => $ultrasonido->id]);

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

        $ordenes = $query->orderBy('fecha', 'desc')->paginate(10);

        return view('ultrasonidos.index', compact('ordenes'));
    }

 public function show($id)
{
    $orden = Ultrasonido::with(['paciente', 'medico', 'imagenes'])->findOrFail($id);
    return view('ultrasonidos.show', compact('orden'));
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



public function guardarAnalisis(Request $request, $id)
{
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
        'imagenes.*.*.required' => 'Debe subir una imagen para cada bloque.',
        'imagenes.*.*.image' => 'El archivo debe ser una imagen.',
        'imagenes.*.*.mimes' => 'Solo se permiten imágenes JPG, JPEG o PNG.',
        'imagenes.*.*.max' => 'Cada imagen no debe superar 4 MB.',
        'descripciones.*.*.required' => 'Debe agregar una descripción para cada imagen.',
    ]);

    $orden = Ultrasonido::findOrFail($id);
    $orden->medico_id = $request->medico_id;
    $orden->estado = 'completado'; // Opcional: cambiar estado
    $orden->save();

    // Procesar imágenes por examen
    if ($request->has('imagenes')) {
        foreach ($request->file('imagenes') as $examenIndex => $imagenesExamen) {
            foreach ($imagenesExamen as $index => $imagen) {
                $ruta = $imagen->store('ultrasonidos', 'public');
                
                // Obtener la descripción correspondiente
                $descripcion = $request->descripciones[$examenIndex][$index] ?? '';
                
                UltrasonidoImagen::create([
                    'ultrasonido_id' => $orden->id,
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
