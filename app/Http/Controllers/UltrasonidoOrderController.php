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

    public function show(Ultrasonido $ultrasonido)
{
    return view('ultrasonidos.show', compact('ultrasonido'));
}

}
